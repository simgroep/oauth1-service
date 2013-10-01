<?php

/**
 * Example usage of service.
 *
 * The variables in $_SERVER represent what you might get in a typical application
 */
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/ConsumerProvider.php';
require __DIR__ . '/TokenProvider.php';

$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['HTTP_HOST'] = 'example.org';
$_SERVER['REQUEST_URI'] = '/test';

$_SERVER['HTTP_AUTHORIZATION'] = 'OAuth oauth_consumer_key="consumerKey", oauth_nonce="7e17b4754c0b43078688a1fd5565b762", oauth_signature="rElCV6n%2FCeexrlLnR0w67NFMTf4%3D", oauth_signature_method="HMAC-SHA1", oauth_timestamp="1380629569", oauth_token="tokenKey", oauth_version="1.0"';

use Simgroep\Oauth1Service\Service;
use Simgroep\Oauth1Service\Request;
use Simgroep\Oauth1Service\SymfonyRequest;
use Simgroep\Oauth1Service\Zf1Request;

try {
    $request = new Request;
    #  or:
    #$request = new SymfonyRequest(/* instance of Symfony\Component\HttpFoundation\Request */);
    #  or
    #$request = new Zf1Request(/* instance of Zend_Controller_Request_Http */);

    $os = new Service($request, new ConsumerProvider, new TokenProvider);
    $valid = $os->isValidRequest();

    if ($valid === true) {
        print_r($os->getDetails());
    } else {
        print_r($os->getError());
    }
} catch (\Simgroep\Oauth1Service\Exception $e) {
    echo $e->getMessage();
}
