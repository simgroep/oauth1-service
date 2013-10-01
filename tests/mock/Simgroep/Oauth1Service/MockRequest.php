<?php

namespace Simgroep\Oauth1Service;

class Request
{
    /**
     * @var OAuthHeader
     */
    public $header;

    public function __construct($error = null)
    {
        $header = $this->getAuthorizationHeader($error);
        $this->header = new Header($header);
    }

    public function getRequestMethod()
    {
        return 'GET';
    }

    public function getRequestParameters()
    {
        return array();
    }

    public function getRequestUri()
    {
        return 'http://simgroep.nl';
    }

    protected function getAuthorizationHeader($error)
    {
        $header = array();

        switch ($error) {
            case null:
                $header['Authorization'] =
                  <<<EOF
OAuth realm="http://simgroep.nl/",
oauth_consumer_key="0685bd9184jfhq22",
oauth_token="ad180jjd733klru7",
oauth_signature_method="HMAC-SHA1",
oauth_signature="3A7XNSlFRQ8GYjjbypt2w5FN4NQ=",
oauth_timestamp="137131200",
oauth_nonce="4572616e48616d6d65724c61686176",
oauth_version="1.0"
EOF;
                break;

            case 'version':
                $header['Authorization'] =
                  <<<EOF
OAuth realm="http://simgroep.nl/",
oauth_consumer_key="0685bd9184jfhq22",
oauth_token="ad180jjd733klru7",
oauth_signature_method="HMAC-SHA1",
oauth_signature="3A7XNSlFRQ8GYjjbypt2w5FN4NQ=",
oauth_timestamp="137131200",
oauth_nonce="4572616e48616d6d65724c61686176",
oauth_version="2.0"
EOF;
                break;

            case 'hash':
                $header['Authorization'] =
                  <<<EOF
OAuth realm="http://simgroep.nl/",
oauth_consumer_key="0685bd9184jfhq22",
oauth_token="ad180jjd733klru7",
oauth_signature_method="MD5",
oauth_signature="3A7XNSlFRQ8GYjjbypt2w5FN4NQ=",
oauth_timestamp="137131200",
oauth_nonce="4572616e48616d6d65724c61686176",
oauth_version="1.0"
EOF;
                break;

            case 'consumer_token':
                $header['Authorization'] =
                  <<<EOF
OAuth realm="http://simgroep.nl/",
oauth_consumer_key="this_is_wrong_consumer_key",
oauth_token="ad180jjd733klru7",
oauth_signature_method="HMAC-SHA1",
oauth_signature="3A7XNSlFRQ8GYjjbypt2w5FN4NQ=",
oauth_timestamp="137131200",
oauth_nonce="4572616e48616d6d65724c61686176",
oauth_version="1.0"
EOF;
                break;

            case 'access_token':
                $header['Authorization'] =
                  <<<EOF
OAuth realm="http://simgroep.nl/",
oauth_consumer_key="0685bd9184jfhq22",
oauth_token="this_is_wrong_tokan",
oauth_signature_method="HMAC-SHA1",
oauth_signature="3A7XNSlFRQ8GYjjbypt2w5FN4NQ=",
oauth_timestamp="137131200",
oauth_nonce="4572616e48616d6d65724c61686176",
oauth_version="1.0"
EOF;
                break;
        }


        return $header['Authorization'];
    }
}