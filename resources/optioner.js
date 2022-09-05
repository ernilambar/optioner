import './sass/optioner.scss';

import 'select2';

class App {
	constructor() {

		if ( jQuery('#optioner-wrapper').length === 0 ) {
			return;
		}

		this.initHeading();
		this.initSelect();
		this.initColor();
		this.initMedia();

		const isTab = document.querySelector( '.wrap-content' ).classList.contains( 'tab-enabled' );

		if ( true === isTab ) {
			this.initTab();
		}
	}

	initHeading() {
		const formFieldHeading = document.getElementsByClassName( 'form-field-heading' );
		const formFieldHeadingArray = [ ...formFieldHeading ];

		formFieldHeadingArray.forEach( ( elem ) => {
			const tr = elem.parentNode.parentNode;

			tr.querySelector( 'th' ).style.display = 'none';
			tr.querySelector( 'td' ).setAttribute( 'colspan', 2 );
		} );
	}

	initSelect() {
		const fieldSelect = document.getElementsByClassName( 'optioner-select' );
		const fieldSelectArray = [ ...fieldSelect ];

		fieldSelectArray.forEach( ( elem ) => {
			jQuery( elem ).select2({minimumResultsForSearch: 10});
		} );

	}

	initColor() {
		const fieldColor = document.getElementsByClassName( 'optioner-color' );
		const fieldColorArray = [ ...fieldColor ];

		fieldColorArray.forEach( ( elem ) => {
			jQuery( elem ).wpColorPicker();
		} );
	}

	initTab() {
		const optionerWrapper = document.getElementById( 'optioner-wrapper' );
		const tabContents = document.getElementsByClassName( 'tab-content' );
		const tabLinks = document.querySelectorAll( '.nav-tab-wrapper a' );

		const tabContentsArray = [ ...tabContents ];
		const tabLinksArray = [ ...tabLinks ];

		// Initially hide tab content.
		tabContentsArray.forEach( ( elem ) => {
			elem.style.display = 'none';
		} );

		let activeTab = '';

		if ( 'undefined' !== typeof localStorage ) {
			activeTab = localStorage.getItem( OPTIONER_OBJ.storage_key );
		}

		// Initial status for tab content.
		if ( null !== activeTab && document.getElementById( activeTab ) ) {
			const targetGroup = document.getElementById( activeTab );
			if ( targetGroup ) {
				targetGroup.style.display = 'block';
			}
		} else {
			tabContents[ 0 ].style.display = 'block';
		}

		// Initial status for tab nav.
		if ( null !== activeTab && document.getElementById( activeTab ) ) {
			const targetNav = optionerWrapper.querySelector( `.nav-tab-wrapper a[href="#${ activeTab }"]` );
			if ( targetNav ) {
				targetNav.classList.add( 'nav-tab-active' );
			}
		} else {
			tabLinks[ 0 ].classList.add( 'nav-tab-active' );
		}

		tabLinksArray.forEach( ( elem ) => {
			elem.addEventListener( 'click', ( e ) => {
				e.preventDefault();

				// Remove tab active class from all.
				tabLinksArray.forEach( ( elemLink ) => {
					elemLink.classList.remove( 'nav-tab-active' );
				} );

				// Add active class to current tab.
				elem.classList.add( 'nav-tab-active' );

				// Get target.
				const targetGroup = elem.getAttribute( 'href' );

				// Save active tab in local storage.
				if ( 'undefined' !== typeof localStorage ) {
					localStorage.setItem( OPTIONER_OBJ.storage_key, targetGroup.replace( '#', '' ) );
				}

				tabContentsArray.forEach( ( elemContent ) => {
					elemContent.style.display = 'none';
				} );

				document.getElementById( targetGroup.replace( '#', '' ) ).style.display = 'block';
			} );
		} );
	}

	initMedia() {
		let optionerCustomFileFrame = '';

		const uploadField = document.getElementsByClassName( 'select-img' );
		const uploadFieldArray = [ ...uploadField ];

		uploadFieldArray.forEach( ( elem ) => {
			const uploaderTitle = elem.dataset.uploader_title;
			const uploaderButtonText = elem.dataset.uploader_button_text;

			elem.addEventListener( 'click', ( e ) => {
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
					const state = optionerCustomFileFrame.state( 'optioner-custom-insert-image' );
					const currentImage = state.get( 'selection' ).first();
					const url = currentImage.toJSON().url;

					elem.parentNode.querySelector( '.img' ).value = url;

					elem.parentNode.querySelector( '.image-preview-wrap' ).innerHTML = `<img src="${ url }" alt="" />`;

					// Show remove button.
					const removeButton = elem.parentNode.querySelector( '.js-remove-image' );
					removeButton.classList.remove( 'hide' );
					removeButton.classList.add( 'show' );
				} );

				// Open modal.
				optionerCustomFileFrame.open();
			} );
		} );

		const btnRemoveImage = document.getElementsByClassName( 'js-remove-image' );
		const btnRemoveImageArray = [ ...btnRemoveImage ];

		btnRemoveImageArray.forEach( ( elem ) => {
			elem.addEventListener( 'click', ( e ) => {
				e.preventDefault();
				// Empty value.
				elem.parentNode.querySelector( '.img' ).value = '';
				// Hide preview.
				elem.parentNode.querySelector( '.image-preview-wrap' ).innerHTML = '';
				// Hide remove button.
				elem.classList.remove( 'show' );
				elem.classList.add( 'hide' );
			} );
		} );
	}
}

document.addEventListener( 'DOMContentLoaded', function() {
	new App();
} );
