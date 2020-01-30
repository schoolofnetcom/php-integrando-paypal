<?php

namespace BrPayments\Requests\PayPal;

use BrPayments\OrderInterface;
use BrPayments\Requests\RequestAbstract;

class Payment extends RequestAbstract
{
    const URL = 'https://api.paypal.com/v1/payments/payment';
    const URL_SANDBOX = 'https://api.sandbox.paypal.com/v1/payments/payment';
    const METHOD = 'POST';
    const PARAMETERS_IN = 'config';

    public function config(OrderInterface $order = null) :array
    {
        $token = $order->toArray()['token'];
        $body = $order->toArray()['body'];
        return [
            'json'=>$body,
            'headers'=>[
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $token,
            ]
        ];
    }
}
