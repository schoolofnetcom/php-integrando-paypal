<?php

namespace BrPayments\Requests;

use BrPayments\OrderInterface;

interface RequestInterface
{
    public function getUrl($order = null, bool $sandbox = null) :string;
    public function getMethod() :string;
    public function config(OrderInterface $order = null) :array;
}
