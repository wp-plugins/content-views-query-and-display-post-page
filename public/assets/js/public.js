/**
 * Common scripts for Front-end
 *
 * @package   PT_Content_Views
 * @author    PT Guy <palaceofthemes@gmail.com>
 * @license   GPL-2.0+
 * @link      http://www.contentviewspro.com/
 * @copyright 2014 PT Guy
 */

( function ( $ ) {
	"use strict";

	$.PT_CV_Public = $.PT_CV_Public || { };

	PT_CV_PUBLIC = PT_CV_PUBLIC || { };

	$.PT_CV_Public = function ( options ) {
		this.options = $.extend( { }, options );
		this.move_bootstrap_to_top();
		this.pagination();
	};

	$.PT_CV_Public.prototype = {
		/**
		 * Manually move Bootstrap to top of all styles
		 *
		 * @returns {undefined}
		 */
		move_bootstrap_to_top: function () {
			if ( PT_CV_PUBLIC.move_bootstrap === '0' ) {
				return;
			}

			var _prefix = PT_CV_PUBLIC._prefix;

			var selector = _prefix + 'bootstrap-style-css';
			var bootstrap_css = $( '#' + selector );
			if ( bootstrap_css.length > 0 ) {
				bootstrap_css.remove();
				$( 'title' ).after( "<!-- Manually move Bootstrap to top of all styles --><link rel='stylesheet' id='" + selector + "' href='" + bootstrap_css.attr( 'href' ) + "' type='text/css' media='all' />" );
			}
		},
		/**
		 * Bootstrap pagination
		 * @returns {undefined}
		 */
		pagination: function () {
			var $self = this;
			var _prefix = PT_CV_PUBLIC._prefix;

			// Get current page
			var query_string = window.location.search;
			var vpage = query_string.split( /vpage=/ );
			var current_page = ( vpage[1] !== null && vpage[1] !== undefined ) ? parseInt( vpage[1] ) : 1;

			// Bootstrap paginator
			$( '.' + _prefix + 'pagination.' + _prefix + 'ajax' ).each( function () {
				var this_ = $( this );
				var total_pages = $( this ).attr( 'data-totalpages' );
				$( this ).bootstrapPaginator( {
					bootstrapMajorVersion: 3,
					currentPage: current_page,
					totalPages: total_pages,
					shouldShowPage: function ( type, page, current ) {
						if ( total_pages && total_pages < 10 ) {
							switch ( type ) {
								case "first":
								case "last":
									return false;
								default:
									return true;
							}
						} else {
							return true;
						}
					},
					// When changing page
					onPageClicked: function ( e, originalEvent, type, page ) {
						var selected_page = page;

						$self._setup_pagination( this_, selected_page, function () {
							PT_CV_PUBLIC.paging = 0;
						} );
					}
				} );
			} );
		},
		/**
		 * Get parameters to process pagination
		 *
		 * @param {object} this_
		 * @param {int} selected_page
		 * @param {function} callback
		 * @returns {undefined}
		 */
		_setup_pagination: function ( this_, selected_page, callback ) {
			var $self = this;
			var _prefix = PT_CV_PUBLIC._prefix;

			PT_CV_PUBLIC.paging = PT_CV_PUBLIC.paging || 0;
			// Prevent duplicate processing
			if ( PT_CV_PUBLIC.paging ) {
				return;
			} else {
				PT_CV_PUBLIC.paging = 1;
			}

			var session_id = this_.attr( 'data-sid' );
			var spinner = this_.next( '.' + _prefix + 'spinner' );

			// Get the pagination element
			var pagination_wrapper = this_;
			if ( this_.parent( '.' + _prefix + 'pagination-wrapper' ).length ) {
				pagination_wrapper = this_.parent( '.' + _prefix + 'pagination-wrapper' );
			}

			// Get the View element
			var pages_holder = pagination_wrapper.prev( '.' + _prefix + 'view' );

			// For Timeline
			if ( pages_holder.hasClass( _prefix + 'timeline' ) ) {
				pages_holder = pages_holder.children( '.tl-items' );
			}

			$self._get_page( session_id, selected_page, spinner, pages_holder, callback );
		},
		/**
		 * Get wrapper of selected page
		 *
		 * @param {string} session_id The session id of view
		 * @param {int} selected_page The page to show
		 * @param {object} spinner The jquery object of loading element
		 * @param {string} pages_holder The selector expression of wrapper of pages
		 * @param {function} callback The callback function
		 * @returns void
		 */
		_get_page: function ( session_id, selected_page, spinner, pages_holder, callback ) {

			var $self = this;
			// Show content of page if it existed
			var page_existed = $self._active_page( selected_page, pages_holder, callback );
			// If page is loaded, exit
			if ( page_existed ) {
				return;
			}

			// Setup data
			var data = {
				action: 'pagination_request',
				sid: session_id,
				page: selected_page,
				lang: PT_CV_PUBLIC.lang,
				ajax_nonce: PT_CV_PUBLIC._nonce
			};

			// Sent POST request
			$.ajax( {
				type: "POST",
				url: PT_CV_PUBLIC.ajaxurl,
				data: data,
				beforeSend: function () {
					// Show loading icon
					spinner.addClass( 'active' );
				}
			} ).done( function ( response ) {
				// Hide loading icon
				spinner.removeClass( 'active' );

				// Update content of Preview box
				pages_holder.append( response );

				// Active current page
				$self._active_page( selected_page, pages_holder, callback );
			} );
		},
		/**
		 * Show content of selected page
		 *
		 * @param {int} selected_page The page to show
		 * @param {string} pages_holder The selector expression of wrapper of pages
		 * @param {function} callback The callback function
		 * @returns bool
		 */
		_active_page: function ( selected_page, pages_holder, callback ) {
			var _prefix = PT_CV_PUBLIC._prefix;
			var page_existed = false;
			var page_selector = '#' + _prefix + 'page' + '-' + parseInt( selected_page );

			if ( pages_holder.children( page_selector ).length ) {
				page_existed = true;

				// Hide all pages
				pages_holder.children().hide();

				// Show this page
				pages_holder.children( page_selector ).show();

				// Scroll to this page
				$( 'html, body' ).animate( {
					scrollTop: pages_holder.children( page_selector ).offset().top - 160
				}, 1000 );
			}

			if ( callback && typeof callback === 'function' ) {
				callback();
			}

			// Trigger to make Pinterest layout works when do pagination
			$( 'body' ).trigger( _prefix + 'pagination-finished' );

			return page_existed;
		}
	};

	$( function () {
		var _prefix = PT_CV_PUBLIC._prefix;

		// Run at page load
		new $.PT_CV_Public( { _prefix: _prefix } );
	} );

}( jQuery ) );