(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	 function ajaxformsendmail(name,email,subject,contents)

		{

		jQuery.ajax({

		type: 'POST',

		url: ajaxcontactajax.ajaxurl,

		data: {

		action: 'ajaxcontact_send_mail',

		acfname: name,

		acfemail: email,

		acfsubject:subject,

		acfcontents:contents

		},

		 

		success:function(data, textStatus, XMLHttpRequest){

		var id = '#ajaxcontact-response';

		jQuery(id).html('');

		jQuery(id).append(data);

		},

		 

		error: function(MLHttpRequest, textStatus, errorThrown){

		alert(errorThrown);

		}

		 

		});

		}
})( jQuery );
