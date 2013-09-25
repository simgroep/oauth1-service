<?php
/**
 * Example usage of service.
 *
 */
require 'vendor/autoload.php';

use Simgroep\Oauth1Service\Header;
use Simgroep\Oauth1Service\Service;
use Simgroep\Oauth1Service\Request;
use Simgroep\Oauth1Service\ConsumerProvider;
use Simgroep\Oauth1Service\TokenProvider;

$header = 'OAuth oauth_consumer_key="a", oauth_nonce="9c7e78fc42a259ee7ec5b600543e2495", oauth_signature="1Qiem0TXjO05aB2Z77YIuCEikMA%3D", oauth_signature_method="HMAC-SHA1", oauth_timestamp="1380103322", oauth_token="c", oauth_version="1.0"';

$oh = new Header($header);

$os = new Service(new Request, $oh, new ConsumerProvider, new TokenProvider);
$valid = $os->isValidRequest();
if ($valid === true) {
    print_r($os->getDetails());
} else {
    print_r($os->getError());
}
