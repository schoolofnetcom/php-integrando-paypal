<?php

namespace BrPayments;

use BrPayments\Requests\RequestInterface as Request;
use BrPayments\OrderInterface as Order;
use GuzzleHttp\Client;

class MakeRequest
{
    private $client;
    private $request;

    public function __construct(Request $request)
    {
        $this->client = new Client;
        $this->request = $request;
    }

    public function make($order, bool $sandbox = null)
    {
        if (!is_a($order, Order::class) and !is_null($order)) {
            throw new \Exception('Argument 1 passed to BrPayments\MakeRequest::make() must be an instance of BrPayments\OrderInterface or null, ' . gettype($order) . ' given');
        }
        $response = $this->client->request(
            $this->request->getMethod(),
            $this->request->getUrl($order, $sandbox),
            $this->request->config($order)
        );
        return $response->getBody();
    }
}
