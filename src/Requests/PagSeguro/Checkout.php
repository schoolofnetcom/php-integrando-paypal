<?php

namespace BrPayments\Requests\PagSeguro;

use BrPayments\OrderInterface as Order;
use BrPayments\Requests\RequestAbstract;

class Checkout extends RequestAbstract
{
    const URL = 'https://ws.pagseguro.uol.com.br/v2/checkout?';
    const URL_SANDBOX = 'https://ws.sandbox.pagseguro.uol.com.br/v2/checkout?';
    const METHOD = 'POST';

    const URL_CHECKOUT = 'https://pagseguro.uol.com.br/v2/checkout/payment.html';
    const URL_CHECKOUT_SANDBOX = 'https://sandbox.pagseguro.uol.com.br/v2/checkout/payment.html';

    public function getUrlFinal($code, bool $sandbox = null)
    {
        if ($sandbox) {
            return Checkout::URL_CHECKOUT_SANDBOX . '?code=' . (string)$code;
        }
        return Checkout::URL_CHECKOUT . '?code=' . (string)$code;
    }

    public function config(Order $order = null) :array
    {
        return [
            'form_params'=>[]
        ];
    }
}
