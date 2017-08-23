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

	var SQLSDEBUG = true;

	var skyserverws = 'http://skyserver.sdss.org/dr14/en/tools/search/x_results.aspx?searchtool=SQL&TaskName=Skyserver.Search.SQL&ReturnHtml=true&format=html&cmd=';
	
	var sqlsearchwp = {

		context: '#sqls-container',
		
		levels: [
			'info',
			'warning',
			'danger',
		],
		
		whichs: [
			'test',
			'prod',
		],
		
		wheres: {
			'skyserverws': 'http://skyserver.sdss.org/dr14/en/tools/search/x_results.aspx?searchtool=SQL&TaskName=Skyserver.Search.SQL&ReturnHtml=true&format=html&cmd='
			//'odbc',
			//'casjobs',
		},
		
		query: 
			{ test:'SELECT TOP 10 '+
			   'p.objid,p.ra,p.dec,p.u,p.g,p.r,p.i,p.z, '+
			   'p.run, p.rerun, p.camcol, p.field, '+
			   's.specobjid, s.class, s.z as redshift, '+
			   's.plate, s.mjd, s.fiberid '+
			'FROM PhotoObj AS p '+
			   'JOIN SpecObj AS s ON s.bestobjid = p.objid '+
			'WHERE '+
			   'p.u BETWEEN 0 AND 19.6 '+
			'AND g BETWEEN 0 AND 20' ,
			prod: ''
			},
			
		init: function(  ){
						
			var s=this;
			
			// get base url of files, test or prod query, target query location, and how to show results.
			var webroot = $( sqlsearchwp.context ).data('sqls-webroot');
			var which = $( sqlsearchwp.context ).data('sqls-which');
			
			// Show the Search Page
			this.showMessages( 'Welcome' , 'Please enjoy this form.' , 'info' , false );
			this.showInstructions( webroot+"includes/" );
			this.showForm( sqlsearchwp.context , false , true );
			this.showResults( '' , false , false );
			
			// Prevent form submitting/reloading page
			$( sqlsearchwp.context ).on( "submit" , "form#sqls-form" , function( e ){ e.preventDefault(); });
			
			// Add (delegated) click event handlers to buttons
			$( sqlsearchwp.context ).on( "click" , "#sqls-submit" , sqlsearchwp.doSubmit );
			$( sqlsearchwp.context ).on( "click" , "#sqls-syntax" , sqlsearchwp.doSyntax );
			$( sqlsearchwp.context ).on( "click" , "#sqls-reset" , sqlsearchwp.doReset );
			
		},
		
		/**
		 * @summary Submits form data to target db
		 * 
		 * @param Object e Event Object
		**/
		doSubmit: function( e ) {
			if (SQLSDEBUG) { console.log('doSubmit'); }
			
			// Get target db from form data
			var where = $( sqlsearchwp.context ).data('sqls-where');
			var query = sqlsearchwp.wheres[where] +
				encodeURI( $( '#sqls-query' ).val() ) +
				'&syntax=NoSyntax';
				
			//send query from form to skyserverws and listen for return
			var xhttp;
			xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (this.readyState === 4 && this.status === 200) {
					var response = this.responseText;
					sqlsearchwp.showResults( response , false , true );
					sqlsearchwp.showForm( '' , true , false );
				}
			};
			xhttp.open("GET", query , true);
			xhttp.send();
			
		},
		
		/**
		 * @summary Sends form data to skyserverws for syntax review
		 * 
		 * @param Object e Event Object
		**/
		doSyntax: function( e ) {
			if (SQLSDEBUG) { console.log('doSyntax'); }
			// Get target db from form data
			var where = $( sqlsearchwp.context ).data('sqls-where');
			var query = sqlsearchwp.wheres[where] +
				encodeURI( $( '#sqls-query' ).val() ) +
				'&syntax=Syntax';
				
			//send query from form to skyserverws and listen for return
			var xhttp;
			xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (this.readyState === 4 && this.status === 200) {
					var response = this.responseText;
					sqlsearchwp.showResults( response , false , true );
					//sqlsearchwp.showForm( '' , true , true );
				}
			};
			xhttp.open("GET", query , true);
			xhttp.send();
		},
		
		/**
		 * @summary Resets form data
		 * 
		 * @param Object e Event Object
		**/
		doReset: function( e ) {
			if (SQLSDEBUG) { console.log('reset form'); }
			// Reset query - don't do this while testing...
			sqlsearchwp.showResults( '' , false , false );
			sqlsearchwp.showForm( sqlsearchwp.context , false , true );
		},
		
		doCollapse: function( toggle, container, show ) {
			$('.collapse').collapse();
			if ( show === true ) {
				$(container).collapse('show');
			} else {
				$(container).collapse('hide');
			}
			/*/
				$( container ).addClass('collapse in');
				$( container ).removeClass('in');
			/*/
		},
		
		/**
		 * @summary Appends or updates the displayed messages.
		 * 
		 * @param String $title Message Title
		 * @param String $msg Message text
		 * @param String $level One of info, warning, error
		 * @param Boolean $append Append or replace current message(s)
		**/
		showMessages: function( title , msg , level , append ) {
			var msgContainer = $( '.sqls-messages' )[0];
			var msgWrapper = $( '.sqls-messages-wrap' )[0];
			
			// Append or replace existing contents
			var message = ( append !== undefined && append ) ? $(msgContainer).html() : '' ;
			
			// Class for error level
			var msgLevel = ( ( level !== undefined ) && ( sqlsearchwp.levels.indexOf( level ) >= 0 ) ) ? 'alert-'+level : 'alert-primary' ;
			
			// Put Content in alert
			message += ( title !== undefined ) ? 
				'<h3 class="sqls-msg-title ' + msgLevel + ' ">' + 
					'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
					title + '</h3>' : 
				'' ;
			message += ( msg !== undefined ) ? 
				'<div class="sqls-msg-body ' + msgLevel + ' ">'+
					msg + '</div>' : 
				'' ;
			message = '<div class="alert ' + msgLevel + ' alert-dismissable sqls-msg" role="alert">' + message + '</div>';
			$(msgContainer).html( message );
			
			// Hide if empty
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
			xhttp.open("GET", instructions + 'instructions-test.txt' , true);
			xhttp.send();
		},
		
		showForm: function( context , append , show ) {
			var container = $( '#sqls-form' );
			
			var contents = ( append !== undefined && append ) ? $(container).html() : '' ;
			
			var query = sqlsearchwp.query[ $( context ).data('sqls-which') ];
			
			$( '#sqls-query' ).prop( 'value' , query );
			/*/
			if ( show === true ) {
				$( container ).addClass('collapse in');
			} else {
				$( container ).removeClass('in');
			}
			/*/
			sqlsearchwp.doCollapse( '#sqls-form-wrap>h2>a:[data-toggle]', container, show );
			
		},
		
		/**
		 * @summary Appends or updates the displayed Results.
		 * 
		 * @param String $results Results to display
		 * @param Boolean $append Append or replace current message(s)
		**/
		showResults: function( results , append , show ) {
			var container = $( '#sqls-results' );

			var contents = ( append !== undefined && append ) ? $(container).html() : '' ;
			
			contents += ( results !== undefined ) ? results : '' ;
			$(container).html( contents );
			/*/
			if ( show ) {
				$( container ).addClass('collapse in');
			} else {
				$( container ).removeClass('in');
			}
			/*/
			sqlsearchwp.doCollapse( '#sqls-results-wrap>h2>a:[data-toggle]', container, show );
		},
	};

	$(document).ready( function(  ) {
		if ( $( '#sqls-container' ).length === 1 ) {
			sqlsearchwp.init(  );
		} else {
			if (SQLSDEBUG) { console.log('Error running sqlsearchwp.js. Only one "#sqls-container" expected.');}
		}
	} );
	
})(jQuery);