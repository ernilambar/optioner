/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/sass/optioner.scss":
/*!**************************************!*\
  !*** ./resources/sass/optioner.scss ***!
  \**************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
(() => {
/*!*******************************!*\
  !*** ./resources/optioner.js ***!
  \*******************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _sass_optioner_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./sass/optioner.scss */ "./resources/sass/optioner.scss");


class App {
  constructor() {
    this.initHeading();
    this.initColor();
    this.initMedia();
    const isTab = document.querySelector('.wrap-content').classList.contains('tab-enabled');

    if (true === isTab) {
      this.initTab();
    }
  }

  initHeading() {
    const formFieldHeading = document.getElementsByClassName('form-field-heading');
    const formFieldHeadingArray = [...formFieldHeading];
    formFieldHeadingArray.forEach(elem => {
      const tr = elem.parentNode.parentNode;
      tr.querySelector('th').style.display = 'none';
      tr.querySelector('td').setAttribute('colspan', 2);
    });
  }

  initColor() {
    const fieldColor = document.getElementsByClassName('optioner-color');
    const fieldColorArray = [...fieldColor];
    fieldColorArray.forEach(elem => {
      jQuery(elem).wpColorPicker();
    });
  }

  initTab() {
    const optionerWrapper = document.getElementById('optioner-wrapper');
    const tabContents = document.getElementsByClassName('tab-content');
    const tabLinks = document.querySelectorAll('.nav-tab-wrapper a');
    const tabContentsArray = [...tabContents];
    const tabLinksArray = [...tabLinks]; // Initially hide tab content.

    tabContentsArray.forEach(elem => {
      elem.style.display = 'none';
    });
    let activeTab = '';

    if ('undefined' !== typeof localStorage) {
      activeTab = localStorage.getItem(OPTIONER_OBJ.storage_key);
    } // Initial status for tab content.


    if (null !== activeTab && document.getElementById(activeTab)) {
      const targetGroup = document.getElementById(activeTab);

      if (targetGroup) {
        targetGroup.style.display = 'block';
      }
    } else {
      tabContents[0].style.display = 'block';
    } // Initial status for tab nav.


    if (null !== activeTab && document.getElementById(activeTab)) {
      const targetNav = optionerWrapper.querySelector(`.nav-tab-wrapper a[href="#${activeTab}"]`);

      if (targetNav) {
        targetNav.classList.add('nav-tab-active');
      }
    } else {
      tabLinks[0].classList.add('nav-tab-active');
    }

    tabLinksArray.forEach(elem => {
      elem.addEventListener('click', e => {
        e.preventDefault(); // Remove tab active class from all.

        tabLinksArray.forEach(elemLink => {
          elemLink.classList.remove('nav-tab-active');
        }); // Add active class to current tab.

        elem.classList.add('nav-tab-active'); // Get target.

        const targetGroup = elem.getAttribute('href'); // Save active tab in local storage.

        if ('undefined' !== typeof localStorage) {
          localStorage.setItem(OPTIONER_OBJ.storage_key, targetGroup.replace('#', ''));
        }

        tabContentsArray.forEach(elemContent => {
          elemContent.style.display = 'none';
        });
        document.getElementById(targetGroup.replace('#', '')).style.display = 'block';
      });
    });
  }

