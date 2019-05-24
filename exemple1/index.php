<?php

//changer /var/www/html/ par le chemin  de vos fichiers

require_once ('/var/www/html/DilixPay-PHP-SDK-1.1.0/DilixPay-PHP-SDK/DilixPay/autoload.php');

require_once ('/var/www/html/DilixPay-PHP-SDK-1.1.0/exemple1/dbConfig.php');

try {

$dilixPay= new DilixPayPHPSDK\DilixPay\DilixPay\DilixPay('SANDBOX','api-xxxxxxxxxxxxxxxxxxxxxxxxx' , '5bdeb3f159hjhh4555555442fffffff0');

 } catch(Exception $e) {
   
    echo $e->getMessage();

}   


$success = 0;

$msg = "Une erreur est survenue, merci de bien vouloir réessayer ultérieurement...";

$payment_data = [
  "intent"                 => "sale", 
  "payer"                  => [
  "paymentMethod"          => "DIlixpay",
  "payerInfo"              => [
      "email"              => "test@ymail.com",
      "salutation"         => "Mr",
      "firstName"          => "zongo",
      "middleName"         => "w.",
      "lastName"           => "pat",
      "phone"              => "76xxxxxx",
      "birthDate"          => "string",
      "countryCode"        => "BF",
      "billingAddress"     => [
        "address"          => "03 bp 4097 bobo-dioulasso",
        "city"             => "bobo-dioulasso",
        "country"          => "Burkina faso",
        "phoneAddresses"   => [
          [
            "countryCode"  => "string",
            "phoneNumber"  => "string"
          ]
        ]
      ]
    ]
  ],
  "payee"                  => [
    "Website"              => "https://example.com",
    "siteId"               => "3b6e60a54bb1e8ebtfghhff6c69"
  ],
  "transactions"           => [
    [
      "referenceId"        => "",
      "amount"             => [
        "currency"         => "CFA",
        "total"            => "500"
      ],
      "description"        => "first test",
      "noteToPayee"        =>"string",
      "items"              => [
        [
          "sku"            => "1",
          "name"           => "product",
          "description"    => "string",
          "quantity"       => "1",
          "price"          => "500",
          "currency"       => "CFA",
          "url"            => "https://example.com"
        ]
      ]
    ]
  ],
  "noteToPayer"            => "string",
  "redirectUrls"           => [
    "returnUrl"            => "http://127.0.0.1/dilixpayExemple/redirect.html",
    "cancelUrl"            => "http://127.0.0.1/dilixpayExemple/cancel.php",
    "notifyUrl"            => "http://127.0.0.1/dilixpayExemple/notify.php"
  ]
];


try {

$response = DilixPayPHPSDK\DilixPay\DilixPay\Payments::create($payment_data,$dilixPay);



if (!empty($response->id)) {

   $insert = $db->prepare("INSERT INTO paiements (payment_id, payment_status, payment_amount, payment_currency, payment_date, payer_email) VALUES (:payment_id, :payment_status, :payment_amount, :payment_currency, NOW(), '')");
   
   $insertOk = $insert->execute(array(
         "payment_id" => $response->id,
         "payment_status" => $response->state,
         "payment_amount" => $response->transactions[0]->amount->total,
         "payment_currency" => $response->transactions[0]->amount->currency,
      ));

   if ($insertOk) {
      $success = 1;
      
      $msg = "";
   }else{
        $msg = "insert pas ok";
        }
} else {
   $msg = "Une erreur est survenue durant la communication avec les serveurs de dilixPay. Merci de bien vouloir reessayer ulterieurement.";
}

  DilixPayPHPSDK\DilixPay\DilixPay\Payments::execute($success, $msg, $response,$dilixPay);



   } catch(Exception $e) {

    echo $e->getMessage();

}   