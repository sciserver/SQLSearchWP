/* ========================================================================
 * ZENDGAME: drillablz.js v1.0.0
 * ========================================================================
 * What it does:
 * 
 *   Lets the user drill down via multiple options.
 *   Hides unselected elements. Shows Selected ones.
 * 
 * ======================================================================== 
 * Copyright 2011-2016 IDIES
 * Licensed under MIT 
 * ======================================================================== */

+function ($) {
	
	'use strict';

	// Drillablz PUBLIC CLASS DEFINITION
	// ================================
	var Drillablz = {

		// initialize the plugin
		init: function( context ){
			
			var group_toggles = $( ".drillablz-controls [data-group]:not([data-target])" , context );
			var indiv_toggles = $( ".drillablz-controls [data-group][data-target]" , context );
			var all_targets = $( ".drillablz-target" );
			var hidden_targets = $( ".drillablz-target:not('.hidden')" );
			
			//console.log( "context = '" + context + "'");
			this.reset( context );
			
			// set onclick functions for group-controllers and individual-controllers
			$( group_toggles ).each( function() {
				//console.log( "setting group click functions ");
				$( this ).click( { context: context } , Drillablz.toggleGroup );

				//console.log( "setting indiv click functions ");
				//console.log( $( ".drillablz-controls [data-group='" + $(this).data('group') + "'][data-target]" , context ) );
				$( ".drillablz-controls [data-group='" + $(this).data('group') + "'][data-target]" , context ).each( function(){
					$( this ).click( { context: context } , Drillablz.toggleIndiv );
				});
			});
			
		} ,
		
		// reset the menu count of shown elements
		reset: function( context ){
			
			//console.log("setting the toggle counts");
			//console.log( "found " + $(".drillablz-controls [data-group]:not([data-target])" , context ).length + " groups ");
			var thislen ;
			
			$( ".drillablz-controls [data-group]:not([data-target])" , context ).each( function() {
				//console.log( "Found " + $( ".drillablz-controls [data-group='" + $(this).data('group') + "'][data-target]" , context ).length  + " individual toggles");
				//console.log( "counting not-hidden targets" );
				$( ".drillablz-controls [data-group='" + $(this).data('group') + "'][data-target]" , context ).each(  function() {
					//$( $(this).next('span') ).html( $( ".drillablz-target[class*='drillablz-" + $(this).data('target') + "']:not('.hidden')" , context).length ) ;
					thislen = $( ".drillablz-target[class*='drillablz-" + $(this).data('target') + "']:not('.hidden')" , context).length;
					thislen = (thislen) ? "("+thislen+")" : "";
					$( $(this).next('span') ).html( thislen ) ;
				});
			});
			
			//console.log("setting counts in overview");
			
			$(".drillablz-overview span.showing" , context).html( $( ".drillablz-target:not('.hidden')" , context).length ) ;
			$(".drillablz-overview span.total" , context).html( $( ".drillablz-target" , context).length ) ;
					
		} ,
		
		// toggle an group controller - uncheck indiv if checked
		toggleGroup: function( event ) {
			
			console.log( "group toggle clicked " );
			// if checked
			if ( $( this ).prop('checked') ) {

				//console.log( " group taggle was checked - unchecking indiv in group" );
				$( ".drillablz-controls [data-toggle='drillablz'][data-group='" + $(this).data('group') + "'][data-target]", event.data.context ).each( function(  ) {
					$( this ).prop( 'checked' , false );
				});

			} else {
				//recheck - can't unchecked this way
				//console.log("rechecking, can't unchecked group toggle");
				$( this ).prop( 'checked' , true )
			}
			
			Drillablz.update( event.data.context );
			Drillablz.reset( event.data.context );
			
		} ,
		
		// toggle an 'individual' controller
		toggleIndiv: function( event ) {
			
			//console.log( "individual toggle clicked " );
			// if checked
			if ( $( this ).prop('checked') ) {
				//console.log("individuals checked");
				
				//are all indiv in this group checked?
				if ( $( ".drillablz-controls [data-toggle='drillablz'][data-group='" + $(this).data('group') + "'][data-target]:not(:checked)" , event.data.context ).length ) {
					
					//console.log("unchecking group control");
					$( ".drillablz-controls [data-toggle='drillablz'][data-group='" + $(this).data('group') + "']:not([data-target])" , event.data.context ).prop('checked',false);
					
				} else {
					
					//console.log("all are checked");
					//console.log("checking group control");
					$( ".drillablz-controls [data-toggle='drillablz'][data-group='" + $(this).data('group') + "']:not([data-target])" , event.data.context ).prop('checked',true);
					//console.log("unchecking indiv control");
					$( ".drillablz-controls [data-toggle='drillablz'][data-group='" + $(this).data('group') + "'][data-target]" , event.data.context ).prop('checked',false);
					
				}
			}
						
			Drillablz.update( event.data.context );
			Drillablz.reset( event.data.context );
			
		} ,
		
		// Show targets that have controls Checked.
		update: function( context ){
		
			console.log( "updating... " );
			
			console.log( "show everything");
			$( ".drillablz-target[class*='drillablz-'].hidden" , context ).each( function() {
				$(this).removeClass('hidden');
			});
			
			console.log( "work on unchecked groups:");
			$( ".drillablz-controls [data-toggle='drillablz']:not([data-target]):not(:checked)", context ).each( function() {

				//first hide everything in this group
				console.log( "first hide everything in " + this.dataset.group );
				$( ".drillablz-controls [data-target^='" + this.dataset.group + "']:not(:checked)" , context ).each( function() {
					$( ".drillablz-target[class*='drillablz-" + this.dataset.target + "']:not(.hidden)" , context ).addClass('hidden');
				});
				
				console.log( "then show everything that's checked");
				$( ".drillablz-controls [data-target^='" + this.dataset.group + "'][data-target]:checked" , context ).each( function(  ) {
					$( ".drillablz-target[class*='drillablz-" + this.dataset.target + "'].hidden" , context ).removeClass('hidden');
				});
			});
		}
	}

	$(document).ready(function() {
		
		//give each drillablz container a unique id and initialize it
		var z=0;
		$(".drillablz").each( function() {
			$( this ).prop("id" , $( this ).prop("id").replace(/[^a-z0-9_]/gim,"") );
			if ( $( this ).prop("id").length == 0 ) $( this ).prop( "id" , "drillablz-" + z++ );
			Drillablz.init( "#" + $( this ).prop("id") );
		});

		return;
		
	});
  
}(jQuery);
