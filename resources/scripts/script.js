class App {

	constructor() {
		this.initHeading();
		this.initColor();
		this.initMedia();
	}

	initHeading() {
		const formFieldHeading = document.getElementsByClassName('form-field-heading');

		for ( let i = 0; i < formFieldHeading.length; i++ ) {
		   let elem = formFieldHeading[i];
		   let tr = elem.parentNode.parentNode;

		   tr.querySelector('th').style.display = 'none';
		   tr.querySelector('td').setAttribute( 'colspan', 2 );
		}
	}

	initColor() {
		const fieldColor = document.getElementsByClassName( 'optioner-color' );

		for ( let i = 0; i < fieldColor.length; i++ ) {
		   let elem = fieldColor[i];
		   jQuery(elem).wpColorPicker();
		}
	}

	initMedia() {
		let optioner_custom_file_frame = '';

		const uploadField = document.getElementsByClassName('select-img');

		for ( let i = 0; i < uploadField.length; i++ ) {
			let elem = uploadField[i];

			const uploaderTitle = elem.dataset.uploader_title;
			const uploaderButtonText = elem.dataset.uploader_button_text;

			elem.addEventListener('click', (e) => {
				e.preventDefault();

				if ( optioner_custom_file_frame ) {
					optioner_custom_file_frame.open();
					return;
				}

				// Setup modal.
				const OptionerCustomImage = wp.media.controller.Library.extend({
					defaults :  _.defaults({
						id: 'optioner-custom-insert-image',
						title: uploaderTitle,
						allowLocalEdits: false,
						displaySettings: true,
						displayUserSettings: false,
						multiple : false,
						library: wp.media.query( { type: 'image' } )
					}, wp.media.controller.Library.prototype.defaults )
				});

				// Create the media frame.
				optioner_custom_file_frame = wp.media.frames.optioner_custom_file_frame = wp.media({
					button: {
						text: uploaderButtonText
					},
					state : 'optioner-custom-insert-image',
					states : [
						new OptionerCustomImage()
					],
					multiple: false
				});

				optioner_custom_file_frame.on('select', () => {
					// Get state.
					let state = optioner_custom_file_frame.state('optioner-custom-insert-image');
					// Get image.
					let current_image = state.get('selection').first();
					// Get image status.
					let meta = state.display( current_image ).toJSON();
					// We need only size.
					let { size } = meta;
					// Get image details
					let image_details = current_image.toJSON();
					// Final image URL.
					let { url } = image_details.sizes[size];

					// Now assign value.
					elem.parentNode.querySelector('.img').value = url;

					// Show preview.
					let previewWrap = elem.parentNode.querySelector('.image-preview-wrap').innerHTML = `<img src="${url}" alt="" />`;

					// Show remove button.
					let removeButton = elem.parentNode.querySelector('.js-remove-image');
					removeButton.classList.remove('hide');
					removeButton.classList.add('show');
				});

				// Open modal.
				optioner_custom_file_frame.open();
			});
		}

		const btnRemoveImage = document.getElementsByClassName('js-remove-image');

		for ( let i = 0; i < btnRemoveImage.length; i++ ) {
			let elem = btnRemoveImage[i];

			elem.addEventListener('click', (e) => {
				e.preventDefault();
				// Empty value.
				elem.parentNode.querySelector('.img').value = '';
				// Hide preview.
				elem.parentNode.querySelector('.image-preview-wrap').innerHTML = '';
				// Hide remove button.
				elem.classList.remove('show');
				elem.classList.add('hide');
			});
		}
	}
}

document.addEventListener('DOMContentLoaded',function() {
	let a = new App();
});

( function( $ ) {

	jQuery( document ).ready( function( $ ) {
		var $is_tab = $('.wrap-content').hasClass('tab-enabled');

		if ( true == $is_tab ) {
			// Switches tabs.
			$( '.tab-content' ).hide();

			var activetab = '';

			if ( 'undefined' != typeof localStorage ) {
				activetab = localStorage.getItem( OPTIONER_OBJ.storage_key );
			}

			if ( '' != activetab && $( activetab ).length ) {
				$( activetab ).fadeIn();
			} else {
				$( '.tab-content:first' ).fadeIn();
			}

			// Tab links.
			if ( '' != activetab && $( activetab + '-tab' ).length ) {
				$( activetab + '-tab' ).addClass( 'nav-tab-active' );
			} else {
				$( '.nav-tab-wrapper a:first' ).addClass( 'nav-tab-active' );
			}

			// Tab switcher.
			$( '.nav-tab-wrapper a' ).click( function( evt ) {
				$( '.nav-tab-wrapper a' ).removeClass( 'nav-tab-active' );
				$( this ).addClass( 'nav-tab-active' ).blur();

				var clicked_group = $( this ).attr( 'href' );
				if ( 'undefined' != typeof localStorage ) {
					localStorage.setItem( OPTIONER_OBJ.storage_key, $( this ).attr( 'href' ) );
				}
				$( '.tab-content' ).hide();
				$( clicked_group ).fadeIn();
				evt.preventDefault();
			});

		} // End if is_tab.
	});


} )( jQuery );
