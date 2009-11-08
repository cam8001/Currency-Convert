<?php

function __autoload($class_name) {
    //require_once $class_name . '.php';
	$filename = 'library/'. $class_name .'.class.php';
	if ( file_exists($filename) ) {
	 require_once $filename; 
	} else {
		trigger_error("Could not load class: $class_name");
	}
}


function pecho($object, $require_admin = false) {
  
  $do_print = false;
  if( ! $require_admin ) {
    $do_print = true;
  } else {
    global $user;
    if ($user->uid == 1) {
      $do_print = true;
    }
  }
  
  $stack_trace = debug_backtrace();

  
  if ( $do_print) {
      echo "<pre>" .
      "pecho() called in " . $stack_trace[0]['file'] . ' on line ' . $stack_trace[0]['line'] . "<br /> Output: <br /><hr />".
      print_r($object, true) .
      "</pre>";
  } 
}

