/* ========================================================================
 * sqlsearchwp v1.0.0
 * ========================================================================
 *
 * What it does:
 * 		Nothing yet...
 * 
 * Licensed under MIT 
 * ======================================================================== */
(function($) {
	'use strict';

	// PUBLIC CLASS DEFINITION
	// ================================

	var sqlsearchwp = {
				
		init: function( context ){
			var dflt_which = 'test';
			
			var webroot = $( context ).data('sqls-webroot');
			var which = ( $( context ).data('sqls-which') === undefined ) ? dflt_which : $( context ).data('sqls-which') ;

			this.showMessages(  );
			this.showInstructions( webroot+"includes/" );
			this.showForm(  );
			this.showResults(  );
			
		},
		
		showMessages: function( newTitle , newMessage ) {
			var msgContainer = $( '.sqls-messages' )[0];
			var msgWrapper = $( '.sqls-messages-wrap' )[0];
			
			// Show new messages as needed
			
			// Hide if still empty
			$( msgWrapper ).show();
			if ( $(msgContainer).html().length === 0 ) {
				$( msgWrapper ).hide();
			} 
		},
		
		showInstructions: function( instructions ) {
			var instContainer = $( '.sqls-instructions' )[0];
			var instWrapper = $( '.sqls-instructions-wrap' )[0];
			
			var xhttp;
			xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (this.readyState === 4 && this.status === 200) {
					var response = this.responseText;
					$( instContainer ).html(response);
				}
			};
			xhttp.open("GET", instructions , true);
			xhttp.send();
		},
		
		showForm: function() {
		},
		
		showResults: function() {
		},
		
	};

	$(document).ready( function(  ) {
		if ( $( '#sqls-container' ).length === 1 ) {
			sqlsearchwp.init( '#sqls-container' );
		} else {
			console.log('Error running sqlsearchwp.js. Only one "#sqls-container" expected.');
		}
	} );
	
})(jQuery);