  initMedia() {
    let optionerCustomFileFrame = '';
    const uploadField = document.getElementsByClassName('select-img');
    const uploadFieldArray = [...uploadField];
    uploadFieldArray.forEach(elem => {
      const uploaderTitle = elem.dataset.uploader_title;
      const uploaderButtonText = elem.dataset.uploader_button_text;
      elem.addEventListener('click', e => {
        e.preventDefault();

        if (optionerCustomFileFrame) {
          optionerCustomFileFrame.open();
          return;
        } // Setup modal.


        const OptionerCustomImage = wp.media.controller.Library.extend({
          defaults: _.defaults({
            id: 'optioner-custom-insert-image',
            title: uploaderTitle,
            allowLocalEdits: false,
            displaySettings: false,
            displayUserSettings: false,
            multiple: false,
            library: wp.media.query({
              type: 'image'
            })
          }, wp.media.controller.Library.prototype.defaults)
        }); // Create the media frame.

        optionerCustomFileFrame = wp.media.frames.optionerCustomFileFrame = wp.media({
          button: {
            text: uploaderButtonText
          },
          state: 'optioner-custom-insert-image',
          states: [new OptionerCustomImage()],
          multiple: false
        });
        optionerCustomFileFrame.on('select', () => {
          const state = optionerCustomFileFrame.state('optioner-custom-insert-image');
          const currentImage = state.get('selection').first();
          const url = currentImage.toJSON().url;
          elem.parentNode.querySelector('.img').value = url;
          elem.parentNode.querySelector('.image-preview-wrap').innerHTML = `<img src="${url}" alt="" />`; // Show remove button.

          const removeButton = elem.parentNode.querySelector('.js-remove-image');
          removeButton.classList.remove('hide');
          removeButton.classList.add('show');
        }); // Open modal.

        optionerCustomFileFrame.open();
      });
    });
    const btnRemoveImage = document.getElementsByClassName('js-remove-image');
    const btnRemoveImageArray = [...btnRemoveImage];
    btnRemoveImageArray.forEach(elem => {
      elem.addEventListener('click', e => {
        e.preventDefault(); // Empty value.

        elem.parentNode.querySelector('.img').value = ''; // Hide preview.

        elem.parentNode.querySelector('.image-preview-wrap').innerHTML = ''; // Hide remove button.

        elem.classList.remove('show');
        elem.classList.add('hide');
      });
    });
  }

}

document.addEventListener('DOMContentLoaded', function () {
  new App();
});
})();

