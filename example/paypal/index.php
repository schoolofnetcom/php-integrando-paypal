<?php

require __DIR__.'/../../vendor/autoload.php';

//configurações
$client_id = 'AR1b-d8yYJnyuaiG74xVydjL2F6h_dy6X09g7PN_KwURch9gDfM5hYt3KQ-jlvjl56rqhiXbVCf8RC8q';
$secret = 'EJy2IHG7U5X2mONFqdcl872VoFWf5emwgnSSp276hbWkK2f8IADGIj8mADESghknaHipsigHQiD0_Lzt';

$intent = 'sale';
$method = 'paypal';
$note = 'Pagamento de teste, favor não concluir!!!';
$redirect_urls = [
    'return_url'=>'http://localhost:8080/pago.html',
    'cancel_url'=>'http://localhost:8080/nao_pago.html'
];
$payment_options = [
    'allowed_payment_method'=>'IMMEDIATE_PAY',
];

//pedido
$amount = [
  'total' => 25.00,
  'currency' => "BRL",
];
$description = 'Descrição do pedido';
$items = [
    [
        "name" => "hat",
        "description" => "Brown hat.",
        "quantity" => "5",
        "price" => "2.50",
        "tax" => "0.08",
        "sku" => "1"
    ],
    [
        "name" => "handbag",
        "description" => "Black handbag.",
        "quantity" => "1",
        "price" => "12.50",
        "tax" => "0.40",
        "sku" => "product34"
    ]
];
$shipping_address = [
    "recipient_name" => "Brian Robinson",
    "line1" => "4th Floor",
    "line2" => "Unit #34",
    "city" => "San Jose",
    "country_code" => "US",
    "postal_code" => "95131",
    "phone" => "011862212345678",
    "state" => "CA"
];

$paypal = new BrPayments\PayPal($client_id, $secret, true);
$response = $paypal->payment($intent, $method, $note, $redirect_urls, $amount, $description, $payment_options, $items, $shipping_address);

$result = null;
foreach ($response['links'] as &$link) {
    if ($link['rel'] == 'approval_url') {
        $result = $link['href'];
    }
}

//header('location: '.$result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
</head>
<body>
  <a href="<?php echo $result;?>" target="blank">pagar</a>
</body>
</html>
