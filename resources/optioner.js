import './sass/optioner.scss';

import 'select2';

( function( $ ) {
	class App {
		wrapper;

		constructor() {
			this.wrapper = $( '#optioner-wrapper' );

			if ( this.wrapper.length === 0 ) {
				return;
			}

			this.initHeading();
			this.initMessage();
			this.initSelect();
			this.initColor();
			this.initMedia();

			const isTab = this.wrapper.find( '.wrap-content' ).hasClass( 'tab-enabled' );

			if ( true === isTab ) {
				this.initTab();
			}
		}

		initHeading() {
			this.wrapper.find( '.form-field-heading' ).each( function() {
				const tr = $( this ).parent().parent();
				tr.find( 'th' ).hide();
				tr.find( 'td' ).attr( 'colspan', '2' );
			} );
		}

		initMessage() {
			console.log('messs')
			this.wrapper.find( '.form-field-message' ).each( function() {
				const tr = $( this ).parent().parent();
				tr.find( 'th' ).hide();
				tr.find( 'td' ).attr( 'colspan', '2' );
			} );
		}

		initSelect() {
			this.wrapper.find( '.form-field-select select' ).each( function() {
				$( this ).select2( { minimumResultsForSearch: 10 } );
			} );
		}

		initColor() {
			this.wrapper.find( '.form-field-color input' ).each( function() {
				$( this ).wpColorPicker();
			} );
		}

		initTab() {
			this.wrapper.find( '.tab-content' ).hide();

			let activeTab = '';

			if ( 'undefined' !== typeof localStorage ) {
				activeTab = localStorage.getItem( OPTIONER_OBJ.storage_key );
			}

			// Initial status for tab content.
			if ( null !== activeTab && $( `#${ activeTab }` ) ) {
				$( `#${ activeTab }` ).show();
				$( `.nav-tab-wrapper a[href="#${ activeTab }"]` ).addClass( 'nav-tab-active' );
			} else {
				this.wrapper.find( '.tab-content' ).first().show();
				this.wrapper.find( '.nav-tab-wrapper a' ).first().addClass( 'nav-tab-active' );
			}

			this.wrapper.find( '.nav-tab-wrapper a' ).on( 'click', ( e ) => {
				e.preventDefault();

				this.wrapper.find( '.nav-tab-wrapper a' ).removeClass( 'nav-tab-active' );
				$( e.target ).addClass( 'nav-tab-active' );

				// Get target.
				const targetGroup = $( e.target ).attr( 'href' );

				// Save active tab in local storage.
				if ( 'undefined' !== typeof localStorage ) {
					localStorage.setItem( OPTIONER_OBJ.storage_key, targetGroup.replace( '#', '' ) );
				}

				this.wrapper.find( '.tab-content' ).hide();
				$( targetGroup ).show();
			} );
		}

		initMedia() {
			let optionerCustomFileFrame = '';

			const $imageField = this.wrapper.find( '.form-field-image' );

			$imageField.find( '.js-upload-image' ).each( ( i, elem ) => {
				const uploaderTitle = $( elem ).data( 'uploader_title' );
				const uploaderButtonText = $( elem ).data( 'uploader_button_text' );

				$( elem ).on( 'click', ( e ) => {
					e.preventDefault();

					if ( optionerCustomFileFrame ) {
						optionerCustomFileFrame.open();
						return;
					}

					// Setup modal.
					const OptionerCustomImage = wp.media.controller.Library.extend( {
						defaults: _.defaults( {
							id: 'optioner-custom-insert-image',
							title: uploaderTitle,
							allowLocalEdits: false,
							displaySettings: false,
							displayUserSettings: false,
							multiple: false,
							library: wp.media.query( { type: 'image' } ),
						}, wp.media.controller.Library.prototype.defaults ),
					} );

					// Create the media frame.
					optionerCustomFileFrame = wp.media.frames.optionerCustomFileFrame = wp.media( {
						button: {
							text: uploaderButtonText,
						},
						state: 'optioner-custom-insert-image',
						states: [
							new OptionerCustomImage(),
						],
						multiple: false,
					} );

					optionerCustomFileFrame.on( 'select', () => {
						const currentImage = optionerCustomFileFrame.state( 'optioner-custom-insert-image' ).get( 'selection' ).first();
						const attachmentURL = currentImage.toJSON().url;

						$( elem ).parent().find( '.field-input' ).val( attachmentURL );
						$( elem ).parent().find( '.preview-wrap' ).addClass( 'preview-on' );
						$( elem ).parent().find( '.field-preview' ).attr( 'src', attachmentURL );
						$( elem ).parent().find( '.js-remove-image' ).removeClass( 'hide' );
					} );

					// Open modal.
					optionerCustomFileFrame.open();
				} );
			} );

			$imageField.find( '.js-remove-image' ).each( ( i, el ) => {
				$( el ).on( 'click', ( e ) => {
					e.preventDefault();
					$( el ).parent().find( '.field-input' ).val( '' );
					$( el ).parent().find( '.preview-wrap' ).removeClass( 'preview-on' );
					$( el ).parent().find( '.field-preview' ).attr( 'src', '' );
					$( el ).parent().find( '.js-remove-image' ).addClass( 'hide' );
				} );
			} );

			$imageField.find( '.field-input' ).each( ( i, elInput ) => {
				$( elInput ).on( 'change keyup paste click', () => {
					const inputValue = $( elInput ).val();

					if ( inputValue !== '' ) {
						$( elInput ).parent().find( '.preview-wrap' ).addClass( 'preview-on' );
						$( elInput ).parent().find( '.field-preview' ).attr( 'src', inputValue );
						$( elInput ).parent().find( '.js-remove-image' ).removeClass( 'hide' );
					} else {
						$( elInput ).parent().find( '.preview-wrap' ).removeClass( 'preview-on' );
						$( elInput ).parent().find( '.js-remove-image' ).addClass( 'hide' );
					}
				} );
			} );
		}
	}

	document.addEventListener( 'DOMContentLoaded', function() {
		new App();
	} );
}( jQuery ) );
