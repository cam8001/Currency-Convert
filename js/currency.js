/**
 * @author cam
 */
$('document').ready(function(){
	
	// Include the JS Css
	load_css('/soap/js/js.css');
	
 // Catch the onclick event
 $('form#cc').submit(function(){
  
	target = $('#convert-result');
	hist = $('#convert-history ul');
	
	insertThrobber(target);
	values = getFormValues($('form#cc :input'));
  //@todo: Clean up the values at this point before they get passed into PHP.

	// Run the result
	$.get('proxy.php?do=currencyConvert&amount=' + values['amount'] + '&from=' + values['currency_from'] + '&to=' + values['currency_to'], function(data){
		target.hide();
    target.html(data);
		//hist.append('<li>' + data + '</li>');
		target.fadeIn();
  });
	
	return false;
 });
 
 /**
  * Set up the autocomplete.
  */ 
 $('#currency_from').autocomplete('proxy.php?do=getCurrencies');
 $('#currency_to').autocomplete('proxy.php?do=getCurrencies');
 
 /*
  * Limit currency input to numbers only.
  */
	$('#amount').bind('keypress', function(e){
		var c = String.fromCharCode(e.which);
		//alert('Key Pressed: ' + c + ' ' + e.which);
		/*
		 * If the key pressed is not a digit, don't allow it to entered.
		 */
		// @todo Investigate regex as variable
		if ( ! c.match(/^\d+$/) ) {
		  return false;  
		}
		
	});
 
});



/**
 * @desc Load a new javascript file by adding a script tag to
 * the page.
 *
 * @param path {string} the path to the file you want to load.
 *
 **/
function load_script(path) {
    $('head').append('<scr' + 'ipt type="text/javascript" src="' + path + '"></scr' + 'ipt>');
}

/**
 * @desc Load a new css file by adding a link tag to
 * the page.
 *
 * @param path {string} the path to the file you want to include.
 *
 **/
function load_css(path) {
    $('head').append('<li' + 'nk rel="stylesheet" type="text/css" media="screen" href="' + path + '" />');
}


function insertThrobber(target) {
	var html = '<img src="http://localhost/soap/images/ajax-loader.gif" />';
	target.html(html);
}


function getFormValues(formInputs){
		var values = {};
    formInputs.each(function() {
        values[this.name] = $(this).val();
    });
		
		return values;
}

/*
 * Hex to Decimal and back again.
 */
function d2h(d) {return d.toString(16);}
function h2d(h) {return parseInt(h,16);} 

