<?php
include 'util.php';

$function = $_GET['do'];

if ( function_exists($function)) {
	echo $function($_GET);
} 
else { 
  echo "Bad function name.";
}

/**
 * 
 * Load a remote webpage.
 * 
 * @param array $args
 * @return HTML string of page
 */
function getUrl($args) {
	
	$fp = fopen( $args['url'], 'r');
  $content = "";

  while( !feof($fp) ) {
    $buffer = trim( fgets( $fp, 4096 ) );
    $content .= $buffer;
	}
	
	return $content;
}


function currencyConvert($args) {
	extract($args);
	$cc = new CurrencyConvert();
	
	// Let's make a nice format.
	$currencies = $cc->getSupportedCurrencies();
	
	$from = strtoupper(substr($from,0,3));
	$to = strtoupper(substr($to,0,3));
	
	
	$from_text = $currencies[$from];
	$to_text = $currencies[$to];
	
	$number = money_format('%i', $cc->convert($amount, $from, $to));
	
	return $amount .' '. $from_text .' is equal to '. $number .' '. $to_text .'s.';
}

function getCurrencies($args) {
	
  $input = strtolower($args['q']);
  $output = array();

	$cc = new CurrencyConvert();
	$array = $cc->getSupportedCurrencies();
	// Make sure they're alphabetic
	ksort($array, SORT_STRING);
	
	// Try this crap hack
	foreach($array as $key=>$value) {
		//$tmp[] = $key .': '. $value;
	}
	//$array = $tmp;
	
	
	// Reduce the array to only those values specified by the user.
	// @todo There must be a better way to reduce!
	foreach($array as $key=>$value) {
		//if ( (strpos(strtolower($key), $input) !== FALSE) || (strpos(strtolower($value), $input) !== FALSE) ) {
		if ((strpos(strtolower($key . $value), $input) !== FALSE) ) { 
		  $output[] = "$key: $value\n";
		}
	}
	
	if ($output) {
    return implode("\n", $output);
	}
	
	return 'Nothing found!';
	
}
