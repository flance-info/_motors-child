/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./css/main.scss":
/*!***********************!*\
  !*** ./css/main.scss ***!
  \***********************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("// removed by extract-text-webpack-plugin//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9jc3MvbWFpbi5zY3NzP2I1NTEiXSwibmFtZXMiOltdLCJtYXBwaW5ncyI6IkFBQUEiLCJmaWxlIjoiLi9jc3MvbWFpbi5zY3NzLmpzIiwic291cmNlc0NvbnRlbnQiOlsiLy8gcmVtb3ZlZCBieSBleHRyYWN0LXRleHQtd2VicGFjay1wbHVnaW4iXSwic291cmNlUm9vdCI6IiJ9\n//# sourceURL=webpack-internal:///./css/main.scss\n");

/***/ }),

/***/ "./js/slide-in-panel.js":
/*!******************************!*\
  !*** ./js/slide-in-panel.js ***!
  \******************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("(function () {\n  // Slide In Panel - by CodyHouse.co\n  var panelTriggers = document.getElementsByClassName('js-cd-panel-trigger');\n\n  if (panelTriggers.length > 0) {\n    for (var i = 0; i < panelTriggers.length; i++) {\n      (function (i) {\n        var panelClass = 'js-cd-panel-' + panelTriggers[i].getAttribute('data-panel'),\n            panel = document.getElementsByClassName(panelClass)[0]; // open panel when clicking on trigger btn\n\n        panelTriggers[i].addEventListener('click', function (event) {\n          event.preventDefault();\n          addClass(panel, 'cd-panel--is-visible');\n        }); //close panel when clicking on 'x' or outside the panel\n\n        panel.addEventListener('click', function (event) {\n          if (hasClass(event.target, 'js-cd-close') || hasClass(event.target, panelClass)) {\n            event.preventDefault();\n            removeClass(panel, 'cd-panel--is-visible');\n            var c = document.documentElement.scrollTop || document.body.scrollTop;\n\n            if (c > 0) {\n              window.scrollTo(0, 0);\n            }\n          }\n        });\n      })(i);\n    }\n  } //class manipulations - needed if classList is not supported\n  //https://jaketrent.com/post/addremove-classes-raw-javascript/\n\n\n  function hasClass(el, className) {\n    if (el.classList) return el.classList.contains(className);else return !!el.className.match(new RegExp('(\\\\s|^)' + className + '(\\\\s|$)'));\n  }\n\n  function addClass(el, className) {\n    if (el.classList) el.classList.add(className);else if (!hasClass(el, className)) el.className += \" \" + className;\n  }\n\n  function removeClass(el, className) {\n    if (el.classList) el.classList.remove(className);else if (hasClass(el, className)) {\n      var reg = new RegExp('(\\\\s|^)' + className + '(\\\\s|$)');\n      el.className = el.className.replace(reg, ' ');\n    }\n  }\n})();//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9qcy9zbGlkZS1pbi1wYW5lbC5qcz81MGZjIl0sIm5hbWVzIjpbInBhbmVsVHJpZ2dlcnMiLCJkb2N1bWVudCIsImdldEVsZW1lbnRzQnlDbGFzc05hbWUiLCJsZW5ndGgiLCJpIiwicGFuZWxDbGFzcyIsImdldEF0dHJpYnV0ZSIsInBhbmVsIiwiYWRkRXZlbnRMaXN0ZW5lciIsImV2ZW50IiwicHJldmVudERlZmF1bHQiLCJhZGRDbGFzcyIsImhhc0NsYXNzIiwidGFyZ2V0IiwicmVtb3ZlQ2xhc3MiLCJjIiwiZG9jdW1lbnRFbGVtZW50Iiwic2Nyb2xsVG9wIiwiYm9keSIsIndpbmRvdyIsInNjcm9sbFRvIiwiZWwiLCJjbGFzc05hbWUiLCJjbGFzc0xpc3QiLCJjb250YWlucyIsIm1hdGNoIiwiUmVnRXhwIiwiYWRkIiwicmVtb3ZlIiwicmVnIiwicmVwbGFjZSJdLCJtYXBwaW5ncyI6IkFBQUEsQ0FBQyxZQUFVO0FBQ1A7QUFDSCxNQUFJQSxhQUFhLEdBQUdDLFFBQVEsQ0FBQ0Msc0JBQVQsQ0FBZ0MscUJBQWhDLENBQXBCOztBQUNBLE1BQUlGLGFBQWEsQ0FBQ0csTUFBZCxHQUF1QixDQUEzQixFQUErQjtBQUM5QixTQUFJLElBQUlDLENBQUMsR0FBRyxDQUFaLEVBQWVBLENBQUMsR0FBR0osYUFBYSxDQUFDRyxNQUFqQyxFQUF5Q0MsQ0FBQyxFQUExQyxFQUE4QztBQUM3QyxPQUFDLFVBQVNBLENBQVQsRUFBVztBQUNYLFlBQUlDLFVBQVUsR0FBRyxpQkFBZUwsYUFBYSxDQUFDSSxDQUFELENBQWIsQ0FBaUJFLFlBQWpCLENBQThCLFlBQTlCLENBQWhDO0FBQUEsWUFDQ0MsS0FBSyxHQUFHTixRQUFRLENBQUNDLHNCQUFULENBQWdDRyxVQUFoQyxFQUE0QyxDQUE1QyxDQURULENBRFcsQ0FHWDs7QUFDQUwscUJBQWEsQ0FBQ0ksQ0FBRCxDQUFiLENBQWlCSSxnQkFBakIsQ0FBa0MsT0FBbEMsRUFBMkMsVUFBU0MsS0FBVCxFQUFlO0FBQ3pEQSxlQUFLLENBQUNDLGNBQU47QUFDQUMsa0JBQVEsQ0FBQ0osS0FBRCxFQUFRLHNCQUFSLENBQVI7QUFDQSxTQUhELEVBSlcsQ0FRWDs7QUFDQUEsYUFBSyxDQUFDQyxnQkFBTixDQUF1QixPQUF2QixFQUFnQyxVQUFTQyxLQUFULEVBQWU7QUFDOUMsY0FBSUcsUUFBUSxDQUFDSCxLQUFLLENBQUNJLE1BQVAsRUFBZSxhQUFmLENBQVIsSUFBeUNELFFBQVEsQ0FBQ0gsS0FBSyxDQUFDSSxNQUFQLEVBQWVSLFVBQWYsQ0FBckQsRUFBaUY7QUFDaEZJLGlCQUFLLENBQUNDLGNBQU47QUFDQUksdUJBQVcsQ0FBQ1AsS0FBRCxFQUFRLHNCQUFSLENBQVg7QUFFQSxnQkFBTVEsQ0FBQyxHQUFHZCxRQUFRLENBQUNlLGVBQVQsQ0FBeUJDLFNBQXpCLElBQXNDaEIsUUFBUSxDQUFDaUIsSUFBVCxDQUFjRCxTQUE5RDs7QUFDQSxnQkFBSUYsQ0FBQyxHQUFHLENBQVIsRUFBVztBQUNWSSxvQkFBTSxDQUFDQyxRQUFQLENBQWdCLENBQWhCLEVBQW1CLENBQW5CO0FBQ0E7QUFDRDtBQUNELFNBVkQ7QUFXQSxPQXBCRCxFQW9CR2hCLENBcEJIO0FBcUJBO0FBQ0QsR0EzQlMsQ0E2QlY7QUFDQTs7O0FBQ0EsV0FBU1EsUUFBVCxDQUFrQlMsRUFBbEIsRUFBc0JDLFNBQXRCLEVBQWlDO0FBQzlCLFFBQUlELEVBQUUsQ0FBQ0UsU0FBUCxFQUFrQixPQUFPRixFQUFFLENBQUNFLFNBQUgsQ0FBYUMsUUFBYixDQUFzQkYsU0FBdEIsQ0FBUCxDQUFsQixLQUNLLE9BQU8sQ0FBQyxDQUFDRCxFQUFFLENBQUNDLFNBQUgsQ0FBYUcsS0FBYixDQUFtQixJQUFJQyxNQUFKLENBQVcsWUFBWUosU0FBWixHQUF3QixTQUFuQyxDQUFuQixDQUFUO0FBQ1A7O0FBQ0QsV0FBU1gsUUFBVCxDQUFrQlUsRUFBbEIsRUFBc0JDLFNBQXRCLEVBQWlDO0FBQy9CLFFBQUlELEVBQUUsQ0FBQ0UsU0FBUCxFQUFrQkYsRUFBRSxDQUFDRSxTQUFILENBQWFJLEdBQWIsQ0FBaUJMLFNBQWpCLEVBQWxCLEtBQ0ssSUFBSSxDQUFDVixRQUFRLENBQUNTLEVBQUQsRUFBS0MsU0FBTCxDQUFiLEVBQThCRCxFQUFFLENBQUNDLFNBQUgsSUFBZ0IsTUFBTUEsU0FBdEI7QUFDcEM7O0FBQ0QsV0FBU1IsV0FBVCxDQUFxQk8sRUFBckIsRUFBeUJDLFNBQXpCLEVBQW9DO0FBQ2pDLFFBQUlELEVBQUUsQ0FBQ0UsU0FBUCxFQUFrQkYsRUFBRSxDQUFDRSxTQUFILENBQWFLLE1BQWIsQ0FBb0JOLFNBQXBCLEVBQWxCLEtBQ0ssSUFBSVYsUUFBUSxDQUFDUyxFQUFELEVBQUtDLFNBQUwsQ0FBWixFQUE2QjtBQUNoQyxVQUFJTyxHQUFHLEdBQUcsSUFBSUgsTUFBSixDQUFXLFlBQVlKLFNBQVosR0FBd0IsU0FBbkMsQ0FBVjtBQUNBRCxRQUFFLENBQUNDLFNBQUgsR0FBYUQsRUFBRSxDQUFDQyxTQUFILENBQWFRLE9BQWIsQ0FBcUJELEdBQXJCLEVBQTBCLEdBQTFCLENBQWI7QUFDRDtBQUNIO0FBQ0QsQ0E5Q0QiLCJmaWxlIjoiLi9qcy9zbGlkZS1pbi1wYW5lbC5qcy5qcyIsInNvdXJjZXNDb250ZW50IjpbIihmdW5jdGlvbigpe1xuICAgIC8vIFNsaWRlIEluIFBhbmVsIC0gYnkgQ29keUhvdXNlLmNvXG5cdHZhciBwYW5lbFRyaWdnZXJzID0gZG9jdW1lbnQuZ2V0RWxlbWVudHNCeUNsYXNzTmFtZSgnanMtY2QtcGFuZWwtdHJpZ2dlcicpO1xuXHRpZiggcGFuZWxUcmlnZ2Vycy5sZW5ndGggPiAwICkge1xuXHRcdGZvcih2YXIgaSA9IDA7IGkgPCBwYW5lbFRyaWdnZXJzLmxlbmd0aDsgaSsrKSB7XG5cdFx0XHQoZnVuY3Rpb24oaSl7XG5cdFx0XHRcdHZhciBwYW5lbENsYXNzID0gJ2pzLWNkLXBhbmVsLScrcGFuZWxUcmlnZ2Vyc1tpXS5nZXRBdHRyaWJ1dGUoJ2RhdGEtcGFuZWwnKSxcblx0XHRcdFx0XHRwYW5lbCA9IGRvY3VtZW50LmdldEVsZW1lbnRzQnlDbGFzc05hbWUocGFuZWxDbGFzcylbMF07XG5cdFx0XHRcdC8vIG9wZW4gcGFuZWwgd2hlbiBjbGlja2luZyBvbiB0cmlnZ2VyIGJ0blxuXHRcdFx0XHRwYW5lbFRyaWdnZXJzW2ldLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgZnVuY3Rpb24oZXZlbnQpe1xuXHRcdFx0XHRcdGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XG5cdFx0XHRcdFx0YWRkQ2xhc3MocGFuZWwsICdjZC1wYW5lbC0taXMtdmlzaWJsZScpO1xuXHRcdFx0XHR9KTtcblx0XHRcdFx0Ly9jbG9zZSBwYW5lbCB3aGVuIGNsaWNraW5nIG9uICd4JyBvciBvdXRzaWRlIHRoZSBwYW5lbFxuXHRcdFx0XHRwYW5lbC5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIGZ1bmN0aW9uKGV2ZW50KXtcblx0XHRcdFx0XHRpZiggaGFzQ2xhc3MoZXZlbnQudGFyZ2V0LCAnanMtY2QtY2xvc2UnKSB8fCBoYXNDbGFzcyhldmVudC50YXJnZXQsIHBhbmVsQ2xhc3MpKSB7XG5cdFx0XHRcdFx0XHRldmVudC5wcmV2ZW50RGVmYXVsdCgpO1xuXHRcdFx0XHRcdFx0cmVtb3ZlQ2xhc3MocGFuZWwsICdjZC1wYW5lbC0taXMtdmlzaWJsZScpO1xuXG5cdFx0XHRcdFx0XHRjb25zdCBjID0gZG9jdW1lbnQuZG9jdW1lbnRFbGVtZW50LnNjcm9sbFRvcCB8fCBkb2N1bWVudC5ib2R5LnNjcm9sbFRvcDtcblx0XHRcdFx0XHRcdGlmIChjID4gMCkge1xuXHRcdFx0XHRcdFx0XHR3aW5kb3cuc2Nyb2xsVG8oMCwgMCk7XG5cdFx0XHRcdFx0XHR9XG5cdFx0XHRcdFx0fVxuXHRcdFx0XHR9KTtcblx0XHRcdH0pKGkpO1xuXHRcdH1cblx0fVxuXHRcblx0Ly9jbGFzcyBtYW5pcHVsYXRpb25zIC0gbmVlZGVkIGlmIGNsYXNzTGlzdCBpcyBub3Qgc3VwcG9ydGVkXG5cdC8vaHR0cHM6Ly9qYWtldHJlbnQuY29tL3Bvc3QvYWRkcmVtb3ZlLWNsYXNzZXMtcmF3LWphdmFzY3JpcHQvXG5cdGZ1bmN0aW9uIGhhc0NsYXNzKGVsLCBjbGFzc05hbWUpIHtcblx0ICBcdGlmIChlbC5jbGFzc0xpc3QpIHJldHVybiBlbC5jbGFzc0xpc3QuY29udGFpbnMoY2xhc3NOYW1lKTtcblx0ICBcdGVsc2UgcmV0dXJuICEhZWwuY2xhc3NOYW1lLm1hdGNoKG5ldyBSZWdFeHAoJyhcXFxcc3xeKScgKyBjbGFzc05hbWUgKyAnKFxcXFxzfCQpJykpO1xuXHR9XG5cdGZ1bmN0aW9uIGFkZENsYXNzKGVsLCBjbGFzc05hbWUpIHtcblx0IFx0aWYgKGVsLmNsYXNzTGlzdCkgZWwuY2xhc3NMaXN0LmFkZChjbGFzc05hbWUpO1xuXHQgXHRlbHNlIGlmICghaGFzQ2xhc3MoZWwsIGNsYXNzTmFtZSkpIGVsLmNsYXNzTmFtZSArPSBcIiBcIiArIGNsYXNzTmFtZTtcblx0fVxuXHRmdW5jdGlvbiByZW1vdmVDbGFzcyhlbCwgY2xhc3NOYW1lKSB7XG5cdCAgXHRpZiAoZWwuY2xhc3NMaXN0KSBlbC5jbGFzc0xpc3QucmVtb3ZlKGNsYXNzTmFtZSk7XG5cdCAgXHRlbHNlIGlmIChoYXNDbGFzcyhlbCwgY2xhc3NOYW1lKSkge1xuXHQgICAgXHR2YXIgcmVnID0gbmV3IFJlZ0V4cCgnKFxcXFxzfF4pJyArIGNsYXNzTmFtZSArICcoXFxcXHN8JCknKTtcblx0ICAgIFx0ZWwuY2xhc3NOYW1lPWVsLmNsYXNzTmFtZS5yZXBsYWNlKHJlZywgJyAnKTtcblx0ICBcdH1cblx0fVxufSkoKTsiXSwic291cmNlUm9vdCI6IiJ9\n//# sourceURL=webpack-internal:///./js/slide-in-panel.js\n");

/***/ }),

/***/ 0:
/*!****************************************************!*\
  !*** multi ./js/slide-in-panel.js ./css/main.scss ***!
  \****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(/*! /Users/maxipal/Documents/Projects/cruxinterfaces/js/slide-in-panel.js */"./js/slide-in-panel.js");
module.exports = __webpack_require__(/*! /Users/maxipal/Documents/Projects/cruxinterfaces/css/main.scss */"./css/main.scss");


/***/ })

/******/ });