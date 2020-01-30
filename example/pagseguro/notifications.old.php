<?php

require __DIR__.'/../../vendor/autoload.php';

$notificationCode = filter_input(INPUT_POST, 'notificationCode');
if (is_null($notificationCode)) {
    $notificationCode = '123456';
}

$access = [
    'email'=>'admin@admin.com',
    'token'=>'123456',
    'notificationCode'=>$notificationCode
];

$pag_seguro = new BrPayments\Notifications\PagSeguro($access);
$pag_seguro_request = new BrPayments\Requests\PagSeguro\Notification;

$response = (new BrPayments\MakeRequest($pag_seguro_request))->make($pag_seguro, true);

$xml = new \SimpleXMLElement((string)$response);

//var_dump($xml);
header("Content-type: text/xml");
echo $response;
