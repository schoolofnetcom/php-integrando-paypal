<?php

namespace BrPayments\MakeRequest\PayPal;

use PHPUnit\Framework\TestCase;
use BrPayments\MakeRequest;
use BrPayments\Requests\PayPal\Auth;

/**
 * @group paypal
 */
class AuthTest extends TestCase
{
    public function testCreateAccessToken()
    {
        $client_id = 'AR1b-d8yYJnyuaiG74xVydjL2F6h_dy6X09g7PN_KwURch9gDfM5hYt3KQ-jlvjl56rqhiXbVCf8RC8q';
        $secret = 'EJy2IHG7U5X2mONFqdcl872VoFWf5emwgnSSp276hbWkK2f8IADGIj8mADESghknaHipsigHQiD0_Lzt';
        $auth = new Auth($client_id, $secret);

        $request = new MakeRequest($auth);
        $response = $request->make(null, true);

        $actual = json_decode($response->getContents(), true);
        $this->assertTrue(is_string($actual['access_token']));
    }
}
