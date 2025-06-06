import 'tom-select/dist/css/tom-select.default.css';
import './styles/optioner.css';

import TomSelect from 'tom-select';

import 'conditionize2';

( function ( $ ) {
	const initializeCodeEditor = ( tabId ) => {
		jQuery( tabId )
			.find( '.code-editor' )
			.each( function () {
				const isInitialized = $( this ).data( 'initialized' );
				if ( '1' !== isInitialized ) {
					const settings =
						'javascript' === $( this ).data( 'mime' )
							? codeEditorSettings.javascript
							: codeEditorSettings.css;
					const textareaId = $( this ).attr( 'id' );
					$( this ).data( 'initialized', '1' );
					wp.codeEditor.initialize( textareaId, settings );
				}
			} );
	};

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
			} else {
				this.wrapper.find( '.tab-content' ).fadeIn( 'fast' );
				const tabSingleId = this.wrapper.find( '.tab-content' ).attr( 'id' );
				initializeCodeEditor( `#${ tabSingleId }` );
			}
		}

		initHeading() {
			this.wrapper.find( '.form-field-heading' ).each( function () {
				const tr = $( this ).parent().parent();
				tr.find( 'td' ).attr( 'colspan', '2' );
			} );
		}

		initMessage() {
			this.wrapper.find( '.form-field-message' ).each( function () {
				const tr = $( this ).parent().parent();
				tr.find( 'td' ).attr( 'colspan', '2' );
			} );
		}

		initSelect() {
			this.wrapper
				.find( '.form-field-select select.optioner-stylish-select' )
				.each( function () {
					const settings = {
						searchField: 'text',
						create: false,
						allowEmptyOption: false,
						highlight: true,
						onType: function ( str ) {
							if ( str.length > 0 && this.getValue() ) {
								this.clear( true );
							}
						},
					};
					new TomSelect( this, settings );
				} );
		}

		initColor() {
			this.wrapper.find( '.form-field-color input' ).each( function () {
				$( this ).wpColorPicker();
			} );
		}

		initTab() {
			this.wrapper.find( '.tab-content' ).hide();

			let activeTab = '';

			if ( 'undefined' !== typeof localStorage ) {
				activeTab = localStorage.getItem( optionerObject.storage_key );
			}

			// Initial status for tab content.
			if ( null !== activeTab && $( `#${ activeTab }` ).length ) {
				$( `#${ activeTab }` ).hide().fadeIn( 'fast' );
				initializeCodeEditor( `#${ activeTab }` );
				$( `.optioner-tabs-nav a[href="#${ activeTab }"]` ).addClass( 'active' );
			} else {
				this.wrapper.find( '.tab-content' ).first().hide().fadeIn( 'fast' );
				this.wrapper.find( '.optioner-tabs-nav a' ).first().addClass( 'active' );
			}

			this.wrapper.find( '.optioner-tabs-nav a' ).on( 'click', ( e ) => {
				e.preventDefault();

				if ( $( e.target ).hasClass( 'active' ) ) {
					return;
				}

				this.wrapper.find( '.optioner-tabs-nav a' ).removeClass( 'active' );
				$( e.target ).addClass( 'active' );

				// Get target.
				const targetGroup = $( e.target ).attr( 'href' );

				// Save active tab in local storage.
				if ( 'undefined' !== typeof localStorage ) {
					localStorage.setItem(
						optionerObject.storage_key,
						targetGroup.replace( '#', '' )
					);
				}

				this.wrapper.find( '.tab-content' ).hide();
				$( targetGroup ).fadeIn( 'fast' );
				initializeCodeEditor( targetGroup );
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
						defaults: _.defaults(
							{
								id: 'optioner-custom-insert-image',
								title: uploaderTitle,
								allowLocalEdits: false,
								displaySettings: false,
								displayUserSettings: false,
								multiple: false,
								library: wp.media.query( {
									type: 'image',
								} ),
							},
							wp.media.controller.Library.prototype.defaults
						),
					} );

					// Create the media frame.
					optionerCustomFileFrame = wp.media.frames.optionerCustomFileFrame = wp.media( {
						button: {
							text: uploaderButtonText,
						},
						state: 'optioner-custom-insert-image',
						states: [ new OptionerCustomImage() ],
						multiple: false,
					} );

					optionerCustomFileFrame.on( 'select', () => {
						const currentImage = optionerCustomFileFrame
							.state( 'optioner-custom-insert-image' )
							.get( 'selection' )
							.first();
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

	document.addEventListener( 'DOMContentLoaded', function () {
		new App();

		jQuery( '.conditional' ).conditionize( {
			onload: true,
			ifTrue( $s ) {
				$s.closest( 'tr' ).show();
			},
			ifFalse( $s ) {
				$s.closest( 'tr' ).hide();
			},
		} );
	} );
} )( jQuery );
