<?php

class CurrencyConvert {
	
	
	private $client; // This is the webservice provided class.
  private $webServiceUrl = 'http://webservice.webserviceshare.com/currencyconverter/rates.asmx?WSDL';

  private $webServiceKey = 'c2e7dbbb-4ffa-4fb8-a6a5-797bae45d636';
	
	public $currencyFrom;
	public $currencyTo;
	
	public $cacheDir = 'cache';
	
  private $supportedCurrencies;
	
	function __construct() {
		$this->client = new SoapClient($this->webServiceUrl);
		$currencies = $this->loadFromCache('GetSupportedCurrencies');
		if ( $currencies ) {
		  $this->supportedCurrencies = $currencies;
		}
	}
	
	public function getFunctions() {
		return $this->client->__getFunctions();
	}
	
	public function getTypes() {
    return $this->client->__getTypes();
  }


  public function convert($amount, $from, $to) {
  	
		// Pull out the country code only, regardless of whether the name of the currency has been passed.
		/*
		$regex = '/[A-Z]3/';
		$from = str_replace(preg_replace('/([A-Z]{3})/', '',  $from), '', $from);
    $to = str_replace(preg_replace('/([A-Z]{3})/', '',  $to), '', $to);
		*/
		$from = substr(trim($from),0,3);
		$to = substr(trim($to),0,3);
  	// First, set up the convesion rates
		$this->getRates($from, $to);
		
		// Now do the calculation
		return $amount * $this->currencyTo->Amount;
		
		
  }
	
	private function getRates($from, $to) {
		
		$params = array(
		  'Key' => (string)$this->webServiceKey,
			'CurrencyFrom' => (string) $from,
			'CurrencyTo' => (string) $to
			
		);
		try {
		  $result = $this->client->getRates($params)->GetRatesResult;
		} catch (Exception $e) {
	 	   //pecho($_SERVER['REQUEST_URI']);
			 //pecho($e);
		}
		$this->currencyFrom = $result->CurrencyFrom;
		$this->currencyTo = $result->CurrencyTo->CurrencyToRow;
	}
	
	
	private function supportedCurrencies() {
		if ( !$this->supportedCurrencies) {
      $result = $this->client->GetSupportedCurrencies()->GetSupportedCurrenciesResult->CurrencyListRow;			
      //$this->supportedCurrencies = $result->GetSupportedCurrenciesResult->CurrencyListRow;
			$this->supportedCurrencies = $result;
			// Save the result so as not to look it up every time.
      // @todo Create a webservice cache class to cache all results.
			// @todo Extend the SOAP class to automatically cache.
			$this->cacheResult($result, 'GetSupportedCurrencies');
		}
	}
	
	public function getSupportedCurrencies() {
		$this->supportedCurrencies();
		// Convert the array of objects into a associative array
		foreach($this->supportedCurrencies as $currency) {
			$return[$currency->CurrencyShortName] = $currency->CurrencyName;
		}
		
		return $return;
		
	}
	
	private function cacheResult($result, $methodCall) {
		
		// Create a new cache file
		$path = getcwd() . '/' . $this->cacheDir .'/'. $methodCall .'.cache';
		$handle = fopen($path, 'w');
		fwrite($handle, serialize($result));
		fclose($handle);

	}
	
	private function loadFromCache($methodCall) {
		$var = '';
		$path = $this->filePath($methodCall);
		if ( !file_exists($path) ) {
		  return FALSE;
		}
		
    $handle = fopen($path, 'r');
		while (!feof($handle)) {
		  $var .= fread($handle, 4096);
		}
		//pecho(unserialize($var));die;
		return unserialize($var);
	}
	
	
	private function filePath($methodCall) {
		$path = getcwd() . '/' . $this->cacheDir .'/'. $methodCall .'.cache';
		return $path;
	}
	

}



