class App {

	constructor() {
		this.initHeading();
		this.initColor();
		this.initMedia();

		const isTab = document.querySelector('.wrap-content').classList.contains('tab-enabled');

		if ( true == isTab ) {
			this.initTab();
		}
	}

	initHeading() {
		const formFieldHeading = document.getElementsByClassName('form-field-heading');
		const formFieldHeadingArray = [...formFieldHeading];

		formFieldHeadingArray.forEach( (elem) => {
			let tr = elem.parentNode.parentNode;

			tr.querySelector('th').style.display = 'none';
			tr.querySelector('td').setAttribute( 'colspan', 2 );
		});
	}

	initColor() {
		const fieldColor = document.getElementsByClassName( 'optioner-color' );
		const fieldColorArray = [...fieldColor];

		fieldColorArray.forEach( (elem) => {
			jQuery(elem).wpColorPicker();
		});
	}

	initTab() {
		const optionerWrapper = document.getElementById( 'optioner-wrapper' );
		const tabContents = document.getElementsByClassName( 'tab-content' );
		const tabLinks = document.querySelectorAll( '.nav-tab-wrapper a' );

		const tabContentsArray = [...tabContents];
		const tabLinksArray = [...tabLinks];

		// Initially hide tab content.
		tabContentsArray.forEach( (elem) => {
			elem.style.display = 'none';
		});

		var activeTab = '';

		if ( 'undefined' != typeof localStorage ) {
			activeTab = localStorage.getItem( OPTIONER_OBJ.storage_key );
		}

		// Initial status for tab content.
		if ( null !== activeTab && document.getElementById(activeTab) ) {
			let targetGroup = document.getElementById(activeTab);
			if ( targetGroup ) {
				targetGroup.style.display = 'block';
			}
		} else {
			tabContents[0].style.display = 'block';
		}

		// Initial status for tab nav.
		if ( null !== activeTab && document.getElementById(activeTab) ) {
			let targetNav = optionerWrapper.querySelector(`.nav-tab-wrapper a[href="#${activeTab}"]`);
			if ( targetNav ) {
				targetNav.classList.add('nav-tab-active');
			}
		} else {
			tabLinks[0].classList.add('nav-tab-active');
		}

		tabLinksArray.forEach( (elem) => {
			elem.addEventListener('click', (e) => {
				e.preventDefault();

				// Remove tab active class from all.
				tabLinksArray.forEach( (elem) => {
					elem.classList.remove('nav-tab-active');
				});

				// Add active class to current tab.
				elem.classList.add('nav-tab-active');

				// Get target.
				let target_group = elem.getAttribute('href');

				// Save active tab in local storage.
				if ( 'undefined' !== typeof localStorage ) {
					localStorage.setItem( OPTIONER_OBJ.storage_key, target_group.replace('#', '') );
				}

				tabContentsArray.forEach( (elem) => {
					elem.style.display = 'none';
				});

				document.getElementById( target_group.replace('#', '') ).style.display = 'block';
			});
		});
	}

	initMedia() {
		let optioner_custom_file_frame = '';

		const uploadField = document.getElementsByClassName('select-img');
		const uploadFieldArray = [...uploadField];

		uploadFieldArray.forEach( (elem) => {
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
		});

		const btnRemoveImage = document.getElementsByClassName('js-remove-image');
		const btnRemoveImageArray = [...btnRemoveImage];

		btnRemoveImageArray.forEach( (elem) => {
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
		});
	}
}

document.addEventListener('DOMContentLoaded',function() {
	let a = new App();
});
