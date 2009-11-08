<?php

class SoapClientCache extends SoapClient {
	
	// Override the standard function to include caching
	function __soapCall($function_name, $arguments) {
		pecho($function_name);
		pecho($arguments);
		die;
	}

}