/* ========================================================================
 * ZENDGAME: sortablz.js v1.0.0
 * ========================================================================
 * What it does:
 * 
 *   Sorts html elements (generally divs) alphabetically.
 *   You can toggle which field to sort by.
 *   You can have more than one sortable list on a page.
 * 
 * ======================================================================== 
 * Copyright 2011-2016 IDIES
 * Licensed under MIT 
 * ======================================================================== */

+function ($) {
	
	'use strict';

	// Sortablz PUBLIC CLASS DEFINITION
	// ================================
	var Sortablz = {

	init: function( context ){
			
			var toggles = new Array();
			var targets = $(".sortablz-target" , context);
			var dataz = new Array();
			var order = new Array();
			var this_data= new Array();

			// create an array of the sorting toggles keys
			$( "[data-toggle=sortablz]" , context ).each( function(){
				order.push(toggles.length);
				toggles.push( $( this ).data( "sortablz" ) );
			});

			// set the function to call when a toggle is clicked
			$( "[data-toggle=sortablz]" , context ).each( function(){
				$(this).click( {
						which: $(this).data("sortablz"), 
						context : context
					//} , Sortablz.update );
					} , Sortablz.safeupdate );
			});
			
			// create a multi dimensional array of the sortable elements' data
			// n-1 columns hold the values to sort by, the nth column holds the html to show.
			$(".sortablz-target>.sortablz-contents" , context).each ( function() {
				this_data = Array();		
				toggles.forEach( function( e ){ this_data.push( ( $( this ).data( e ) == undefined  ? "" : $( this ).data( e ).toString() ) ) }, this );
				this_data.push( this.outerHTML );
				dataz.push( this_data );
			});
			
			//save toggles, initilized order, and dataz
			this[context] = { toggles: toggles, order: order, dataz: dataz};
		
			// manually click the selected toggle
			$( "[data-toggle=sortablz][checked]" ).click();
			
		},
				
		safeupdate: function( event ){
			
			var dataz = new Array();
			var this_data= new Array();
			
			// create a multi dimensional array of the sortable elements' data
			// n-1 columns hold the values to sort by, the nth column holds the html to show.
			$(".sortablz-target>.sortablz-contents" , event.data.context).each ( function() {
				this_data = Array();		
				Sortablz[event.data.context].toggles.forEach( function( e ){ this_data.push( ( $( this ).data( e ) == undefined  ? "" : $( this ).data( e ).toString() ) ) }, this );
				this_data.push( this.outerHTML );
				dataz.push( this_data );
			});
			
			//save toggles, initilized order, and dataz
			Sortablz[event.data.context]['dataz'] = dataz;
			
			Sortablz.update( event ) ;
					
		},
				
		update: function( event ){
			var tndx = Sortablz[ event.data.context ].toggles.indexOf( event.data.which );
			var i=0;
			var n=Sortablz[ event.data.context ].order.length;
			
			//rearrange the order
			Sortablz[ event.data.context ].order.splice( Sortablz[ event.data.context ].order.indexOf( tndx ) , 1 );			
			Sortablz[ event.data.context ].order.unshift( tndx );
			
			Sortablz[ event.data.context ].dataz.sort( function( a , b ) {
				var by;
				for (var i=0; i<Sortablz[ event.data.context ].order.length ; i++) {

					by = Sortablz[ event.data.context ].order[i];					
					if ( 0 === a[ by ].length ) return 1;
					if ( 0 === b[ by ].length ) return -1;
					if ( a[by] !== b[by] ) return ( ( a[ by ]> b[ by ] ) ? 1 : -1 );
										
				}
				return -1 ;
			});
			
			$(".sortablz-target" , event.data.context ).each( function() {
				$(this).html( Sortablz[ event.data.context ].dataz[i++][n]);
			});
		},
	}

	$(document).ready(function() {
		var z=0;
		$(".sortablz").each( function() {
			$( this ).prop("id" , $( this ).prop("id").replace(/[^a-z0-9_]/gim,"") );
			if ( $( this ).prop("id").length == 0 ) $( this ).prop( "id" , "sortablz-" + z++ );
			Sortablz.init( "#" + $( this ).prop("id") );
		});

		return;
		
	});
  
}(jQuery);
