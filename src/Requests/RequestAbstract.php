<?php

namespace BrPayments\Requests;

use BrPayments\OrderInterface as Order;

abstract class RequestAbstract implements RequestInterface
{
    private $child_const;

    public function getUrl($order = null, bool $sandbox = null) :string
    {
        if (!is_a($order, Order::class) and !is_null($order)) {
            throw new \Exception('Argument 1 passed to BrPayments\MakeRequest::make() must be an instance of BrPayments\OrderInterface or null, ' . gettype($order) . ' given');
        }

        $url = $this->getChildConstants('url');
        if ($sandbox) {
            $url = $this->getChildConstants('url_sandbox');
        }
        if ($this->getChildConstants('parameters_in') == 'url' or $this->getChildConstants('parameters_in') == 'both') {
            $url = $url . (string)$order;
        }

        return $url;
    }

    public function getMethod() :string
    {
        return $this->getChildConstants('method');
    }

    private function getChildConstants($const)
    {
        if (!$this->child_const) {
            $child = get_class($this);
            $this->child_const = [
                'url'=> constant($child . '::URL'),
                'url_sandbox'=> constant($child . '::URL_SANDBOX'),
                'method'=> constant($child . '::METHOD'),
                'parameters_in'=> constant($child . '::PARAMETERS_IN'),
            ];
        }

        return $this->child_const[$const];
    }
}
