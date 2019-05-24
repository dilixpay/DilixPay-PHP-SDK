<?php
require_once ('/var/www/html/DilixPay-PHP-SDK-1.1.0/DilixPay-PHP-SDK/DilixPay/autoload.php');

require_once "dbConfig.php";

$success = 0;
$msg = "Une erreur est survenue, merci de bien vouloir reessayer ulterieurement...";


if(!empty($_POST['paymentId'])){

    $paymentId = htmlspecialchars($_POST['paymentId']);
    $state = htmlspecialchars($_POST['state']);



   $payment = $db->prepare('SELECT * FROM paiements WHERE payment_id = ?');
   $payment->execute(array($paymentId));
   $payment = $payment->fetch();

   if ($payment) {


        $update_payment = $db->prepare('UPDATE paiements SET payment_status = ? WHERE payment_id = ?');
        $update_payment->execute(array($state,  $paymentId));

      if ($state == "approved") {
         $success = 1;
         $msg = "";
      } else {
         $msg = "Une erreur est survenue durant l'approbation de votre paiement. Merci de réessayer ultérieurement ou contacter un administrateur du site.";
      }
   } else {
      $msg = "Votre paiement n'a pas ete trouve dans la  base de donnees. Merci de reessayer ulterieurement ou contacter un administrateur du site. ";
   }
 }
  DilixPayPHPSDK\DilixPay\DilixPay\Payments::approved($success, $msg);
