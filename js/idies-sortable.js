/* ========================================================================
 * IDIES: idies-sortable.js v1.0.0
 * ========================================================================
 * What it does:
 * 
 *   Sorts html elements alphabetically.
 *   Toggle which field to sort by.
 * 
 * 
 * ======================================================================== 
 * Copyright 2011-2016 IDIES
 * Licensed under MIT 
 * ======================================================================== */

 //self invoking function
+function ($) {
	
	'use strict';

	// Sortable PUBLIC CLASS DEFINITION
	// ================================
	var Sortable = {
				// initialize the plugin
		init: function( a ){
			
			// set global completed to false
			Sortable.completed = false;
			Sortable.sortby = new Array();
			Sortable.contents = new Array();
			
			// get all the sortable keys
			$("[data-toggle=sortable]").each ( function() {
				Sortable.sortby.push( $(this).data("sortable-by") );
			});
			
			// get the contents of the sortby's
			var i=0;
			var tmp_sortable={};
			var sortable;
			$(".data-sortable-by").each ( function() {
				sortable = this;
				tmp_sortable["dflt"]= i++ ;
				tmp_sortable["html"] = $(this).html() ;
				Sortable.sortby.forEach( function( item , index ){
					tmp_sortable[ item ] = ( $( "[data-sortable-by-" + item + "]" , sortable ).length  == 0 ) ? "" : $("[data-sortable-by-" + item + "]" , sortable).data("sortable-by-" + item);
				});
				Sortable.contents.push( tmp_sortable ) ;
				tmp_sortable={};
			});
			
			// set completed to true
			Sortable.completed=true;
			
		},
		
		update: function( b ){
		

		},
		
		reset: function( c ){
			
		
		}		

	}

	$(document).ready(function() {
		
		if ( $("#sortable-container").length > 0 ) {
			Sortable.init("sortable-container");
		}

		return;
		
	});
  
}(jQuery);
