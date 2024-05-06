<?php

require_once "./includes/paypalConfig.php";

use PayPal\Api\ChargeModel;
use PayPal\Api\Currency;
use PayPal\Api\MerchantPreferences;
use PayPal\Api\PaymentDefinition;
use PayPal\Api\Plan;
use PayPal\Api\Patch;
use PayPal\Api\PatchRequest;
use PayPal\Common\PayPalModel;
use PayPal\Exception\PayPalConnectionException;

$plan = new Plan();

$plan->setName('Reeceflix monthly subscription')
    ->setDescription('Gets you all the features of our site.')
    ->setType('INFINITE');


$paymentDefinition = new PaymentDefinition();
$paymentDefinition->setName('Regular Payments')
    ->setType('REGULAR')
    ->setFrequency('Month')
    ->setFrequencyInterval("1")
    // ->setCycles("12")
    ->setAmount(new Currency(array('value' => 9.99, 'currency' => 'BRL')));

// $chargeModel = new ChargeModel();
// $chargeModel->setType('SHIPPING')
//     ->setAmount(new Currency(array('value' => 10, 'currency' => 'BRL')));

// $paymentDefinition->setChargeModels(array($chargeModel));

$merchantPreferences = new MerchantPreferences();
$baseUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$returnUrl = str_replace("billing.php", "profile.php", $baseUrl);

// ReturnURL and CancelURL are not required and used when creating billing agreement with payment_method as "credit_card".
// However, it is generally a good idea to set these values, in case you plan to create billing agreements which accepts "paypal" as payment_method.
// This will keep your plan compatible with both the possible scenarios on how it is being used in agreement.
$merchantPreferences->setReturnUrl($returnUrl . "?success=true")
    ->setCancelUrl($returnUrl . "?success=false")
    ->setAutoBillAmount("yes")
    ->setInitialFailAmountAction("CONTINUE")
    ->setMaxFailAttempts("0")
    ->setSetupFee(new Currency(array('value' => 9.99, 'currency' => 'BRL')));;

$plan->setPaymentDefinitions(array($paymentDefinition));
$plan->setMerchantPreferences($merchantPreferences);

// ### Create Plan
try {
    $createdPlan = $plan->create($apiContext);

    try {
        $patch = new Patch();
        $value = new PayPalModel('{"state":"ACTIVE"}');
        $patch->setOp('replace')
            ->setPath('/')
            ->setValue($value);
        $patchRequest = new PatchRequest();
        $patchRequest->addPatch($patch);
        $createdPlan->update($patchRequest, $apiContext);
        $plan = Plan::get($createdPlan->getId(), $apiContext);

        echo $plan->getId();
    } catch (PayPalConnectionException $ex) {
        echo $ex->getCode();
        echo $ex->getData();
        die($ex);
    }
} catch (Exception $ex) {
    exit(1);
}


// return $output;
