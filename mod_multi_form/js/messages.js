/*! jQuery Validation Plugin - v1.19.1 - 6/15/2019
 * https://jqueryvalidation.org/
 * Copyright (c) 2019 Jörn Zaefferer; Licensed MIT */
(function( factory ) {
	if ( typeof define === "function" && define.amd ) {
		define( ["jquery", "../jquery.validate"], factory );
	} else if (typeof module === "object" && module.exports) {
		module.exports = factory( require( "jquery" ) );
	} else {
		factory( jQuery );
	}
}(function( $ ) {
var mfMessages = {};
/*
 * Translated default messages for the jQuery validation plugin.
 * Locale: Joomla Language
 */
jQuery.extend( jQuery.validator.messages, {
	required: Joomla.JText._("MOD_MULTI_FORM_VALIDATE_RQUIRED"),
	remote: Joomla.JText._("MOD_MULTI_FORM_VALIDATE_REMOTE"),
	email: Joomla.JText._("MOD_MULTI_FORM_VALIDATE_EMAIL"),
	url: Joomla.JText._("MOD_MULTI_FORM_VALIDATE_URL"),
	date: Joomla.JText._("MOD_MULTI_FORM_VALIDATE_DATE"),
	dateISO: Joomla.JText._("MOD_MULTI_FORM_VALIDATE_DATE_ISO"),
	number: Joomla.JText._("MOD_MULTI_FORM_VALIDATE_NUMBER"),
	digits: Joomla.JText._("MOD_MULTI_FORM_VALIDATE_DIGITS"),
	creditcard: Joomla.JText._("MOD_MULTI_FORM_VALIDATE_CREDIT_CARD"),
	equalTo: Joomla.JText._("MOD_MULTI_FORM_VALIDATE_EQUAL_TO"),
	extension: Joomla.JText._("MOD_MULTI_FORM_VALIDATE_EXTENSION"),
	maxlength: jQuery.validator.format( Joomla.JText._("MOD_MULTI_FORM_VALIDATE_MAX_LENGHT") ),
	minlength: jQuery.validator.format( Joomla.JText._("MOD_MULTI_FORM_VALIDATE_MIN_LENGHT") ),
	rangelength: jQuery.validator.format( Joomla.JText._("MOD_MULTI_FORM_VALIDATE_RANGE_LENGHT") ),
	range: jQuery.validator.format( Joomla.JText._("MOD_MULTI_FORM_VALIDATE_RANGE") ),
	max: jQuery.validator.format( Joomla.JText._("MOD_MULTI_FORM_VALIDATE_MAX") ),
	min: jQuery.validator.format( Joomla.JText._("MOD_MULTI_FORM_VALIDATE_MIN") )
} );
return $;
}));
//console.log('jQuery.validator.messages',jQuery.validator.messages);
//var mfMessages = jQuery.validator.messages;