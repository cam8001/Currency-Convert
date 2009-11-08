<?php
/**
 * @file Test currency conversion using AJAX.
 */
ini_set('display_errors', 0);
 require 'util.php';

 if ( $_REQUEST) {
 	extract($_REQUEST);
  $cc = new CurrencyConvert();
	$result = $cc->convert($amount, $currency_from, $currency_to);
	echo $result;
	pecho($cc);
	
	$output = sprintf("%d %s in %s is %f", $amount, $currency_from, $currency_to, $result);
 }
 
  $cc = new CurrencyConvert();
	//pecho($cc->getSupportedCurrencies());
 
  //$cc = new CurrencyConvert();
  
	//echo $cc->convert(2, 'AUD','THB');
	//pecho($cc);
  //pecho($cc->getFunctions());
  //pecho($cc->getTypes());
 
//$client     = new SoapClient($wsdl_url);
  //pecho($client->__getFunctions());
//pecho($client->KeywordSearchRequest('Drupal'));
//pecho($cc->getSupportedCurrencies());
 // echo $cc->convert(2, 'AUD','THB');
	//pecho($cc);
 
 include 'templates/page.html';
 //include 'typetest.html';