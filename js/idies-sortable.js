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
			Sortable.toggles = new Array();
			Sortable.targets = new Array();
			
			// get all the sortable toggles
			$("[data-toggle=sortable]").each ( function() {
				Sortable.toggles.push( {key:$(this).data("sortable-target") , label:$(this).text() } );
			});
			
			// get the contents of the targets
			$(".data-sortable-target").each ( function() {
				Sortable.targets.push( $(this).html() );
			});
			console.log(Sortable.targets);
			
			
			// set completed to true
			Sortable.completed=true;
			
		},
		
		// Show targets that have controls Checked.
		update: function( b ){
		

		},
		
		// reset the menu count of shown elements
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
