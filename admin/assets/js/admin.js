/**
 * Main script file for WP admin
 *
 * @package   PT_Content_Views_Admin
 * @author    PT Guy <palaceofthemes@gmail.com>
 * @license   GPL-2.0+
 * @link      http://www.contentviewspro.com/
 * @copyright 2014 PT Guy
 */

( function ( $ ) {
	"use strict";

	$.PT_CV_Admin = $.PT_CV_Admin || { };
	PT_CV_ADMIN = PT_CV_ADMIN || { };
	ajaxurl = ajaxurl || { };

	$.PT_CV_Admin = function ( options ) {
		this.options = options;
		this.options.onload = 1;
		this.options.scroll_time = 500;
		this.options.can_preview = 1;
	};

	$.PT_CV_Admin.prototype = {
		/**
		 * Toggle panel when click Show/Hide icon on Heading
		 *
		 * @param {type} $selector
		 * @returns {undefined}
		 */
		_toggle_panel: function ( $selector ) {
			$( 'body' ).on( 'click', $selector, function ( e ) {
				var $heading = $( this );
				var $span = $heading.find( 'span.clickable' );
				var time = 100;

				if ( !$span.hasClass( 'panel-collapsed' ) ) {
					$heading.next( '.panel-body' ).slideUp( time );
					$span.addClass( 'panel-collapsed' );
					$span.find( 'i' ).removeClass( 'glyphicon-minus' ).addClass( 'glyphicon-plus' );
				} else {
					$heading.next( '.panel-body' ).slideDown( time );
					$span.removeClass( 'panel-collapsed' );
					$span.find( 'i' ).removeClass( 'glyphicon-plus' ).addClass( 'glyphicon-minus' );
				}
			} );
		},
		/**
		 * Toggle Taxonomy Relation setting on page load & on change
		 *
		 * @returns void
		 */
		_toggle_taxonomy_relation: function () {
			var $self = this;
			var _prefix = $self.options._prefix;

			var $taxonomy_relation = $( '.' + _prefix + 'taxonomy-relation' ).parent().parent( '.form-group' );

			// Taxonomies Settings wrapper div
			var $wrap_taxonomies = $( '#' + _prefix + 'group-taxonomy' );

			// Get Taxonomy checkbox item
			var taxonomy_item = '.' + _prefix + 'taxonomy-item';

			// Run on page load
			$self._do_toggle_taxonomy_relation( $taxonomy_relation, $wrap_taxonomies );

			// Run on change
			$( taxonomy_item ).change( function () {
				$self._do_toggle_taxonomy_relation( $taxonomy_relation, $wrap_taxonomies );
			} );
		},
		/**
		 * Toggle Taxonomy Relation setting by number of selected taxonomies
		 *
		 * @param {type} $taxonomy_relation
		 * @param {type} $wrap_taxonomies
		 * @returns {undefined}
		 */
		_do_toggle_taxonomy_relation: function ( $taxonomy_relation, $wrap_taxonomies ) {
			var $self = this;
			var _prefix = $self.options._prefix;

			// If there is no taxonomies
			if ( $wrap_taxonomies.find( '.' + _prefix + 'taxonomies .checkbox' ).filter( function () {
				return !$( this ).hasClass( 'hidden' ) && $( this ).find( 'input:checked' ).length;
			} ).length > 1 ) {
				$taxonomy_relation.removeClass( 'hidden' );
				$( '.pt-wrap' ).trigger( _prefix + 'multiple-taxonomies', [ 1, $self.options.onload ] );
			} else {
				$taxonomy_relation.addClass( 'hidden' );
				$( '.pt-wrap' ).trigger( _prefix + 'multiple-taxonomies', [ 0, $self.options.onload ] );
			}
		},
		/**
		 * Get field value, depends on field type & its parent is show/hide
		 *
		 * @param {type} el     : string to selector
		 * @returns {undefined}
		 */
		_get_field_val: function ( el ) {
			var $this = $( el );
			var value = $( el ).val();

			if ( $this.is( ':checkbox' ) || $this.is( ':radio' ) ) {
				value = $( el + ':checked' ).val();
			}

			return value;
		},
		/**
		 * Do toggle all dependency groups
		 *
		 * @param {type} $toggle_data_js_
		 * @returns {undefined}
		 */
		dependence_do_all: function ( $toggle_data_js_ ) {
			var $self = this;
			var _prefix = $self.options._prefix;
			var $toggle_data_js = $.parseJSON( $toggle_data_js_ );
			$.each( $toggle_data_js, function ( idx, obj ) {
				// Obj_sub: an object contains (dependence_id, operator, expect_val)
				$.each( obj, function ( key, obj_sub ) {
					// Get name of depended element (which other elements depend on it)
					var el_name = _prefix + key;

					var el = "[name='" + el_name + "']";

					// Run on page load
					$self._dependence_group( $self._get_field_val( el ), obj_sub );

					// Run on change
					$( el ).change( function () {
						$self._dependence_group( $self._get_field_val( el ), obj_sub );
					} );
				} );
			} );
		},
		/**
		 * Toggle each dependency group
		 * @param {type} this_val   : current value of depended element (which other elements depend on it)
		 * @param {type} obj_sub    : an object contains (dependence_id, expect_val, operator)
		 * @returns {undefined}
		 */
		_dependence_group: function ( this_val, obj_sub ) {
			var $self = this;
			$.each( obj_sub, function ( key, data ) {
				$self._dependence_element( data[0], this_val, data[2], data[1] );
			} );
		},
		/**
		 * Toggle each dependency element
		 *
		 * @param {type} dependence_id  : id of group A which depends on an element B
		 * @param {type} this_val   : current value of B
		 * @param {type} operator   : operator to comparing A's value & B's value : =, >, < ...
		 * @param {type} expect_val : expect value of B to show A group
		 * @returns {undefined}
		 */
		_dependence_element: function ( dependence_id, this_val, operator, expect_val ) {

			var dependence_el = $( "#" + dependence_id );
			var pass = 0;
			switch ( operator ) {
				case "=":
					{
						if ( typeof expect_val === 'string' )
							expect_val = [ expect_val ];
						pass = ( $.inArray( this_val, expect_val ) >= 0 );
					}
					break;
				case "!=":
					{
						if ( typeof expect_val === 'string' )
							expect_val = [ expect_val ];
						pass = ( $.inArray( this_val, expect_val ) < 0 );
					}
					break;
				default :
					if ( typeof expect_val !== 'object' )
						pass = eval( "this_val " + operator + " expect_val" );
					break;

			}
			var action = '';
			var result = 0;
			if ( pass ) {
				dependence_el.removeClass( 'hidden' );

				action = 'remove';
				result = !dependence_el.hasClass( 'hidden' );
			} else {
				dependence_el.addClass( 'hidden' );

				action = 'add';
				result = dependence_el.hasClass( 'hidden' );
			}

			// Log if something is wrong
			if ( !result )
				console.log( dependence_id, this_val, operator, expect_val, action );
		},
		/**
		 * Toggle a group inside Panel group when check/uncheck a checkbox inside checboxes list
		 *
		 * @param {type} selector
		 * @param {type} id_prefix
		 * @returns {undefined}
		 */
		toggle_group: function ( selector, id_prefix ) {
			var $self = this;
			// Run on page load
			$( selector ).each( function () {
				$self._toggle_each_group( $( this ), id_prefix );
			} );
			// Run on change
			$( selector ).each( function () {
				$( this ).change( function () {
					var this_ = $( this );
					setTimeout( function () {
						$self._toggle_each_group( this_, id_prefix );
					}, 200 );
				} );
			} );
		},
		/**
		 * Toggle group depends on selector value
		 *
		 * @param {type} $this
		 * @param {type} id_prefix
		 * @returns {undefined}
		 */
		_toggle_each_group: function ( $this, id_prefix ) {
			var $self = this;
			var _prefix = $self.options._prefix;
			if ( $this.is( 'select' ) || ( ( $this.is( ':checkbox' ) || $this.is( ':radio' ) ) && $this.is( ':checked' ) ) ) {
				// Get id of element A which needs to toggle
				var toggle_id = '#' + id_prefix + $this.val();

				// Get siblings groups of A
				var other_groups = $( toggle_id ).parent().children( '.' + _prefix + 'group' ).not( toggle_id );

				if ( $( toggle_id ).hasClass( _prefix + 'only-one' ) ) {
					// Hide other group in a same Panel group
					other_groups.addClass( 'hidden' );
				} else {
				}

				// Show group
				$( toggle_id ).removeClass( 'hidden' );

				// Show the content
				$( toggle_id ).find( '.panel-body' ).show();

				// Scroll to
				if ( !$self.options.onload && !$( toggle_id ).hasClass( _prefix + 'no-animation' ) && $( toggle_id ).offset() ) {
					$( 'html, body' ).animate( {
						scrollTop: $( toggle_id ).offset().top - 40
					}, $self.options.scroll_time );
				}

				// Highlight color
				var activate_group = _prefix + 'group-activate';
				$( toggle_id ).addClass( activate_group );

				// Remove highlight color
				setTimeout( function () {
					$( toggle_id ).removeClass( activate_group );
				}, 2000 );

			} else {
				$( '#' + id_prefix + $this.val() ).addClass( 'hidden' );
			}
		},
		/**
		 * Custom function for 'Content Type'
		 *
		 * @returns {undefined}
		 */
		_content_type: function () {
			var $self = this;
			var _prefix = $self.options._prefix;

			// Taxonomies Settings wrapper div
			var $wrap_taxonomies = $( '#' + _prefix + 'group-taxonomy' );

			// Append <div> : "There is no taxonomy for selected content type" before description of Taxonomies
			var no_taxonomy_id = _prefix + 'no-taxonomy';
			var no_taxonomy_class = _prefix + 'text';
			$wrap_taxonomies.find( '.text-muted' ).first().before( '<div id="' + no_taxonomy_id + '" class="' + no_taxonomy_class + '">' + PT_CV_ADMIN.text.no_taxonomy + '</div>' );
			var no_taxonomy = $( '#' + no_taxonomy_id );

			// Hide all Taxonomies at beginning
			var fn_taxonomy_hide = function ( taxonomies ) {
				taxonomies.each( function () {
					$( this ).parents( '.checkbox' ).addClass( 'hidden' );
				} );
				// Hide no taxonomy div
				no_taxonomy.addClass( 'hidden' );

				// Hide Terms group
				$( '.panel-group.terms' ).find( '.' + _prefix + 'group' ).addClass( 'hidden' );
			};
			var $taxonomies = $( '.' + _prefix + 'taxonomy-item' );
			fn_taxonomy_hide( $taxonomies );

			// Create function to handle
			var fn_content_type = function ( this_val, is_change ) {
				if ( typeof this_val === 'undefined' ) {
					return;
				}

				if ( is_change ) {
					// Uncheck all checkbox of taxonomies
					$taxonomies.attr( 'checked', false );

					// Toggle Taxonomy Relation setting
					var $taxonomy_relation = $( '.' + _prefix + 'taxonomy-relation' ).parent().parent( '.form-group' );
					$self._do_toggle_taxonomy_relation( $taxonomy_relation, $wrap_taxonomies );
				}

				// Show taxonomies relates to selected post type
				if ( this_val !== '' ) {
					fn_taxonomy_hide( $taxonomies );
					$taxonomies.filter( function () {
						var val = $( this ).val();
						var $taxonomies_of_this = PT_CV_ADMIN.data.post_types_vs_taxonomies[this_val] || '';
						return $.inArray( val, $taxonomies_of_this ) >= 0;
					} ).parents( '.checkbox' ).removeClass( 'hidden' );
				}

				// Show there is no taxonomies
				if ( $wrap_taxonomies.find( '.pt-params .checkbox' ).filter( function () {
					return !$( this ).hasClass( 'hidden' );
				} ).length === 0 ) {
					// Show no taxonomy div
					no_taxonomy.removeClass( 'hidden' );
				}

				// Trigger custom actions
				$( '.pt-wrap' ).trigger( 'content-type-change', [ this_val ] );
			};

			// Get "Content Type" input object
			var content_type = '[name="' + _prefix + 'content-type' + '"]';

			// Run on page load
			fn_content_type( $( content_type + ':checked' ).val() );

			// Run on change
			$( content_type ).change( function () {
				fn_content_type( $( content_type + ':checked' ).val(), 1 );
			} );
		},
		/**
		 * Preview handle
		 *
		 * @param {string} _nonce
		 * @returns {undefined}
		 */
		preview: function ( _nonce ) {
			var $self = this;
			var _prefix = $self.options._prefix;

			// Store previous offset top position
			var offset_top;

			$( '#' + _prefix + 'show-preview' ).click( function ( e ) {
				e.stopPropagation();
				e.preventDefault();

				var $this_btn = $( this );

				// Get Preview box
				var $preview = $( '#' + _prefix + 'preview-box' );

				// Show/hide Preview box
				if ( $self.options.can_preview ) {
					$preview.addClass( 'in' );
				} else {
					$preview.removeClass( 'in' );
				}

				/**
				 * Animation
				 */
				// Scroll to preview box if want to show it
				if ( $self.options.can_preview ) {
					// Get current offset top to go back later
					offset_top = $( document ).scrollTop();

					// Scroll to preview box
					$( 'html, body' ).animate( {
						scrollTop: $preview.offset().top - 100
					}, $self.options.scroll_time );

					/// Send request
					$preview.css( 'opacity', '0.2' );
					// Show loading icon
					$preview.next().removeClass( 'hidden' );

					// Get settings data
					var data = $( '#' + _prefix + 'form-view' ).serialize();
					// Call handle function
					$self._preview_request( $preview, data, _nonce, $this_btn );
				} else {
					// Scroll to previous position
					$( 'html, body' ).animate( {
						scrollTop: offset_top
					}, $self.options.scroll_time );

					// Toggle text of this button
					$this_btn.html( PT_CV_ADMIN.btn.preview.show );

					// Enable preview
					setTimeout( function () {
						$self.options.can_preview = 1;
					}, $self.options.scroll_time );
				}
			} );
		},
		/**
		 * Send preview Ajax request
		 *
		 * @param {object} preview_box The jqurey object
		 * @param {string} _data
		 * @param {string} _nonce The generated nonce
		 * @param {object} $this_btn The Show/Hide preview button
		 * @returns void
		 */
		_preview_request: function ( preview_box, _data, _nonce, $this_btn ) {
			var $self = this;
			var _prefix = $self.options._prefix;

			// Setup data
			var data = {
				action: 'preview_request',
				data: _data,
				ajax_nonce: _nonce
			};

			// Sent POST request
			$.ajax( {
				type: "POST",
				url: ajaxurl,
				data: data
			} ).done( function ( response ) {
				if ( response == -1 ) {
					location.reload();
				}

				preview_box.css( 'opacity', '1' );
				// Hide loading icon
				preview_box.next().addClass( 'hidden' );

				// Update content of Preview box
				preview_box.html( response );

				// Toggle text of this button
				$this_btn.html( PT_CV_ADMIN.btn.preview.hide );

				// Disable preview
				$self.options.can_preview = 0;

				// Trigger action, to recall function such as pagination, pinterest render layout...
				$( 'body' ).trigger( _prefix + 'admin-preview' );
			} );
		},
		/**
		 * Toggle 'Thumbnail settings'
		 *
		 * @returns {undefined}
		 */
		_thumbnail_settings: function () {
			var _prefix = this.options._prefix;
			var _thumbnail_setting_state = 1;

			/**
			 * Toggle 'Thumbnail settings' when change 'Layout format'
			 *
			 * @param this_val Layout format value
			 * @returns void
			 */
			var fn_thumbnail_setting = function ( this_val ) {

				var $thumbnail_wrapper = $( '.' + _prefix + 'thumbnail-setting' ).parent();
				if ( this_val === '2-col' ) {
					_thumbnail_setting_state = $thumbnail_wrapper.hasClass( 'hidden' ) ? 0 : 1;
					$thumbnail_wrapper.removeClass( 'hidden' );
				} else if ( this_val === '1-col' ) {
					if ( _thumbnail_setting_state ) {
						$thumbnail_wrapper.removeClass( 'hidden' );
					} else {
						$thumbnail_wrapper.addClass( 'hidden' );
					}
				}
			};

			var layout_format = '[name="' + _prefix + 'layout-format' + '"]';

			// Run on page load
			fn_thumbnail_setting( $( layout_format + ':checked' ).val() );

			// Run on change
			$( layout_format ).change( function () {
				fn_thumbnail_setting( $( layout_format + ':checked' ).val() );
			} );

			/**
			 * Toggle 'Layout format' when change 'View type'
			 *
			 * @param {type} this_val
			 * @param {type} layout_format
			 * @returns {undefined}
			 */
			var fn_layout_format = function ( this_val, layout_format ) {
				var expect_val = [ 'scrollable' ];

				// Add more layouts
				$( '.pt-wrap' ).trigger( 'toggle-layout-format', [ expect_val ] );

				if ( $.inArray( this_val, expect_val ) >= 0 ) {
					// Trigger select 1-col
					$( layout_format + '[value="1-col"]' ).trigger( 'click' );
					// Disable 2-col
					$( layout_format + '[value="2-col"]' ).attr( 'disabled', true );
				} else {
					// Enable 2-col
					$( layout_format + '[value="2-col"]' ).attr( 'disabled', false );
				}
			};

			var view_type = '[name="' + _prefix + 'view-type' + '"]';

			// Run on page load
			fn_layout_format( $( view_type + ':checked' ).val(), layout_format );

			// Run on change
			$( view_type ).change( function () {
				fn_layout_format( $( view_type + ':checked' ).val(), layout_format );
			} );
		},
		/**
		 * Toggle text of Preview button
		 * @returns {undefined}
		 */
		_preview_btn_toggle: function () {

			var $self = this;
			var _prefix = $self.options._prefix;

			var _fn = function ( is_trigger ) {
				if ( !is_trigger ) {
					$self.options.onload = 0;
				}

				// Toggle text of this button
				$( '#' + _prefix + 'show-preview' ).html( PT_CV_ADMIN.btn.preview.update );

				// Enable preview
				$self.options.can_preview = 1;
			};
			// Bind on change input after page load
			$( '.pt-wrap .tab-content' ).on( 'change', 'input, select, textarea', function ( evt, is_trigger ) {
				_fn( is_trigger );
			} );

			$( 'body' ).bind( _prefix + 'preview-btn-toggle', function () {
				_fn();
			} );
		},
		/**
		 * Do handy toggle for Excerpt settings
		 *
		 * @returns {undefined}
		 */
		multi_level_toggle: function () {
			var _prefix = this.options._prefix;

			// For Excerpt Settings
			var _this_toggle = function ( show_content ) {
				if ( !show_content ) {
					$( '#' + _prefix + 'group-excerpt-settings' ).addClass( 'hidden' );
				} else {
					$( '#' + _prefix + 'group-excerpt-settings' ).removeClass( 'hidden' );
				}
			};

			var selector = '[name="' + _prefix + 'show-field-content' + '"]';

			// Run on page load
			_this_toggle( $( selector ).is( ':checked' ) );

			// Run on change
			$( selector ).change( function () {
				_this_toggle( $( selector ).is( ':checked' ) );
			} );

			// Handy do other toggle
			$( '.pt-wrap' ).trigger( _prefix + 'multi-level-toggle' );
		},
		/**
		 * Validate number: prevent negative value
		 * @returns {undefined}
		 */
		validate_number: function () {
			$( 'input[type="number"]' ).on( 'keypress', function ( event ) {
				var min = $( this ).prop( 'min' );
				if ( min == 0 && !( event.charCode >= 48 && event.charCode <= 57 ) )
					event.preventDefault();
			} );
		},
		/**
		 * Custom js for elements
		 * @returns {undefined}
		 */
		custom: function () {
			var $self = this;
			var _prefix = $self.options._prefix;

			$self._preview_btn_toggle();

			// Custom JS for Content Type
			$self._content_type();

			// Toggle Taxonomy Relation
			$self._toggle_taxonomy_relation();

			// Toggle panel of 'Advanced filters'
			$self._toggle_panel( '.' + _prefix + 'group .panel-heading' );

			// 'Thumbnail settings' toggle
			$self._thumbnail_settings();

			// Select 2
			$( '.' + _prefix + 'select2' ).select2();

			// Change class of panel inside panel
			$( '.' + _prefix + 'group .panel .panel' ).each( function () {
				$( this ).removeClass( 'panel-primary' ).addClass( 'panel-info' );
			} );

			// Set custom style for 'Thumbnail position' box
			$( '.' + _prefix + 'bg-none' ).parent().css( { 'background-color': '#fff', 'padding-bottom': '10px' } );
			$( '.' + _prefix + 'bg-none' ).parent().addClass( 'unsortable' );

			// Prevent click on links
			$( '#' + _prefix + 'preview-box' ).on( 'click', 'a', function ( e ) {
				e.preventDefault();
			} );

			// Handle Pagination actions
			$( 'body' ).bind( _prefix + 'admin-preview', function () {
				new $.PT_CV_Public( { _prefix: _prefix } );
			} );

			// Prevent missing changes
			var checked = 0;
			$( '#' + _prefix + 'form-view input[type="submit"]' + ',' + 'a[href*="action=duplicate"]' ).click( function () {
				checked = 1;
			} );
			window.onbeforeunload = function ( event ) {
				if ( !$self.options.onload && !checked ) {
					var message = 'The changes you made will be lost if you navigate away from this page.';
					if ( typeof event === 'undefined' ) {
						event = window.event;
					}
					if ( event ) {
						event.returnValue = message;
					}
					return message;
				}
			};

			// Validate number
			$self.validate_number();
		}
	};
}( jQuery ) );