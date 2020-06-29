"use strict";

function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread(); }

function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _iterableToArray(iter) { if (typeof Symbol !== "undefined" && Symbol.iterator in Object(iter)) return Array.from(iter); }

function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) return _arrayLikeToArray(arr); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var App = /*#__PURE__*/function () {
  function App() {
    _classCallCheck(this, App);

    this.initHeading();
    this.initColor();
    this.initMedia();
    var isTab = document.querySelector('.wrap-content').classList.contains('tab-enabled');

    if (true == isTab) {
      this.initTab();
    }
  }

  _createClass(App, [{
    key: "initHeading",
    value: function initHeading() {
      var formFieldHeading = document.getElementsByClassName('form-field-heading');

      var formFieldHeadingArray = _toConsumableArray(formFieldHeading);

      formFieldHeadingArray.forEach(function (elem) {
        var tr = elem.parentNode.parentNode;
        tr.querySelector('th').style.display = 'none';
        tr.querySelector('td').setAttribute('colspan', 2);
      });
    }
  }, {
    key: "initColor",
    value: function initColor() {
      var fieldColor = document.getElementsByClassName('optioner-color');

      var fieldColorArray = _toConsumableArray(fieldColor);

      fieldColorArray.forEach(function (elem) {
        jQuery(elem).wpColorPicker();
      });
    }
  }, {
    key: "initTab",
    value: function initTab() {
      var optionerWrapper = document.getElementById('optioner-wrapper');
      var tabContents = document.getElementsByClassName('tab-content');
      var tabLinks = document.querySelectorAll('.nav-tab-wrapper a');

      var tabContentsArray = _toConsumableArray(tabContents);

      var tabLinksArray = _toConsumableArray(tabLinks); // Initially hide tab content.


      tabContentsArray.forEach(function (elem) {
        elem.style.display = 'none';
      });
      var activeTab = '';

      if ('undefined' != typeof localStorage) {
        activeTab = localStorage.getItem(OPTIONER_OBJ.storage_key);
      } // Initial status for tab content.


      if (null !== activeTab && document.getElementById(activeTab)) {
        var targetGroup = document.getElementById(activeTab);

        if (targetGroup) {
          targetGroup.style.display = 'block';
        }
      } else {
        tabContents[0].style.display = 'block';
      } // Initial status for tab nav.


      if (null !== activeTab && document.getElementById(activeTab)) {
        var targetNav = optionerWrapper.querySelector(".nav-tab-wrapper a[href=\"#".concat(activeTab, "\"]"));

        if (targetNav) {
          targetNav.classList.add('nav-tab-active');
        }
      } else {
        tabLinks[0].classList.add('nav-tab-active');
      }

      tabLinksArray.forEach(function (elem) {
        elem.addEventListener('click', function (e) {
          e.preventDefault(); // Remove tab active class from all.

          tabLinksArray.forEach(function (elem) {
            elem.classList.remove('nav-tab-active');
          }); // Add active class to current tab.

          elem.classList.add('nav-tab-active'); // Get target.

          var target_group = elem.getAttribute('href'); // Save active tab in local storage.

          if ('undefined' !== typeof localStorage) {
            localStorage.setItem(OPTIONER_OBJ.storage_key, target_group.replace('#', ''));
          }

          tabContentsArray.forEach(function (elem) {
            elem.style.display = 'none';
          });
          document.getElementById(target_group.replace('#', '')).style.display = 'block';
        });
      });
    }
  }, {
    key: "initMedia",
    value: function initMedia() {
      var optioner_custom_file_frame = '';
      var uploadField = document.getElementsByClassName('select-img');

      var uploadFieldArray = _toConsumableArray(uploadField);

      uploadFieldArray.forEach(function (elem) {
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
      });
      var btnRemoveImage = document.getElementsByClassName('js-remove-image');

      var btnRemoveImageArray = _toConsumableArray(btnRemoveImage);

      btnRemoveImageArray.forEach(function (elem) {
        elem.addEventListener('click', function (e) {
          e.preventDefault(); // Empty value.

          elem.parentNode.querySelector('.img').value = ''; // Hide preview.

          elem.parentNode.querySelector('.image-preview-wrap').innerHTML = ''; // Hide remove button.

          elem.classList.remove('show');
          elem.classList.add('hide');
        });
      });
    }
  }]);

  return App;
}();

document.addEventListener('DOMContentLoaded', function () {
  var a = new App();
});