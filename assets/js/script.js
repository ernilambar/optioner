"use strict";

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var App = /*#__PURE__*/function () {
  function App() {
    _classCallCheck(this, App);

    this.initHeading();
    this.initMedia();
  }

  _createClass(App, [{
    key: "initHeading",
    value: function initHeading() {
      var formFieldHeading = document.getElementsByClassName('form-field-heading');

      for (var i = 0; i < formFieldHeading.length; i++) {
        var elem = formFieldHeading[i];
        var tr = elem.parentNode.parentNode;
        tr.querySelector('th').style.display = 'none';
        tr.querySelector('td').setAttribute('colspan', 2);
      }
    }
  }, {
    key: "initMedia",
    value: function initMedia() {
      var optioner_custom_file_frame = '';
      var uploadField = document.getElementsByClassName('select-img');

      var _loop = function _loop(i) {
        var elem = uploadField[i];
        var uploaderTitle = elem.dataset.uploader_title;
        var uploaderButtonText = elem.dataset.uploader_button_text;
        elem.addEventListener('click', function (e) {
          e.preventDefault();

          if (optioner_custom_file_frame) {
            optioner_custom_file_frame.open();
            return;
          } // Setup modal.


          var OptionerCustomImage = wp.media.controller.Library.extend({
            defaults: _.defaults({
              id: 'optioner-custom-insert-image',
              title: uploaderTitle,
              allowLocalEdits: false,
              displaySettings: true,
              displayUserSettings: false,
              multiple: false,
              library: wp.media.query({
                type: 'image'
              })
            }, wp.media.controller.Library.prototype.defaults)
          }); // Create the media frame.

          optioner_custom_file_frame = wp.media.frames.optioner_custom_file_frame = wp.media({
            button: {
              text: uploaderButtonText
            },
            state: 'optioner-custom-insert-image',
            states: [new OptionerCustomImage()],
            multiple: false
          });
          optioner_custom_file_frame.on('select', function () {
            // Get state.
            var state = optioner_custom_file_frame.state('optioner-custom-insert-image'); // Get image.

            var current_image = state.get('selection').first(); // Get image status.

            var meta = state.display(current_image).toJSON(); // We need only size.

            var size = meta.size; // Get image details

            var image_details = current_image.toJSON(); // Final image URL.

            var url = image_details.sizes[size].url; // Now assign value.

            elem.parentNode.querySelector('.img').value = url; // Show preview.

            var previewWrap = elem.parentNode.querySelector('.image-preview-wrap').innerHTML = "<img src=\"".concat(url, "\" alt=\"\" />"); // Show remove button.

            var removeButton = elem.parentNode.querySelector('.js-remove-image');
            removeButton.classList.remove('hide');
            removeButton.classList.add('show');
          }); // Open modal.

          optioner_custom_file_frame.open();
        });
      };

      for (var i = 0; i < uploadField.length; i++) {
        _loop(i);
      }

      var btnRemoveImage = document.getElementsByClassName('js-remove-image');

      var _loop2 = function _loop2(_i) {
        var elem = btnRemoveImage[_i];
        elem.addEventListener('click', function (e) {
          e.preventDefault(); // Empty value.

          elem.parentNode.querySelector('.img').value = ''; // Hide preview.

          elem.parentNode.querySelector('.image-preview-wrap').innerHTML = ''; // Hide remove button.

          elem.classList.remove('show');
          elem.classList.add('hide');
        });
      };

      for (var _i = 0; _i < btnRemoveImage.length; _i++) {
        _loop2(_i);
      }
    }
  }]);

  return App;
}();

document.addEventListener('DOMContentLoaded', function () {
  var a = new App();
});

(function ($) {
  var optioner_custom_file_frame;
  jQuery(document).ready(function ($) {
    //Initiate Color Picker.
    $('.optioner-color').each(function () {
      $(this).wpColorPicker();
    });
    var $is_tab = $('.wrap-content').hasClass('tab-enabled');

    if (true == $is_tab) {
      // Switches tabs.
      $('.tab-content').hide();
      var activetab = '';

      if ('undefined' != typeof localStorage) {
        activetab = localStorage.getItem(OPTIONER_OBJ.storage_key);
      }

      if ('' != activetab && $(activetab).length) {
        $(activetab).fadeIn();
      } else {
        $('.tab-content:first').fadeIn();
      } // Tab links.


      if ('' != activetab && $(activetab + '-tab').length) {
        $(activetab + '-tab').addClass('nav-tab-active');
      } else {
        $('.nav-tab-wrapper a:first').addClass('nav-tab-active');
      } // Tab switcher.


      $('.nav-tab-wrapper a').click(function (evt) {
        $('.nav-tab-wrapper a').removeClass('nav-tab-active');
        $(this).addClass('nav-tab-active').blur();
        var clicked_group = $(this).attr('href');

        if ('undefined' != typeof localStorage) {
          localStorage.setItem(OPTIONER_OBJ.storage_key, $(this).attr('href'));
        }

        $('.tab-content').hide();
        $(clicked_group).fadeIn();
        evt.preventDefault();
      });
    } // End if is_tab.

  });
})(jQuery);