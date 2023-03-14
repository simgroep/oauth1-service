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

    protected $accessTokenRequired = true;

    public function __construct(
        Request $request,
        TokenProviderInterface $consumerProvider,
        TokenProviderInterface $tokenProvider = null
    ) {
        $this->request = $request;
        $this->header = $this->request->header;
        $this->consumerProvider = $consumerProvider;
        $this->tokenProvider = $tokenProvider;
    }

    /**
     * Indicates whether it is required to include an accessToken (oauth_token) in the request
     *
     * @param boolean $required
     */
    public function setAccessTokenRequired($required)
    {
        $this->accessTokenRequired = (bool) $required;
    }

    /**
     * @return boolean
     */
    public function getAccessTokenRequired()
    {
        return $this->accessTokenRequired;
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
        if ($this->header['consumer_key'] == '') {
            $this->error = 'Consumer token missing.';
            return false;
        } elseif (empty($this->consumerSecret)) {
            $this->error = 'Consumer token unknown.';
            return false;
        }

        if ($this->accessTokenRequired === true) {
            $this->tokenSecret = $this->tokenProvider->getSecret($this->header['token']);
        }

        if ($this->accessTokenRequired === true and $this->header['token'] == '') {
            $this->error = 'Access token missing.';
            return false;
        } elseif ($this->accessTokenRequired === true and empty($this->tokenSecret)) {
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
            'accessToken' => $this->accessTokenRequired ? $this->header['token'] : '',
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
            'oauth_token' => $this->accessTokenRequired ? $this->header['token'] : '',
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
            '&',
            $signatureValues
        ); # don't use http_build_query because that one doesn't do the encoding right

        $outputString = $this->request->getRequestMethod() . '&' . rawurlencode($this->getBaseUrl()) . '&' . rawurlencode($signatureString);
        $signingKey = rawurlencode($this->consumerSecret) . '&' . rawurlencode($this->tokenSecret);

        $signature = hash_hmac('SHA1', $outputString, $signingKey, true);
        return base64_encode($signature);
    }

    /**
     * Remove hash and query string to supply base url for signature
     * @return mixed
     */
    protected function getBaseUrl()
    {
        $parts = parse_url($this->request->getRequestUri());
        return sprintf('%s://%s%s', $parts['scheme'] ?? 'http' , $parts['host'] ?? '80', $parts['path']);
    }
}

