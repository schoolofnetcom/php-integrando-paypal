<?php

namespace BrPayments\MakeRequest\PayPal;

use PHPUnit\Framework\TestCase;
use BrPayments\Payments\PayPal;
use BrPayments\Requests\PayPal\Auth;
use BrPayments\Requests\PayPal\Payment as PaymentRequest;
use BrPayments\MakeRequest;

/**
 * @group paypal
 */
class PaymentTest extends TestCase
{
    private $token;
    protected $transaction = [
        'amount'=> [ // todos os preçoss são com 2 casas decimais
            //'total' => 30.55,
            'total' => 25.00,
            'currency' => "BRL", // lista: https://developer.paypal.com/docs/classic/api/currency_codes/
            /*'details' => [
                'subtotal'=> 25.00, // valor do pedido
                'tax'=> 0.8, // impostos
                'shipping'=> 2.05, //taxa de entrega
                'handling_fee'=> 1, //taxa de manuseio
                'shipping_discount'=> 0.3, //taxa de desconto
                'insurance'=> 1, //taxa de seguro
                'gift_wrap'=> 1, //taxa de embrulho de presente
            ],*/
        ],
        'description' => 'Descrição do pedido', //127 caracteres
        'payment_options' => [
            'allowed_payment_method'=>'IMMEDIATE_PAY', //ou UNRESTRICTED ou INSTANT_FUNDING_SOURCE
        ],
        'item_list' => [
            'items'=> [
                [
                    "name" => "hat",
                    "description" => "Brown hat.",
                    "quantity" => "5",
                    "price" => "2.50",
                    "tax" => "0.08",
                    "sku" => "1" // o "stock keeping unit (SKU)" ou código do seu estoque para este produto
                ],
                [
                    "name" => "handbag",
                    "description" => "Black handbag.",
                    "quantity" => "1",
                    "price" => "12.50",
                    "tax" => "0.40",
                    "sku" => "product34" // o "stock keeping unit (SKU)" ou código do seu estoque para este produto
                ]
            ]
        ],
        "shipping_address" => [
            "recipient_name" => "Brian Robinson",
            "line1" => "4th Floor",
            "line2" => "Unit #34",
            "city" => "San Jose",
            "country_code" => "US",
            "postal_code" => "95131",
            "phone" => "011862212345678",
            "state" => "CA"
        ]
    ];

    public function testSendPaymentRequest()
    {
        $token = $this->getToken();
        $paypal = new PayPal($token);

        $paypal->setIntent(PayPal::INTENT_SALE)
            ->setPaymentMethod(PayPal::PAYER_PAYPAL)
            ->setNoteToPayer('Pagamento de teste, favor não concluir!!!')
            ->setRedirectUrls([
                'return_url'=>'http://localhost:8080/pago.html',
                'cancel_url'=>'http://localhost:8080/nao_pago.html'
            ]);

        extract($this->transaction);
        $paypal->setTransaction($amount, $description, $payment_options, $item_list['items'], $shipping_address);

        $request = new PaymentRequest();
        $response = (new MakeRequest($request))->make($paypal, true);
        $obj = json_decode((string)$response);

        $this->assertTrue(is_string($obj->id));
    }

    protected function getToken()
    {
        if (!$this->token) {
            $client_id = 'AR1b-d8yYJnyuaiG74xVydjL2F6h_dy6X09g7PN_KwURch9gDfM5hYt3KQ-jlvjl56rqhiXbVCf8RC8q';
            $secret = 'EJy2IHG7U5X2mONFqdcl872VoFWf5emwgnSSp276hbWkK2f8IADGIj8mADESghknaHipsigHQiD0_Lzt';
            $auth = new Auth($client_id, $secret);

            $request = new MakeRequest($auth);
            $response = $request->make(null, true);

            $actual = json_decode($response->getContents(), true);

            $this->token = $actual['access_token'];
        }
        return $this->token;
    }
}