/******/ })()
;
//# sourceMappingURL=data:application/json;charset=utf-8;base64,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R0FBSjtBQUNBLENBRkQsRSIsInNvdXJjZXMiOlsid2VicGFjazovL29wdGlvbmVyLy4vcmVzb3VyY2VzL3Nhc3Mvb3B0aW9uZXIuc2Nzcz9hOWNjIiwid2VicGFjazovL29wdGlvbmVyL3dlYnBhY2svYm9vdHN0cmFwIiwid2VicGFjazovL29wdGlvbmVyL3dlYnBhY2svcnVudGltZS9tYWtlIG5hbWVzcGFjZSBvYmplY3QiLCJ3ZWJwYWNrOi8vb3B0aW9uZXIvLi9yZXNvdXJjZXMvb3B0aW9uZXIuanMiXSwic291cmNlc0NvbnRlbnQiOlsiLy8gZXh0cmFjdGVkIGJ5IG1pbmktY3NzLWV4dHJhY3QtcGx1Z2luXG5leHBvcnQge307IiwiLy8gVGhlIG1vZHVsZSBjYWNoZVxudmFyIF9fd2VicGFja19tb2R1bGVfY2FjaGVfXyA9IHt9O1xuXG4vLyBUaGUgcmVxdWlyZSBmdW5jdGlvblxuZnVuY3Rpb24gX193ZWJwYWNrX3JlcXVpcmVfXyhtb2R1bGVJZCkge1xuXHQvLyBDaGVjayBpZiBtb2R1bGUgaXMgaW4gY2FjaGVcblx0dmFyIGNhY2hlZE1vZHVsZSA9IF9fd2VicGFja19tb2R1bGVfY2FjaGVfX1ttb2R1bGVJZF07XG5cdGlmIChjYWNoZWRNb2R1bGUgIT09IHVuZGVmaW5lZCkge1xuXHRcdHJldHVybiBjYWNoZWRNb2R1bGUuZXhwb3J0cztcblx0fVxuXHQvLyBDcmVhdGUgYSBuZXcgbW9kdWxlIChhbmQgcHV0IGl0IGludG8gdGhlIGNhY2hlKVxuXHR2YXIgbW9kdWxlID0gX193ZWJwYWNrX21vZHVsZV9jYWNoZV9fW21vZHVsZUlkXSA9IHtcblx0XHQvLyBubyBtb2R1bGUuaWQgbmVlZGVkXG5cdFx0Ly8gbm8gbW9kdWxlLmxvYWRlZCBuZWVkZWRcblx0XHRleHBvcnRzOiB7fVxuXHR9O1xuXG5cdC8vIEV4ZWN1dGUgdGhlIG1vZHVsZSBmdW5jdGlvblxuXHRfX3dlYnBhY2tfbW9kdWxlc19fW21vZHVsZUlkXShtb2R1bGUsIG1vZHVsZS5leHBvcnRzLCBfX3dlYnBhY2tfcmVxdWlyZV9fKTtcblxuXHQvLyBSZXR1cm4gdGhlIGV4cG9ydHMgb2YgdGhlIG1vZHVsZVxuXHRyZXR1cm4gbW9kdWxlLmV4cG9ydHM7XG59XG5cbiIsIi8vIGRlZmluZSBfX2VzTW9kdWxlIG9uIGV4cG9ydHNcbl9fd2VicGFja19yZXF1aXJlX18uciA9IChleHBvcnRzKSA9PiB7XG5cdGlmKHR5cGVvZiBTeW1ib2wgIT09ICd1bmRlZmluZWQnICYmIFN5bWJvbC50b1N0cmluZ1RhZykge1xuXHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShleHBvcnRzLCBTeW1ib2wudG9TdHJpbmdUYWcsIHsgdmFsdWU6ICdNb2R1bGUnIH0pO1xuXHR9XG5cdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShleHBvcnRzLCAnX19lc01vZHVsZScsIHsgdmFsdWU6IHRydWUgfSk7XG59OyIsImltcG9ydCAnLi9zYXNzL29wdGlvbmVyLnNjc3MnO1xuXG5jbGFzcyBBcHAge1xuXHRjb25zdHJ1Y3RvcigpIHtcblx0XHR0aGlzLmluaXRIZWFkaW5nKCk7XG5cdFx0dGhpcy5pbml0Q29sb3IoKTtcblx0XHR0aGlzLmluaXRNZWRpYSgpO1xuXG5cdFx0Y29uc3QgaXNUYWIgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCAnLndyYXAtY29udGVudCcgKS5jbGFzc0xpc3QuY29udGFpbnMoICd0YWItZW5hYmxlZCcgKTtcblxuXHRcdGlmICggdHJ1ZSA9PT0gaXNUYWIgKSB7XG5cdFx0XHR0aGlzLmluaXRUYWIoKTtcblx0XHR9XG5cdH1cblxuXHRpbml0SGVhZGluZygpIHtcblx0XHRjb25zdCBmb3JtRmllbGRIZWFkaW5nID0gZG9jdW1lbnQuZ2V0RWxlbWVudHNCeUNsYXNzTmFtZSggJ2Zvcm0tZmllbGQtaGVhZGluZycgKTtcblx0XHRjb25zdCBmb3JtRmllbGRIZWFkaW5nQXJyYXkgPSBbIC4uLmZvcm1GaWVsZEhlYWRpbmcgXTtcblxuXHRcdGZvcm1GaWVsZEhlYWRpbmdBcnJheS5mb3JFYWNoKCAoIGVsZW0gKSA9PiB7XG5cdFx0XHRjb25zdCB0ciA9IGVsZW0ucGFyZW50Tm9kZS5wYXJlbnROb2RlO1xuXG5cdFx0XHR0ci5xdWVyeVNlbGVjdG9yKCAndGgnICkuc3R5bGUuZGlzcGxheSA9ICdub25lJztcblx0XHRcdHRyLnF1ZXJ5U2VsZWN0b3IoICd0ZCcgKS5zZXRBdHRyaWJ1dGUoICdjb2xzcGFuJywgMiApO1xuXHRcdH0gKTtcblx0fVxuXG5cdGluaXRDb2xvcigpIHtcblx0XHRjb25zdCBmaWVsZENvbG9yID0gZG9jdW1lbnQuZ2V0RWxlbWVudHNCeUNsYXNzTmFtZSggJ29wdGlvbmVyLWNvbG9yJyApO1xuXHRcdGNvbnN0IGZpZWxkQ29sb3JBcnJheSA9IFsgLi4uZmllbGRDb2xvciBdO1xuXG5cdFx0ZmllbGRDb2xvckFycmF5LmZvckVhY2goICggZWxlbSApID0+IHtcblx0XHRcdGpRdWVyeSggZWxlbSApLndwQ29sb3JQaWNrZXIoKTtcblx0XHR9ICk7XG5cdH1cblxuXHRpbml0VGFiKCkge1xuXHRcdGNvbnN0IG9wdGlvbmVyV3JhcHBlciA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCAnb3B0aW9uZXItd3JhcHBlcicgKTtcblx0XHRjb25zdCB0YWJDb250ZW50cyA9IGRvY3VtZW50LmdldEVsZW1lbnRzQnlDbGFzc05hbWUoICd0YWItY29udGVudCcgKTtcblx0XHRjb25zdCB0YWJMaW5rcyA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoICcubmF2LXRhYi13cmFwcGVyIGEnICk7XG5cblx0XHRjb25zdCB0YWJDb250ZW50c0FycmF5ID0gWyAuLi50YWJDb250ZW50cyBdO1xuXHRcdGNvbnN0IHRhYkxpbmtzQXJyYXkgPSBbIC4uLnRhYkxpbmtzIF07XG5cblx0XHQvLyBJbml0aWFsbHkgaGlkZSB0YWIgY29udGVudC5cblx0XHR0YWJDb250ZW50c0FycmF5LmZvckVhY2goICggZWxlbSApID0+IHtcblx0XHRcdGVsZW0uc3R5bGUuZGlzcGxheSA9ICdub25lJztcblx0XHR9ICk7XG5cblx0XHRsZXQgYWN0aXZlVGFiID0gJyc7XG5cblx0XHRpZiAoICd1bmRlZmluZWQnICE9PSB0eXBlb2YgbG9jYWxTdG9yYWdlICkge1xuXHRcdFx0YWN0aXZlVGFiID0gbG9jYWxTdG9yYWdlLmdldEl0ZW0oIE9QVElPTkVSX09CSi5zdG9yYWdlX2tleSApO1xuXHRcdH1cblxuXHRcdC8vIEluaXRpYWwgc3RhdHVzIGZvciB0YWIgY29udGVudC5cblx0XHRpZiAoIG51bGwgIT09IGFjdGl2ZVRhYiAmJiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCggYWN0aXZlVGFiICkgKSB7XG5cdFx0XHRjb25zdCB0YXJnZXRHcm91cCA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCBhY3RpdmVUYWIgKTtcblx0XHRcdGlmICggdGFyZ2V0R3JvdXAgKSB7XG5cdFx0XHRcdHRhcmdldEdyb3VwLnN0eWxlLmRpc3BsYXkgPSAnYmxvY2snO1xuXHRcdFx0fVxuXHRcdH0gZWxzZSB7XG5cdFx0XHR0YWJDb250ZW50c1sgMCBdLnN0eWxlLmRpc3BsYXkgPSAnYmxvY2snO1xuXHRcdH1cblxuXHRcdC8vIEluaXRpYWwgc3RhdHVzIGZvciB0YWIgbmF2LlxuXHRcdGlmICggbnVsbCAhPT0gYWN0aXZlVGFiICYmIGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCBhY3RpdmVUYWIgKSApIHtcblx0XHRcdGNvbnN0IHRhcmdldE5hdiA9IG9wdGlvbmVyV3JhcHBlci5xdWVyeVNlbGVjdG9yKCBgLm5hdi10YWItd3JhcHBlciBhW2hyZWY9XCIjJHsgYWN0aXZlVGFiIH1cIl1gICk7XG5cdFx0XHRpZiAoIHRhcmdldE5hdiApIHtcblx0XHRcdFx0dGFyZ2V0TmF2LmNsYXNzTGlzdC5hZGQoICduYXYtdGFiLWFjdGl2ZScgKTtcblx0XHRcdH1cblx0XHR9IGVsc2Uge1xuXHRcdFx0dGFiTGlua3NbIDAgXS5jbGFzc0xpc3QuYWRkKCAnbmF2LXRhYi1hY3RpdmUnICk7XG5cdFx0fVxuXG5cdFx0dGFiTGlua3NBcnJheS5mb3JFYWNoKCAoIGVsZW0gKSA9PiB7XG5cdFx0XHRlbGVtLmFkZEV2ZW50TGlzdGVuZXIoICdjbGljaycsICggZSApID0+IHtcblx0XHRcdFx0ZS5wcmV2ZW50RGVmYXVsdCgpO1xuXG5cdFx0XHRcdC8vIFJlbW92ZSB0YWIgYWN0aXZlIGNsYXNzIGZyb20gYWxsLlxuXHRcdFx0XHR0YWJMaW5rc0FycmF5LmZvckVhY2goICggZWxlbUxpbmsgKSA9PiB7XG5cdFx0XHRcdFx0ZWxlbUxpbmsuY2xhc3NMaXN0LnJlbW92ZSggJ25hdi10YWItYWN0aXZlJyApO1xuXHRcdFx0XHR9ICk7XG5cblx0XHRcdFx0Ly8gQWRkIGFjdGl2ZSBjbGFzcyB0byBjdXJyZW50IHRhYi5cblx0XHRcdFx0ZWxlbS5jbGFzc0xpc3QuYWRkKCAnbmF2LXRhYi1hY3RpdmUnICk7XG5cblx0XHRcdFx0Ly8gR2V0IHRhcmdldC5cblx0XHRcdFx0Y29uc3QgdGFyZ2V0R3JvdXAgPSBlbGVtLmdldEF0dHJpYnV0ZSggJ2hyZWYnICk7XG5cblx0XHRcdFx0Ly8gU2F2ZSBhY3RpdmUgdGFiIGluIGxvY2FsIHN0b3JhZ2UuXG5cdFx0XHRcdGlmICggJ3VuZGVmaW5lZCcgIT09IHR5cGVvZiBsb2NhbFN0b3JhZ2UgKSB7XG5cdFx0XHRcdFx0bG9jYWxTdG9yYWdlLnNldEl0ZW0oIE9QVElPTkVSX09CSi5zdG9yYWdlX2tleSwgdGFyZ2V0R3JvdXAucmVwbGFjZSggJyMnLCAnJyApICk7XG5cdFx0XHRcdH1cblxuXHRcdFx0XHR0YWJDb250ZW50c0FycmF5LmZvckVhY2goICggZWxlbUNvbnRlbnQgKSA9PiB7XG5cdFx0XHRcdFx0ZWxlbUNvbnRlbnQuc3R5bGUuZGlzcGxheSA9ICdub25lJztcblx0XHRcdFx0fSApO1xuXG5cdFx0XHRcdGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCB0YXJnZXRHcm91cC5yZXBsYWNlKCAnIycsICcnICkgKS5zdHlsZS5kaXNwbGF5ID0gJ2Jsb2NrJztcblx0XHRcdH0gKTtcblx0XHR9ICk7XG5cdH1cblxuXHRpbml0TWVkaWEoKSB7XG5cdFx0bGV0IG9wdGlvbmVyQ3VzdG9tRmlsZUZyYW1lID0gJyc7XG5cblx0XHRjb25zdCB1cGxvYWRGaWVsZCA9IGRvY3VtZW50LmdldEVsZW1lbnRzQnlDbGFzc05hbWUoICdzZWxlY3QtaW1nJyApO1xuXHRcdGNvbnN0IHVwbG9hZEZpZWxkQXJyYXkgPSBbIC4uLnVwbG9hZEZpZWxkIF07XG5cblx0XHR1cGxvYWRGaWVsZEFycmF5LmZvckVhY2goICggZWxlbSApID0+IHtcblx0XHRcdGNvbnN0IHVwbG9hZGVyVGl0bGUgPSBlbGVtLmRhdGFzZXQudXBsb2FkZXJfdGl0bGU7XG5cdFx0XHRjb25zdCB1cGxvYWRlckJ1dHRvblRleHQgPSBlbGVtLmRhdGFzZXQudXBsb2FkZXJfYnV0dG9uX3RleHQ7XG5cblx0XHRcdGVsZW0uYWRkRXZlbnRMaXN0ZW5lciggJ2NsaWNrJywgKCBlICkgPT4ge1xuXHRcdFx0XHRlLnByZXZlbnREZWZhdWx0KCk7XG5cblx0XHRcdFx0aWYgKCBvcHRpb25lckN1c3RvbUZpbGVGcmFtZSApIHtcblx0XHRcdFx0XHRvcHRpb25lckN1c3RvbUZpbGVGcmFtZS5vcGVuKCk7XG5cdFx0XHRcdFx0cmV0dXJuO1xuXHRcdFx0XHR9XG5cblx0XHRcdFx0Ly8gU2V0dXAgbW9kYWwuXG5cdFx0XHRcdGNvbnN0IE9wdGlvbmVyQ3VzdG9tSW1hZ2UgPSB3cC5tZWRpYS5jb250cm9sbGVyLkxpYnJhcnkuZXh0ZW5kKCB7XG5cdFx0XHRcdFx0ZGVmYXVsdHM6IF8uZGVmYXVsdHMoIHtcblx0XHRcdFx0XHRcdGlkOiAnb3B0aW9uZXItY3VzdG9tLWluc2VydC1pbWFnZScsXG5cdFx0XHRcdFx0XHR0aXRsZTogdXBsb2FkZXJUaXRsZSxcblx0XHRcdFx0XHRcdGFsbG93TG9jYWxFZGl0czogZmFsc2UsXG5cdFx0XHRcdFx0XHRkaXNwbGF5U2V0dGluZ3M6IGZhbHNlLFxuXHRcdFx0XHRcdFx0ZGlzcGxheVVzZXJTZXR0aW5nczogZmFsc2UsXG5cdFx0XHRcdFx0XHRtdWx0aXBsZTogZmFsc2UsXG5cdFx0XHRcdFx0XHRsaWJyYXJ5OiB3cC5tZWRpYS5xdWVyeSggeyB0eXBlOiAnaW1hZ2UnIH0gKSxcblx0XHRcdFx0XHR9LCB3cC5tZWRpYS5jb250cm9sbGVyLkxpYnJhcnkucHJvdG90eXBlLmRlZmF1bHRzICksXG5cdFx0XHRcdH0gKTtcblxuXHRcdFx0XHQvLyBDcmVhdGUgdGhlIG1lZGlhIGZyYW1lLlxuXHRcdFx0XHRvcHRpb25lckN1c3RvbUZpbGVGcmFtZSA9IHdwLm1lZGlhLmZyYW1lcy5vcHRpb25lckN1c3RvbUZpbGVGcmFtZSA9IHdwLm1lZGlhKCB7XG5cdFx0XHRcdFx0YnV0dG9uOiB7XG5cdFx0XHRcdFx0XHR0ZXh0OiB1cGxvYWRlckJ1dHRvblRleHQsXG5cdFx0XHRcdFx0fSxcblx0XHRcdFx0XHRzdGF0ZTogJ29wdGlvbmVyLWN1c3RvbS1pbnNlcnQtaW1hZ2UnLFxuXHRcdFx0XHRcdHN0YXRlczogW1xuXHRcdFx0XHRcdFx0bmV3IE9wdGlvbmVyQ3VzdG9tSW1hZ2UoKSxcblx0XHRcdFx0XHRdLFxuXHRcdFx0XHRcdG11bHRpcGxlOiBmYWxzZSxcblx0XHRcdFx0fSApO1xuXG5cdFx0XHRcdG9wdGlvbmVyQ3VzdG9tRmlsZUZyYW1lLm9uKCAnc2VsZWN0JywgKCkgPT4ge1xuXHRcdFx0XHRcdGNvbnN0IHN0YXRlID0gb3B0aW9uZXJDdXN0b21GaWxlRnJhbWUuc3RhdGUoICdvcHRpb25lci1jdXN0b20taW5zZXJ0LWltYWdlJyApO1xuXHRcdFx0XHRcdGNvbnN0IGN1cnJlbnRJbWFnZSA9IHN0YXRlLmdldCggJ3NlbGVjdGlvbicgKS5maXJzdCgpO1xuXHRcdFx0XHRcdGNvbnN0IHVybCA9IGN1cnJlbnRJbWFnZS50b0pTT04oKS51cmw7XG5cblx0XHRcdFx0XHRlbGVtLnBhcmVudE5vZGUucXVlcnlTZWxlY3RvciggJy5pbWcnICkudmFsdWUgPSB1cmw7XG5cblx0XHRcdFx0XHRlbGVtLnBhcmVudE5vZGUucXVlcnlTZWxlY3RvciggJy5pbWFnZS1wcmV2aWV3LXdyYXAnICkuaW5uZXJIVE1MID0gYDxpbWcgc3JjPVwiJHsgdXJsIH1cIiBhbHQ9XCJcIiAvPmA7XG5cblx0XHRcdFx0XHQvLyBTaG93IHJlbW92ZSBidXR0b24uXG5cdFx0XHRcdFx0Y29uc3QgcmVtb3ZlQnV0dG9uID0gZWxlbS5wYXJlbnROb2RlLnF1ZXJ5U2VsZWN0b3IoICcuanMtcmVtb3ZlLWltYWdlJyApO1xuXHRcdFx0XHRcdHJlbW92ZUJ1dHRvbi5jbGFzc0xpc3QucmVtb3ZlKCAnaGlkZScgKTtcblx0XHRcdFx0XHRyZW1vdmVCdXR0b24uY2xhc3NMaXN0LmFkZCggJ3Nob3cnICk7XG5cdFx0XHRcdH0gKTtcblxuXHRcdFx0XHQvLyBPcGVuIG1vZGFsLlxuXHRcdFx0XHRvcHRpb25lckN1c3RvbUZpbGVGcmFtZS5vcGVuKCk7XG5cdFx0XHR9ICk7XG5cdFx0fSApO1xuXG5cdFx0Y29uc3QgYnRuUmVtb3ZlSW1hZ2UgPSBkb2N1bWVudC5nZXRFbGVtZW50c0J5Q2xhc3NOYW1lKCAnanMtcmVtb3ZlLWltYWdlJyApO1xuXHRcdGNvbnN0IGJ0blJlbW92ZUltYWdlQXJyYXkgPSBbIC4uLmJ0blJlbW92ZUltYWdlIF07XG5cblx0XHRidG5SZW1vdmVJbWFnZUFycmF5LmZvckVhY2goICggZWxlbSApID0+IHtcblx0XHRcdGVsZW0uYWRkRXZlbnRMaXN0ZW5lciggJ2NsaWNrJywgKCBlICkgPT4ge1xuXHRcdFx0XHRlLnByZXZlbnREZWZhdWx0KCk7XG5cdFx0XHRcdC8vIEVtcHR5IHZhbHVlLlxuXHRcdFx0XHRlbGVtLnBhcmVudE5vZGUucXVlcnlTZWxlY3RvciggJy5pbWcnICkudmFsdWUgPSAnJztcblx0XHRcdFx0Ly8gSGlkZSBwcmV2aWV3LlxuXHRcdFx0XHRlbGVtLnBhcmVudE5vZGUucXVlcnlTZWxlY3RvciggJy5pbWFnZS1wcmV2aWV3LXdyYXAnICkuaW5uZXJIVE1MID0gJyc7XG5cdFx0XHRcdC8vIEhpZGUgcmVtb3ZlIGJ1dHRvbi5cblx0XHRcdFx0ZWxlbS5jbGFzc0xpc3QucmVtb3ZlKCAnc2hvdycgKTtcblx0XHRcdFx0ZWxlbS5jbGFzc0xpc3QuYWRkKCAnaGlkZScgKTtcblx0XHRcdH0gKTtcblx0XHR9ICk7XG5cdH1cbn1cblxuZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lciggJ0RPTUNvbnRlbnRMb2FkZWQnLCBmdW5jdGlvbigpIHtcblx0bmV3IEFwcCgpO1xufSApO1xuIl0sIm5hbWVzIjpbIkFwcCIsImNvbnN0cnVjdG9yIiwiaW5pdEhlYWRpbmciLCJpbml0Q29sb3IiLCJpbml0TWVkaWEiLCJpc1RhYiIsImRvY3VtZW50IiwicXVlcnlTZWxlY3RvciIsImNsYXNzTGlzdCIsImNvbnRhaW5zIiwiaW5pdFRhYiIsImZvcm1GaWVsZEhlYWRpbmciLCJnZXRFbGVtZW50c0J5Q2xhc3NOYW1lIiwiZm9ybUZpZWxkSGVhZGluZ0FycmF5IiwiZm9yRWFjaCIsImVsZW0iLCJ0ciIsInBhcmVudE5vZGUiLCJzdHlsZSIsImRpc3BsYXkiLCJzZXRBdHRyaWJ1dGUiLCJmaWVsZENvbG9yIiwiZmllbGRDb2xvckFycmF5IiwialF1ZXJ5Iiwid3BDb2xvclBpY2tlciIsIm9wdGlvbmVyV3JhcHBlciIsImdldEVsZW1lbnRCeUlkIiwidGFiQ29udGVudHMiLCJ0YWJMaW5rcyIsInF1ZXJ5U2VsZWN0b3JBbGwiLCJ0YWJDb250ZW50c0FycmF5IiwidGFiTGlua3NBcnJheSIsImFjdGl2ZVRhYiIsImxvY2FsU3RvcmFnZSIsImdldEl0ZW0iLCJPUFRJT05FUl9PQkoiLCJzdG9yYWdlX2tleSIsInRhcmdldEdyb3VwIiwidGFyZ2V0TmF2IiwiYWRkIiwiYWRkRXZlbnRMaXN0ZW5lciIsImUiLCJwcmV2ZW50RGVmYXVsdCIsImVsZW1MaW5rIiwicmVtb3ZlIiwiZ2V0QXR0cmlidXRlIiwic2V0SXRlbSIsInJlcGxhY2UiLCJlbGVtQ29udGVudCIsIm9wdGlvbmVyQ3VzdG9tRmlsZUZyYW1lIiwidXBsb2FkRmllbGQiLCJ1cGxvYWRGaWVsZEFycmF5IiwidXBsb2FkZXJUaXRsZSIsImRhdGFzZXQiLCJ1cGxvYWRlcl90aXRsZSIsInVwbG9hZGVyQnV0dG9uVGV4dCIsInVwbG9hZGVyX2J1dHRvbl90ZXh0Iiwib3BlbiIsIk9wdGlvbmVyQ3VzdG9tSW1hZ2UiLCJ3cCIsIm1lZGlhIiwiY29udHJvbGxlciIsIkxpYnJhcnkiLCJleHRlbmQiLCJkZWZhdWx0cyIsIl8iLCJpZCIsInRpdGxlIiwiYWxsb3dMb2NhbEVkaXRzIiwiZGlzcGxheVNldHRpbmdzIiwiZGlzcGxheVVzZXJTZXR0aW5ncyIsIm11bHRpcGxlIiwibGlicmFyeSIsInF1ZXJ5IiwidHlwZSIsInByb3RvdHlwZSIsImZyYW1lcyIsImJ1dHRvbiIsInRleHQiLCJzdGF0ZSIsInN0YXRlcyIsIm9uIiwiY3VycmVudEltYWdlIiwiZ2V0IiwiZmlyc3QiLCJ1cmwiLCJ0b0pTT04iLCJ2YWx1ZSIsImlubmVySFRNTCIsInJlbW92ZUJ1dHRvbiIsImJ0blJlbW92ZUltYWdlIiwiYnRuUmVtb3ZlSW1hZ2VBcnJheSJdLCJzb3VyY2VSb290IjoiIn0=