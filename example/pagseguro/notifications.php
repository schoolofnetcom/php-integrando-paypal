<?php

require __DIR__.'/../../vendor/autoload.php';

$notificationCode = filter_input(INPUT_POST, 'notificationCode');
if (is_null($notificationCode)) {
    $notificationCode = '123';
}

$pagseguro = new BrPayments\PagSeguro('erik.figueiredo@gmail.com', '1321312', true);
$response = $pagseguro->notification('1231');

header("Content-type: text/xml");
echo $response;
