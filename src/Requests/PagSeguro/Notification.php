<?php

namespace BrPayments\Requests\PagSeguro;

use BrPayments\OrderInterface as Order;
use BrPayments\Requests\RequestAbstract;

class Notification extends RequestAbstract
{
    const METHOD = 'GET';
    const URL = 'https://ws.pagseguro.uol.com.br/v3/transactions/notifications/';
    const URL_SANDBOX = 'https://ws.sandbox.pagseguro.uol.com.br/v3/transactions/notifications/';

    public function config(Order $order = null) :array
    {
        return [];
    }
}
