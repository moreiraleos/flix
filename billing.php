<?php

require_once "./includes/paypalConfig.php";
require_once "billingPlan.php";


use PayPal\Api\Agreement;
use PayPal\Api\Payer;
use PayPal\Api\Plan;
use PayPal\Api\ShippingAddress;

$id = $plan->getId();


$agreement = new Agreement();

$agreement->setName('Subscription to Flix')
    ->setDescription('R$ 9,99 setup free and then recurring payments of R$9.99 to Flix')
    ->setStartDate(gmdate("Y-m-d\TH:i:s\Z", strtotime("+1month", time())));


$plan = new Plan();
$plan->setId($id);
$agreement->setPlan($plan);

// Add Payer
$payer = new Payer();
$payer->setPaymentMethod('paypal');
$agreement->setPayer($payer);


// ### Create Agreement
try {
    // Please note that as the agreement has not yet activated, we wont be receiving the ID just yet.
    $agreement = $agreement->create($apiContext);
    // ### Get redirect url
    // The API response provides the url that you must redirect
    // the buyer to. Retrieve the url from the $agreement->getApprovalLink()
    // method
    $approvalUrl = $agreement->getApprovalLink();
    header("Location: $approvalUrl");
} catch (Exception $ex) {
    // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
    exit(1);
}
