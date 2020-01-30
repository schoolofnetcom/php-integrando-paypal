<?php

namespace BrPayments\Payments;

use BrPayments\OrderInterface;

class PayPal implements OrderInterface
{
    protected $token; //autenticação
    protected $intent = 'sale'; // ou authorize ou order
    protected $payer = [
        'payment_method'=>'paypal' //ou credit_card
    ];
    protected $transactions;
    protected $note_to_payer; //nota para o comprador - max: 165 - opcional
    protected $redirect_urls; //opcional

    const INTENT_AUTHORIZE = 'authorize';
    const INTENT_ORDER = 'order';
    const INTENT_SALE = 'sale';

    const PAYER_PAYPAL = 'paypal';
    const PAYER_CREDIT_CARD = 'credit_card';

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function setIntent(string $intent)
    {
        $valid_values = [
            PayPal::INTENT_AUTHORIZE,
            PayPal::INTENT_ORDER,
            PayPal::INTENT_SALE
        ];

        if (in_array($intent, $valid_values)) {
            $this->intent = $intent;
        } else {
            throw new \Exception("Intent receive wrong value, {$intent} given");
        }
        return $this;
    }

    public function setPaymentMethod(string $payment_method)
    {
        $valid_values = [
            PayPal::PAYER_PAYPAL,
            PayPal::PAYER_CREDIT_CARD
        ];

        if (in_array($payment_method, $valid_values)) {
            $this->payer['payment_method'] = $payment_method;
        } else {
            throw new \Exception("Payment Method receive wrong value, {$intent} given");
        }
        return $this;
    }

    public function setTransaction($amount, $description, $payment_options, $items = [], $shipping_address = [], $funding_instruments = [])
    {
        foreach ($items as &$item) {
            $item['price'] = number_format($item['price'], 2, '.', '');
            $item['tax'] = number_format($item['tax'], 2, '.', '');
            $item['currency'] = $amount['currency'];
        }

        $amount['total'] = number_format($amount['total'], 2, '.', '');
        if (!empty($amount['details'])) {
            if (isset($amount['details']['subtotal'])) $amount['details']['subtotal'] = number_format($amount['details']['subtotal'], 2, '.', '');
            if (isset($amount['details']['tax'])) $amount['details']['tax'] = number_format($amount['details']['tax'], 2, '.', '');
            if (isset($amount['details']['shipping'])) $amount['details']['shipping'] = number_format($amount['details']['shipping'], 2, '.', '');
            if (isset($amount['details']['handling_fee'])) $amount['details']['handling_fee'] = number_format($amount['details']['handling_fee'], 2, '.', '');
            if (isset($amount['details']['shipping_discount'])) $amount['details']['shipping_discount'] = number_format($amount['details']['shipping_discount'], 2, '.', '');
            if (isset($amount['details']['insurance'])) $amount['details']['insurance'] = number_format($amount['details']['insurance'], 2, '.', '');
            if (isset($amount['details']['gift_wrap'])) $amount['details']['gift_wrap'] = number_format($amount['details']['gift_wrap'], 2, '.', '');
        }

        $transaction = [
            'amount'=> $amount,
            'description' => $description,
            'payment_options' => $payment_options,
        ];

        if ($items != []) {
            $transaction['item_list']['items'] = $items;
        }
        if ($shipping_address != []) {
            $transaction['item_list']['shipping_address'] = $shipping_address;
        }
        if ($funding_instruments != []) {
            $this->payer['funding_instruments'] = [$funding_instruments];
        }

        $this->transactions[] = $transaction;
        return $this;
    }

    public function setNoteToPayer(string $note_to_payer)
    {
        if (strlen($note_to_payer) > 127) {
            throw new \Exception("Not To Payer must be less then 127, {$intent} given");
        }
        $this->note_to_payer = $note_to_payer;
        return $this;
    }

    public function setRedirectUrls(array $redirect_urls)
    {
        if (empty($redirect_urls['return_url']) and empty($redirect_urls['cancel_url'])) {
            throw new \Exception("return_url and cancel_url is required");
        }
        $this->redirect_urls = $redirect_urls;
        return $this;
    }

    public function toArray() :array
    {
        return [
            'token' => $this->token,
            'body'=> [
                'intent' => $this->intent,
                'payer' => $this->payer,
                'transactions' => $this->transactions,
                'note_to_payer' => $this->note_to_payer,
                'redirect_urls' => $this->redirect_urls,
            ]
        ];
    }

    public function __toString() :string
    {
        $data = $this->toArray();
        return json_encode($data['body']);
    }
}
