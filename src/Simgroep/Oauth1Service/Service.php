<?php

namespace Simgroep\Oauth1Service;

class Service
{
    /**
     * @var OauthRequest
     */
    protected $request;

    /**
     * @var OauthHeader
     */
    protected $header;
    protected $error = 'Unknown error.';
    protected $consumerProvider;
    protected $tokenProvider;
    protected $consumerSecret = '';
    protected $tokenSecret = '';

    public function __construct(
    Request $request, TokenProviderInterface $consumerProvider, TokenProviderInterface $tokenProvider
    )
    {
        $this->request = $request;
        $this->header = $this->request->header;
        $this->consumerProvider = $consumerProvider;
        $this->tokenProvider = $tokenProvider;

    }

    public function isValidRequest()
    {
        if ($this->header['version'] !== '1.0') {
            $this->error = 'Wrong version.';
            return false;
        }

        if ($this->header['signature_method'] !== 'HMAC-SHA1') {
            $this->error = 'Wrong hashing method.';
            return false;
        }

        #@todo check for nonce/timestamp

        $this->consumerSecret = $this->consumerProvider->getSecret($this->header['consumer_key']);
        if (empty($this->consumerSecret)) {
            $this->error = 'Consumer token unknown.';
            return false;
        }

        $this->tokenSecret = $this->tokenProvider->getSecret($this->header['token']);
        if (empty($this->tokenSecret)) {
            $this->error = 'Access token unknown.';
            return false;
        }

        if ($this->buildSignature() !== $this->header['signature']) {
            $this->error = 'Incorrect signature.';
            return false;
        }
        return true;
    }

    public function getError()
    {
        return $this->error;
    }

    public function getDetails()
    {
        return array(
            'consumerToken' => $this->header['consumer_key'],
            'accessToken' => $this->header['token'],
        );
    }

    protected function buildSignature()
    {
        $authorizationParts = array(
            'oauth_consumer_key' => $this->header['consumer_key'],
            'oauth_nonce' => $this->header['nonce'],
            'oauth_signature' => '',
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_timestamp' => $this->header['timestamp'],
            'oauth_token' => $this->header['token'],
            'oauth_version' => '1.0',
        );

        $signatureValues = array();

        foreach ($this->request->getRequestParameters() as $k => $v) {
            $signatureValues[$k] = rawurlencode($k) . '=' . rawurlencode($v);
        }
        foreach ($authorizationParts as $k => $v) {
            if ($k == 'oauth_signature') {
                continue;
            }
            $signatureValues[$k] = rawurlencode($k) . '=' . rawurlencode($v);
        }
        ksort($signatureValues); # sort key alphabetically
        $signatureString = implode(
          '&', $signatureValues
        ); # don't use http_build_query because that one doesn't do the encoding right

        $outputString = $this->request->getRequestMethod() . '&' . rawurlencode(
            $this->request->getRequestUri()
          ) . '&' . rawurlencode($signatureString);
        $signingKey = rawurlencode($this->consumerSecret) . '&' . rawurlencode($this->tokenSecret);

//        echo "\n\nservice:\n";
//        var_dump($outputString, $signingKey);

        $signature = hash_hmac('SHA1', $outputString, $signingKey, true);
        return base64_encode($signature);
    }
}

