/* ========================================================================
 * ZENDGAME: filterz.js v1.0.0
 * ========================================================================
 * What it does:
 * 
 * Applies filters to results on a webpage. 
 * All Filters are in groups.
 * Filter group does not include an All or Any checkbox.
 * The filter count indicates how many items that filter applies to.
 * If all filters a toggle applies to are hidden, the toggle will appear gray.
 * The number of filtered results is shown at the top of the page.
 * Applying a filter from a group will *only* show items with that group-filter.
 * Applying a filter from multiple groups will *only* show items will *all* group-filters,
 * the cross product, e.g. size-11 AND color-black.
 * Applied filters are shown next to an X icon.
 * Clear Filters option is shown with Applied Filters
 * A filter can be removed by clicking on the X
 * A filter can be removed by unchecking its box.
 * Each Filter Toggle group can be minimized and maximized, default is max.
 * Applying a filter with no results gives you a message: Sorry, couldn't find any results to show.
 * [optional] Applying a filter willgray out other filters in other groups that would result in a null result.
 *   Lets the user drill down via multiple options.
 *   Hides unselected elements. Shows Selected ones.
 * 
 * ======================================================================== 
 * Copyright 2011-2016 IDIES
 * Licensed under MIT 
 * ======================================================================== */

+function ($) {
	
	'use strict';

	// Filterz PUBLIC CLASS DEFINITION
	// ================================
	var Filterz = {
	
		dim:"0.5",
		normal:"1.0",
		idnum:0,

		// initialize the plugin
		init: function( context ){
			

			//console.log( "setting toggle functions ");
			$( ".filterz-controls [data-target]" , context ).each( function(){
				$( this ).click( { context: context } , Filterz.toggle );
			});
			
			//set a delegated click event handler on all <a>'s in .filterz-filters
			// to clear filters
			$(".filterz-filters").on( "click" , "a" , {context: context} , Filterz.clear );

			this.count( context );
			this.messages( context );
		} ,
		
		// count the elements
		count: function( context ){
			
			//console.log("setting the toggle counts");
			var thislen ;
			
			$( ".filterz-controls [data-target]" , context ).each( function() {
				
				if ( $( ".filterz-target[class*='filterz-" + $(this).data('target') + "']" , context).length ) {
					
					//there are some targets for this filter - unhide & set count
					$( $(this).parent('label') ).removeClass('hidden');
					
					$( $(this).nextAll('span.count') ).html( "(" + $( ".filterz-target[class*='filterz-" + $(this).data('target') + "']:not(.hidden)" , context).length + " of " + $( ".filterz-target[class*='filterz-" + $(this).data('target') + "']" , context).length + ")" ) ;
										
				} else {
					
					//there are no targets for this filter - hide it
					$( $(this).parent('label') ).addClass('hidden');
			
				}	
			//});
			
			//$( ".filterz-controls [data-target]" , context ).each( function() {
				
				if( $( ".filterz-target[class*='filterz-" + $(this).data('target') + "']:not(.hidden)" , context).length ) {
					$( $(this).parent('label') ).css("opacity",Filterz.normal);
				} else { 
					$( $(this).parent('label') ).css("opacity",Filterz.dim);
				}			
	
			});
			
		} ,
		
		// update the overview message and filters message
		messages: function( context ){
			
			//console.log("setting counts in overview message");
			$(".filterz-overview span.showing" , context).html( $( ".filterz-target:not('.hidden')" , context).length ) ;
			$(".filterz-overview span.total" , context).html( $( ".filterz-target" , context).length ) ;
			
			//Show which filters have been applies, clicking x turns them off
			if ($( ".filterz-controls [data-toggle='filterz'][data-target]:checked" , context ).length) {
				var filtermsg = "<strong>Your Filters: </strong>";
				$( ".filterz-controls [data-toggle='filterz'][data-target]:checked" , context ).each(function() {
					filtermsg += $( $(this).nextAll('span.name') ).html() + " <a data-clearfilterz='" + this.dataset.target + "'><i class='fa fa-times-circle'></i></a> | " ;
				});
				$(".filterz-filters" ,  context ).html(filtermsg + "Clear All <a data-clearfilterz='all'><i class='fa fa-times-circle'></i></a> ");
				$(".filterz-filters" ,  context ).removeClass("hidden");

			} else {
				$(".filterz-filters" ,  context ).addClass("hidden");
			}
			
			//if no results, populate noresults msg
			if( !$( ".filterz-target:not(.hidden)" , context).length) {
				$(".filterz-noresults" ,  context ).html("<em>Sorry, no results found.</em>");
			} else {
				$(".filterz-noresults" ,  context ).html("");
			}
			
		} ,
		
		// clear a filter
		clear: function( event ) {
			if ( "all" == event.currentTarget.dataset.clearfilterz) {
				$( ".filterz-controls [data-toggle='filterz'][data-target]:checked" , event.data.context ).each(function(){
					$(this).click();
				});
			} else {
				$(".filterz-controls [data-toggle='filterz'][data-target='" + event.currentTarget.dataset.clearfilterz + "']", event.data.context ).click();
			}
		} ,
		
		// toggle a filter
		toggle: function( event ) {
			//console.log( "toggle clicked " );
			
			if ( $( this ).prop('checked') ) {
				//console.log("checked");
				
				if ( !$( ".filterz-controls [data-toggle='filterz'][data-group='" + $(this).data('group') + "'][data-target]:not(:checked)" , event.data.context ).length ) {
									
					//console.log("all in group are checked, unchecking all ");
					$( ".filterz-controls [data-toggle='filterz'][data-group='" + $(this).data('group') + "'][data-target]" , event.data.context ).prop('checked',false);
					
				} 
				
			} else {
				//console.log("unchecked");
			}
								
			Filterz.update( event.data.context );
			
		} ,
		
		// Update filtered view
		update: function( context ){

			//console.log( "updating... " );
			var all_sets = [ [] ];
			var last_group;
			var current = 0;
			
			//console.log( "get checked toggles");
			$( ".filterz-controls [data-toggle='filterz'][data-target]:checked" , context ).each(function(){
				if ( undefined == last_group ) last_group = this.dataset.group;
				if ( last_group == this.dataset.group ) {
					all_sets[current].push( ".filterz-"+this.dataset.target);
				} else {
					++current;
					all_sets.push([".filterz-"+this.dataset.target]);
					last_group = this.dataset.group;
				}
			});

			//get the cartesian product of all the class combinations
			var next, last, curr;
			while ( all_sets.length > 1 ) {
				var next = all_sets.pop();
				var last = all_sets.pop();
				curr = [];
				next.forEach( function(n) {
					last.forEach( function(l) {
						curr.push(n+l);
					});
				});
				all_sets.push(curr);
			}
			var filters = all_sets.join(",");
			
			if (filters.length) {
				//got filterz , hide everything and show filters
				$(".filterz-target:not(.hidden)" , context ).each( function(){
					$(this).addClass("hidden")
				});
				$(filters).each( function(){
					$(this).removeClass("hidden");
				});
			} else {
				//no filters, show everything
				$(".filterz-target.hidden" , context ).each( function(){
					$(this).removeClass("hidden")
				});
			}
			
			Filterz.count( context );
			Filterz.messages( context );
		},
	}

	$(document).ready(function() {
		
		//give each filterz container a unique id and initialize it
		var z=0;
		$(".filterz").each( function() {
			$( this ).prop("id" , $( this ).prop("id").replace(/[^a-z0-9_]/gim,"") );
			if ( $( this ).prop("id").length == 0 ) $( this ).prop( "id" , "filterz-" + z++ );
			Filterz.init( "#" + $( this ).prop("id") );
		});

		return;
	});
  
}(jQuery);
