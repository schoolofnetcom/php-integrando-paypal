<?php

namespace BrPayments;

use BrPayments\Payments\PayPal as Payment;
use BrPayments\Requests\PayPal\Auth;
use BrPayments\Requests\PayPal\Payment as PaymentRequest;
use BrPayments\MakeRequest;

class PayPal
{
    private $user;
    private $pwd;
    private $token;
    private $sandbox;

    public function __construct(string $user, string $pwd, bool $sandbox = true)
    {
        $this->user = $user;
        $this->pwd = $pwd;
        $this->sandbox = $sandbox;
    }

    public function auth()
    {
        $auth = new Auth($this->user, $this->pwd);
        $request = new MakeRequest($auth);
        $response = $request->make(null, $this->sandbox);
        $response = json_decode($response->getContents(), true);
        return $this->token = $response['access_token'];
    }

    public function payment($intent, $method, $note, $redirect_urls, $amount, $description, $payment_options, $items = [], $shipping_address = [], $funding_instruments = [])
    {
        $this->auth();
        $payment = new Payment($this->token);

        $payment->setIntent($intent)
            ->setPaymentMethod($method)
            ->setNoteToPayer($note)
            ->setRedirectUrls($redirect_urls)
            ->setTransaction($amount, $description, $payment_options, $items, $shipping_address);

        $request = new PaymentRequest;
        $response = (new MakeRequest($request))->make($payment, $this->sandbox);
        return json_decode((string) $response, true);
    }
}
