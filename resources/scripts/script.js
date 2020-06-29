class App {

	constructor() {
		this.initHeading();
		this.initMedia();
	}

	initHeading() {
		const formFieldHeading = document.getElementsByClassName('form-field-heading');

		for ( let i = 0; i < formFieldHeading.length; i++ ) {
		   let elem = formFieldHeading[i];
		   let tr = elem.parentNode.parentNode;

		   tr.querySelector('th').style.display = 'none';
		   tr.querySelector('td').setAttribute('colspan', 2);
		}
	}

	initMedia() {
		let optioner_custom_file_frame = '';

		const uploadField = document.getElementsByClassName('select-img');

		for ( let i = 0; i < uploadField.length; i++ ) {
			let elem = uploadField[i];

			elem.addEventListener('click', (e) => {
				e.preventDefault();

				if ( optioner_custom_file_frame ) {
					optioner_custom_file_frame.open();
					return;
				}

				var OptionerCustomImage = wp.media.controller.Library.extend({
					defaults :  _.defaults({
						id: 'optioner-custom-insert-image',
						title: 'uploader_title',
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
						text: 'Upload button text'
					},
					state : 'optioner-custom-insert-image',
					states : [
						new OptionerCustomImage()
					],
					multiple: false
				});

				optioner_custom_file_frame.on('select', () => {
					let state = optioner_custom_file_frame.state('optioner-custom-insert-image');
					let current_image = state.get('selection').first();
					let meta = state.display( current_image ).toJSON();
					let { size } = meta;
					let image_details = current_image.toJSON();
					let { url } = image_details.sizes[size];

					console.log( url, 'url' );
				});

				// Open.
				optioner_custom_file_frame.open();
			});
		}
	}
}

document.addEventListener('DOMContentLoaded',function() {
	let a = new App();
});

( function( $ ) {

	var optioner_custom_file_frame;

	jQuery( document ).ready( function( $ ) {
		//Initiate Color Picker.
		$('.optioner-color').each(function(){
			$(this).wpColorPicker();
		});

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


		// Uploads.
		jQuery(document).on('click', 'input.select-img1', function( event ){
			var $this = $(this);

			event.preventDefault();

			var OptionerCustomImage = wp.media.controller.Library.extend({
				defaults :  _.defaults({
					id: 'optioner-custom-insert-image',
					title: $this.data( 'uploader_title' ),
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
					text: jQuery( this ).data( 'uploader_button_text' )
				},
				state : 'optioner-custom-insert-image',
				states : [
				new OptionerCustomImage()
				],
				multiple: false
			});

			// When an image is selected, run a callback.
			optioner_custom_file_frame.on( 'select', function() {
				var state = optioner_custom_file_frame.state('optioner-custom-insert-image');
				var selection = state.get('selection');
				var display = state.display( selection.first() ).toJSON();
				var obj_attachment = selection.first().toJSON();
				display = wp.media.string.props( display, obj_attachment );

				var image_field = $this.siblings('.img');
				var imgurl = display.src;

				// Copy image URL.
				image_field.val(imgurl);
				image_field.trigger('change');

				// Show in preview.
				var image_preview_wrap = $this.siblings('.image-preview-wrap');
				var image_html = '<img src="' + imgurl + '" alt="" style="max-width:100%;max-height:200px;" />';
				image_preview_wrap.html( image_html );

				// Show Remove button.
				var image_remove_button = $this.siblings('.btn-image-remove');
				image_remove_button.css('display','inline-block');
			});

			// Finally, open the modal.
			optioner_custom_file_frame.open();
		});

		// Remove image.
		jQuery(document).on('click', 'input.btn-image-remove', function( e ) {
			e.preventDefault();

			var $this = $(this);
			var image_field = $this.siblings('.img');
			image_field.val('');
			var image_preview_wrap = $this.siblings('.image-preview-wrap');
			image_preview_wrap.html('');
			$this.css('display','none');
			image_field.trigger('change');
		});
	});


} )( jQuery );
