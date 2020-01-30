<?php

namespace BrPayments\Requests\PayPal;

use BrPayments\Requests\RequestAbstract;
use BrPayments\OrderInterface;

/**
 * @see https://developer.paypal.com/docs/api/auth-headers/ Documentation of Auth
 **/
class Auth extends RequestAbstract
{
    const URL = 'https://api.paypal.com/v1/oauth2/token';
    const URL_SANDBOX = 'https://api.sandbox.paypal.com/v1/oauth2/token';
    const METHOD = 'POST';
    const PARAMETERS_IN = 'none'; // ou both ou url

    private $client_id;
    private $secret;

    public function __construct($client_id, $secret)
    {
        $this->client_id = $client_id;
        $this->secret = $secret;
    }

    public function config(OrderInterface $order = null) :array
    {
        return [
            'form_params' => [
                'grant_type'=>'client_credentials'
            ],
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Authorization' => 'Basic ' . base64_encode("{$this->client_id}:{$this->secret}")
            ]
        ];
    }
}
