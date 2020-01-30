<?php

namespace BrPayments\Payments;

use PHPUnit\Framework\TestCase;
use BrPayments\Payments\PayPal;

/**
 * @group paypal
 */
class PayPalTest extends TestCase
{
    private $transaction = [
        'amount'=> [ // todos os preçoss são com 2 casas decimais
            'total' => 25.55,
            'currency' => "BRL", // lista: https://developer.paypal.com/docs/classic/api/currency_codes/
            'details' => [
                'subtotal'=> 20, // valor do pedido
                'tax'=> 0.8, // impostos
                'shipping'=> 2.05, //taxa de entrega
                'handling_fee'=> 1, //taxa de manuseio
                'shipping_discount'=> 0.3, //taxa de desconto
                'insurance'=> 1, //taxa de seguro
                'gift_wrap'=> 1, //taxa de embrulho de presente
            ],
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
                    "sku" => "1", // o "stock keeping unit (SKU)" ou código do seu estoque para este produto
                    'currency' => "BRL", //será substituido dentro da classe
                ],
                [
                    "name" => "handbag",
                    "description" => "Black handbag.",
                    "quantity" => "1",
                    "price" => "12.50",
                    "tax" => "0.40",
                    "sku" => "product34", // o "stock keeping unit (SKU)" ou código do seu estoque para este produto
                    'currency' => "BRL", //será substituido dentro da classe
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
        ]
    ];

    public function setUp()
    {
        //documentação do método: https://developer.paypal.com/docs/api/payments/
        $this->paypal = new PayPal('token');
        $this->paypal->setIntent(PayPal::INTENT_SALE)
            ->setPaymentMethod(PayPal::PAYER_PAYPAL)
            ->setNoteToPayer('Pagamento de teste, favor não concluir!!!')
            ->setRedirectUrls([
                'return_url'=>'http://localhost:8080/pago',
                'cancel_url'=>'http://localhost:8080/nao_pago',
            ]);

        extract($this->transaction);
        $this->paypal->setTransaction($amount, $description, $payment_options, $item_list['items'], $item_list['shipping_address']);
    }

    public function testArrayReturn()
    {
        $expected = [
            'token' => 'token',
            'body' => [
                'intent' => 'sale',
                'payer' => ['payment_method'=>'paypal'],
                'transactions' => [
                    $this->transaction
                ],
                'note_to_payer' => 'Pagamento de teste, favor não concluir!!!',
                'redirect_urls' => [
                    'return_url'=>'http://localhost:8080/pago',
                    'cancel_url'=>'http://localhost:8080/nao_pago'
                ],
            ]
        ];
        $actual = $this->paypal->toArray();

        $this->assertEquals($expected, $actual);
    }

    public function testStringReturn()
    {
        $actual = $this->paypal;
        $this->assertTrue(is_string((string)$actual));
    }
}
