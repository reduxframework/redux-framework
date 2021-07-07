/******/ (function(modules) { // webpackBootstrap
/******/ 	// install a JSONP callback for chunk loading
/******/ 	function webpackJsonpCallback(data) {
/******/ 		var chunkIds = data[0];
/******/ 		var moreModules = data[1];
/******/ 		var executeModules = data[2];
/******/
/******/ 		// add "moreModules" to the modules object,
/******/ 		// then flag all "chunkIds" as loaded and fire callback
/******/ 		var moduleId, chunkId, i = 0, resolves = [];
/******/ 		for(;i < chunkIds.length; i++) {
/******/ 			chunkId = chunkIds[i];
/******/ 			if(Object.prototype.hasOwnProperty.call(installedChunks, chunkId) && installedChunks[chunkId]) {
/******/ 				resolves.push(installedChunks[chunkId][0]);
/******/ 			}
/******/ 			installedChunks[chunkId] = 0;
/******/ 		}
/******/ 		for(moduleId in moreModules) {
/******/ 			if(Object.prototype.hasOwnProperty.call(moreModules, moduleId)) {
/******/ 				modules[moduleId] = moreModules[moduleId];
/******/ 			}
/******/ 		}
/******/ 		if(parentJsonpFunction) parentJsonpFunction(data);
/******/
/******/ 		while(resolves.length) {
/******/ 			resolves.shift()();
/******/ 		}
/******/
/******/ 		// add entry modules from loaded chunk to deferred list
/******/ 		deferredModules.push.apply(deferredModules, executeModules || []);
/******/
/******/ 		// run deferred modules when all chunks ready
/******/ 		return checkDeferredModules();
/******/ 	};
/******/ 	function checkDeferredModules() {
/******/ 		var result;
/******/ 		for(var i = 0; i < deferredModules.length; i++) {
/******/ 			var deferredModule = deferredModules[i];
/******/ 			var fulfilled = true;
/******/ 			for(var j = 1; j < deferredModule.length; j++) {
/******/ 				var depId = deferredModule[j];
/******/ 				if(installedChunks[depId] !== 0) fulfilled = false;
/******/ 			}
/******/ 			if(fulfilled) {
/******/ 				deferredModules.splice(i--, 1);
/******/ 				result = __webpack_require__(__webpack_require__.s = deferredModule[0]);
/******/ 			}
/******/ 		}
/******/
/******/ 		return result;
/******/ 	}
/******/
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// object to store loaded and loading chunks
/******/ 	// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 	// Promise = chunk loading, 0 = chunk loaded
/******/ 	var installedChunks = {
/******/ 		"redux-templates": 0
/******/ 	};
/******/
/******/ 	var deferredModules = [];
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
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	var jsonpArray = window["webpackJsonp"] = window["webpackJsonp"] || [];
/******/ 	var oldJsonpFunction = jsonpArray.push.bind(jsonpArray);
/******/ 	jsonpArray.push = webpackJsonpCallback;
/******/ 	jsonpArray = jsonpArray.slice();
/******/ 	for(var i = 0; i < jsonpArray.length; i++) webpackJsonpCallback(jsonpArray[i]);
/******/ 	var parentJsonpFunction = oldJsonpFunction;
/******/
/******/
/******/ 	// add entry module to deferred list
/******/ 	deferredModules.push(["./redux-templates/src/index.js","vendor"]);
/******/ 	// run deferred modules when ready
/******/ 	return checkDeferredModules();
/******/ })
/************************************************************************/
/******/ ({

/***/ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/blocks/library/style.scss":
/*!************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/blocks/library/style.scss ***!
  \************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js")(false);
// Module
exports.push([module.i, ".redux-template-library-block .components-placeholder__label svg {\n  margin-right: 8px; }\n\n.redux-template-library-block button.components-button {\n  height: auto;\n  font-weight: 600;\n  text-transform: uppercase;\n  font-size: 13px;\n  padding: 11px 20px;\n  background: #fff; }\n\n.components-button.has-icon.redux-templates-insert-library-button {\n  height: 100%; }\n\n.redux-templates-insert-library-button {\n  margin-left: 10px;\n  margin-right: 10px; }\n  .redux-templates-insert-library-button svg {\n    width: 20px;\n    height: 20px; }\n\n.redux-insert-library-button {\n  margin-left: 10px;\n  margin-right: 10px; }\n", ""]);



/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/challenge/challenge-list-block/style.scss":
/*!****************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/challenge/challenge-list-block/style.scss ***!
  \****************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js")(false);
// Module
exports.push([module.i, ".challenge-list-block {\n  padding: 15px 20px 20px;\n  margin-bottom: 15px;\n  background-color: #fff;\n  overflow: hidden;\n  border-radius: 4px;\n  box-shadow: 0 0 40px 0 rgba(0, 0, 0, 0.2);\n  -webkit-box-shadow: 0 0 40px 0 rgba(0, 0, 0, 0.2);\n  -moz-box-shadow: 0 0 40px 0 rgba(0, 0, 0, 0.2); }\n\n.challenge-bar {\n  border-radius: 20px;\n  background-color: #eee; }\n\n.challenge-bar div {\n  width: 0;\n  height: 20px;\n  border-radius: 20px;\n  background-color: #2576a4; }\n\n.challenge-list {\n  list-style: none;\n  margin: 17px 0 20px;\n  font-size: 13px; }\n  .challenge-list li {\n    margin-bottom: 17px; }\n    .challenge-list li i {\n      display: inline-block;\n      font-size: 18px;\n      color: #d6d6d6;\n      margin-right: 8px;\n      line-height: 15px;\n      vertical-align: bottom;\n      border-radius: 50%; }\n    .challenge-list li.challenge-item-current {\n      font-weight: bold; }\n    .challenge-list li.challenge-item-current i {\n      color: #df7739;\n      font-size: 17.5px;\n      line-height: 1;\n      text-indent: 0.5px; }\n    .challenge-list li.challenge-item-completed {\n      font-weight: initial;\n      text-decoration: line-through; }\n    .challenge-list li.challenge-item-completed i {\n      color: #6ab255;\n      font-size: 18px;\n      background-color: #fff; }\n    .challenge-list li .dashicons-yes {\n      display: none;\n      vertical-align: middle; }\n\n/* /.challenge-list */\nbutton.btn-challenge-start {\n  font-size: 12px;\n  padding: 6px 15px;\n  border: 1px solid #00a7e5;\n  background-color: #24b0a6;\n  border-radius: 3px;\n  color: #fff;\n  cursor: pointer; }\n  button.btn-challenge-start:hover {\n    background-color: #19837c; }\n\n.btn-challenge-cancel,\n.btn-challenge-skip {\n  margin: 6px 0;\n  border: 0;\n  text-decoration: underline; }\n\n.btn-challenge-cancel,\n.btn-challenge-skip {\n  align-self: flex-end;\n  color: #909090;\n  font-size: 12px;\n  font-weight: normal;\n  background: none; }\n\n.wpforms-btn-md {\n  min-height: initial; }\n\n.challenge-button-row {\n  display: flex;\n  justify-content: space-between; }\n  .challenge-button-row button {\n    cursor: pointer; }\n\n.started.challenge-button-row {\n  align-content: space-between;\n  flex-direction: column; }\n", ""]);



/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/challenge/challenge-timer/style.scss":
/*!***********************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/challenge/challenge-timer/style.scss ***!
  \***********************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js")(false);
// Module
exports.push([module.i, ".block-timer {\n  padding: 5px;\n  background-color: #2d2d2d;\n  border-radius: 500px;\n  width: 277px;\n  box-sizing: border-box;\n  display: -webkit-box;\n  display: -ms-flexbox;\n  display: flex;\n  -webkit-box-pack: justify;\n  -ms-flex-pack: justify;\n  justify-content: space-between;\n  -webkit-box-align: center;\n  -ms-flex-align: center;\n  align-items: center;\n  box-shadow: 0 0 40px 0 rgba(0, 0, 0, 0.2);\n  -webkit-box-shadow: 0 0 40px 0 rgba(0, 0, 0, 0.2);\n  -moz-box-shadow: 0 0 40px 0 rgba(0, 0, 0, 0.2);\n  padding-left: 50px; }\n  .block-timer img {\n    width: 50px;\n    height: 50px;\n    border-radius: 50%; }\n  .block-timer h3 {\n    font-size: 14px;\n    font-weight: 500;\n    color: #fff;\n    margin: 0; }\n  .block-timer p {\n    font-size: 14px;\n    font-weight: 100;\n    color: #ababab;\n    margin: 0; }\n  .block-timer .caret-icon {\n    border: 2px solid;\n    border-radius: 50%;\n    color: #6c6c6c;\n    margin: 0 15px;\n    width: 23px;\n    height: 23px;\n    font-size: 20px;\n    cursor: pointer; }\n    .block-timer .caret-icon .fa {\n      width: 100%;\n      text-align: center;\n      -webkit-transition: 400ms;\n      -o-transition: 400ms;\n      transition: 400ms; }\n    .block-timer .caret-icon.closed .fa {\n      -webkit-transform: rotate(180deg) translateY(1px);\n      -ms-transform: rotate(180deg) translateY(1px);\n      transform: rotate(180deg) translateY(1px); }\n", ""]);



/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/challenge/final-templates/style.scss":
/*!***********************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/challenge/final-templates/style.scss ***!
  \***********************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js")(false);
// Module
exports.push([module.i, ".challenge-popup-wrapper {\n  height: 500px;\n  border-radius: 10px; }\n\n.challenge-popup-header {\n  width: 100%;\n  height: 212px;\n  border-top-left-radius: 8px;\n  border-top-right-radius: 8px; }\n\n.challenge-popup-header-congrats {\n  background-position: center;\n  background-size: cover; }\n\n.challenge-popup-header-contact {\n  background-position: center;\n  background-size: auto 75%;\n  background-color: #eee;\n  background-repeat: no-repeat; }\n\n.challenge-popup-content {\n  padding: 30px 40px;\n  -webkit-font-smoothing: antialiased; }\n\n.challenge-popup-content h3 {\n  color: #24b0a6;\n  margin: 0 0 20px;\n  font-size: 24px;\n  font-family: \"Helvetica Neue\";\n  font-weight: 500; }\n\n.challenge-popup-content p {\n  font-size: 16px;\n  margin: 0 0 22px; }\n\n.challenge-popup-content b {\n  font-weight: 500; }\n\n.challenge-popup-content .challenge-contact-message {\n  box-shadow: none;\n  resize: none;\n  margin-bottom: 21px;\n  width: 100%;\n  min-height: 175px; }\n\n.challenge-popup-content label {\n  font-size: 13.8px;\n  display: block;\n  margin-bottom: 23px; }\n\n.challenge-popup-content input[type=\"checkbox\"] {\n  margin-right: 8px; }\n\n.challenge-popup-content .rating-stars {\n  color: #fdb72c;\n  font-size: 18px;\n  font-weight: bold; }\n\n.challenge-popup-close .fa-times {\n  font-size: 20px;\n  color: #777;\n  float: right;\n  margin: 15px;\n  border-radius: 50%;\n  cursor: pointer; }\n\n.challenge-popup-btn {\n  display: inline-block;\n  border-radius: 2px;\n  cursor: pointer;\n  text-decoration: none;\n  text-align: center;\n  vertical-align: middle;\n  white-space: nowrap;\n  box-shadow: none;\n  font-size: 15px;\n  font-weight: 600;\n  padding: 14px 25px;\n  border: 1px solid #00a7e5;\n  background-color: #24b0a6;\n  color: #fff; }\n  .challenge-popup-btn:hover {\n    border: 1px solid #19837c;\n    background-color: #19837c;\n    color: #fff; }\n  .challenge-popup-btn .dashicons-external {\n    margin-left: 6px; }\n\n.challenge-popup-content.challenge-contact p {\n  font-size: 14px; }\n\n.challenge-popup-content.challenge-contact textarea {\n  margin-bottom: 10px; }\n\n.challenge-popup-content.challenge-contact label {\n  font-size: 13px;\n  margin-bottom: 15px; }\n", ""]);



/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/challenge/style.scss":
/*!*******************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/challenge/style.scss ***!
  \*******************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js")(false);
// Module
exports.push([module.i, ".redux-templates-challenge {\n  display: block;\n  position: fixed;\n  right: 1em;\n  bottom: 55px;\n  max-width: 277px;\n  z-index: 9999; }\n  .redux-templates-challenge p {\n    font-size: 14px;\n    line-height: 1.4;\n    margin-top: 0;\n    color: #23282c; }\n  .redux-templates-challenge b {\n    font-weight: 500; }\n  .redux-templates-challenge.challenge-start {\n    display: initial; }\n\n@media all and (max-height: 900px) {\n  #challenge-contact-popup {\n    margin: 50px 0 20px; } }\n\n.challenge-tooltip.tooltipster-sidetip {\n  z-index: 100100 !important; }\n\n.challenge-tooltip.tooltipster-sidetip.tooltipster-top .tooltipster-box {\n  margin-bottom: 18px; }\n\n.challenge-tooltip.tooltipster-sidetip.tooltipster-top .tooltipster-arrow {\n  bottom: 8px; }\n\n.challenge-tooltip.tooltipster-sidetip.tooltipster-top .tooltipster-arrow-background {\n  top: 0; }\n\n.challenge-tooltip.tooltipster-sidetip.tooltipster-right .tooltipster-box {\n  margin-right: 18px; }\n\n.challenge-tooltip.tooltipster-sidetip.tooltipster-right .tooltipster-arrow {\n  left: 8px; }\n\n.challenge-tooltip.tooltipster-sidetip .tooltipster-box {\n  background: #fff;\n  border: none;\n  border-radius: 4px;\n  box-shadow: 0 10px 35px 0 rgba(0, 0, 0, 0.25);\n  -webkit-box-shadow: 0 10px 35px 0 rgba(0, 0, 0, 0.25);\n  -moz-box-shadow: 0 10px 35px 0 rgba(0, 0, 0, 0.25); }\n\n.challenge-tooltip.tooltipster-sidetip .tooltipster-box .tooltipster-content {\n  color: #444;\n  padding: 16px 20px 18px; }\n\n.challenge-tooltip.tooltipster-sidetip .tooltipster-box .tooltipster-content h3 {\n  font-size: 15px;\n  margin: 0; }\n\n.challenge-tooltip.tooltipster-sidetip .tooltipster-box .tooltipster-content p {\n  margin: 10px 0 0; }\n\n.challenge-tooltip.tooltipster-sidetip .tooltipster-box .challenge-done-btn {\n  border-radius: 3px;\n  cursor: pointer;\n  text-decoration: none;\n  text-align: center;\n  vertical-align: middle;\n  white-space: nowrap;\n  box-shadow: none;\n  font-size: 13px;\n  font-weight: 600;\n  padding: 7px 18px;\n  border: 1px solid #00a7e5;\n  background-color: #24b0a6;\n  color: #fff;\n  display: block;\n  margin: 15px auto 0;\n  outline: none; }\n\n.challenge-tooltip.tooltipster-sidetip .tooltipster-box .challenge-done-btn:hover {\n  border: 1px solid #19837c;\n  background-color: #19837c; }\n\n.challenge-tooltip.tooltipster-sidetip .tooltipster-arrow-border {\n  border: none; }\n\n.challenge-tooltip.tooltipster-sidetip.tooltipster-top .tooltipster-arrow-background {\n  border-bottom-color: #fff; }\n\n.challenge-tooltip.tooltipster-sidetip.tooltipster-left .tooltipster-arrow-background {\n  border-right-color: #fff; }\n\n.challenge-tooltip.tooltipster-sidetip.tooltipster-bottom .tooltipster-arrow-background {\n  border-top-color: #fff; }\n\n.challenge-tooltip.tooltipster-sidetip.tooltipster-right .tooltipster-arrow-background {\n  border-left-color: #fff; }\n\n.block-editor-page .edit-post-layout .components-notice-list > div {\n  padding-left: 50px; }\n\n.block-editor-page span.wpforms-challenge-dot-step5 {\n  margin: 22px 18px;\n  z-index: 9999; }\n\n.block-editor-page .wpforms-challenge-tooltip.wpforms-challenge-tooltip-step5 {\n  max-width: 233px;\n  z-index: 99980 !important; }\n\n.challenge-wrapper {\n  position: fixed;\n  top: 0;\n  left: 0;\n  z-index: 600000; }\n\n.challenge-tooltip-holder {\n  position: fixed;\n  top: 0;\n  left: 0;\n  z-index: 600000; }\n  .challenge-tooltip-holder .tooltipster-box {\n    position: absolute;\n    box-shadow: 0 -10px 35px 0 rgba(0, 0, 0, 0.25);\n    z-index: 10000;\n    background: #fff;\n    padding: 15px 20px; }\n\n.challenge-dot {\n  display: inline-block;\n  width: 16px;\n  height: 16px;\n  background: #24b0a6;\n  box-shadow: 0 0 0 4px rgba(25, 131, 124, 0.15);\n  border-radius: 50%;\n  border: 0;\n  padding: 0; }\n\n.tooltipster-sidetip .tooltipster-arrow {\n  position: absolute;\n  width: 20px;\n  height: 10px;\n  z-index: 10000; }\n\n.tooltipster-sidetip .tooltipster-arrow-uncropped {\n  position: relative; }\n\n.tooltipster-sidetip .tooltipster-arrow-border {\n  left: 0;\n  top: 0;\n  border: none;\n  width: 0;\n  height: 0;\n  position: absolute; }\n\n.challenge-tooltip.tooltipster-sidetip .tooltipster-arrow-background {\n  top: 0;\n  left: 0;\n  width: 0;\n  height: 0;\n  position: absolute;\n  border: 10px solid transparent; }\n\n.challenge-tooltip.tooltipster-sidetip.tooltipster-top {\n  border-top-color: #fff; }\n\n.challenge-tooltip.tooltipster-sidetip.tooltipster-bottom {\n  border-bottom-color: #fff; }\n\n.block-timer .caret-icon .fa {\n  -webkit-transition: 400ms;\n  -o-transition: 400ms;\n  transition: 400ms;\n  line-height: 23px; }\n", ""]);



/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/components/background-image/style.scss":
/*!*************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/components/background-image/style.scss ***!
  \*************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js")(false);
// Module
exports.push([module.i, ".redux-templates-single-section-item .block-editor-block-preview__container {\n  margin: 0 auto;\n  min-height: 130px; }\n", ""]);



/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/components/button-group/style.scss":
/*!*********************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/components/button-group/style.scss ***!
  \*********************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js")(false);
// Module
exports.push([module.i, ".redux-templates-import-button-group {\n  text-align: center; }\n  .redux-templates-import-button-group.disabled span a {\n    cursor: default;\n    opacity: 0.8; }\n\n.redux-templates-single-section-item {\n  margin-bottom: 15px; }\n  .redux-templates-single-section-item .redux-templates-import-button-group {\n    margin-top: 10%; }\n", ""]);



/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/components/dependent-plugins/style.scss":
/*!**************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/components/dependent-plugins/style.scss ***!
  \**************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js")(false);
// Module
exports.push([module.i, ".redux-templates-button-display-dependencies {\n  display: flex;\n  justify-content: center;\n  margin: 10px; }\n  .redux-templates-button-display-dependencies span svg {\n    margin-right: 5px;\n    cursor: pointer; }\n  .redux-templates-button-display-dependencies span svg * {\n    fill: #f7f7f7; }\n  .redux-templates-button-display-dependencies span.missing-dependency svg * {\n    fill: rgba(247, 247, 247, 0.5); }\n", ""]);



/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/components/error-notice/style.scss":
/*!*********************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/components/error-notice/style.scss ***!
  \*********************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js")(false);
// Module
exports.push([module.i, ".redux-templates-error-notice .components-notice {\n  display: flex;\n  font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, Oxygen-Sans, Ubuntu, Cantarell, \"Helvetica Neue\", sans-serif;\n  font-size: 13px;\n  background-color: #e5f5fa;\n  border-left: 4px solid #00a0d2;\n  margin: 5px 15px 2px;\n  padding: 8px 12px;\n  align-items: center;\n  position: absolute;\n  height: 50px;\n  z-index: 9999;\n  width: 50%;\n  right: 0;\n  top: 70px;\n  transition: opacity 2s linear; }\n  .redux-templates-error-notice .components-notice.is-dismissible {\n    padding-right: 0; }\n  .redux-templates-error-notice .components-notice.is-success {\n    border-left-color: #4ab866;\n    background-color: rgba(74, 184, 102, 0.95); }\n  .redux-templates-error-notice .components-notice.is-warning {\n    border-left-color: #f0b849;\n    background-color: rgba(254, 248, 238, 0.95); }\n  .redux-templates-error-notice .components-notice.is-error {\n    border-left-color: #d94f4f;\n    background-color: rgba(249, 226, 226, 0.95); }\n", ""]);



/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/components/fab-wrapper/styles.scss":
/*!*********************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/components/fab-wrapper/styles.scss ***!
  \*********************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js")(false);
// Module
exports.push([module.i, ".rtf {\n  box-sizing: border-box;\n  margin: 25px;\n  position: fixed;\n  white-space: nowrap;\n  z-index: 9998;\n  padding-left: 0;\n  list-style: none; }\n  .rtf.open .rtf--mb > * {\n    transform-origin: center center;\n    transform: none;\n    transition: ease-in-out transform 0.2s; }\n  .rtf.open .rtf--mb > ul {\n    list-style: none;\n    margin: 0;\n    padding: 0; }\n  .rtf.open .rtf--ab__c:hover > span {\n    transition: ease-in-out opacity 0.2s;\n    opacity: 0.9; }\n  .rtf.open .rtf--ab__c > span.always-show {\n    transition: ease-in-out opacity 0.2s;\n    opacity: 0.9; }\n  .rtf.open .rtf--ab__c:nth-child(1) {\n    transform: translateY(-60px) scale(1);\n    transition-delay: 0.03s; }\n    .rtf.open .rtf--ab__c:nth-child(1).top {\n      transform: translateY(60px) scale(1); }\n  .rtf.open .rtf--ab__c:nth-child(2) {\n    transform: translateY(-120px) scale(1);\n    transition-delay: 0.09s; }\n    .rtf.open .rtf--ab__c:nth-child(2).top {\n      transform: translateY(120px) scale(1); }\n  .rtf.open .rtf--ab__c:nth-child(3) {\n    transform: translateY(-180px) scale(1);\n    transition-delay: 0.12s; }\n    .rtf.open .rtf--ab__c:nth-child(3).top {\n      transform: translateY(180px) scale(1); }\n  .rtf.open .rtf--ab__c:nth-child(4) {\n    transform: translateY(-240px) scale(1);\n    transition-delay: 0.15s; }\n    .rtf.open .rtf--ab__c:nth-child(4).top {\n      transform: translateY(240px) scale(1); }\n  .rtf.open .rtf--ab__c:nth-child(5) {\n    transform: translateY(-300px) scale(1);\n    transition-delay: 0.18s; }\n    .rtf.open .rtf--ab__c:nth-child(5).top {\n      transform: translateY(300px) scale(1); }\n  .rtf.open .rtf--ab__c:nth-child(6) {\n    transform: translateY(-360px) scale(1);\n    transition-delay: 0.21s; }\n    .rtf.open .rtf--ab__c:nth-child(6).top {\n      transform: translateY(360px) scale(1); }\n\n.rtf--mb__c {\n  padding: 25px;\n  margin: -25px; }\n  .rtf--mb__c *:last-child {\n    margin-bottom: 0; }\n  .rtf--mb__c:hover > span {\n    transition: ease-in-out opacity 0.2s;\n    opacity: 0.9; }\n  .rtf--mb__c > span.always-show {\n    transition: ease-in-out opacity 0.2s;\n    opacity: 0.9; }\n  .rtf--mb__c > span {\n    opacity: 0;\n    transition: ease-in-out opacity 0.2s;\n    position: absolute;\n    top: 50%;\n    transform: translateY(-50%);\n    margin-right: 6px;\n    margin-left: 4px;\n    background: rgba(0, 0, 0, 0.75);\n    padding: 2px 4px;\n    border-radius: 2px;\n    color: #fff;\n    font-size: 13px;\n    box-shadow: 0 0 4px rgba(0, 0, 0, 0.14), 0 4px 8px rgba(0, 0, 0, 0.28); }\n    .rtf--mb__c > span.right {\n      right: 100%; }\n\n.rtf--mb {\n  height: 56px;\n  width: 56px;\n  z-index: 9999;\n  background-color: #666;\n  display: inline-flex;\n  justify-content: center;\n  align-items: center;\n  position: relative;\n  border: none;\n  border-radius: 50%;\n  box-shadow: 0 0 4px rgba(0, 0, 0, 0.14), 0 4px 8px rgba(0, 0, 0, 0.28);\n  cursor: pointer;\n  outline: none;\n  padding: 0;\n  -webkit-user-drag: none;\n  font-weight: bold;\n  color: #f1f1f1;\n  font-size: 18px; }\n  .rtf--mb > * {\n    transition: ease-in-out transform 0.2s; }\n\n.rtf--ab__c {\n  display: block;\n  position: absolute;\n  top: 0;\n  right: 1px;\n  padding: 10px 0;\n  margin: -10px 0;\n  transition: ease-in-out transform 0.2s; }\n  .rtf--ab__c > span {\n    opacity: 0;\n    transition: ease-in-out opacity 0.2s;\n    position: absolute;\n    top: 50%;\n    transform: translateY(-50%);\n    margin-right: 6px;\n    background: rgba(0, 0, 0, 0.75);\n    padding: 2px 4px;\n    border-radius: 2px;\n    color: #fff;\n    font-size: 13px;\n    box-shadow: 0 0 4px rgba(0, 0, 0, 0.14), 0 4px 8px rgba(0, 0, 0, 0.28); }\n    .rtf--ab__c > span.right {\n      right: 100%; }\n  .rtf--ab__c:nth-child(1) {\n    transform: translateY(-60px) scale(0);\n    transition-delay: 0.21s; }\n    .rtf--ab__c:nth-child(1).top {\n      transform: translateY(60px) scale(0); }\n  .rtf--ab__c:nth-child(2) {\n    transform: translateY(-120px) scale(0);\n    transition-delay: 0.18s; }\n    .rtf--ab__c:nth-child(2).top {\n      transform: translateY(120px) scale(0); }\n  .rtf--ab__c:nth-child(3) {\n    transform: translateY(-180px) scale(0);\n    transition-delay: 0.15s; }\n    .rtf--ab__c:nth-child(3).top {\n      transform: translateY(180px) scale(0); }\n  .rtf--ab__c:nth-child(4) {\n    transform: translateY(-240px) scale(0);\n    transition-delay: 0.12s; }\n    .rtf--ab__c:nth-child(4).top {\n      transform: translateY(240px) scale(0); }\n  .rtf--ab__c:nth-child(5) {\n    transform: translateY(-300px) scale(0);\n    transition-delay: 0.09s; }\n    .rtf--ab__c:nth-child(5).top {\n      transform: translateY(300px) scale(0); }\n  .rtf--ab__c:nth-child(6) {\n    transform: translateY(-360px) scale(0);\n    transition-delay: 0.03s; }\n    .rtf--ab__c:nth-child(6).top {\n      transform: translateY(360px) scale(0); }\n\n.rtf--ab {\n  height: 48px;\n  width: 48px;\n  background-color: #aaa;\n  display: inline-flex;\n  justify-content: center;\n  align-items: center;\n  position: relative;\n  border: none;\n  border-radius: 50%;\n  box-shadow: 0 0 4px rgba(0, 0, 0, 0.14), 0 4px 8px rgba(0, 0, 0, 0.28);\n  cursor: pointer;\n  outline: none;\n  padding: 0;\n  -webkit-user-drag: none;\n  font-weight: bold;\n  color: #f1f1f1;\n  margin-right: 4px;\n  font-size: 16px;\n  z-index: 10000; }\n", ""]);



/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/components/multiple-item/style.scss":
/*!**********************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/components/multiple-item/style.scss ***!
  \**********************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js")(false);
// Module
exports.push([module.i, "/*multiple box*/\n.redux-templates-multiple-template-box {\n  margin-bottom: 25px;\n  position: relative;\n  transition: all 0.05s ease-in-out; }\n  .redux-templates-multiple-template-box img {\n    transition: all 0.05s ease-in-out; }\n  .redux-templates-multiple-template-box .redux-templates-box-shadow {\n    transition: all 0.05s ease-in-out;\n    box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.1); }\n  .redux-templates-multiple-template-box .redux-templates-default-template-image .imageloader-loaded {\n    overflow: hidden; }\n  .redux-templates-multiple-template-box .multiple-template-view {\n    background: #fff;\n    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05), 0 10px 0 -5px #fff, 0 10px 1px -4px rgba(0, 0, 0, 0.08), 0 20px 0 -10px #fff, 0 20px 1px -9px rgba(0, 0, 0, 0.08);\n    cursor: pointer;\n    min-height: 100px; }\n  .redux-templates-multiple-template-box .redux-templates-import-button-group {\n    margin-top: 15%; }\n  .redux-templates-multiple-template-box .redux-templates-tmpl-info {\n    padding: 10px 12px;\n    position: absolute;\n    bottom: 0;\n    width: 100%;\n    background: rgba(255, 255, 255, 0.95);\n    border-top: 1px solid #f2f4f7;\n    transition: all 0.2s ease-in-out; }\n    .redux-templates-multiple-template-box .redux-templates-tmpl-info h5 {\n      margin: 0;\n      font-size: 14px;\n      color: #23282d;\n      line-height: 19px; }\n      .redux-templates-multiple-template-box .redux-templates-tmpl-info h5 span {\n        font-size: 13px;\n        color: #cdcfd1;\n        line-height: 18px; }\n  .redux-templates-multiple-template-box .redux-templates-button-overlay {\n    width: 100%;\n    height: 100%;\n    position: absolute;\n    top: 0;\n    left: 0;\n    border-radius: 0px;\n    opacity: 0;\n    -webkit-transition: opacity 0.2s ease-in-out;\n    transition: opacity 0.2s ease-in-out;\n    box-sizing: border-box; }\n  .redux-templates-multiple-template-box::before {\n    z-index: 2; }\n  .redux-templates-multiple-template-box::after {\n    z-index: 1; }\n  .redux-templates-multiple-template-box .redux-templates-button-overlay {\n    background: rgba(0, 0, 0, 0.5);\n    position: absolute;\n    height: 100%;\n    width: 100%;\n    opacity: 0; }\n  .redux-templates-multiple-template-box:hover .redux-templates-box-shadow {\n    box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.3); }\n  .redux-templates-multiple-template-box:hover .multiple-template-view {\n    border-color: transparent; }\n  .redux-templates-multiple-template-box:hover .redux-templates-tmpl-info {\n    border-top-color: transparent;\n    background: #fff; }\n  .redux-templates-multiple-template-box:hover .redux-templates-button-overlay {\n    opacity: 1; }\n  .redux-templates-multiple-template-box:hover img {\n    filter: blur(2px); }\n", ""]);



/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/components/pagination/style.scss":
/*!*******************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/components/pagination/style.scss ***!
  \*******************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js")(false);
// Module
exports.push([module.i, ".tablenav-pages {\n  display: flex;\n  justify-content: center;\n  align-items: center;\n  margin-bottom: 20px; }\n  .tablenav-pages span.displaying-num {\n    margin-right: 20px; }\n  .tablenav-pages #table-paging {\n    margin-left: 10px;\n    margin-right: 10px; }\n    .tablenav-pages #table-paging span {\n      line-height: 30px; }\n  .tablenav-pages span.tablenav-pages-navspan.button {\n    cursor: pointer;\n    margin: 0 2px; }\n    .tablenav-pages span.tablenav-pages-navspan.button.disabled {\n      cursor: default; }\n", ""]);



/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/components/preview-import-button/style.scss":
/*!******************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/components/preview-import-button/style.scss ***!
  \******************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js")(false);
// Module
exports.push([module.i, ".action-buttons span,\n.action-buttons a {\n  display: inline-block;\n  padding: 0 12px 2px;\n  margin: 2px;\n  height: 33px;\n  line-height: 32px;\n  font-size: 13px;\n  color: #353535;\n  border: 1px solid #f7f7f7;\n  background: #f7f7f7;\n  box-shadow: 0 1px 2px #ddd;\n  vertical-align: top;\n  border-radius: 3px;\n  text-decoration: none;\n  cursor: pointer;\n  -webkit-transition: all 0.2s ease-in-out;\n  transition: all 0.2s ease-in-out; }\n  .action-buttons span:hover,\n  .action-buttons a:hover {\n    box-shadow: 0 1px 2px #ccc;\n    background: #f1f1f1; }\n\n.action-buttons span i,\n.action-buttons a i {\n  font-size: 10px;\n  margin-right: 4px; }\n\n.action-buttons span {\n  background: #0085ba;\n  border-color: #006a95 #00648c #00648c;\n  box-shadow: inset 0 -1px 0 #00648c;\n  color: #fff;\n  text-decoration: none;\n  text-shadow: 0 -1px 1px #005d82, 1px 0 1px #005d82, 0 1px 1px #005d82, -1px 0 1px #005d82; }\n\n.action-buttons a.redux-templates-button-download {\n  border: 1px solid #f5a623;\n  background: #f5a623;\n  box-shadow: 0 1px 0 #165cb4;\n  color: #fff; }\n\n.action-buttons .redux-templates-button-download {\n  margin-left: 5px; }\n\n.action-buttons i.challenge-dot {\n  margin-top: 10px;\n  margin-left: 5px; }\n", ""]);



/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/components/single-item/style.scss":
/*!********************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/components/single-item/style.scss ***!
  \********************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js")(false);
// Module
exports.push([module.i, ".redux-templates-single-section-item {\n  box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.2);\n  margin-bottom: 30px;\n  transition: all 0.05s ease-in-out; }\n  .redux-templates-single-section-item .redux-templates-tmpl-title {\n    background: rgba(255, 255, 255, 0.95);\n    border-top: 1px solid #f2f4f7;\n    position: absolute;\n    bottom: 0;\n    width: 100%;\n    margin: 0;\n    color: #23282d;\n    padding: 13px 15px;\n    font-size: 15px; }\n  .redux-templates-single-section-item .redux-templates-single-item-inner {\n    position: relative;\n    overflow: hidden;\n    background: #999; }\n    .redux-templates-single-section-item .redux-templates-single-item-inner .warn_notice {\n      color: #fbbc0e;\n      font-weight: bold;\n      margin-bottom: 15px;\n      font-size: 14px;\n      opacity: 0;\n      text-align: center; }\n    .redux-templates-single-section-item .redux-templates-single-item-inner .redux-templates-default-template-image {\n      max-height: 350px;\n      min-height: 100px;\n      transition: 300ms; }\n\n#collections-sections-list.large > div {\n  width: 50%; }\n\n#collections-sections-list.small > div {\n  width: 25%; }\n", ""]);



/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/components/template-list-subheader/style.scss":
/*!********************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/components/template-list-subheader/style.scss ***!
  \********************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js")(false);
// Module
exports.push([module.i, ".redux-templates-template-filters .is-active {\n  background: #fff;\n  color: #191e23;\n  box-shadow: inset 0 0 0 1px #555d66, inset 0 0 0 2px #fff; }\n\n.redux-templates-template-filters .components-button:focus:not(:disabled):not(.is-active) {\n  background: transparent;\n  box-shadow: none;\n  color: #555d66; }\n\n.refresh-library {\n  margin-right: 10px; }\n\n.tour-icon {\n  font-size: 18px; }\n\n.trial_notice * {\n  vertical-align: middle; }\n\n.trial_notice .components-notice__content {\n  margin-right: 0; }\n", ""]);



/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/components/welcome-guide/style.scss":
/*!**********************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/components/welcome-guide/style.scss ***!
  \**********************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js")(false);
// Module
exports.push([module.i, ".redux-edit-post-welcome-guide {\n  width: 412px; }\n  .redux-edit-post-welcome-guide__image {\n    background: #00a0d2;\n    width: 412px;\n    border-bottom: 1px solid #ddd; }\n    .redux-edit-post-welcome-guide__image__prm-r {\n      display: none; }\n    @media (prefers-reduced-motion: reduce) {\n      .redux-edit-post-welcome-guide__image__prm-r {\n        display: block; }\n      .redux-edit-post-welcome-guide__image__prm-np {\n        display: none; } }\n  .redux-edit-post-welcome-guide__heading {\n    font-family: \"Noto Serif\", serif;\n    font-size: 24px;\n    line-height: 1.4;\n    margin: 0 0 16px 0;\n    padding: 0 32px; }\n  .redux-edit-post-welcome-guide__text {\n    font-size: 16px;\n    line-height: 1.4;\n    margin: 0 0 24px 0;\n    padding: 0 32px;\n    font-weight: normal; }\n  .redux-edit-post-welcome-guide__inserter-icon {\n    margin: 0 4px;\n    vertical-align: text-top; }\n", ""]);



/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/custom-css/style.scss":
/*!********************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/custom-css/style.scss ***!
  \********************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js")(false);
// Module
exports.push([module.i, ".redux-css-editor {\n  border: 1px solid #e2e4e7;\n  margin-bottom: 20px; }\n\n.redux-css-editor-help {\n  background: #f7f7f7;\n  padding: 20px; }\n", ""]);



/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/editor.scss":
/*!**********************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/editor.scss ***!
  \**********************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js")(false);
// Module
exports.push([module.i, "#redux-templatesImportCollectionBtn {\n  vertical-align: middle;\n  display: inline-flex;\n  align-items: center;\n  text-decoration: none;\n  border: 1px solid #bababa;\n  border-radius: 3px;\n  white-space: nowrap;\n  color: #555d66;\n  font-size: 13px;\n  margin: 0 15px 0 15px;\n  padding: 9px 12px;\n  background: transparent;\n  cursor: pointer;\n  -webkit-appearance: none;\n  transition: 400ms; }\n  #redux-templatesImportCollectionBtn svg {\n    height: 16px;\n    width: 16px;\n    margin-right: 8px; }\n    #redux-templatesImportCollectionBtn svg * {\n      stroke: #555d66;\n      fill: #555d66;\n      stroke-width: 0; }\n  #redux-templatesImportCollectionBtn:hover, #redux-templatesImportCollectionBtn:focus, #redux-templatesImportCollectionBtn:active {\n    text-decoration: none;\n    border: 1px solid #191e23;\n    color: #191e23; }\n    #redux-templatesImportCollectionBtn:hover svg *, #redux-templatesImportCollectionBtn:focus svg *, #redux-templatesImportCollectionBtn:active svg * {\n      stroke: #191e23 !important;\n      fill: #191e23 !important;\n      stroke-width: 0; }\n\n.redux-templates-editor-btn {\n  background: none;\n  border: 0;\n  color: inherit;\n  font: inherit;\n  line-height: normal;\n  overflow: visible;\n  padding: 0;\n  -webkit-appearance: button;\n  -webkit-user-select: none;\n  -moz-user-select: none;\n  -ms-user-select: none; }\n  .redux-templates-editor-btn::-moz-focus-inner {\n    border: 0;\n    padding: 0; }\n\n.d-flex {\n  display: flex; }\n\n.justify-content-center {\n  justify-content: center; }\n\n.redux-css-editor {\n  border: 1px solid #e2e4e7;\n  margin-bottom: 20px; }\n\n.redux-css-editor-help {\n  background: #f7f7f7;\n  padding: 20px; }\n", ""]);



/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/modal-import-wizard/style.scss":
/*!*****************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/modal-import-wizard/style.scss ***!
  \*****************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js")(false);
// Module
exports.push([module.i, ".redux-templates-modal-wrapper {\n  /* ReduxTemplatesPremiumBox */ }\n  .redux-templates-modal-wrapper .redux-templates-modal-body {\n    flex: 1 1 auto;\n    padding-left: 30px;\n    padding-right: 30px;\n    box-sizing: border-box;\n    background: #fff; }\n    .redux-templates-modal-wrapper .redux-templates-modal-body h5 {\n      font-size: 1.1em;\n      font-weight: 600; }\n    .redux-templates-modal-wrapper .redux-templates-modal-body ul {\n      list-style-position: inside;\n      list-style-type: disc; }\n      .redux-templates-modal-wrapper .redux-templates-modal-body ul.redux-templates-import-wizard-missing-dependency li {\n        line-height: 1.8; }\n    .redux-templates-modal-wrapper .redux-templates-modal-body .error {\n      color: #f00; }\n      .redux-templates-modal-wrapper .redux-templates-modal-body .error i {\n        color: inherit; }\n      .redux-templates-modal-wrapper .redux-templates-modal-body .error.installError {\n        text-align: center; }\n  .redux-templates-modal-wrapper .redux-templates-import-wizard-spinner-wrapper {\n    position: absolute;\n    width: calc(100% - 60px);\n    height: 100%;\n    flex: 1 1 auto;\n    align-items: center;\n    justify-content: center;\n    display: flex;\n    flex-direction: column; }\n    .redux-templates-modal-wrapper .redux-templates-import-wizard-spinner-wrapper .text-transition {\n      text-align: center;\n      font-size: 18px;\n      color: #555d66;\n      margin-bottom: 20px; }\n  .redux-templates-modal-wrapper .redux-templates-import-progress {\n    font-size: 1.1em;\n    text-align: center; }\n    .redux-templates-modal-wrapper .redux-templates-import-progress li {\n      list-style: none; }\n      .redux-templates-modal-wrapper .redux-templates-import-progress li.success i {\n        color: #46b450; }\n      .redux-templates-modal-wrapper .redux-templates-import-progress li.info i {\n        color: #00a0d2; }\n  .redux-templates-modal-wrapper .redux-templates-import-progress {\n    width: 50%;\n    margin: 10px auto; }\n    .redux-templates-modal-wrapper .redux-templates-import-progress li {\n      display: flex;\n      justify-content: space-between; }\n  .redux-templates-modal-wrapper .section-box.premium-box {\n    margin: 35px auto;\n    text-align: center; }\n    .redux-templates-modal-wrapper .section-box.premium-box input {\n      display: block;\n      width: 100%;\n      box-shadow: 0 0 0 transparent;\n      transition: box-shadow 0.1s linear;\n      border-radius: 2px;\n      line-height: normal;\n      display: block;\n      padding: 16px 48px 16px 16px;\n      background: #f3f4f5;\n      border: none;\n      width: 100%;\n      height: 40px;\n      font-size: 13px;\n      text-align: center; }\n      .redux-templates-modal-wrapper .section-box.premium-box input::-webkit-input-placeholder {\n        /* WebKit browsers */\n        color: #606a73;\n        font-style: italic;\n        opacity: 1; }\n      .redux-templates-modal-wrapper .section-box.premium-box input:-moz-placeholder {\n        /* Mozilla Firefox 4 to 18 */\n        color: #606a73;\n        font-style: italic;\n        opacity: 1; }\n      .redux-templates-modal-wrapper .section-box.premium-box input::-moz-placeholder {\n        /* Mozilla Firefox 19+ */\n        color: #606a73;\n        font-style: italic;\n        opacity: 1; }\n      .redux-templates-modal-wrapper .section-box.premium-box input:-ms-input-placeholder {\n        /* Internet Explorer 10+ */\n        color: #606a73;\n        font-style: italic;\n        opacity: 1; }\n      .redux-templates-modal-wrapper .section-box.premium-box input:focus {\n        border-color: #007cba;\n        border-color: var(--wp-admin-theme-color);\n        background: #fff;\n        box-shadow: 0 0 0 1.5px #007cba;\n        box-shadow: 0 0 0 1.5px var(--wp-admin-theme-color);\n        outline: 2px solid transparent; }\n    .redux-templates-modal-wrapper .section-box.premium-box h3 {\n      font-size: 1.5em;\n      line-height: 1.1em;\n      margin-top: 0px; }\n    .redux-templates-modal-wrapper .section-box.premium-box p {\n      font-size: calc(13px + 0.2vw); }\n    .redux-templates-modal-wrapper .section-box.premium-box ul {\n      width: 50%;\n      margin: 0 auto;\n      text-align: left;\n      list-style-type: disc;\n      list-style-position: inside; }\n    .redux-templates-modal-wrapper .section-box.premium-box .redux-templates-upgrade-button {\n      border: none;\n      border-radius: 4px;\n      cursor: pointer;\n      opacity: 1;\n      background: #24b0a6;\n      transition: opacity 0.2s ease-in-out;\n      box-shadow: none !important;\n      color: #fff;\n      text-decoration: none;\n      padding: 0.75em 1.25em;\n      display: block;\n      margin: 30px auto 0 auto;\n      max-width: 250px;\n      text-align: center;\n      font-size: 1em; }\n      .redux-templates-modal-wrapper .section-box.premium-box .redux-templates-upgrade-button:hover {\n        color: #fff;\n        opacity: 0.85;\n        box-shadow: none !important;\n        background: #19837c; }\n    .redux-templates-modal-wrapper .section-box.premium-box .subscription_key_button {\n      margin-top: 40px; }\n      .redux-templates-modal-wrapper .section-box.premium-box .subscription_key_button button {\n        background: #f8f8f8;\n        border: 1px solid #ddd; }\n    .redux-templates-modal-wrapper .section-box.premium-box .redux-pro-activate-button {\n      border: none;\n      border-radius: 4px;\n      cursor: pointer;\n      opacity: 1;\n      background: #24b0a6;\n      transition: opacity 0.2s ease-in-out;\n      box-shadow: none !important;\n      color: #fff;\n      text-decoration: none;\n      padding: 0.75em 1.25em;\n      display: block;\n      margin: 30px auto 0 auto;\n      max-width: 250px;\n      text-align: center;\n      font-size: 1em; }\n      .redux-templates-modal-wrapper .section-box.premium-box .redux-pro-activate-button i {\n        margin-right: 10px; }\n      .redux-templates-modal-wrapper .section-box.premium-box .redux-pro-activate-button:hover {\n        color: #fff;\n        opacity: 0.85;\n        box-shadow: none !important;\n        background: #19837c; }\n  .redux-templates-modal-wrapper .redux-templates-importmodal-content {\n    flex: 1;\n    display: flex;\n    flex-direction: column; }\n  .redux-templates-modal-wrapper .redux-templates-psmodal-content {\n    flex: 1;\n    display: flex;\n    flex-direction: column;\n    align-items: center;\n    justify-content: center;\n    text-align: center; }\n  .redux-templates-modal-wrapper .redux-templates-modal-footer.nps-footer {\n    text-align: center;\n    justify-content: center; }\n\n.text-transition {\n  width: 100% !important;\n  text-align: center; }\n  .text-transition .text-transition_inner > div {\n    font-size: 1.1rem; }\n", ""]);



/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/modal-library/sidebar/style.scss":
/*!*******************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/modal-library/sidebar/style.scss ***!
  \*******************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js")(false);
// Module
exports.push([module.i, ".redux-templates-collection-modal-sidebar {\n  min-width: 270px;\n  background: #fff;\n  color: #32373c;\n  /* $secondaryColor;*/\n  border-right: 1px solid #e2e4e7;\n  overflow-y: auto; }\n  .redux-templates-collection-modal-sidebar .redux-templates-template-filter-button-group {\n    margin: 10px 0;\n    border-bottom: 1px solid #e2e4e7;\n    width: 100%;\n    display: inline-flex; }\n    .redux-templates-collection-modal-sidebar .redux-templates-template-filter-button-group button {\n      font-weight: 500;\n      flex-grow: 1;\n      min-width: 30%;\n      line-height: 20px;\n      padding: 8px 0 10px 15px;\n      align-items: center;\n      text-align: left;\n      background: none;\n      position: relative;\n      margin-bottom: -1px;\n      border-width: 0;\n      z-index: 1;\n      cursor: pointer;\n      outline: none;\n      border-color: transparent;\n      box-shadow: none;\n      border-bottom: unset; }\n      .redux-templates-collection-modal-sidebar .redux-templates-template-filter-button-group button::after {\n        content: attr(data-label);\n        display: block;\n        height: 0;\n        overflow: hidden;\n        speak: none;\n        visibility: hidden; }\n      .redux-templates-collection-modal-sidebar .redux-templates-template-filter-button-group button:hover {\n        color: #007cba;\n        color: var(--wp-admin-theme-color); }\n      .redux-templates-collection-modal-sidebar .redux-templates-template-filter-button-group button:focus {\n        box-shadow: inset 0 0 0 1.5px #007cba;\n        box-shadow: inset 0 0 0 1.5px var(--wp-admin-theme-color); }\n      .redux-templates-collection-modal-sidebar .redux-templates-template-filter-button-group button.active {\n        box-shadow: inset 0 0 0 1.5px transparent, inset 0 -4px 0 0 #007cba;\n        box-shadow: inset 0 0 0 1.5px transparent, inset 0 -4px 0 0 var(--wp-admin-theme-color); }\n        .redux-templates-collection-modal-sidebar .redux-templates-template-filter-button-group button.active::before {\n          content: \"\";\n          position: absolute;\n          top: 0;\n          bottom: 1px;\n          right: 0;\n          left: 0;\n          border-bottom: 4px solid transparent; }\n        .redux-templates-collection-modal-sidebar .redux-templates-template-filter-button-group button.active:focus {\n          box-shadow: inset 0 0 0 1.5px #007cba, inset 0 -4px 0 0 #007cba;\n          box-shadow: inset 0 0 0 1.5px var(--wp-admin-theme-color), inset 0 -4px 0 0 var(--wp-admin-theme-color); }\n      .redux-templates-collection-modal-sidebar .redux-templates-template-filter-button-group button.disabled {\n        opacity: 0.4; }\n      .redux-templates-collection-modal-sidebar .redux-templates-template-filter-button-group button img {\n        display: inline-block;\n        width: auto;\n        height: 14px;\n        margin-right: 4px; }\n      .redux-templates-collection-modal-sidebar .redux-templates-template-filter-button-group button:last-child img {\n        margin-bottom: -2px; }\n  .redux-templates-collection-modal-sidebar .redux-templates-modal-sidebar-content {\n    padding: 0 15px 15px; }\n    .redux-templates-collection-modal-sidebar .redux-templates-modal-sidebar-content h3 {\n      margin: 5px 12px 10px 0;\n      color: #757575;\n      text-transform: uppercase;\n      font-size: 11px;\n      font-weight: 500; }\n    .redux-templates-collection-modal-sidebar .redux-templates-modal-sidebar-content ul {\n      list-style: unset;\n      -webkit-touch-callout: none;\n      /* iOS Safari */\n      -webkit-user-select: none;\n      /* Safari */\n      -khtml-user-select: none;\n      /* Konqueror HTML */\n      -moz-user-select: none;\n      /* Old versions of Firefox */\n      -ms-user-select: none;\n      /* Internet Explorer/Edge */\n      user-select: none;\n      /* Non-prefixed version, currently\n                                             supported by Chrome, Edge, Opera and Firefox */\n      margin: 0 15px 15px 15px;\n      padding: 0; }\n      .redux-templates-collection-modal-sidebar .redux-templates-modal-sidebar-content ul li {\n        display: block;\n        font-size: 13px;\n        cursor: pointer;\n        height: auto;\n        -webkit-transition: height 0.5s linear;\n        -moz-transition: height 0.5s linear;\n        -ms-transition: height 0.5s linear;\n        -o-transition: height 0.5s linear;\n        transition: height 0.5s linear; }\n        .redux-templates-collection-modal-sidebar .redux-templates-modal-sidebar-content ul li .redux-icon-wrapper {\n          margin-left: 10px; }\n        .redux-templates-collection-modal-sidebar .redux-templates-modal-sidebar-content ul li:not(.disabled):hover {\n          color: #007cba;\n          color: var(--wp-admin-theme-color); }\n        .redux-templates-collection-modal-sidebar .redux-templates-modal-sidebar-content ul li.active {\n          color: #007cba;\n          color: var(--wp-admin-theme-color);\n          text-shadow: 0 0 0.5px #007cba;\n          text-shadow: 0 0 0.5px var(--wp-admin-theme-color); }\n        .redux-templates-collection-modal-sidebar .redux-templates-modal-sidebar-content ul li.disabled {\n          display: none;\n          height: 0px;\n          -webkit-transition: height 0.5s linear;\n          -moz-transition: height 0.5s linear;\n          -ms-transition: height 0.5s linear;\n          -o-transition: height 0.5s linear;\n          transition: height 0.5s linear; }\n        .redux-templates-collection-modal-sidebar .redux-templates-modal-sidebar-content ul li span {\n          float: right; }\n        .redux-templates-collection-modal-sidebar .redux-templates-modal-sidebar-content ul li.missing-dependency i.fa-exclamation-triangle {\n          color: #b27823; }\n        .redux-templates-collection-modal-sidebar .redux-templates-modal-sidebar-content ul li.missing-dependency:hover i.fa-exclamation-triangle {\n          color: #f5a623; }\n        .redux-templates-collection-modal-sidebar .redux-templates-modal-sidebar-content ul li.missing-dependency.active i.fa-exclamation-triangle {\n          color: #f5a623; }\n  .redux-templates-collection-modal-sidebar ul.redux-templates-sidebar-dependencies li .components-base-control {\n    display: inline-block;\n    margin-bottom: 0 !important; }\n    .redux-templates-collection-modal-sidebar ul.redux-templates-sidebar-dependencies li .components-base-control .components-base-control__field {\n      margin-bottom: 3px; }\n    .redux-templates-collection-modal-sidebar ul.redux-templates-sidebar-dependencies li .components-base-control span {\n      float: none; }\n  .redux-templates-collection-modal-sidebar .redux-templates-select-actions {\n    margin: 0 0 10px 15px;\n    display: inline-flex; }\n    .redux-templates-collection-modal-sidebar .redux-templates-select-actions i.challenge-dot {\n      margin-left: 10px; }\n    .redux-templates-collection-modal-sidebar .redux-templates-select-actions.disabled a {\n      pointer-events: none;\n      cursor: default;\n      text-decoration: none;\n      opacity: 0.6; }\n  .redux-templates-collection-modal-sidebar .redux-templates-sidebar-dependencies li a {\n    display: inline-block;\n    margin-left: 10px; }\n  .redux-templates-collection-modal-sidebar #redux-templates-filter-dependencies h3 {\n    margin-top: 0;\n    padding-top: 3px; }\n", ""]);



/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/modal-library/style.scss":
/*!***********************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/modal-library/style.scss ***!
  \***********************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js")(false);
// Module
exports.push([module.i, ".redux-templates-collections-modal-body {\n  display: flex;\n  flex: 1;\n  overflow-y: auto; }\n\n.redux-templates-builder-modal {\n  position: fixed;\n  top: 0;\n  left: 0;\n  width: 100%;\n  height: 100%;\n  z-index: 9999;\n  display: flex;\n  align-items: center;\n  justify-content: center;\n  pointer-events: none; }\n  .redux-templates-builder-modal.hidden {\n    display: none; }\n  .redux-templates-builder-modal .wp-full-overlay-sidebar .wp-core-ui .button-group.button-hero .button,\n  .redux-templates-builder-modal .wp-full-overlay-sidebar .wp-core-ui .button.button-hero {\n    text-align: center !important; }\n  .redux-templates-builder-modal * {\n    box-sizing: border-box;\n    pointer-events: all; }\n  .redux-templates-builder-modal .redux-templates-pagelist-modal-overlay {\n    position: absolute;\n    width: 100%;\n    height: 100%;\n    background: #f00;\n    left: 0;\n    top: 0;\n    z-index: -1;\n    background: rgba(3, 8, 15, 0.75); }\n  .redux-templates-builder-modal .components-base-control__field {\n    display: flex; }\n  .redux-templates-builder-modal textarea {\n    width: 100%;\n    height: 80px; }\n  .redux-templates-builder-modal .redux-icon-wrapper {\n    display: inline-block; }\n  .redux-templates-builder-modal button.components-button {\n    z-index: unset; }\n\n.redux-templates-builder-modal-header {\n  display: flex;\n  border-bottom: 1px solid #e2e4e7;\n  background: #fff; }\n  .redux-templates-builder-modal-header .template-search-box {\n    position: relative;\n    width: 270px; }\n    .redux-templates-builder-modal-header .template-search-box > div {\n      padding: 10px; }\n    .redux-templates-builder-modal-header .template-search-box i {\n      font-size: 13px;\n      color: #757575;\n      position: absolute;\n      top: 50%;\n      right: 30px;\n      transform: translateY(-50%); }\n      .redux-templates-builder-modal-header .template-search-box i.challenge-dot {\n        right: 20px;\n        left: auto; }\n      .redux-templates-builder-modal-header .template-search-box i.clear-search {\n        right: 20px;\n        left: auto;\n        color: #fff;\n        font-size: 12px;\n        display: none;\n        cursor: pointer;\n        padding: 5px; }\n    .redux-templates-builder-modal-header .template-search-box input {\n      display: block;\n      width: 100%;\n      box-shadow: 0 0 0 transparent;\n      transition: box-shadow 0.1s linear;\n      border-radius: 2px;\n      line-height: normal;\n      display: block;\n      padding: 16px 48px 16px 16px;\n      background: #f3f4f5;\n      border: none;\n      width: 100%;\n      height: 40px;\n      font-size: 13px; }\n      .redux-templates-builder-modal-header .template-search-box input::-webkit-input-placeholder {\n        /* WebKit browsers */\n        color: #606a73;\n        font-style: italic;\n        opacity: 1; }\n      .redux-templates-builder-modal-header .template-search-box input:-moz-placeholder {\n        /* Mozilla Firefox 4 to 18 */\n        color: #606a73;\n        font-style: italic;\n        opacity: 1; }\n      .redux-templates-builder-modal-header .template-search-box input::-moz-placeholder {\n        /* Mozilla Firefox 19+ */\n        color: #606a73;\n        font-style: italic;\n        opacity: 1; }\n      .redux-templates-builder-modal-header .template-search-box input:-ms-input-placeholder {\n        /* Internet Explorer 10+ */\n        color: #606a73;\n        font-style: italic;\n        opacity: 1; }\n      .redux-templates-builder-modal-header .template-search-box input:focus {\n        border-color: #007cba;\n        border-color: var(--wp-admin-theme-color);\n        background: #fff;\n        box-shadow: 0 0 0 1.5px #007cba;\n        box-shadow: 0 0 0 1.5px var(--wp-admin-theme-color);\n        outline: 2px solid transparent; }\n    .redux-templates-builder-modal-header .template-search-box:hover .clear-search {\n      display: block; }\n\n.redux-templates-pagelist-modal-inner {\n  position: relative;\n  display: flex;\n  flex-direction: column;\n  overflow: hidden;\n  background: #f1f1f1;\n  width: 85.9375%;\n  height: 89.537037%;\n  max-width: 1650px;\n  max-height: 967px;\n  box-shadow: 0 0 45px 10px rgba(3, 8, 15, 0.2);\n  animation: components-modal__appear-animation 0.1s ease-out;\n  animation-fill-mode: forwards; }\n  .redux-templates-pagelist-modal-inner .redux-templates-collection-modal-content-area {\n    flex-grow: 1;\n    max-height: 100%;\n    overflow-y: auto;\n    position: relative; }\n\n.redux-templates-template-list-header {\n  text-align: center;\n  position: relative;\n  flex-grow: 1;\n  padding-right: 50px; }\n  .redux-templates-template-list-header button:not(.redux-templates-builder-close-modal) {\n    flex-grow: 1;\n    line-height: 28px;\n    margin-top: 6px;\n    padding: 14px 15px 13px 15px;\n    text-align: left;\n    font-weight: 600;\n    width: 150px;\n    align-items: center;\n    background: none;\n    position: relative;\n    margin-bottom: -1px;\n    border-width: 0;\n    z-index: 1;\n    cursor: pointer;\n    outline: none;\n    border-color: transparent;\n    box-shadow: none;\n    border-bottom: unset; }\n    .redux-templates-template-list-header button:not(.redux-templates-builder-close-modal)::after {\n      content: attr(data-label);\n      display: block;\n      height: 0;\n      overflow: hidden;\n      speak: none;\n      visibility: hidden; }\n    .redux-templates-template-list-header button:not(.redux-templates-builder-close-modal):hover {\n      color: #007cba;\n      color: var(--wp-admin-theme-color); }\n    .redux-templates-template-list-header button:not(.redux-templates-builder-close-modal):focus {\n      box-shadow: inset 0 0 0 1.5px #007cba;\n      box-shadow: inset 0 0 0 1.5px var(--wp-admin-theme-color); }\n    .redux-templates-template-list-header button:not(.redux-templates-builder-close-modal).active {\n      box-shadow: inset 0 0 0 1.5px transparent, inset 0 -4px 0 0 #007cba;\n      box-shadow: inset 0 0 0 1.5px transparent, inset 0 -4px 0 0 var(--wp-admin-theme-color); }\n      .redux-templates-template-list-header button:not(.redux-templates-builder-close-modal).active::before {\n        content: \"\";\n        position: absolute;\n        top: 0;\n        bottom: 1px;\n        right: 0;\n        left: 0;\n        border-bottom: 4px solid transparent; }\n      .redux-templates-template-list-header button:not(.redux-templates-builder-close-modal).active:focus {\n        box-shadow: inset 0 0 0 1.5px #007cba, inset 0 -4px 0 0 #007cba;\n        box-shadow: inset 0 0 0 1.5px var(--wp-admin-theme-color), inset 0 -4px 0 0 var(--wp-admin-theme-color); }\n    .redux-templates-template-list-header button:not(.redux-templates-builder-close-modal).disabled {\n      opacity: 0.5; }\n  .redux-templates-template-list-header .redux-templates-builder-close-modal {\n    position: absolute;\n    top: 0;\n    right: 0;\n    width: 60px;\n    height: 60px;\n    margin: 0;\n    padding: 0;\n    border: 1px solid transparent;\n    background: none;\n    font-size: 15px;\n    cursor: pointer;\n    outline: none;\n    transition: color 0.1s ease-in-out, background 0.1s ease-in-out; }\n    .redux-templates-template-list-header .redux-templates-builder-close-modal i {\n      -webkit-text-stroke: 1.2px #fff; }\n    .redux-templates-template-list-header .redux-templates-builder-close-modal svg {\n      margin-top: 4px; }\n    .redux-templates-template-list-header .redux-templates-builder-close-modal:hover, .redux-templates-template-list-header .redux-templates-builder-close-modal:active {\n      color: #00a0d2; }\n    .redux-templates-template-list-header .redux-templates-builder-close-modal:focus {\n      color: #00a0d2;\n      border-color: #5b9dd9;\n      box-shadow: 0 0 3px rgba(0, 115, 170, 0.8);\n      /* Only visible in Windows High Contrast mode */\n      outline: 2px solid transparent; }\n\n.redux-templates-template-list-sub-header {\n  display: flex;\n  justify-content: space-between;\n  align-items: center;\n  padding: 23px 25px 0; }\n  .redux-templates-template-list-sub-header h4 {\n    font-size: 21px;\n    color: #0e2244;\n    font-weight: 500;\n    margin: 0; }\n    .redux-templates-template-list-sub-header h4 i.challenge-dot {\n      margin-left: 10px; }\n  .redux-templates-template-list-sub-header .redux-templates-template-filters {\n    display: flex;\n    justify-content: space-between;\n    align-items: center; }\n  .redux-templates-template-list-sub-header .redux-templates-template-filter-button-group {\n    background: #f8fafb;\n    display: inline-flex;\n    border: 1px solid #d8d8d8;\n    border-radius: 4px;\n    margin-left: 10px; }\n    .redux-templates-template-list-sub-header .redux-templates-template-filter-button-group button {\n      display: inline-flex;\n      line-height: 28px;\n      padding: 5px 18px;\n      align-items: center;\n      background: none;\n      border: none;\n      color: #587e97;\n      position: relative;\n      z-index: 1;\n      cursor: pointer; }\n      .redux-templates-template-list-sub-header .redux-templates-template-filter-button-group button:focus {\n        outline: none;\n        box-shadow: 0 0 0 1.5px #007cba;\n        box-shadow: 0 0 0 1.5px var(--wp-admin-theme-color);\n        outline: 1px solid transparent; }\n      .redux-templates-template-list-sub-header .redux-templates-template-filter-button-group button:last-child {\n        color: #f5a623; }\n      .redux-templates-template-list-sub-header .redux-templates-template-filter-button-group button.active {\n        background: #f5a623;\n        color: #fff; }\n      .redux-templates-template-list-sub-header .redux-templates-template-filter-button-group button.disabled {\n        opacity: 0.5; }\n      .redux-templates-template-list-sub-header .redux-templates-template-filter-button-group button img {\n        display: inline-block;\n        width: auto;\n        height: 14px;\n        margin-right: 4px; }\n      .redux-templates-template-list-sub-header .redux-templates-template-filter-button-group button:not(:last-child)::after {\n        content: \"\";\n        height: 13px;\n        background-color: #c4cbcf;\n        width: 1px;\n        right: 0px;\n        top: 50%;\n        position: absolute;\n        transform: translateY(-50%); }\n      .redux-templates-template-list-sub-header .redux-templates-template-filter-button-group button.active::after {\n        display: none; }\n      .redux-templates-template-list-sub-header .redux-templates-template-filter-button-group button::before {\n        content: \"\";\n        position: absolute;\n        background: #f5a623;\n        height: calc(100% + 4px);\n        width: calc(100% + 4px);\n        left: -2px;\n        top: -2px;\n        z-index: -1;\n        border-radius: 4px;\n        box-shadow: 0 0 4px rgba(33, 32, 249, 0.3);\n        opacity: 0; }\n      .redux-templates-template-list-sub-header .redux-templates-template-filter-button-group button.active::before {\n        opacity: 1; }\n\n.redux-templates-modal-loader {\n  display: inline-block;\n  position: absolute;\n  width: 80px;\n  height: 80px;\n  line-height: 80px;\n  text-align: center;\n  left: 50%;\n  margin-left: -50px;\n  top: 50%;\n  margin-top: -50px;\n  font-size: 24px;\n  color: #1e7ed8; }\n\n.redux-templates-modal-loader img {\n  height: 80px;\n  width: 80px; }\n\n/*block style*/\n.redux-templates-builder-template-found-empty {\n  text-align: center;\n  border-color: transparent !important; }\n  .redux-templates-builder-template-found-empty .redux-templates-builder-empty-title {\n    display: block;\n    width: 100%; }\n\n.redux-templates-pro-badge {\n  position: absolute;\n  background: rgba(255, 0, 0, 0.75);\n  cursor: pointer;\n  padding: 5px 6px;\n  border-radius: 3px;\n  color: #fff;\n  font-size: 10px;\n  right: 20px;\n  top: 20px;\n  line-height: 1;\n  transition: background 0.1s linear; }\n\n.redux-templates-missing-badge {\n  position: absolute;\n  cursor: pointer;\n  background: rgba(242, 168, 72, 0.75);\n  border-radius: 3px;\n  color: #fff;\n  font-size: 10px;\n  right: 20px;\n  top: 20px;\n  line-height: 1;\n  transition: background 0.1s linear; }\n  .redux-templates-missing-badge i {\n    margin: 5px; }\n\n.redux-templates-button-overlay .redux-templates-pro-badge {\n  background: rgba(255, 0, 0, 0.85); }\n\n.redux-templates-button-overlay .redux-templates-missing-badge {\n  background: rgba(242, 168, 72, 0.85); }\n\n.redux-templates-default-template-image {\n  /* background-image: url('/img/redux-templates-medium.jpg');\n    background-size: cover; */\n  background: #888;\n  transition: 300ms; }\n  .redux-templates-default-template-image .imageloader-loaded {\n    max-height: 285px; }\n  .redux-templates-default-template-image img {\n    width: 100%;\n    display: block; }\n\n.redux-templates-item-wrapper {\n  position: relative; }\n  .redux-templates-item-wrapper .redux-templates-button-overlay {\n    position: absolute;\n    width: 100%;\n    opacity: 0;\n    background: rgba(0, 0, 0, 0.5);\n    height: 100%;\n    top: 0;\n    left: 0;\n    display: flex;\n    flex-direction: column;\n    align-items: center;\n    justify-content: center;\n    transition: 300ms; }\n    .redux-templates-item-wrapper .redux-templates-button-overlay .redux-templates-tmpl-title {\n      margin: 0 0 15px;\n      color: #fff;\n      font-size: 19px;\n      font-weight: 400; }\n  .redux-templates-item-wrapper.focused .redux-templates-button-overlay, .redux-templates-item-wrapper:hover .redux-templates-button-overlay {\n    opacity: 1; }\n  .redux-templates-item-wrapper.focused .redux-templates-default-template-image, .redux-templates-item-wrapper:hover .redux-templates-default-template-image {\n    filter: blur(3px); }\n  .redux-templates-item-wrapper.missing_requirements .warn_notice {\n    opacity: 1; }\n  .redux-templates-item-wrapper.missing_requirements .redux-templates-button-download {\n    background-color: #fdbb05;\n    background-image: none;\n    border-color: #ffc107; }\n\n.redux-templates-template-option-header {\n  padding: 20px 20px 0; }\n  .redux-templates-template-option-header .redux-templates-template-back {\n    cursor: pointer; }\n    .redux-templates-template-option-header .redux-templates-template-back .dashicons {\n      vertical-align: text-bottom; }\n\n#wpwrap .edit-post-visual-editor .import-collection-btn-container {\n  text-align: center;\n  margin-top: 20px; }\n\n#wpwrap .edit-post-visual-editor .import-collection-btn-container #importCollectionBtn {\n  color: #fff;\n  font-size: 13px; }\n\n.redux-templates-template-back {\n  cursor: pointer; }\n  .redux-templates-template-back .dashicons {\n    vertical-align: text-bottom; }\n\n.spinner-wrapper {\n  position: absolute;\n  left: 0;\n  top: 0;\n  right: 0;\n  bottom: 0;\n  display: flex;\n  justify-content: center;\n  align-items: center;\n  background: rgba(0, 0, 0, 0.5); }\n", ""]);



/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/modal-library/view-collection/style.scss":
/*!***************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/modal-library/view-collection/style.scss ***!
  \***************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js")(false);
// Module
exports.push([module.i, ".redux-templates-collection-details-view {\n  padding: 40px 22.5px 60px;\n  width: 100%;\n  justify-content: center;\n  position: relative;\n  display: flex; }\n  .redux-templates-collection-details-view .redux-templates-collection-details-left {\n    width: 600px;\n    margin: 0 37px 0 17px; }\n    .redux-templates-collection-details-view .redux-templates-collection-details-left .details-back {\n      height: 32px;\n      line-height: 20px;\n      color: #818a91;\n      font-size: 15px;\n      font-weight: 600;\n      display: -webkit-inline-box;\n      display: -ms-inline-flexbox;\n      display: inline-flex;\n      -webkit-box-align: center;\n      -ms-flex-align: center;\n      align-items: center;\n      cursor: pointer;\n      margin-bottom: 20px;\n      -webkit-transition: color 0.1s ease;\n      transition: color 0.1s ease; }\n    .redux-templates-collection-details-view .redux-templates-collection-details-left .details-preview {\n      background-position: center top;\n      background-size: contain;\n      width: 100%;\n      background-repeat: no-repeat;\n      transition: background 1.5s ease;\n      height: 84.71%; }\n      .redux-templates-collection-details-view .redux-templates-collection-details-left .details-preview.has_full {\n        transition: background-position 1.5s linear;\n        background-position: center top;\n        background-size: cover;\n        width: 100%;\n        background-repeat: no-repeat;\n        -webkit-box-shadow: 0 1px 5px 0 rgba(0, 0, 0, 0.1);\n        box-shadow: 0 1px 5px 0 rgba(0, 0, 0, 0.1); }\n        .redux-templates-collection-details-view .redux-templates-collection-details-left .details-preview.has_full:hover {\n          background-position: center bottom;\n          background-size: cover; }\n  .redux-templates-collection-details-view .redux-templates-collection-details-right {\n    width: 520px; }\n    .redux-templates-collection-details-view .redux-templates-collection-details-right .details-title {\n      height: 30px;\n      display: -webkit-box;\n      display: -ms-flexbox;\n      display: flex;\n      -webkit-box-align: center;\n      -ms-flex-align: center;\n      align-items: center;\n      -webkit-box-pack: justify;\n      -ms-flex-pack: justify;\n      justify-content: space-between;\n      padding: 2px 25px 0 10px;\n      margin-bottom: 20px; }\n      .redux-templates-collection-details-view .redux-templates-collection-details-right .details-title h3 {\n        color: rgba(3, 8, 15, 0.92);\n        font-size: 28px;\n        font-weight: 600;\n        line-height: 34px;\n        margin: 0; }\n      .redux-templates-collection-details-view .redux-templates-collection-details-right .details-title span {\n        color: #818a91;\n        font-size: 13px;\n        font-weight: 600;\n        line-height: 16px; }\n    .redux-templates-collection-details-view .redux-templates-collection-details-right .details-list {\n      height: 84.71%; }\n      .redux-templates-collection-details-view .redux-templates-collection-details-right .details-list .details-inner {\n        height: 100%;\n        overflow-y: auto; }\n    .redux-templates-collection-details-view .redux-templates-collection-details-right .detail-select {\n      width: 150px;\n      height: 150px;\n      overflow: hidden;\n      margin: 0 8px 15px;\n      -webkit-box-shadow: 0 1px 5px 0 rgba(0, 0, 0, 0.1);\n      box-shadow: 0 1px 5px 0 rgba(0, 0, 0, 0.1);\n      position: relative;\n      display: inline-block;\n      cursor: pointer;\n      -webkit-transition: all 0.1s ease-in-out;\n      transition: all 0.1s ease-in-out; }\n      .redux-templates-collection-details-view .redux-templates-collection-details-right .detail-select::before, .redux-templates-collection-details-view .redux-templates-collection-details-right .detail-select::after {\n        content: \"\";\n        width: 100%;\n        height: 100%;\n        position: absolute;\n        top: 0;\n        left: 0;\n        pointer-events: none;\n        opacity: 0;\n        box-sizing: border-box; }\n      .redux-templates-collection-details-view .redux-templates-collection-details-right .detail-select::before {\n        opacity: 0.7;\n        z-index: 2; }\n      .redux-templates-collection-details-view .redux-templates-collection-details-right .detail-select::after {\n        opacity: 0.7;\n        z-index: 1; }\n      .redux-templates-collection-details-view .redux-templates-collection-details-right .detail-select .detail-image {\n        width: 100%;\n        height: 150px;\n        background-repeat: no-repeat;\n        background-size: cover;\n        border: 1px solid #ececec;\n        position: relative; }\n        .redux-templates-collection-details-view .redux-templates-collection-details-right .detail-select .detail-image .pro,\n        .redux-templates-collection-details-view .redux-templates-collection-details-right .detail-select .detail-image .install {\n          position: absolute;\n          background: #f00;\n          padding: 2px 3px;\n          border-radius: 3px;\n          color: #fff;\n          font-size: 9px;\n          right: 5px;\n          top: 5px;\n          text-transform: uppercase;\n          line-height: 1; }\n        .redux-templates-collection-details-view .redux-templates-collection-details-right .detail-select .detail-image .pro {\n          background: #f00; }\n        .redux-templates-collection-details-view .redux-templates-collection-details-right .detail-select .detail-image .install {\n          background: #f2a848; }\n      .redux-templates-collection-details-view .redux-templates-collection-details-right .detail-select .detail-label {\n        border-top: 1px solid #f2f4f7;\n        width: 100%;\n        height: 30px;\n        opacity: 1;\n        background-color: rgba(255, 255, 255, 0.9);\n        position: absolute;\n        bottom: 0;\n        left: 0;\n        color: #23282d;\n        font-family: pn, \"Open Sans\", Arial, sans-serif;\n        font-size: 13px;\n        font-weight: 600;\n        line-height: 30px;\n        padding-left: 10px; }\n      .redux-templates-collection-details-view .redux-templates-collection-details-right .detail-select:hover {\n        -webkit-box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);\n        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2); }\n        .redux-templates-collection-details-view .redux-templates-collection-details-right .detail-select:hover::before, .redux-templates-collection-details-view .redux-templates-collection-details-right .detail-select:hover::after {\n          opacity: 1; }\n      .redux-templates-collection-details-view .redux-templates-collection-details-right .detail-select.detail-select-active {\n        -webkit-box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3); }\n        .redux-templates-collection-details-view .redux-templates-collection-details-right .detail-select.detail-select-active::before, .redux-templates-collection-details-view .redux-templates-collection-details-right .detail-select.detail-select-active::after,\n        .redux-templates-collection-details-view .redux-templates-collection-details-right .detail-select.detail-select-active .detail-label {\n          opacity: 1; }\n        .redux-templates-collection-details-view .redux-templates-collection-details-right .detail-select.detail-select-active::before {\n          border: 3px solid #24b0a6; }\n  .redux-templates-collection-details-view .redux-templates-collection-details-footer {\n    width: 100%;\n    height: 60px;\n    background: #fff;\n    position: absolute;\n    bottom: 0;\n    left: 0;\n    z-index: 2; }\n    .redux-templates-collection-details-view .redux-templates-collection-details-footer .footer-grid {\n      width: 100%;\n      padding: 0 10px;\n      height: 100%;\n      margin: auto;\n      display: flex;\n      align-items: center;\n      justify-content: flex-end; }\n    .redux-templates-collection-details-view .redux-templates-collection-details-footer .import-button {\n      margin-left: 11.5px;\n      background-color: #3dbfe8;\n      color: #fff;\n      font-family: pn, \"Open Sans\", Arial, sans-serif;\n      font-size: 15px;\n      font-weight: 600;\n      line-height: 18px;\n      padding: 9px 32px;\n      border-radius: 3px;\n      cursor: pointer;\n      -webkit-transition: background-color 150ms linear;\n      transition: background-color 150ms linear; }\n      .redux-templates-collection-details-view .redux-templates-collection-details-footer .import-button:hover {\n        background: rgba(61, 191, 232, 0.8); }\n", ""]);



/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/modal-library/view-saved/style.scss":
/*!**********************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/modal-library/view-saved/style.scss ***!
  \**********************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js")(false);
// Module
exports.push([module.i, ".redux-templates-two-sections {\n  position: absolute;\n  width: 85.9375%;\n  height: 89.537037%;\n  max-width: 1650px;\n  max-height: 967px;\n  top: 0;\n  right: 0;\n  bottom: 0;\n  left: 0;\n  margin: auto;\n  border-radius: 5px;\n  overflow: hidden;\n  -webkit-box-shadow: 0 45px 10px rgba(3, 8, 15, 0.2);\n  box-shadow: 0 0 45px 10px rgba(3, 8, 15, 0.2); }\n\n.redux-templates-two-sections__grid {\n  width: 100%;\n  height: 100%;\n  padding: 40px 22.5px;\n  position: relative;\n  display: -webkit-box;\n  display: -ms-flexbox;\n  display: flex; }\n\n.redux-templates-two-sections__grid__column {\n  width: 100%; }\n\n.redux-templates-two-sections__grid-clear {\n  -webkit-box-orient: vertical;\n  -webkit-box-direction: normal;\n  -ms-flex-direction: column;\n  flex-direction: column;\n  -webkit-box-align: center;\n  -ms-flex-align: center;\n  align-items: center;\n  -webkit-box-pack: center;\n  -ms-flex-pack: center;\n  justify-content: center;\n  height: 100%; }\n\n.redux-templates-two-sections__grid-clear-text {\n  opacity: 0.9;\n  color: #818a91;\n  font-family: pn, \"Open Sans\", Arial, sans-serif;\n  font-size: 14px;\n  font-weight: 600;\n  line-height: 17px;\n  margin: 0 0 20px; }\n\n.redux-templates-two-sections__grid-clear-image-saved {\n  width: 322px;\n  height: 145px;\n  margin-top: -21px;\n  pointer-events: none; }\n\n.redux-templates-two-sections__grid-clear-image-global {\n  width: 524px;\n  height: 207px;\n  margin-top: -28px;\n  pointer-events: none; }\n\n.redux-templates-two-section {\n  position: relative;\n  margin: 0 17.5px 35px;\n  cursor: pointer;\n  outline: 3px solid transparent;\n  -webkit-transition: outline 0.3s ease-in-out;\n  transition: outline 0.3s ease-in-out;\n  -webkit-box-shadow: 0 1px 5px 0 rgba(0, 0, 0, 0.1);\n  box-shadow: 0 1px 5px 0 rgba(0, 0, 0, 0.1);\n  border-radius: 3px; }\n  .redux-templates-two-section:last-child {\n    margin-bottom: 0; }\n  .redux-templates-two-section .redux-templates-two-section-remove {\n    position: absolute;\n    z-index: 4;\n    top: -7px;\n    right: -7px;\n    opacity: 0;\n    -webkit-transform: scale(0.7);\n    transform: scale(0.7);\n    -webkit-transition: opacity 0.3s ease-in-out, -webkit-transform 0.3s ease-in-out;\n    transition: opacity 0.3s ease-in-out, -webkit-transform 0.3s ease-in-out;\n    transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out;\n    transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out, -webkit-transform 0.3s ease-in-out;\n    width: 28px;\n    height: 28px;\n    display: -webkit-box;\n    display: -ms-flexbox;\n    display: flex;\n    -webkit-box-align: center;\n    -ms-flex-align: center;\n    align-items: center;\n    -webkit-box-pack: center;\n    -ms-flex-pack: center;\n    justify-content: center;\n    font-size: 12px;\n    border-radius: 28px;\n    background-color: #fff;\n    color: #03080f;\n    -webkit-box-shadow: 0 0 2px 0 rgba(0, 0, 0, 0.25);\n    box-shadow: 0 0 2px 0 rgba(0, 0, 0, 0.25);\n    cursor: pointer; }\n  .redux-templates-two-section:hover .redux-templates-two-section-remove {\n    opacity: 1;\n    -webkit-transform: scale(1);\n    transform: scale(1);\n    -webkit-transition: opacity 0.3s ease-in-out, -webkit-transform 0.3s ease-in-out;\n    transition: opacity 0.3s ease-in-out, -webkit-transform 0.3s ease-in-out;\n    transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out;\n    transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out, -webkit-transform 0.3s ease-in-out; }\n    .redux-templates-two-section:hover .redux-templates-two-section-remove:hover {\n      color: #f00; }\n  .redux-templates-two-section:hover .redux-templates-two-section-item::before {\n    border-color: #3dbfe8; }\n  .redux-templates-two-section:hover .redux-templates-two-section-item::after {\n    border-color: #ececec; }\n\n.redux-templates-two-section .preview-image-wrapper {\n  transition: all 0.05s ease-in-out;\n  width: 100%;\n  min-height: 130px;\n  max-height: 300px;\n  overflow: hidden; }\n  .redux-templates-two-section .preview-image-wrapper img {\n    animation-name: fadeIn;\n    animation-fill-mode: both;\n    animation-delay: 200ms;\n    animation-duration: 200ms;\n    width: 100%;\n    height: auto; }\n\n.redux-templates-two-section .saved-section-title {\n  border-top: 1px solid #f2f4f7;\n  background: rgba(255, 255, 255, 0.9);\n  position: absolute;\n  bottom: 0;\n  width: 100%;\n  margin: 0;\n  color: #23282d;\n  padding: 13px 15px;\n  font-size: 15px;\n  text-align: center;\n  display: flex;\n  justify-content: center;\n  align-items: center; }\n\n.no-section {\n  display: flex;\n  width: 100%;\n  align-items: center;\n  justify-content: center;\n  font-size: 16px; }\n\n.preview-image-wrapper .block-editor-block-preview__container {\n  transition: all 0.05s ease-in-out;\n  background: #fff;\n  margin: 0 auto;\n  min-height: 130px; }\n", ""]);



/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/modal-library/view-template-list/style.scss":
/*!******************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/modal-library/view-template-list/style.scss ***!
  \******************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js")(false);
// Module
exports.push([module.i, "#collections-sections-list {\n  width: 100%;\n  display: flex;\n  flex-wrap: wrap;\n  padding: 10px 10px 0 10px; }\n  #collections-sections-list > div {\n    width: 33.3333%;\n    padding: 15px;\n    position: relative; }\n    @media (max-width: 1199px) {\n      #collections-sections-list > div {\n        width: 50%; } }\n    #collections-sections-list > div.redux-templates-builder-template-found-empty {\n      width: 100%;\n      text-align: center;\n      opacity: 0.5;\n      border: none !important;\n      padding-top: 70px !important; }\n  #collections-sections-list.redux-templates-frontend-section-list {\n    display: block;\n    padding-bottom: 10px;\n    padding-top: 0px; }\n    #collections-sections-list.redux-templates-frontend-section-list > div {\n      width: 100%;\n      display: flex;\n      flex-wrap: nowrap;\n      border-bottom: 1px solid #e2e4e7;\n      font-weight: 600;\n      padding: 12px 0 12px 15px;\n      margin-bottom: 0;\n      align-items: center; }\n      #collections-sections-list.redux-templates-frontend-section-list > div.redux-templates-reusable-list-title {\n        color: #adafb2; }\n      #collections-sections-list.redux-templates-frontend-section-list > div:first-child {\n        border-top: 1px solid #e2e4e7; }\n      #collections-sections-list.redux-templates-frontend-section-list > div .redux-templates-reusable-list-content {\n        flex-grow: 1; }\n      #collections-sections-list.redux-templates-frontend-section-list > div .redux-templates-reusable-list-info {\n        flex-grow: 1;\n        max-width: 165px;\n        display: flex;\n        flex-wrap: nowrap; }\n      #collections-sections-list.redux-templates-frontend-section-list > div .redux-templates-reusable-list-button {\n        margin-left: 30px; }\n      #collections-sections-list.redux-templates-frontend-section-list > div .redux-templates-reusable-list-button button {\n        display: inline-block;\n        padding: 0;\n        border: none;\n        transition: 300ms;\n        cursor: pointer;\n        background-color: transparent; }\n        #collections-sections-list.redux-templates-frontend-section-list > div .redux-templates-reusable-list-button button:not(:last-child) {\n          margin-right: 10px; }\n        #collections-sections-list.redux-templates-frontend-section-list > div .redux-templates-reusable-list-button button i {\n          font-size: 16px;\n          color: #cdcfd1; }\n        #collections-sections-list.redux-templates-frontend-section-list > div .redux-templates-reusable-list-button button:hover i {\n          color: #007cba; }\n        #collections-sections-list.redux-templates-frontend-section-list > div .redux-templates-reusable-list-button button:last-child:hover i {\n          color: #f00; }\n  #collections-sections-list .redux-templates-pagelist-column.loading {\n    height: 100px;\n    display: flex;\n    align-items: center;\n    justify-content: center;\n    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05); }\n", ""]);



/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/modal-preview/style.scss":
/*!***********************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/modal-preview/style.scss ***!
  \***********************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js")(false);
// Module
exports.push([module.i, ".wp-full-overlay {\n  margin-left: 300px; }\n\n@media screen and (min-width: 1667px) {\n  .wp-full-overlay {\n    margin-left: 18%; } }\n\nbutton {\n  box-sizing: border-box;\n  pointer-events: all; }\n\n.wp-full-overlay.hide {\n  display: none; }\n\n.theme-screenshot-wrap {\n  overflow: hidden;\n  position: relative;\n  max-height: 300px;\n  margin: 15px 0;\n  border: 1px solid #ccc; }\n\n.install-theme-info {\n  padding-bottom: 0px; }\n  .install-theme-info h3 a {\n    float: right;\n    opacity: 0.6; }\n    .install-theme-info h3 a:hover {\n      opacity: 1; }\n\n.install-theme-info .theme-screenshot {\n  width: 100% !important;\n  border: none !important;\n  margin: 0 !important;\n  display: block; }\n\n.expanded .wp-full-overlay-footer {\n  height: 111px !important;\n  left: initial; }\n  .expanded .wp-full-overlay-footer .button-hero {\n    text-align: center; }\n\n.wp-full-overlay .wp-full-overlay-sidebar-content {\n  bottom: 100px; }\n  .wp-full-overlay .wp-full-overlay-sidebar-content .redux-templates-dependencies-list {\n    border-top: 1px solid #eee;\n    color: #82878c;\n    font-size: 13px;\n    font-weight: 400;\n    margin: 30px 0 0 0; }\n    .wp-full-overlay .wp-full-overlay-sidebar-content .redux-templates-dependencies-list h4 {\n      color: #23282d;\n      font-size: 1.1em;\n      text-align: center; }\n    .wp-full-overlay .wp-full-overlay-sidebar-content .redux-templates-dependencies-list .redux-templates-dependency-blocks .redux-templates-dependency-name {\n      color: #444;\n      font-weight: 600;\n      margin-right: 5px; }\n\n.footer-import-button-wrap {\n  padding: 10px 20px;\n  display: flex;\n  justify-content: center; }\n\n.wp-full-overlay-footer .view-site,\n.wp-full-overlay-footer .go-pro,\n.wp-full-overlay-footer .redux-templates-import {\n  width: 100%; }\n\n.redux-templates-button-download {\n  border: 1px solid #3dbfe8;\n  background: #3dbfe8;\n  box-shadow: 0 1px 0 #165cb4;\n  color: #fff; }\n\n.wp-full-overlay-main {\n  left: 0;\n  right: 0;\n  top: 0;\n  bottom: 0;\n  height: 100%;\n  -webkit-transition: background-color 1000ms linear;\n  -ms-transition: background-color 1000ms linear;\n  transition: background-color 1000ms linear;\n  background-color: unset; }\n  .wp-full-overlay-main.loaded::before {\n    display: none !important; }\n  .wp-full-overlay-main.loaded iframe {\n    background-color: #fff; }\n  .wp-full-overlay-main .components-spinner {\n    position: absolute;\n    top: 50%;\n    left: 50%;\n    transform: translateX(-50%) translateY(-50%); }\n\n.theme-install-overlay iframe {\n  height: 100%;\n  width: 100%;\n  z-index: 20;\n  transition: opacity 0.3s; }\n\n.redux-templates-dependency-blocks {\n  display: flex; }\n  .redux-templates-dependency-blocks .block-head {\n    text-align: center;\n    width: 60px;\n    margin-right: 10px; }\n\n.requirements-list {\n  width: 100%; }\n  .requirements-list ul {\n    margin: 0;\n    padding: 0;\n    list-style: none; }\n    .requirements-list ul li {\n      cursor: pointer;\n      line-height: 20px;\n      padding-bottom: 25px;\n      clear: left;\n      transition: 300ms; }\n      .requirements-list ul li svg {\n        margin-right: 5px; }\n        .requirements-list ul li svg * {\n          fill: #9a9a9a; }\n      .requirements-list ul li svg,\n      .requirements-list ul li span,\n      .requirements-list ul li div {\n        float: left; }\n      .requirements-list ul li .redux-icon-wrapper {\n        display: inline; }\n      .requirements-list ul li span.pluginURL {\n        float: right; }\n      .requirements-list ul li i {\n        font-size: 1.1em; }\n      .requirements-list ul li .redux-icon-wrapper {\n        padding-left: 5px; }\n      .requirements-list ul li i.fa-exclamation-triangle {\n        font-size: 0.9em;\n        line-height: 1.5em;\n        color: #b27823; }\n      .requirements-list ul li:hover svg * {\n        fill: dimgray; }\n      .requirements-list ul li:hover i.fa-exclamation-triangle {\n        color: #f5a623; }\n\n.redux-block-pills ul {\n  margin: 0;\n  padding: 0; }\n  .redux-block-pills ul li {\n    margin: 0px 5px 10px 0;\n    float: left; }\n    .redux-block-pills ul li span {\n      --bg-opacity: 1 !important;\n      background-color: #edf2f7 !important;\n      background-color: rgba(237, 242, 247, var(--bg-opacity)) !important;\n      border-radius: 9999px !important;\n      padding-top: 0.25rem !important;\n      padding-bottom: 0.25rem !important;\n      padding-left: 0.75rem !important;\n      padding-right: 0.75rem !important;\n      --text-opacity: 1 !important;\n      color: #4a5568 !important;\n      color: rgba(74, 85, 104, var(--text-opacity)) !important; }\n\n.redux-templates-modal-preview-box {\n  background: #f1f1f1; }\n  .redux-templates-modal-preview-box img {\n    position: absolute;\n    top: 50%;\n    left: 50%;\n    transform: translateX(-50%) translateY(-50%);\n    max-width: 100%;\n    max-height: 100%; }\n\n.theme-hash {\n  text-align: center;\n  font-size: 14px;\n  position: relative; }\n  .theme-hash i {\n    cursor: pointer;\n    margin-right: 5px;\n    margin-left: 5px; }\n  .theme-hash .copied {\n    color: #656a6f;\n    position: absolute;\n    line-height: 75%;\n    margin-left: 10px;\n    opacity: 0.6; }\n  .theme-hash .the-copy {\n    border-bottom-right-radius: 0 !important;\n    border-top-right-radius: 0 !important; }\n  .theme-hash .the-hash {\n    border-bottom-left-radius: 0 !important;\n    border-top-left-radius: 0 !important;\n    border-left: 0 !important; }\n  .theme-hash .hideMe {\n    -webkit-animation: cssAnimation 3s forwards;\n    animation: cssAnimation 3s forwards; }\n\n@keyframes cssAnimation {\n  0% {\n    opacity: 1; }\n  90% {\n    opacity: 1; }\n  100% {\n    opacity: 0; } }\n\n@-webkit-keyframes cssAnimation {\n  0% {\n    opacity: 1; }\n  90% {\n    opacity: 1; }\n  100% {\n    opacity: 0; } }\n", ""]);



/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/modal-promotor-score/style.scss":
/*!******************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/modal-promotor-score/style.scss ***!
  \******************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js")(false);
// Module
exports.push([module.i, ".components-snackbar-list {\n  z-index: 99999; }\n", ""]);



/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/modals.scss":
/*!**********************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/modals.scss ***!
  \**********************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js")(false);
// Module
exports.push([module.i, ".redux-templates-modal-overlay {\n  position: fixed;\n  top: 0;\n  left: 0;\n  right: 0;\n  bottom: 0;\n  background: rgba(255, 255, 255, 0.6);\n  display: flex;\n  align-items: center;\n  justify-content: center;\n  z-index: 600000; }\n\n.redux-templates-modal-wrapper {\n  width: 550px;\n  height: 400px;\n  background: #fcfcfc;\n  position: relative;\n  display: flex;\n  align-items: center;\n  justify-content: center;\n  flex-direction: column;\n  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.7); }\n  .redux-templates-modal-wrapper div {\n    width: 100%; }\n  .redux-templates-modal-wrapper .redux-templates-modal-header {\n    border-bottom: 1px solid #ddd;\n    flex: 0 0 60px;\n    padding: 10px 15px;\n    display: flex;\n    align-items: center;\n    justify-content: space-between;\n    box-sizing: border-box; }\n    .redux-templates-modal-wrapper .redux-templates-modal-header h3 {\n      margin: 0;\n      font-size: 1.2rem; }\n    .redux-templates-modal-wrapper .redux-templates-modal-header button {\n      border: none;\n      cursor: pointer; }\n    .redux-templates-modal-wrapper .redux-templates-modal-header .redux-templates-modal-close {\n      font-size: 20px;\n      background: transparent;\n      color: #9b9b9b; }\n  .redux-templates-modal-wrapper .redux-templates-modal-body {\n    flex: 1 1 auto;\n    padding-left: 30px;\n    padding-right: 30px;\n    box-sizing: border-box;\n    background: #fff;\n    position: relative; }\n    .redux-templates-modal-wrapper .redux-templates-modal-body h5 {\n      font-size: 1.1em;\n      font-weight: 600; }\n    .redux-templates-modal-wrapper .redux-templates-modal-body ul {\n      list-style-position: inside;\n      list-style-type: disc; }\n    .redux-templates-modal-wrapper .redux-templates-modal-body .error {\n      color: #f00; }\n  .redux-templates-modal-wrapper .redux-templates-modal-footer {\n    border-top: 1px solid #ddd;\n    flex: 0 0 60px;\n    align-items: center;\n    display: flex;\n    padding: 0 20px;\n    box-sizing: border-box; }\n    .redux-templates-modal-wrapper .redux-templates-modal-footer .button {\n      margin-right: 20px;\n      cursor: pointer; }\n    .redux-templates-modal-wrapper .redux-templates-modal-footer i.fas {\n      margin-right: 3px; }\n  .redux-templates-modal-wrapper .redux-templates-modal-spinner-wrapper {\n    flex: 1 1 auto;\n    align-items: center;\n    justify-content: center;\n    display: flex; }\n", ""]);



/***/ }),

/***/ "./redux-templates/assets/img/icon-color.svg":
/*!***************************************************!*\
  !*** ./redux-templates/assets/img/icon-color.svg ***!
  \***************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
function _extends() { _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; return _extends.apply(this, arguments); }



var _ref = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  d: "M10.9 17.7H7.4l-.9-1.5 2.1-2.4 2.3 3.9zm-5.3-1.6l-1.5 1.6h-4L4 13.3l1.6 2.8z",
  fill: "#011627"
});

var _ref2 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  fill: "#00a7e5",
  d: "M6.1 15.6L.4 5.9h3.5l2.7 4.5 8-9.1h4.3L6.1 15.6z"
});

var SvgIconColor = function SvgIconColor(props) {
  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("svg", _extends({
    viewBox: "0 0 19 19",
    xmlns: "http://www.w3.org/2000/svg"
  }, props), _ref, _ref2);
};

/* harmony default export */ __webpack_exports__["default"] = (SvgIconColor);

/***/ }),

/***/ "./redux-templates/assets/img/icon.svg":
/*!*********************************************!*\
  !*** ./redux-templates/assets/img/icon.svg ***!
  \*********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
function _extends() { _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; return _extends.apply(this, arguments); }



var _ref = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  d: "M10.9 17.7H7.4l-.9-1.5 2.1-2.4 2.3 3.9zm-5.3-1.6l-1.5 1.6h-4L4 13.3l1.6 2.8zM6.1 15.6L.4 5.9h3.5l2.7 4.5 8-9.1h4.3L6.1 15.6z"
});

var SvgIcon = function SvgIcon(props) {
  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("svg", _extends({
    viewBox: "0 0 19 19",
    xmlns: "http://www.w3.org/2000/svg"
  }, props), _ref);
};

/* harmony default export */ __webpack_exports__["default"] = (SvgIcon);

/***/ }),

/***/ "./redux-templates/src/blocks/blocks.js":
/*!**********************************************!*\
  !*** ./redux-templates/src/blocks/blocks.js ***!
  \**********************************************/
/*! exports provided: registerBlocks */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "registerBlocks", function() { return registerBlocks; });
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _import__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./import */ "./redux-templates/src/blocks/import/index.js");
/* harmony import */ var _library__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./library */ "./redux-templates/src/blocks/library/index.js");
function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }


const registerBlockType = wp.blocks.registerBlockType;


function registerBlocks() {
  registerBlockType(`redux/${_library__WEBPACK_IMPORTED_MODULE_2__["name"]}`, _objectSpread({}, _library__WEBPACK_IMPORTED_MODULE_2__["settings"]));
  registerBlockType(`redux/${_import__WEBPACK_IMPORTED_MODULE_1__["name"]}`, _objectSpread({}, _import__WEBPACK_IMPORTED_MODULE_1__["settings"]));
}
registerBlocks();

/***/ }),

/***/ "./redux-templates/src/blocks/import/components/edit.js":
/*!**************************************************************!*\
  !*** ./redux-templates/src/blocks/import/components/edit.js ***!
  \**************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _utils_import__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../utils/import */ "./redux-templates/src/blocks/import/utils/import.js");
/* harmony import */ var _utils_insert__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../utils/insert */ "./redux-templates/src/blocks/import/utils/insert.js");
/**
 * Internal dependencies
 */


/**
 * WordPress dependencies
 */

const __ = wp.i18n.__;
const withInstanceId = wp.compose.withInstanceId;
const _wp$element = wp.element,
      Fragment = _wp$element.Fragment,
      Component = _wp$element.Component;
const MediaUploadCheck = wp.blockEditor.MediaUploadCheck;
const _wp$components = wp.components,
      DropZone = _wp$components.DropZone,
      FormFileUpload = _wp$components.FormFileUpload,
      Placeholder = _wp$components.Placeholder,
      Notice = _wp$components.Notice;
const ALLOWED_BG_MEDIA_TYPES = ['json'];
/**
 * Block edit function
 */

class Edit extends Component {
  constructor() {
    super(...arguments);
    this.state = {
      isLoading: false,
      error: null
    };
    this.isStillMounted = true;
    this.addFile = this.addFile.bind(this);
  }

  componentDidMount() {
    const file = this.props.attributes.file;

    if (file) {
      this.setState({
        isLoading: true
      });
      this.addFile(file);
    }
  }

  componentWillUnmount() {
    this.isStillMounted = false;
  }

  addFile(files) {
    let file = files[0];

    if (files.target) {
      file = event.target.files[0];
    }

    if (!file) {
      return;
    }

    this.setState({
      isLoading: true
    });
    Object(_utils_import__WEBPACK_IMPORTED_MODULE_0__["default"])(file).then(reusableBlock => {
      if (!this.isStillMounted) {
        return;
      }

      this.setState({
        isLoading: false
      });
      Object(_utils_insert__WEBPACK_IMPORTED_MODULE_1__["default"])(this.props.clientId, reusableBlock, this.props.onClose);
    }).catch(error => {
      if (!this.isStillMounted) {
        return;
      }

      let uiMessage;

      switch (error.message) {
        case 'Invalid JSON file':
          uiMessage = __('Invalid JSON file', redux_templates.i18n);
          break;

        case 'Invalid Reusable Block JSON file':
          uiMessage = __('Invalid Reusable Block JSON file', redux_templates.i18n);
          break;

        default:
          uiMessage = __('Unknown error', redux_templates.i18n);
      }

      this.setState({
        isLoading: false,
        error: uiMessage
      });
    });
  }

  render() {
    const _this$state = this.state,
          isLoading = _this$state.isLoading,
          error = _this$state.error;
    return wp.element.createElement(Placeholder, {
      icon: "download",
      label: __('Import a Template from JSON - Redux', redux_templates.i18n),
      instructions: __('Drag a file or upload a new one from your device.', redux_templates.i18n),
      className: "editor-media-placeholder",
      notices: error && wp.element.createElement(Notice, {
        status: "error"
      }, error)
    }, wp.element.createElement(Fragment, null, wp.element.createElement(MediaUploadCheck, null, wp.element.createElement(DropZone, {
      onFilesDrop: this.addFile,
      label: __('Import from JSON', redux_templates.i18n)
    }), wp.element.createElement(FormFileUpload, {
      isLarge: true,
      className: "editor-media-placeholder__button button button-primary",
      onChange: this.addFile,
      accept: ALLOWED_BG_MEDIA_TYPES,
      isBusy: isLoading,
      disabled: isLoading,
      multiple: false
    }, __('Upload', redux_templates.i18n)))));
  }

}

/* harmony default export */ __webpack_exports__["default"] = (withInstanceId(Edit));

/***/ }),

/***/ "./redux-templates/src/blocks/import/icon.js":
/*!***************************************************!*\
  !*** ./redux-templates/src/blocks/import/icon.js ***!
  \***************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/**
 * WordPress dependencies
 */
const SVG = wp.components.SVG;
/* harmony default export */ __webpack_exports__["default"] = (wp.element.createElement(SVG, {
  viewBox: "0 0 24 24",
  xmlns: "http://www.w3.org/2000/svg"
}, wp.element.createElement("path", {
  fill: "none",
  d: "M0 0h24v24H0V0z"
}), wp.element.createElement("path", {
  d: "M9.17 6l2 2H20v10H4V6h5.17M10 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2h-8l-2-2z"
})));

/***/ }),

/***/ "./redux-templates/src/blocks/import/index.js":
/*!****************************************************!*\
  !*** ./redux-templates/src/blocks/import/index.js ***!
  \****************************************************/
/*! exports provided: name, title, category, icon, settings */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "name", function() { return name; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "title", function() { return title; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "category", function() { return category; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "settings", function() { return settings; });
/* harmony import */ var _components_edit__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./components/edit */ "./redux-templates/src/blocks/import/components/edit.js");
/* harmony import */ var _icon__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./icon */ "./redux-templates/src/blocks/import/icon.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "icon", function() { return _icon__WEBPACK_IMPORTED_MODULE_1__["default"]; });

/* harmony import */ var _transforms__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./transforms */ "./redux-templates/src/blocks/import/transforms.js");
/* harmony import */ var _icons__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../icons */ "./redux-templates/src/icons/index.js");
/**
 * Internal dependencies
 */




/**
 * WordPress dependencies
 */

const __ = wp.i18n.__;
/**
 * Block constants
 */

const name = 'import';
const category = 'common';
const schema = {
  file: {
    type: 'object'
  }
};

const title = __('Template Import', redux_templates.i18n);

const keywords = [__('import', redux_templates.i18n), __('download', redux_templates.i18n), __('migrate', redux_templates.i18n)];
const settings = {
  title: title,
  description: __('Import blocks exported using Redux plugin.', redux_templates.i18n),
  category: category,
  keywords: keywords,
  attributes: schema,
  supports: {
    align: true,
    alignWide: false,
    alignFull: false,
    customClassName: false,
    className: false,
    html: false
  },
  transforms: _transforms__WEBPACK_IMPORTED_MODULE_2__["default"],
  edit: _components_edit__WEBPACK_IMPORTED_MODULE_0__["default"],

  save() {
    return null;
  }

};


/***/ }),

/***/ "./redux-templates/src/blocks/import/transforms.js":
/*!*********************************************************!*\
  !*** ./redux-templates/src/blocks/import/transforms.js ***!
  \*********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/**
 * WordPress dependencies
 */
const createBlock = wp.blocks.createBlock;
const transforms = {
  from: [{
    type: 'files',

    isMatch(files) {
      return files[0].type === 'application/json';
    },

    // We define a lower priorty (higher number) than the default of 10. This
    // ensures that the Import block is only created as a fallback.
    priority: 13,
    transform: files => {
      const blocks = [];
      blocks.push(createBlock('redux/import', {
        file: files
      }));
      return blocks;
    }
  }]
};
/* harmony default export */ __webpack_exports__["default"] = (transforms);

/***/ }),

/***/ "./redux-templates/src/blocks/import/utils/file.js":
/*!*********************************************************!*\
  !*** ./redux-templates/src/blocks/import/utils/file.js ***!
  \*********************************************************/
/*! exports provided: readTextFile */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "readTextFile", function() { return readTextFile; });
/**
 * Reads the textual content of the given file.
 *
 * @param  {File} file        File.
 * @return {Promise<string>}  Content of the file.
 */
function readTextFile(file) {
  const reader = new window.FileReader();
  return new Promise(resolve => {
    reader.onload = function () {
      resolve(reader.result);
    };

    reader.readAsText(file);
  });
}

/***/ }),

/***/ "./redux-templates/src/blocks/import/utils/import.js":
/*!***********************************************************!*\
  !*** ./redux-templates/src/blocks/import/utils/import.js ***!
  \***********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _file__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./file */ "./redux-templates/src/blocks/import/utils/file.js");
/**
 * External dependencies
 */

/**
 * Internal dependencies
 */


const _wp$data = wp.data,
      dispatch = _wp$data.dispatch,
      select = _wp$data.select;

const _dispatch = dispatch('core/editor'),
      editPost = _dispatch.editPost;
/**
 * Import a reusable block from a JSON file.
 *
 * @param {File}     file File.
 * @return {Promise} Promise returning the imported reusable block.
 */


async function importReusableBlock(file) {
  const fileContent = await Object(_file__WEBPACK_IMPORTED_MODULE_1__["readTextFile"])(file);
  let parsedContent;

  try {
    parsedContent = JSON.parse(JSON.parse(JSON.stringify(fileContent)));
  } catch (e) {
    throw new Error('Invalid JSON file');
  }

  if (parsedContent.__file === 'redux_template') {
    editPost({
      'template': 'redux-templates_full_width'
    });
    return parsedContent.content;
  }

  if (parsedContent.__file !== 'wp_block' || !parsedContent.title || !parsedContent.content || !Object(lodash__WEBPACK_IMPORTED_MODULE_0__["isString"])(parsedContent.title) || !Object(lodash__WEBPACK_IMPORTED_MODULE_0__["isString"])(parsedContent.content)) {
    if ('' === select('core/editor').getEditedPostAttribute('template')) {
      editPost({
        'template': 'redux-templates_contained'
      });
    }

    return importCoreBlocks(parsedContent);
  }

  const postType = await wp.apiFetch({
    path: '/wp/v2/types/wp_block'
  });
  const reusableBlock = await wp.apiFetch({
    path: `/wp/v2/${postType.rest_base}`,
    data: {
      title: parsedContent.title,
      content: parsedContent.content,
      status: 'publish'
    },
    method: 'POST'
  });

  if (reusableBlock.id) {
    return '<!-- wp:block {"ref":' + reusableBlock.id + '} /-->';
  }

  throw new Error('Invalid Reusable Block JSON file contents');
}

function importCoreBlocks(parsedContent) {
  if (parsedContent.__file !== 'core_block' || !parsedContent.content || !Object(lodash__WEBPACK_IMPORTED_MODULE_0__["isString"])(parsedContent.content)) {
    throw new Error('Invalid JSON file');
  }

  return parsedContent.content;
}

/* harmony default export */ __webpack_exports__["default"] = (importReusableBlock);

/***/ }),

/***/ "./redux-templates/src/blocks/import/utils/insert.js":
/*!***********************************************************!*\
  !*** ./redux-templates/src/blocks/import/utils/insert.js ***!
  \***********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return insertImportedBlocks; });
/**
 * WordPress dependencies
 */
const _wp$data = wp.data,
      select = _wp$data.select,
      dispatch = _wp$data.dispatch;
const _wp$blocks = wp.blocks,
      parse = _wp$blocks.parse,
      createBlock = _wp$blocks.createBlock;
function insertImportedBlocks(clientId, blocks, onClose) {
  blocks = parse(blocks);
  const toSelect = [];
  const blockIndex = select('core/block-editor').getBlockInsertionPoint();

  if (blocks.length > 0) {
    for (const block in blocks) {
      const created = createBlock(blocks[block].name, blocks[block].attributes, blocks[block].innerBlocks);
      dispatch('core/block-editor').insertBlocks(created, parseInt(blockIndex.index) + parseInt(block));

      if (typeof created !== 'undefined') {
        toSelect.push(created.clientId);
      }
    } //remove insertion point if empty


    dispatch('core/block-editor').removeBlock(clientId); //select inserted blocks

    if (toSelect.length > 0) {
      dispatch('core/block-editor').multiSelect(toSelect[0], toSelect.reverse()[0]);
    }
  }

  onClose();
}

/***/ }),

/***/ "./redux-templates/src/blocks/library/edit.js":
/*!****************************************************!*\
  !*** ./redux-templates/src/blocks/library/edit.js ***!
  \****************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _redux_templates_icons__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ~redux-templates/icons */ "./redux-templates/src/icons/index.js");
/* harmony import */ var _modal_manager__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../modal-manager */ "./redux-templates/src/modal-manager/index.js");
/* harmony import */ var _modal_library__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../modal-library */ "./redux-templates/src/modal-library/index.js");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/compose */ "@wordpress/compose");
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_compose__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/blocks */ "@wordpress/blocks");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_7__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_8__);
/* harmony import */ var _wordpress_hooks__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! @wordpress/hooks */ "@wordpress/hooks");
/* harmony import */ var _wordpress_hooks__WEBPACK_IMPORTED_MODULE_9___default = /*#__PURE__*/__webpack_require__.n(_wordpress_hooks__WEBPACK_IMPORTED_MODULE_9__);
/**
 * External dependencies.
 */
 // import { ModalDesignLibrary } from '~stackable/components'



/**
 * WordPress dependencies.
 */









const edit = ({
  removeLibraryBlock,
  preview
}) => {
  if (preview) {
    alert('here i am');
  }

  return wp.element.createElement("div", {
    className: "redux-template-library-block"
  }, wp.element.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__["Placeholder"], {
    icon: wp.element.createElement(_redux_templates_icons__WEBPACK_IMPORTED_MODULE_0__["ReduxTemplatesIcon"], null),
    label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_8__["__"])('Redux Template Library', redux_templates.i18n),
    instructions: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_8__["__"])('Open the Design Library and select a pre-designed block or layout.', redux_templates.i18n)
  }, wp.element.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__["Button"], {
    isSecondary: true,
    isLarge: true,
    hasIcon: true,
    className: "redux-template-library-block__button",
    onClick: () => {
      _modal_manager__WEBPACK_IMPORTED_MODULE_1__["ModalManager"].open(wp.element.createElement(_modal_library__WEBPACK_IMPORTED_MODULE_2__["default"], null));
      removeLibraryBlock();
    }
  }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_8__["__"])('Open Design Library', redux_templates.i18n))));
};

/* harmony default export */ __webpack_exports__["default"] = (Object(_wordpress_compose__WEBPACK_IMPORTED_MODULE_4__["compose"])([Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_6__["withDispatch"])((dispatch, {
  clientId
}) => {
  const _dispatch = dispatch('core/block-editor'),
        removeBlocks = _dispatch.removeBlocks;

  return {
    removeLibraryBlock: serializedBlock => {
      removeBlocks(clientId);
    }
  };
})])(edit));

/***/ }),

/***/ "./redux-templates/src/blocks/library/index.js":
/*!*****************************************************!*\
  !*** ./redux-templates/src/blocks/library/index.js ***!
  \*****************************************************/
/*! exports provided: name, title, category, icon, settings */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "name", function() { return name; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "title", function() { return title; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "category", function() { return category; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "icon", function() { return icon; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "settings", function() { return settings; });
/* harmony import */ var _redux_templates_icons__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ~redux-templates/icons */ "./redux-templates/src/icons/index.js");
/* harmony import */ var _edit__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./edit */ "./redux-templates/src/blocks/library/edit.js");
/* harmony import */ var _insert_library_button__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./insert-library-button */ "./redux-templates/src/blocks/library/insert-library-button.js");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_dom_ready__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/dom-ready */ "./node_modules/@wordpress/dom-ready/build-module/index.js");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__);
/**
 * BLOCK: Design Library
 */

/**
 * External dependencies
 */

/**
 * Internal dependencies
 */



const registerBlockType = wp.blocks.registerBlockType;
/**
 * WordPress dependencies
 */





const name = 'library';
const icon = _insert_library_button__WEBPACK_IMPORTED_MODULE_2__["default"];
const category = 'common';
const schema = {};

const title = Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__["__"])('Template Library', redux_templates.i18n);

const description = Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__["__"])('Choose a section, template, or template kit from the Redux Template Library.', redux_templates.i18n);

const keywords = [Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__["__"])('Template Library', redux_templates.i18n), Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__["__"])('Design Library', redux_templates.i18n), Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__["__"])('Element Layouts', redux_templates.i18n), Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__["__"])('Redux', redux_templates.i18n)];
const blockAttributes = {
  file: {
    type: 'object'
  }
};
const settings = {
  title: title,
  description: description,
  icon: _redux_templates_icons__WEBPACK_IMPORTED_MODULE_0__["ReduxTemplatesIconColor"],
  category: 'layout',
  keywords: keywords,
  attributes: schema,
  supports: {
    customClassName: false // inserter: ! disabledBlocks.includes( name ), // Hide if disabled.

  },
  example: {
    attributes: {// backgroundColor: '#000000',
      // opacity: 0.8,
      // padding: 30,
      // textColor: '#FFFFFF',
      // radius: 10,
      // title: __( 'I am a slide title', 'wp-presenter-pro' ),
    }
  },
  edit: _edit__WEBPACK_IMPORTED_MODULE_1__["default"],

  save() {
    return null;
  }

};

const renderButton = function (toolbar) {
  const buttonDiv = document.createElement('div');
  toolbar.appendChild(buttonDiv);
  Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__["render"])(wp.element.createElement(_insert_library_button__WEBPACK_IMPORTED_MODULE_2__["default"], null), buttonDiv);
};

Object(_wordpress_dom_ready__WEBPACK_IMPORTED_MODULE_4__["default"])(() => {
  let toolbar = document.querySelector('.edit-post-header__toolbar');

  if (!toolbar) {
    toolbar = document.querySelector('.edit-post-header__toolbar');
  }

  if (!toolbar) {
    setTimeout(function () {
      let toolbar = document.querySelector('.edit-post-header__toolbar');

      if (toolbar) {
        renderButton(toolbar);
      }
    }, 500);
    return;
  }

  renderButton(toolbar);
});


/***/ }),

/***/ "./redux-templates/src/blocks/library/insert-library-button.js":
/*!*********************************************************************!*\
  !*** ./redux-templates/src/blocks/library/insert-library-button.js ***!
  \*********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _redux_templates_icons__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ~redux-templates/icons */ "./redux-templates/src/icons/index.js");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _modal_manager__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../modal-manager */ "./redux-templates/src/modal-manager/index.js");
/* harmony import */ var _modal_library__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../../modal-library */ "./redux-templates/src/modal-library/index.js");
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./style.scss */ "./redux-templates/src/blocks/library/style.scss");
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_style_scss__WEBPACK_IMPORTED_MODULE_5__);
/**
 * External dependencies
 */

/**
 * WordPress dependencies
 */







const InsertLibraryButton = () => {
  return wp.element.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__["Tooltip"], {
    text: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])('Redux Templates Library', redux_templates.i18n),
    position: 'bottom'
  }, wp.element.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__["Button"], {
    "data-tut": "tour__library_button",
    onClick: () => {
      _modal_manager__WEBPACK_IMPORTED_MODULE_3__["ModalManager"].open(wp.element.createElement(_modal_library__WEBPACK_IMPORTED_MODULE_4__["default"], null));
    },
    className: "redux-templates-insert-library-button",
    label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])('Open Library', redux_templates.i18n),
    icon: wp.element.createElement(_redux_templates_icons__WEBPACK_IMPORTED_MODULE_0__["ReduxTemplatesIcon"], null)
  }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])('Templates', redux_templates.i18n)));
};

/* harmony default export */ __webpack_exports__["default"] = (InsertLibraryButton);

/***/ }),

/***/ "./redux-templates/src/blocks/library/style.scss":
/*!*******************************************************!*\
  !*** ./redux-templates/src/blocks/library/style.scss ***!
  \*******************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../node_modules/css-loader/dist/cjs.js!../../../../node_modules/sass-loader/dist/cjs.js!./style.scss */ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/blocks/library/style.scss");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./redux-templates/src/challenge/challenge-list-block/ChallengeStepItem.js":
/*!*********************************************************************************!*\
  !*** ./redux-templates/src/challenge/challenge-list-block/ChallengeStepItem.js ***!
  \*********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return ChallengeStepItem; });
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _config__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../config */ "./redux-templates/src/challenge/config.js");
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./style.scss */ "./redux-templates/src/challenge/challenge-list-block/style.scss");
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_style_scss__WEBPACK_IMPORTED_MODULE_2__);
function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }

function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }

function _iterableToArrayLimit(arr, i) { if (typeof Symbol === "undefined" || !(Symbol.iterator in Object(arr))) return; var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }

function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }

/**
 * WordPress dependencies
 */



const compose = wp.compose.compose;
const _wp$data = wp.data,
      withDispatch = _wp$data.withDispatch,
      withSelect = _wp$data.withSelect;
const _wp$element = wp.element,
      useState = _wp$element.useState,
      useEffect = _wp$element.useEffect; // currentStep : indicates where the step is
// step: 1~8 etc

function ChallengeStepItem(props) {
  const currentStep = props.currentStep,
        step = props.step,
        caption = props.caption,
        finalStatus = props.finalStatus;

  const _useState = useState('fa circle'),
        _useState2 = _slicedToArray(_useState, 2),
        iconClassname = _useState2[0],
        setIconClassname = _useState2[1];

  const _useState3 = useState('challenge-item'),
        _useState4 = _slicedToArray(_useState3, 2),
        itemClassname = _useState4[0],
        setItemClassname = _useState4[1];

  useEffect(() => {
    if (currentStep < step) {
      // not completed step
      setItemClassname('challenge-item');
      setIconClassname('far fa-circle');
    }

    if (currentStep === step) {
      // current step
      setItemClassname('challenge-item challenge-item-current');
      setIconClassname('fas fa-circle');
    }

    if (currentStep > step || finalStatus) {
      setItemClassname('challenge-item challenge-item-completed');
      setIconClassname('fas fa-check-circle');
    }
  }, [step, currentStep, finalStatus]);
  return wp.element.createElement("li", {
    className: itemClassname
  }, wp.element.createElement("i", {
    className: iconClassname
  }), caption);
}

/***/ }),

/***/ "./redux-templates/src/challenge/challenge-list-block/ProgressBar.js":
/*!***************************************************************************!*\
  !*** ./redux-templates/src/challenge/challenge-list-block/ProgressBar.js ***!
  \***************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _config__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../config */ "./redux-templates/src/challenge/config.js");
function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }

function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }

function _iterableToArrayLimit(arr, i) { if (typeof Symbol === "undefined" || !(Symbol.iterator in Object(arr))) return; var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }

function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }

const _wp$element = wp.element,
      useState = _wp$element.useState,
      useEffect = _wp$element.useEffect,
      memo = _wp$element.memo;

/* harmony default export */ __webpack_exports__["default"] = (memo(function ProgressBar({
  currentStep
}) {
  const _useState = useState(0),
        _useState2 = _slicedToArray(_useState, 2),
        width = _useState2[0],
        setWidth = _useState2[1];

  useEffect(() => {
    setWidth(currentStep <= 0 ? 0 : currentStep / _config__WEBPACK_IMPORTED_MODULE_0__["default"].totalStep * 100);
  }, [currentStep]);
  return wp.element.createElement("div", {
    className: "challenge-bar"
  }, wp.element.createElement("div", {
    style: {
      width: width + '%'
    }
  }));
}));

/***/ }),

/***/ "./redux-templates/src/challenge/challenge-list-block/index.js":
/*!*********************************************************************!*\
  !*** ./redux-templates/src/challenge/challenge-list-block/index.js ***!
  \*********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _ChallengeStepItem__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ChallengeStepItem */ "./redux-templates/src/challenge/challenge-list-block/ChallengeStepItem.js");
/* harmony import */ var _ProgressBar__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./ProgressBar */ "./redux-templates/src/challenge/challenge-list-block/ProgressBar.js");
/* harmony import */ var _config__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../config */ "./redux-templates/src/challenge/config.js");
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./style.scss */ "./redux-templates/src/challenge/challenge-list-block/style.scss");
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_style_scss__WEBPACK_IMPORTED_MODULE_4__);
function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }

function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }

function _iterableToArrayLimit(arr, i) { if (typeof Symbol === "undefined" || !(Symbol.iterator in Object(arr))) return; var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }

function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }

/**
 * WordPress dependencies
 */





const compose = wp.compose.compose;
const _wp$data = wp.data,
      withDispatch = _wp$data.withDispatch,
      withSelect = _wp$data.withSelect;
const _wp$element = wp.element,
      useState = _wp$element.useState,
      useEffect = _wp$element.useEffect;

function ChallengeListBlock(props) {
  const started = props.started,
        onStarted = props.onStarted;
  const challengeStep = props.challengeStep,
        finalStatus = props.finalStatus,
        setChallengeOpen = props.setChallengeOpen,
        setChallengeStep = props.setChallengeStep;

  const _useState = useState('challenge-button-row'),
        _useState2 = _slicedToArray(_useState, 2),
        buttonRowClassname = _useState2[0],
        setButtonRowClassname = _useState2[1];

  useEffect(() => {
    setButtonRowClassname(challengeStep !== _config__WEBPACK_IMPORTED_MODULE_3__["default"].beginningStep ? 'challenge-button-row started' : 'challenge-button-row');
  }, [challengeStep]);

  const onCancelChallenge = () => {
    setChallengeOpen(false);
    setChallengeStep(-1);
  };

  return wp.element.createElement("div", {
    className: "challenge-list-block"
  }, wp.element.createElement("p", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])('Complete the challenge and get up and running within 5 minutes', redux_templates.i18n)), wp.element.createElement(_ProgressBar__WEBPACK_IMPORTED_MODULE_2__["default"], {
    currentStep: finalStatus === 'success' ? _config__WEBPACK_IMPORTED_MODULE_3__["default"].totalStep : challengeStep
  }), wp.element.createElement("ul", {
    className: "challenge-list"
  }, _config__WEBPACK_IMPORTED_MODULE_3__["default"].list.map((item, i) => {
    return wp.element.createElement(_ChallengeStepItem__WEBPACK_IMPORTED_MODULE_1__["default"], {
      key: i,
      step: i,
      currentStep: challengeStep,
      finalStatus: finalStatus,
      caption: item.caption
    });
  })), finalStatus === '' && wp.element.createElement("div", {
    className: buttonRowClassname
  }, challengeStep === _config__WEBPACK_IMPORTED_MODULE_3__["default"].beginningStep && wp.element.createElement("button", {
    className: "btn-challenge-start",
    onClick: onStarted
  }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])('Start Challenge', redux_templates.i18n)), challengeStep === _config__WEBPACK_IMPORTED_MODULE_3__["default"].beginningStep && wp.element.createElement("button", {
    className: "btn-challenge-skip",
    onClick: onCancelChallenge
  }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])('Skip Challenge', redux_templates.i18n)), challengeStep !== _config__WEBPACK_IMPORTED_MODULE_3__["default"].beginningStep && wp.element.createElement("button", {
    className: "btn-challenge-cancel",
    onClick: onCancelChallenge
  }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])('Cancel Challenge', redux_templates.i18n))));
}

/* harmony default export */ __webpack_exports__["default"] = (compose([withDispatch(dispatch => {
  const _dispatch = dispatch('redux-templates/sectionslist'),
        setChallengeOpen = _dispatch.setChallengeOpen,
        setChallengeStep = _dispatch.setChallengeStep;

  return {
    setChallengeOpen,
    setChallengeStep
  };
}), withSelect(select => {
  const _select = select('redux-templates/sectionslist'),
        getChallengeStep = _select.getChallengeStep,
        getChallengeFinalStatus = _select.getChallengeFinalStatus;

  return {
    challengeStep: getChallengeStep(),
    finalStatus: getChallengeFinalStatus()
  };
})])(ChallengeListBlock));

/***/ }),

/***/ "./redux-templates/src/challenge/challenge-list-block/style.scss":
/*!***********************************************************************!*\
  !*** ./redux-templates/src/challenge/challenge-list-block/style.scss ***!
  \***********************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../node_modules/css-loader/dist/cjs.js!../../../../node_modules/sass-loader/dist/cjs.js!./style.scss */ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/challenge/challenge-list-block/style.scss");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./redux-templates/src/challenge/challenge-timer/index.js":
/*!****************************************************************!*\
  !*** ./redux-templates/src/challenge/challenge-timer/index.js ***!
  \****************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./style.scss */ "./redux-templates/src/challenge/challenge-timer/style.scss");
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_style_scss__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _config__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../config */ "./redux-templates/src/challenge/config.js");
/* harmony import */ var _helper__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../helper */ "./redux-templates/src/challenge/helper.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_4__);
function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }

function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }

function _iterableToArrayLimit(arr, i) { if (typeof Symbol === "undefined" || !(Symbol.iterator in Object(arr))) return; var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }

function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }

/**
 * WordPress dependencies
 */





const compose = wp.compose.compose;
const _wp$data = wp.data,
      withSelect = _wp$data.withSelect,
      withDispatch = _wp$data.withDispatch;
const _wp$element = wp.element,
      useState = _wp$element.useState,
      useEffect = _wp$element.useEffect,
      useRef = _wp$element.useRef;

function useInterval(callback, delay) {
  const savedCallback = useRef(); // Remember the latest callback.

  useEffect(() => {
    savedCallback.current = callback;
  }, [callback]); // Set up the interval.

  useEffect(() => {
    function tick() {
      savedCallback.current();
    }

    if (delay !== null) {
      let id = setInterval(tick, delay);
      return () => clearInterval(id);
    }
  }, [delay]);
}

function ChallengeTimer(props) {
  const started = props.started,
        expanded = props.expanded,
        setChallengeListExpanded = props.setChallengeListExpanded,
        isChallengeOpen = props.isChallengeOpen,
        finalStatus = props.finalStatus;

  const _useState = useState(_helper__WEBPACK_IMPORTED_MODULE_3__["default"].getSecondsLeft()),
        _useState2 = _slicedToArray(_useState, 2),
        secondsLeft = _useState2[0],
        setSecondsLeft = _useState2[1];

  const _useState3 = useState(false),
        _useState4 = _slicedToArray(_useState3, 2),
        paused = _useState4[0],
        setPaused = _useState4[1]; // only timer


  useEffect(() => {
    window.addEventListener('focus', resume);
    window.addEventListener('blur', pause);
    return () => {
      window.removeEventListener('focus', resume);
      window.removeEventListener('blur', pause);
    };
  }); // setup timer

  useEffect(() => {
    setSecondsLeft(_helper__WEBPACK_IMPORTED_MODULE_3__["default"].getSecondsLeft());

    if (_helper__WEBPACK_IMPORTED_MODULE_3__["default"].loadStep() === -1) {
      setSecondsLeft(_config__WEBPACK_IMPORTED_MODULE_2__["default"].initialSecondsLeft);
    }
  }, [isChallengeOpen]); // run timer

  useInterval(() => {
    setSecondsLeft(secondsLeft < 0 ? 0 : secondsLeft - 1);
    _helper__WEBPACK_IMPORTED_MODULE_3__["default"].saveSecondsLeft(secondsLeft < 0 ? 0 : secondsLeft - 1);
  }, started && paused === false && secondsLeft >= 0 && finalStatus === '' ? 1000 : null); // Pause the timer.

  const pause = () => {
    setPaused(true);
  }; // Resume the timer.


  const resume = () => {
    setPaused(false);
  };

  return wp.element.createElement("div", {
    className: "block-timer"
  }, wp.element.createElement("div", null, wp.element.createElement("h3", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])('Redux Challenge', redux_templates.i18n)), wp.element.createElement("p", null, wp.element.createElement("span", null, _helper__WEBPACK_IMPORTED_MODULE_3__["default"].getFormatted(secondsLeft)), Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])(' remaining', redux_templates.i18n))), wp.element.createElement("div", {
    className: classnames__WEBPACK_IMPORTED_MODULE_4___default()('caret-icon', {
      'closed': expanded
    }),
    onClick: () => setChallengeListExpanded(!expanded)
  }, wp.element.createElement("i", {
    className: "fa fa-caret-down"
  })));
}

/* harmony default export */ __webpack_exports__["default"] = (compose([withDispatch(dispatch => {
  const _dispatch = dispatch('redux-templates/sectionslist'),
        setChallengeListExpanded = _dispatch.setChallengeListExpanded;

  return {
    setChallengeListExpanded
  };
}), withSelect(select => {
  const _select = select('redux-templates/sectionslist'),
        getChallengeOpen = _select.getChallengeOpen,
        getChallengeFinalStatus = _select.getChallengeFinalStatus,
        getChallengeListExpanded = _select.getChallengeListExpanded;

  return {
    isChallengeOpen: getChallengeOpen(),
    finalStatus: getChallengeFinalStatus(),
    expanded: getChallengeListExpanded()
  };
})])(ChallengeTimer));

/***/ }),

/***/ "./redux-templates/src/challenge/challenge-timer/style.scss":
/*!******************************************************************!*\
  !*** ./redux-templates/src/challenge/challenge-timer/style.scss ***!
  \******************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../node_modules/css-loader/dist/cjs.js!../../../../node_modules/sass-loader/dist/cjs.js!./style.scss */ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/challenge/challenge-timer/style.scss");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./redux-templates/src/challenge/config.js":
/*!*************************************************!*\
  !*** ./redux-templates/src/challenge/config.js ***!
  \*************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var react_scroll__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react-scroll */ "./node_modules/react-scroll/modules/index.js");
/* harmony import */ var react_scroll__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(react_scroll__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _redux_templates_modal_manager__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ~redux-templates/modal-manager */ "./redux-templates/src/modal-manager/index.js");
/* harmony import */ var _redux_templates_modal_preview__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ~redux-templates/modal-preview */ "./redux-templates/src/modal-preview/index.js");




const _dispatch = Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_2__["dispatch"])('redux-templates/sectionslist'),
      setTourActiveButtonGroup = _dispatch.setTourActiveButtonGroup,
      setImportingTemplate = _dispatch.setImportingTemplate;

const _select = Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_2__["select"])('redux-templates/sectionslist'),
      getPageData = _select.getPageData;



/* harmony default export */ __webpack_exports__["default"] = ({
  initialSecondsLeft: 300,
  beginningStep: -1,
  totalStep: 7,
  list: [{
    selector: '[data-tut="tour__navigation"]',
    caption: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])('Template Type Tabs', redux_templates.i18n),
    offset: {
      x: 0,
      y: 50,
      arrowX: 0,
      arrowY: -20
    },
    box: {
      width: 250
    },
    direction: 'top',
    content: () => wp.element.createElement("div", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])('These are the different types of templates we have.', redux_templates.i18n), wp.element.createElement("ul", null, wp.element.createElement("li", null, wp.element.createElement("strong", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])('Sections', redux_templates.i18n)), Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])(' are the building blocks of a page. Each "row" of content on a page we consider a section.', redux_templates.i18n)), wp.element.createElement("li", null, wp.element.createElement("strong", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])('Pages', redux_templates.i18n)), Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])(' are, you guessed it, a group of multiple sections making up a page.', redux_templates.i18n)), wp.element.createElement("li", null, wp.element.createElement("strong", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])('Template Kits', redux_templates.i18n)), Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])(' are groups of pages that all follow a style or theme.', redux_templates.i18n)), wp.element.createElement("li", null, wp.element.createElement("strong", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])('Saved', redux_templates.i18n)), Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])(' are reusable blocks that you may have previously saved for later.', redux_templates.i18n))))
  }, {
    selector: '[data-tut="tour__filtering"]',
    caption: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])('Sidebar', redux_templates.i18n),
    content: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])('This area is where you can search and filter to find the right kind of templates you want.', redux_templates.i18n),
    direction: 'left',
    offset: {
      x: 40,
      y: 10,
      arrowX: -20,
      arrowY: 0
    },
    box: {
      width: 250,
      height: 130
    },
    action: () => {
      react_scroll__WEBPACK_IMPORTED_MODULE_1__["animateScroll"].scrollToTop({
        containerId: 'redux-templates-collection-modal-sidebar',
        duration: 0
      });
    }
  }, {
    selector: '[data-tut="tour__filtering"]',
    caption: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])('Plugins Filter', redux_templates.i18n),
    offset: {
      x: 40,
      y: 10,
      arrowX: -20,
      arrowY: 0
    },
    box: {
      width: 290,
      height: 185
    },
    content: () => wp.element.createElement("div", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])('Some templates require certain plugins. You can filter or select those templates. Hint, if the text is a ', redux_templates.i18n), wp.element.createElement("a", {
      href: "#",
      className: "missing-dependency"
    }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])('little orange', redux_templates.i18n)), Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])(', you don`t have that plugin installed yet, but don`t worry. Redux will help you with that too.', redux_templates.i18n)),
    action: () => {
      react_scroll__WEBPACK_IMPORTED_MODULE_1__["animateScroll"].scrollToBottom({
        containerId: 'redux-templates-collection-modal-sidebar',
        duration: 0
      });
    },
    direction: 'left'
  }, {
    selector: '[data-tut="tour__main_body"]',
    caption: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])('Templates List', redux_templates.i18n),
    content: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])('This area is where the templates will show up that match the filters you\'ve selected. You can click on many of them to preview or import them.', redux_templates.i18n),
    direction: 'left',
    offset: {
      x: 40,
      y: 10,
      arrowX: -20,
      arrowY: 0
    },
    box: {
      width: 250,
      height: 150
    },
    action: () => {
      react_scroll__WEBPACK_IMPORTED_MODULE_1__["animateScroll"].scrollToTop({
        containerId: 'redux-templates-collection-modal-sidebar',
        duration: 0
      });
      setTourActiveButtonGroup(null);
    }
  }, {
    selector: '#modalContainer .redux-templates-single-item-inner:first-child',
    caption: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])('Template Hover', redux_templates.i18n),
    content: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])('When you hover over a template you can see via icons what plugins are required for this template. You can then choose to Preview or Import a design.', redux_templates.i18n),
    action: () => {
      _redux_templates_modal_manager__WEBPACK_IMPORTED_MODULE_3__["ModalManager"].closeCustomizer();
      const pageData = getPageData();

      if (pageData && pageData.length > 0) {
        setTourActiveButtonGroup(pageData[0]);
      }
    },
    direction: 'left',
    offset: {
      x: 40,
      y: 10,
      arrowX: -20,
      arrowY: 0
    },
    box: {
      width: 240,
      height: 169
    }
  }, {
    selector: '.wp-full-overlay-sidebar',
    caption: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])('Preview Dialog', redux_templates.i18n),
    content: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])('This is the preview dialog. It gives more details about the template and helps you to see what you could expect the templates to look like.', redux_templates.i18n),
    action: () => {
      setTourActiveButtonGroup(null);
      setImportingTemplate(null);
      const pageData = getPageData();

      if (pageData && pageData.length > 0) {
        _redux_templates_modal_manager__WEBPACK_IMPORTED_MODULE_3__["ModalManager"].openCustomizer(wp.element.createElement(_redux_templates_modal_preview__WEBPACK_IMPORTED_MODULE_4__["default"], {
          startIndex: 0,
          currentPageData: pageData
        }));
      }
    },
    position: 'center'
  }, {
    selector: '.redux-templates-import-wizard-wrapper',
    caption: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])('Import Wizard', redux_templates.i18n),
    content: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])('When you click to import a template, sometimes you will be missing one of the required plugins. Redux will do its best to help you install what\'s missing. If some of them are premium plugins, you will be provided details on where you can get them.', redux_templates.i18n),
    direction: 'right',
    offset: {
      x: 0,
      y: 85,
      arrowX: 40,
      arrowY: 25
    },
    box: {
      width: 250,
      height: 169
    },
    action: () => {
      // if (ModalManager.isModalOpened() === false) ModalManager.open(<LibraryModal autoTourStart={false} />)
      if (document.getElementsByClassName('tooltipster-box')) document.getElementsByClassName('tooltipster-box')[0].style.display = 'none';
      _redux_templates_modal_manager__WEBPACK_IMPORTED_MODULE_3__["ModalManager"].show();
      _redux_templates_modal_manager__WEBPACK_IMPORTED_MODULE_3__["ModalManager"].closeCustomizer();
      const pageData = getPageData();
      if (pageData && pageData.length > 0) setImportingTemplate(pageData[0]);
      setTimeout(() => {
        const openedPanel = document.getElementsByClassName('redux-templates-modal-wrapper');

        if (openedPanel && openedPanel.length > 0) {
          let openPanel = openedPanel[0].getBoundingClientRect();
          let box = {
            top: openPanel.top + 90,
            left: openPanel.left - 320
          };
          Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_2__["dispatch"])('redux-templates/sectionslist').setChallengeTooltipRect(box);
        }

        if (document.getElementsByClassName('tooltipster-box')) document.getElementsByClassName('tooltipster-box')[0].style.display = 'block';
      }, 0);
    }
  }]
});

/***/ }),

/***/ "./redux-templates/src/challenge/final-templates/congrats.js":
/*!*******************************************************************!*\
  !*** ./redux-templates/src/challenge/final-templates/congrats.js ***!
  \*******************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _config__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../config */ "./redux-templates/src/challenge/config.js");
/* harmony import */ var _helper__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../helper */ "./redux-templates/src/challenge/helper.js");
/**
 * WordPress dependencies
 */



const compose = wp.compose.compose;
const _wp$data = wp.data,
      withDispatch = _wp$data.withDispatch,
      withSelect = _wp$data.withSelect;
const ratingStars = wp.element.createElement("span", {
  className: "rating-stars"
}, wp.element.createElement("i", {
  className: "fa fa-star"
}), wp.element.createElement("i", {
  className: "fa fa-star"
}), wp.element.createElement("i", {
  className: "fa fa-star"
}), wp.element.createElement("i", {
  className: "fa fa-star"
}), wp.element.createElement("i", {
  className: "fa fa-star"
}));

function ChallengeCongrats(props) {
  const setChallengeStep = props.setChallengeStep,
        setChallengeFinalStatus = props.setChallengeFinalStatus,
        setChallengeOpen = props.setChallengeOpen;

  const closeModal = () => {
    setChallengeStep(_config__WEBPACK_IMPORTED_MODULE_1__["default"].beginningStep);
    setChallengeFinalStatus('');
    setChallengeOpen(false);
  };

  return wp.element.createElement("div", {
    className: "redux-templates-modal-overlay"
  }, wp.element.createElement("div", {
    className: "redux-templates-modal-wrapper challenge-popup-wrapper"
  }, wp.element.createElement("div", {
    className: "challenge-popup-header challenge-popup-header-congrats",
    style: {
      backgroundImage: `url(${redux_templates.plugin + 'assets/img/popup-congrats.png'})`
    }
  }, wp.element.createElement("a", {
    className: "challenge-popup-close",
    onClick: closeModal
  }, wp.element.createElement("i", {
    className: "fas fa-times"
  }))), wp.element.createElement("div", {
    className: "challenge-popup-content"
  }, wp.element.createElement("h3", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])('Congrats, you did it!', redux_templates.i18n)), wp.element.createElement("p", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])('You completed the Redux Challenge in ', redux_templates.i18n), wp.element.createElement("b", null, _helper__WEBPACK_IMPORTED_MODULE_2__["default"].getLocalizedDuration()), ".", Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])('Share your success story with other Redux users and help us spread the word', redux_templates.i18n), wp.element.createElement("b", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])('by giving Redux a 5-star rating (', redux_templates.i18n), " ", ratingStars, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])(') on WordPress.org', redux_templates.i18n)), ".", Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])('Thanks for your support and we look forward to bringing more awesome features.', redux_templates.i18n)), wp.element.createElement("a", {
    href: "https://wordpress.org/support/plugin/redux-framework/reviews/?filter=5#new-post",
    className: "challenge-popup-btn challenge-popup-rate-btn",
    target: "_blank",
    rel: "noopener"
  }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])('Rate Redux on Wordpress.org', redux_templates.i18n), wp.element.createElement("span", {
    className: "dashicons dashicons-external"
  })))));
}

/* harmony default export */ __webpack_exports__["default"] = (compose([withDispatch(dispatch => {
  const _dispatch = dispatch('redux-templates/sectionslist'),
        setChallengeStep = _dispatch.setChallengeStep,
        setChallengeFinalStatus = _dispatch.setChallengeFinalStatus,
        setChallengeOpen = _dispatch.setChallengeOpen;

  return {
    setChallengeStep,
    setChallengeFinalStatus,
    setChallengeOpen
  };
})])(ChallengeCongrats));

/***/ }),

/***/ "./redux-templates/src/challenge/final-templates/contact.js":
/*!******************************************************************!*\
  !*** ./redux-templates/src/challenge/final-templates/contact.js ***!
  \******************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _config__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../config */ "./redux-templates/src/challenge/config.js");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);
function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }

function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }

function _iterableToArrayLimit(arr, i) { if (typeof Symbol === "undefined" || !(Symbol.iterator in Object(arr))) return; var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }

function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }

/**
 * WordPress dependencies
 */



const compose = wp.compose.compose;
const useState = wp.element.useState;
const _wp$data = wp.data,
      withDispatch = _wp$data.withDispatch,
      withSelect = _wp$data.withSelect;
const ratingStars = wp.element.createElement("span", {
  className: "rating-stars"
}, wp.element.createElement("i", {
  className: "fa fa-star"
}), wp.element.createElement("i", {
  className: "fa fa-star"
}), wp.element.createElement("i", {
  className: "fa fa-star"
}), wp.element.createElement("i", {
  className: "fa fa-star"
}), wp.element.createElement("i", {
  className: "fa fa-star"
}));

function ChallengeContact(props) {
  const setChallengeStep = props.setChallengeStep,
        setChallengeFinalStatus = props.setChallengeFinalStatus,
        setChallengeOpen = props.setChallengeOpen;

  const _useState = useState(''),
        _useState2 = _slicedToArray(_useState, 2),
        comment = _useState2[0],
        setComment = _useState2[1];

  const _useState3 = useState(false),
        _useState4 = _slicedToArray(_useState3, 2),
        agreeToContactFurther = _useState4[0],
        setAgreement = _useState4[1];

  const closeModal = () => {
    setChallengeStep(_config__WEBPACK_IMPORTED_MODULE_1__["default"].beginningStep);
    setChallengeFinalStatus('');
    setChallengeOpen(false);
  };

  const handleChange = e => {
    setComment(e.target.value);
  };

  const contactRedux = () => {
    //sending data
    console.log('contact information', comment, agreeToContactFurther);
    closeModal();
  };

  return wp.element.createElement("div", {
    className: "redux-templates-modal-overlay"
  }, wp.element.createElement("div", {
    className: "redux-templates-modal-wrapper challenge-popup-wrapper"
  }, wp.element.createElement("div", {
    className: "challenge-popup-header challenge-popup-header-contact",
    style: {
      backgroundImage: `url(${redux_templates.plugin + 'assets/img/popup-contact.png'})`
    }
  }, wp.element.createElement("a", {
    className: "challenge-popup-close",
    onClick: closeModal
  }, wp.element.createElement("i", {
    className: "fas fa-times"
  }))), wp.element.createElement("div", {
    className: "challenge-popup-content challenge-contact"
  }, wp.element.createElement("h3", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])('Help us improve Redux', redux_templates.i18n)), wp.element.createElement("p", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])('We\'re sorry that it took longer than 5 minutes to try our challenge. We aim to ensure our Block Template library is as beginner friendly as possible. Please take a moment to let us know how we can improve our challenge.', redux_templates.i18n)), wp.element.createElement("textarea", {
    value: comment,
    onChange: handleChange
  }), wp.element.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__["CheckboxControl"], {
    label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])('Yes, I give Redux permission to contact me for any follow up questions.', redux_templates.i18n),
    checked: agreeToContactFurther,
    onChange: () => setAgreement(!agreeToContactFurther)
  }), wp.element.createElement("button", {
    className: "challenge-popup-btn challenge-popup-rate-btn",
    onClick: contactRedux
  }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])('Submit Feedback', redux_templates.i18n)))));
}

/* harmony default export */ __webpack_exports__["default"] = (compose([withDispatch(dispatch => {
  const _dispatch = dispatch('redux-templates/sectionslist'),
        setChallengeStep = _dispatch.setChallengeStep,
        setChallengeFinalStatus = _dispatch.setChallengeFinalStatus,
        setChallengeOpen = _dispatch.setChallengeOpen;

  return {
    setChallengeStep,
    setChallengeFinalStatus,
    setChallengeOpen
  };
})])(ChallengeContact));

/***/ }),

/***/ "./redux-templates/src/challenge/final-templates/index.js":
/*!****************************************************************!*\
  !*** ./redux-templates/src/challenge/final-templates/index.js ***!
  \****************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return ChallengeFinalTemplate; });
/* harmony import */ var _congrats__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./congrats */ "./redux-templates/src/challenge/final-templates/congrats.js");
/* harmony import */ var _contact__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./contact */ "./redux-templates/src/challenge/final-templates/contact.js");
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./style.scss */ "./redux-templates/src/challenge/final-templates/style.scss");
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_style_scss__WEBPACK_IMPORTED_MODULE_2__);
/**
 * WordPress dependencies
 */



function ChallengeFinalTemplate({
  finalStatus
}) {
  return wp.element.createElement(_congrats__WEBPACK_IMPORTED_MODULE_0__["default"], null); // TODO - When feedback is working, uncomment this.
  // if (finalStatus === 'success') return <ChallengeCongrats />
  // return <ChallengeContact />;
}

/***/ }),

/***/ "./redux-templates/src/challenge/final-templates/style.scss":
/*!******************************************************************!*\
  !*** ./redux-templates/src/challenge/final-templates/style.scss ***!
  \******************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../node_modules/css-loader/dist/cjs.js!../../../../node_modules/sass-loader/dist/cjs.js!./style.scss */ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/challenge/final-templates/style.scss");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./redux-templates/src/challenge/helper.js":
/*!*************************************************!*\
  !*** ./redux-templates/src/challenge/helper.js ***!
  \*************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _config__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./config */ "./redux-templates/src/challenge/config.js");


/* harmony default export */ __webpack_exports__["default"] = ({
  /**
   * Get number of seconds left to complete the Challenge.
   */
  getSecondsLeft: function () {
    var secondsLeft = localStorage.getItem('reduxChallengeSecondsLeft');
    secondsLeft = isNaN(secondsLeft) || secondsLeft == null ? _config__WEBPACK_IMPORTED_MODULE_1__["default"].initialSecondsLeft : parseInt(secondsLeft, 10);
    return secondsLeft;
  },

  /**
   * Save number of seconds left to complete the Challenge.
   */
  saveSecondsLeft: function (secondsLeft) {
    localStorage.setItem('reduxChallengeSecondsLeft', secondsLeft);
  },

  /**
   * Get 'minutes' part of timer display.
   */
  getMinutesFormatted: function (secondsLeft) {
    return Math.floor(secondsLeft / 60);
  },

  /**
   * Get 'seconds' part of timer display.
   */
  getSecondsFormatted: function (secondsLeft) {
    return secondsLeft % 60;
  },

  /**
   * Get formatted timer for display.
   */
  getFormatted: function (secondsLeft) {
    if (secondsLeft < 0) return '0:00';
    var timerMinutes = this.getMinutesFormatted(secondsLeft);
    var timerSeconds = this.getSecondsFormatted(secondsLeft);
    return timerMinutes + (9 < timerSeconds ? ':' : ':0') + timerSeconds;
  },

  /**
   * Get Localized time string for display
   */
  getLocalizedDuration: function () {
    let secondsLeft = this.getSecondsLeft();
    secondsLeft = _config__WEBPACK_IMPORTED_MODULE_1__["default"].initialSecondsLeft - secondsLeft;
    var timerMinutes = this.getMinutesFormatted(secondsLeft);
    var timerSeconds = this.getSecondsFormatted(secondsLeft);
    const minutesString = timerMinutes ? timerMinutes + ' ' + Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])('minutes', redux_templates.i18n) + ' ' : '';
    const secondsString = timerSeconds ? timerSeconds + ' ' + Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])('seconds', redux_templates.i18n) : '';
    return minutesString + secondsString;
  },

  /**
   * Get last saved step.
   */
  loadStep: function () {
    var step = localStorage.getItem('reduxChallengeStep');
    step = isNaN(step) ? -1 : parseInt(step, 10);
    return step;
  },

  /**
   * Save Challenge step.
   */
  saveStep: function (step) {
    localStorage.setItem('reduxChallengeStep', step);
  }
});

/***/ }),

/***/ "./redux-templates/src/challenge/index.js":
/*!************************************************!*\
  !*** ./redux-templates/src/challenge/index.js ***!
  \************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./style.scss */ "./redux-templates/src/challenge/style.scss");
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_style_scss__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _helper__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./helper */ "./redux-templates/src/challenge/helper.js");
/* harmony import */ var _config__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./config */ "./redux-templates/src/challenge/config.js");
/* harmony import */ var _challenge_list_block__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./challenge-list-block */ "./redux-templates/src/challenge/challenge-list-block/index.js");
/* harmony import */ var _challenge_timer__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./challenge-timer */ "./redux-templates/src/challenge/challenge-timer/index.js");
function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }

function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }

function _iterableToArrayLimit(arr, i) { if (typeof Symbol === "undefined" || !(Symbol.iterator in Object(arr))) return; var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }

function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }

/**
 * WordPress dependencies
 */






const compose = wp.compose.compose;
const _wp$data = wp.data,
      withDispatch = _wp$data.withDispatch,
      withSelect = _wp$data.withSelect;
const _wp$element = wp.element,
      useState = _wp$element.useState,
      useEffect = _wp$element.useEffect;

function ReduxChallenge(props) {
  const autoChallengeStart = props.autoChallengeStart;
  const isOpen = props.isOpen,
        challengeStep = props.challengeStep,
        setChallengeStep = props.setChallengeStep,
        listExpanded = props.listExpanded;

  const _useState = useState('redux-templates-challenge'),
        _useState2 = _slicedToArray(_useState, 2),
        challengeClassname = _useState2[0],
        setChallengeClassname = _useState2[1];

  const _useState3 = useState(false),
        _useState4 = _slicedToArray(_useState3, 2),
        started = _useState4[0],
        setStarted = _useState4[1];

  useEffect(() => {
    if (challengeStep !== _config__WEBPACK_IMPORTED_MODULE_3__["default"].beginningStep && isOpen) {
      setChallengeClassname('redux-templates-challenge started');
      setStarted(true);
    }
  }, [challengeStep, isOpen]);

  const onStarted = () => {
    setChallengeStep(0);
    setStarted(true);
  };

  return wp.element.createElement("div", {
    className: challengeClassname,
    style: {
      display: isOpen ? 'block' : 'none'
    }
  }, listExpanded && wp.element.createElement(_challenge_list_block__WEBPACK_IMPORTED_MODULE_4__["default"], {
    onStarted: onStarted
  }), wp.element.createElement(_challenge_timer__WEBPACK_IMPORTED_MODULE_5__["default"], {
    started: started
  }));
}

/* harmony default export */ __webpack_exports__["default"] = (compose([withDispatch(dispatch => {
  const _dispatch = dispatch('redux-templates/sectionslist'),
        setChallengeStep = _dispatch.setChallengeStep;

  return {
    setChallengeStep
  };
}), withSelect(select => {
  const _select = select('redux-templates/sectionslist'),
        getChallengeStep = _select.getChallengeStep,
        getChallengeOpen = _select.getChallengeOpen,
        getChallengeListExpanded = _select.getChallengeListExpanded;

  return {
    challengeStep: getChallengeStep(),
    isOpen: getChallengeOpen(),
    listExpanded: getChallengeListExpanded()
  };
})])(ReduxChallenge));

/***/ }),

/***/ "./redux-templates/src/challenge/style.scss":
/*!**************************************************!*\
  !*** ./redux-templates/src/challenge/style.scss ***!
  \**************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../node_modules/css-loader/dist/cjs.js!../../../node_modules/sass-loader/dist/cjs.js!./style.scss */ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/challenge/style.scss");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./redux-templates/src/challenge/tooltip/ChallengeDot.js":
/*!***************************************************************!*\
  !*** ./redux-templates/src/challenge/tooltip/ChallengeDot.js ***!
  \***************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/compose */ "@wordpress/compose");
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_compose__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _config__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../config */ "./redux-templates/src/challenge/config.js");



const _wp$element = wp.element,
      findDOMNode = _wp$element.findDOMNode,
      useRef = _wp$element.useRef,
      useEffect = _wp$element.useEffect;

function ChallengeDot(props) {
  const step = props.step,
        challengeStep = props.challengeStep,
        isOpen = props.isOpen,
        setChallengeTooltipRect = props.setChallengeTooltipRect;
  const selectedElement = useRef(null);
  useEffect(() => {
    window.addEventListener('resize', onResize);
    return () => {
      window.removeEventListener('resize', onResize);
    };
  }, []);
  useEffect(() => {
    if (isOpen === false) return;
    const stepInformation = _config__WEBPACK_IMPORTED_MODULE_2__["default"].list[challengeStep];

    if (stepInformation && stepInformation.action && typeof stepInformation.action === 'function') {
      stepInformation.action();
      onResize();
      setTimeout(onResize, 0);
    } else onResize();
  }, [challengeStep, isOpen]);

  const isVisible = () => {
    return challengeStep >= 0 && challengeStep < _config__WEBPACK_IMPORTED_MODULE_2__["default"].totalStep && isOpen;
  };

  const onResize = () => {
    const box = getElementBounding();
    if (box) setChallengeTooltipRect(box);
  };

  const getElementBounding = () => {
    if (selectedElement && selectedElement.current) {
      const rect = findDOMNode(selectedElement.current).getBoundingClientRect();
      return {
        left: rect.left,
        top: rect.top,
        width: rect.width,
        height: rect.height
      };
    }

    return null;
  };

  if (isVisible() && challengeStep === step) return wp.element.createElement("i", {
    className: "challenge-dot tooltipstered",
    ref: selectedElement
  }, "\xA0");
  return null;
}

/* harmony default export */ __webpack_exports__["default"] = (Object(_wordpress_compose__WEBPACK_IMPORTED_MODULE_0__["compose"])([Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__["withDispatch"])(dispatch => {
  const _dispatch = dispatch('redux-templates/sectionslist'),
        setChallengeTooltipRect = _dispatch.setChallengeTooltipRect;

  return {
    setChallengeTooltipRect
  };
}), Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__["withSelect"])((select, props) => {
  const _select = select('redux-templates/sectionslist'),
        getChallengeOpen = _select.getChallengeOpen,
        getChallengeStep = _select.getChallengeStep;

  return {
    isOpen: getChallengeOpen(),
    challengeStep: getChallengeStep()
  };
})])(ChallengeDot));

/***/ }),

/***/ "./redux-templates/src/challenge/tooltip/TooltipBox.js":
/*!*************************************************************!*\
  !*** ./redux-templates/src/challenge/tooltip/TooltipBox.js ***!
  \*************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _redux_templates_modal_manager__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ~redux-templates/modal-manager */ "./redux-templates/src/modal-manager/index.js");
/* harmony import */ var _config__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../config */ "./redux-templates/src/challenge/config.js");
/* harmony import */ var _helper__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../helper */ "./redux-templates/src/challenge/helper.js");
function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }

function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }

function _iterableToArrayLimit(arr, i) { if (typeof Symbol === "undefined" || !(Symbol.iterator in Object(arr))) return; var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }

function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }


const compose = wp.compose.compose;
const _wp$data = wp.data,
      withDispatch = _wp$data.withDispatch,
      withSelect = _wp$data.withSelect;
const _wp$element = wp.element,
      useState = _wp$element.useState,
      useEffect = _wp$element.useEffect;



const ARROW_BOX = 30;
const DEFAULT_BOX_WIDTH = 250;
const DEFAULT_BOX_HEIGHT = 300;
const DEFAULT_OFFSET_X = 0;
const DEFAULT_OFFSET_Y = 20;
const DEFAULT_ARROW_OFFSET_X = 20;
const DEFAULT_ARROW_OFFSET_Y = 20;

function TooltipBox(props) {
  const challengeStep = props.challengeStep,
        tooltipRect = props.tooltipRect,
        isOpen = props.isOpen,
        setChallengeStep = props.setChallengeStep,
        setChallengeFinalStatus = props.setChallengeFinalStatus,
        setChallengePassed = props.setChallengePassed,
        setChallengeListExpanded = props.setChallengeListExpanded,
        setImportingTemplate = props.setImportingTemplate;

  const _useState = useState({}),
        _useState2 = _slicedToArray(_useState, 2),
        style = _useState2[0],
        setStyle = _useState2[1];

  const _useState3 = useState({}),
        _useState4 = _slicedToArray(_useState3, 2),
        arrowStyle = _useState4[0],
        setArrowStyle = _useState4[1];

  const _useState5 = useState(''),
        _useState6 = _slicedToArray(_useState5, 2),
        content = _useState6[0],
        setContent = _useState6[1];

  const _useState7 = useState(''),
        _useState8 = _slicedToArray(_useState7, 2),
        wrapperClassname = _useState8[0],
        setWrapperClassname = _useState8[1];

  const isVisible = () => {
    return (challengeStep >= 0 || challengeStep > _config__WEBPACK_IMPORTED_MODULE_2__["default"].totalStep) && isOpen;
  };

  const calculateWithStepInformation = () => {
    const stepInformation = _config__WEBPACK_IMPORTED_MODULE_2__["default"].list[challengeStep];
    const boxWidth = stepInformation.box && stepInformation.box.width ? stepInformation.box.width : DEFAULT_BOX_WIDTH;
    const boxHeight = stepInformation.box && stepInformation.box.height ? stepInformation.box.height : DEFAULT_BOX_HEIGHT;
    const offsetX = stepInformation.offset ? stepInformation.offset.x : DEFAULT_OFFSET_X;
    const offsetY = stepInformation.offset ? stepInformation.offset.y : DEFAULT_OFFSET_Y;

    switch (stepInformation.direction) {
      case 'right':
        return [tooltipRect.left + offsetX, tooltipRect.top + offsetY - boxHeight / 2];

      case 'left':
        return [tooltipRect.left + offsetX, tooltipRect.top + offsetY - boxHeight / 2];

      case 'top':
        return [tooltipRect.left + offsetX - boxWidth / 2, tooltipRect.top + offsetY];

      case 'bottom':
        return [tooltipRect.left + offsetX - boxWidth / 2, tooltipRect.top - boxHeight + offsetY];

      default:
        return [tooltipRect.left + offsetX, tooltipRect.top + offsetY];
    }
  };

  const calculateArrowOffset = () => {
    const stepInformation = _config__WEBPACK_IMPORTED_MODULE_2__["default"].list[challengeStep];
    const boxWidth = stepInformation.box && stepInformation.box.width ? stepInformation.box.width : DEFAULT_BOX_WIDTH;
    const boxHeight = stepInformation.box && stepInformation.box.height ? stepInformation.box.height : DEFAULT_BOX_HEIGHT;
    const arrowOffsetX = stepInformation.offset && isNaN(stepInformation.offset.arrowX) === false ? stepInformation.offset.arrowX : DEFAULT_ARROW_OFFSET_X;
    const arrowOffsetY = stepInformation.offset && isNaN(stepInformation.offset.arrowY) === false ? stepInformation.offset.arrowY : DEFAULT_ARROW_OFFSET_Y;

    switch (stepInformation.direction) {
      case 'top':
        return [boxWidth / 2 + arrowOffsetX, arrowOffsetY];

      case 'bottom':
        return [boxWidth / 2 + arrowOffsetX, arrowOffsetY];

      case 'left':
        return [arrowOffsetX, arrowOffsetY + boxHeight / 2 - ARROW_BOX / 2];

      case 'right':
        return [boxWidth + arrowOffsetX, arrowOffsetY + boxHeight / 2 - ARROW_BOX / 2];

      default:
        return [arrowOffsetX, arrowOffsetY];
    }
  }; // adjust position and content upon steps change


  useEffect(() => {
    if (isVisible() && tooltipRect) {
      const stepInformation = _config__WEBPACK_IMPORTED_MODULE_2__["default"].list[challengeStep];

      if (stepInformation) {
        const _calculateWithStepInf = calculateWithStepInformation(),
              _calculateWithStepInf2 = _slicedToArray(_calculateWithStepInf, 2),
              boxLeft = _calculateWithStepInf2[0],
              boxTop = _calculateWithStepInf2[1];

        const _calculateArrowOffset = calculateArrowOffset(),
              _calculateArrowOffset2 = _slicedToArray(_calculateArrowOffset, 2),
              arrowOffsetX = _calculateArrowOffset2[0],
              arrowOffsetY = _calculateArrowOffset2[1];

        setStyle(_objectSpread(_objectSpread({}, style), {}, {
          display: 'block',
          width: stepInformation.box ? stepInformation.box.width : DEFAULT_BOX_WIDTH,
          left: boxLeft,
          top: boxTop //tooltipRect.top + offsetY + PADDING_Y + ARROW_HEIGHT

        }));
        setContent(stepInformation.content);
        setArrowStyle(_objectSpread(_objectSpread({}, arrowStyle), {}, {
          display: 'block',
          left: boxLeft + arrowOffsetX,
          // calculateLeftWithStepInformation(),
          top: boxTop + arrowOffsetY // tooltipRect.top + offsetY + PADDING_Y

        }));
      }
    } else {
      setStyle(_objectSpread(_objectSpread({}, style), {}, {
        display: 'none'
      }));
      setArrowStyle(_objectSpread(_objectSpread({}, arrowStyle), {}, {
        display: 'none'
      }));
    }
  }, [JSON.stringify(tooltipRect), challengeStep, isOpen]); // update wrapper class name based on step change

  useEffect(() => {
    const stepInformation = _config__WEBPACK_IMPORTED_MODULE_2__["default"].list[challengeStep];

    if (stepInformation) {
      switch (stepInformation.direction) {
        case 'top':
          setWrapperClassname('challenge-tooltip tooltipster-sidetip tooltipster-top');
          break;

        case 'bottom':
          setWrapperClassname('challenge-tooltip tooltipster-sidetip tooltipster-bottom');
          break;

        case 'left':
          setWrapperClassname('challenge-tooltip tooltipster-sidetip tooltipster-left');
          break;

        case 'right':
          setWrapperClassname('challenge-tooltip tooltipster-sidetip tooltipster-right');
          break;

        default:
          setWrapperClassname('challenge-tooltip tooltipster-sidetip tooltipster-left');
      }
    }
  }, [challengeStep]);

  const toNextStep = () => {
    if (challengeStep === _config__WEBPACK_IMPORTED_MODULE_2__["default"].totalStep - 1) {
      // finalize challenge
      _redux_templates_modal_manager__WEBPACK_IMPORTED_MODULE_1__["ModalManager"].show();
      setChallengeFinalStatus(_helper__WEBPACK_IMPORTED_MODULE_3__["default"].getSecondsLeft() > 0 ? 'success' : 'contact');
      setChallengeStep(_config__WEBPACK_IMPORTED_MODULE_2__["default"].beginningStep);
      setChallengePassed(true);
      setChallengeListExpanded(true);
      setImportingTemplate(null);
    } else setChallengeStep(challengeStep + 1);
  };

  return wp.element.createElement("div", {
    className: wrapperClassname
  }, wp.element.createElement("div", {
    className: "tooltipster-box",
    style: style
  }, content, wp.element.createElement("div", {
    className: "btn-row"
  }, wp.element.createElement("button", {
    className: "challenge-done-btn",
    onClick: toNextStep
  }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])('Next', redux_templates.i18n)))), wp.element.createElement("div", {
    className: "tooltipster-arrow",
    style: arrowStyle
  }, wp.element.createElement("div", {
    className: "tooltipster-arrow-uncropped"
  }, wp.element.createElement("div", {
    className: "tooltipster-arrow-border"
  }), wp.element.createElement("div", {
    className: "tooltipster-arrow-background"
  }))));
}

/* harmony default export */ __webpack_exports__["default"] = (compose([withDispatch(dispatch => {
  const _dispatch = dispatch('redux-templates/sectionslist'),
        setChallengeStep = _dispatch.setChallengeStep,
        setChallengeFinalStatus = _dispatch.setChallengeFinalStatus,
        setChallengePassed = _dispatch.setChallengePassed,
        setChallengeListExpanded = _dispatch.setChallengeListExpanded,
        setImportingTemplate = _dispatch.setImportingTemplate;

  return {
    setChallengeStep,
    setChallengeFinalStatus,
    setChallengePassed,
    setChallengeListExpanded,
    setImportingTemplate
  };
}), withSelect((select, props) => {
  const _select = select('redux-templates/sectionslist'),
        getChallengeTooltipRect = _select.getChallengeTooltipRect,
        getChallengeOpen = _select.getChallengeOpen,
        getChallengeStep = _select.getChallengeStep,
        getChallengeFinalStatus = _select.getChallengeFinalStatus;

  return {
    tooltipRect: getChallengeTooltipRect(),
    isOpen: getChallengeOpen(),
    challengeStep: getChallengeStep(),
    finalStatus: getChallengeFinalStatus()
  };
})])(TooltipBox));

/***/ }),

/***/ "./redux-templates/src/components/background-image/index.js":
/*!******************************************************************!*\
  !*** ./redux-templates/src/components/background-image/index.js ***!
  \******************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _redux_templates_stores_actionHelper__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ~redux-templates/stores/actionHelper */ "./redux-templates/src/stores/actionHelper.js");
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./style.scss */ "./redux-templates/src/components/background-image/style.scss");
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_style_scss__WEBPACK_IMPORTED_MODULE_2__);
function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }

function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }

function _iterableToArrayLimit(arr, i) { if (typeof Symbol === "undefined" || !(Symbol.iterator in Object(arr))) return; var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }

function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }

const _wp = wp,
      apiFetch = _wp.apiFetch;
const useState = wp.element.useState;
const compose = wp.compose.compose;
const _wp$data = wp.data,
      withDispatch = _wp$data.withDispatch,
      withSelect = _wp$data.withSelect;
const parse = wp.blocks.parse;




function BackgroundImage(props) {
  const data = props.data,
        appendErrorMessage = props.appendErrorMessage,
        activeItemType = props.activeItemType;

  const _useState = useState(false),
        _useState2 = _slicedToArray(_useState, 2),
        dataLoaded = _useState2[0],
        setDataLoaded = _useState2[1];

  const _useState3 = useState(null),
        _useState4 = _slicedToArray(_useState3, 2),
        blocks = _useState4[0],
        setBlocks = _useState4[1];

  if (data && dataLoaded === false) {
    const type = activeItemType === 'section' ? 'sections' : 'pages';
    let the_url = 'redux/v1/templates/template?type=' + type + '&id=' + data.id + '&uid=' + window.userSettings.uid;

    if ('source' in data) {
      the_url += '&source=' + data.source;
    }

    const options = {
      method: 'GET',
      path: the_url,
      headers: {
        'Content-Type': 'application/json',
        'Registered-Blocks': Object(_redux_templates_stores_actionHelper__WEBPACK_IMPORTED_MODULE_1__["installedBlocksTypes"])()
      }
    };
    apiFetch(options).then(response => {
      if (response.success) {
        setBlocks(response.data);
      } else {
        appendErrorMessage(response.data.error);
      }

      setDataLoaded(true);
    }).catch(error => {
      appendErrorMessage(error.code + ' : ' + error.message);
      setDataLoaded(true);
    });
  }

  if (dataLoaded === true) {
    let parsed = parse(blocks.template);
    return wp.element.createElement("div", null, wp.element.createElement(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_0__["BlockPreview"], {
      blocks: parsed
    }));
  }

  return null;
}

/* harmony default export */ __webpack_exports__["default"] = (compose([withDispatch(dispatch => {
  const _dispatch = dispatch('redux-templates/sectionslist'),
        appendErrorMessage = _dispatch.appendErrorMessage;

  return {
    appendErrorMessage
  };
}), withSelect(select => {
  const _select = select('redux-templates/sectionslist'),
        getActiveItemType = _select.getActiveItemType;

  return {
    activeItemType: getActiveItemType()
  };
})])(BackgroundImage));

/***/ }),

/***/ "./redux-templates/src/components/background-image/style.scss":
/*!********************************************************************!*\
  !*** ./redux-templates/src/components/background-image/style.scss ***!
  \********************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../node_modules/css-loader/dist/cjs.js!../../../../node_modules/sass-loader/dist/cjs.js!./style.scss */ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/components/background-image/style.scss");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./redux-templates/src/components/button-group/index.js":
/*!**************************************************************!*\
  !*** ./redux-templates/src/components/button-group/index.js ***!
  \**************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _preview_import_button__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../preview-import-button */ "./redux-templates/src/components/preview-import-button/index.js");
/* harmony import */ var _dependent_plugins__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../dependent-plugins */ "./redux-templates/src/components/dependent-plugins/index.js");
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./style.scss */ "./redux-templates/src/components/button-group/style.scss");
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_style_scss__WEBPACK_IMPORTED_MODULE_3__);
function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }

function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }

function _iterableToArrayLimit(arr, i) { if (typeof Symbol === "undefined" || !(Symbol.iterator in Object(arr))) return; var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }

function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }

const compose = wp.compose.compose;
const withSelect = wp.data.withSelect;





function ButtonGroup(props) {
  const importingTemplate = props.importingTemplate,
        showDependencyBlock = props.showDependencyBlock,
        index = props.index,
        data = props.data,
        pageData = props.pageData;

  const _useState = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["useState"])('redux-templates-import-button-group'),
        _useState2 = _slicedToArray(_useState, 2),
        rootClassName = _useState2[0],
        setRootClassName = _useState2[1]; // When some action is in progress, disable the button groups


  Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["useEffect"])(() => {
    if (importingTemplate === null && rootClassName !== 'redux-templates-import-button-group') setRootClassName('redux-templates-import-button-group');
    if (importingTemplate !== null && rootClassName === 'redux-templates-import-button-group') setRootClassName('redux-templates-import-button-group disabled');
  }, [importingTemplate]);
  return wp.element.createElement("div", {
    className: rootClassName
  }, wp.element.createElement(_preview_import_button__WEBPACK_IMPORTED_MODULE_1__["default"], {
    index: index,
    data: data,
    pageData: pageData
  }), wp.element.createElement(_dependent_plugins__WEBPACK_IMPORTED_MODULE_2__["default"], {
    showDependencyBlock: showDependencyBlock,
    data: data
  }));
}

/* harmony default export */ __webpack_exports__["default"] = (compose([withSelect(select => {
  const _select = select('redux-templates/sectionslist'),
        getImportingTemplate = _select.getImportingTemplate;

  return {
    importingTemplate: getImportingTemplate()
  };
})])(ButtonGroup));

/***/ }),

/***/ "./redux-templates/src/components/button-group/style.scss":
/*!****************************************************************!*\
  !*** ./redux-templates/src/components/button-group/style.scss ***!
  \****************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../node_modules/css-loader/dist/cjs.js!../../../../node_modules/sass-loader/dist/cjs.js!./style.scss */ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/components/button-group/style.scss");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./redux-templates/src/components/dependent-plugins/index.js":
/*!*******************************************************************!*\
  !*** ./redux-templates/src/components/dependent-plugins/index.js ***!
  \*******************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return DependentPlugins; });
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _redux_templates_icons__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ~redux-templates/icons */ "./redux-templates/src/icons/index.js");
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./style.scss */ "./redux-templates/src/components/dependent-plugins/style.scss");
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_style_scss__WEBPACK_IMPORTED_MODULE_2__);



const __ = wp.i18n.__;
function DependentPlugins(props) {
  const data = props.data,
        showDependencyBlock = props.showDependencyBlock;
  const id = data.id;

  const isMissingPlugin = plugin => {
    return data.proDependenciesMissing && data.proDependenciesMissing.indexOf(plugin) >= 0 || data.installDependenciesMissing && data.installDependenciesMissing.indexOf(plugin) >= 0;
  };

  if (showDependencyBlock) {
    let index = data.dependencies.indexOf('core');

    if (index > -1) {
      data.dependencies.splice(index, 1);
      data.dependencies.push('core');
    }

    return wp.element.createElement("div", {
      className: "redux-templates-button-display-dependencies"
    }, data.dependencies && data.dependencies.map(plugin => {
      let pluginInstance = null;
      const plugin_name = plugin.replace('-pro', '').replace('-premium', '').replace(/\W/g, '').toLowerCase();

      if ('core' == plugin) {
        pluginInstance = {
          name: 'WordPress Native'
        };
      } else {
        pluginInstance = redux_templates.supported_plugins[plugin];
      }

      if (!pluginInstance) {
        pluginInstance = redux_templates.supported_plugins[plugin.replace('-pro', '').replace('-premium', '')];
      } // We don't want two of the same icons showing up.


      if (!plugin.includes('-pro') && !plugin.includes('-premium')) {
        if (data.dependencies.includes(plugin + '-pro') || data.dependencies.includes(plugin + '-premium')) {
          return;
        }
      }

      if (!pluginInstance) {
        console.log('Missing plugin details for ' + plugin + ' - ' + plugin.replace('-pro', '').replace('-premium', ''));
        console.log(redux_templates.supported_plugins);
        return;
      }

      if ('redux' === plugin_name) {
        return;
      }

      const IconComponent = _redux_templates_icons__WEBPACK_IMPORTED_MODULE_1__[plugin_name];

      if (IconComponent && pluginInstance) {
        return wp.element.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__["Tooltip"], {
          text: isMissingPlugin(plugin) && 'core' !== plugin ? pluginInstance.name + ' ( ' + __('Not Installed', redux_templates.i18n) + ' )' : pluginInstance.name,
          position: "bottom center",
          key: id + plugin
        }, wp.element.createElement("span", {
          className: isMissingPlugin(plugin) && 'core' !== plugin ? 'missing-dependency' : ''
        }, wp.element.createElement(IconComponent, null)));
      } else if ('shareablockcom' !== plugin_name && 'gutenberghubcom' !== plugin_name) {
        console.log('Need icon for ' + plugin_name);
      }
    }), data.dependencies['core'] && wp.element.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__["Tooltip"], {
      text: __('WordPress Core', redux_templates.i18n),
      position: "bottom center",
      key: id + 'core'
    }, wp.element.createElement("span", null, wp.element.createElement(IconComponent, null))));
  }

  return null;
}

/***/ }),

/***/ "./redux-templates/src/components/dependent-plugins/style.scss":
/*!*********************************************************************!*\
  !*** ./redux-templates/src/components/dependent-plugins/style.scss ***!
  \*********************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../node_modules/css-loader/dist/cjs.js!../../../../node_modules/sass-loader/dist/cjs.js!./style.scss */ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/components/dependent-plugins/style.scss");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./redux-templates/src/components/error-notice/index.js":
/*!**************************************************************!*\
  !*** ./redux-templates/src/components/error-notice/index.js ***!
  \**************************************************************/
/*! exports provided: ErrorNotice, default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "ErrorNotice", function() { return ErrorNotice; });
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/compose */ "@wordpress/compose");
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_compose__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./style.scss */ "./redux-templates/src/components/error-notice/style.scss");
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_style_scss__WEBPACK_IMPORTED_MODULE_4__);





function ErrorNotice(props) {
  const discardAllErrorMessages = props.discardAllErrorMessages,
        errorMessages = props.errorMessages;
  return wp.element.createElement("div", {
    className: "redux-templates-error-notice"
  }, wp.element.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__["Notice"], {
    status: "error",
    onRemove: discardAllErrorMessages
  }, wp.element.createElement("p", null, errorMessages.join(', '))));
}
/* harmony default export */ __webpack_exports__["default"] = (Object(_wordpress_compose__WEBPACK_IMPORTED_MODULE_1__["compose"])([Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_2__["withDispatch"])(dispatch => {
  const _dispatch = dispatch('redux-templates/sectionslist'),
        discardAllErrorMessages = _dispatch.discardAllErrorMessages;

  return {
    discardAllErrorMessages
  };
})])(ErrorNotice));

/***/ }),

/***/ "./redux-templates/src/components/error-notice/style.scss":
/*!****************************************************************!*\
  !*** ./redux-templates/src/components/error-notice/style.scss ***!
  \****************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../node_modules/css-loader/dist/cjs.js!../../../../node_modules/sass-loader/dist/cjs.js!./style.scss */ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/components/error-notice/style.scss");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./redux-templates/src/components/fab-wrapper/config.js":
/*!**************************************************************!*\
  !*** ./redux-templates/src/components/fab-wrapper/config.js ***!
  \**************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony default export */ __webpack_exports__["default"] = ({
  position: {
    bottom: 0,
    right: 0
  },
  event: 'click',
  mainButtonStyles: {
    backgroundColor: '#24B0A6',
    fill: '#ffffff',
    transform: 'none',
    transition: 'none',
    transformOrigin: 'none'
  },
  alwaysShowTitle: false,
  actionButtonStyles: {
    backgroundColor: '#19837C'
  }
});

/***/ }),

/***/ "./redux-templates/src/components/fab-wrapper/index.js":
/*!*************************************************************!*\
  !*** ./redux-templates/src/components/fab-wrapper/index.js ***!
  \*************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return FabWrapper; });
/* harmony import */ var react_tiny_fab__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react-tiny-fab */ "./node_modules/react-tiny-fab/dist/fab.esm.js");
/* harmony import */ var _config__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./config */ "./redux-templates/src/components/fab-wrapper/config.js");
/* harmony import */ var _styles_scss__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./styles.scss */ "./redux-templates/src/components/fab-wrapper/styles.scss");
/* harmony import */ var _styles_scss__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_styles_scss__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _redux_templates_icons__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ~redux-templates/icons */ "./redux-templates/src/icons/index.js");
/* harmony import */ var _redux_templates_modal_manager__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ~redux-templates/modal-manager */ "./redux-templates/src/modal-manager/index.js");
/* harmony import */ var _redux_templates_modal_feedback__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ~redux-templates/modal-feedback */ "./redux-templates/src/modal-feedback/index.js");







const schema = {
  type: 'object',
  properties: {
    comment: {
      type: 'string'
    },
    agreeToContactFurther: {
      type: 'boolean',
      title: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__["__"])('Yes, I give Redux permission to contact me for any follow up questions.', redux_templates.i18n)
    }
  }
};
const uiSchema = {
  'comment': {
    'ui:widget': 'textarea',
    'ui:options': {
      label: false
    }
  }
};
function FabWrapper() {
  const mainButtonStyles = _config__WEBPACK_IMPORTED_MODULE_1__["default"].mainButtonStyles,
        actionButtonStyles = _config__WEBPACK_IMPORTED_MODULE_1__["default"].actionButtonStyles,
        position = _config__WEBPACK_IMPORTED_MODULE_1__["default"].position,
        event = _config__WEBPACK_IMPORTED_MODULE_1__["default"].event,
        alwaysShowTitle = _config__WEBPACK_IMPORTED_MODULE_1__["default"].alwaysShowTitle;
  return wp.element.createElement(react_tiny_fab__WEBPACK_IMPORTED_MODULE_0__["Fab"], {
    mainButtonStyles: mainButtonStyles,
    position: position,
    icon: _redux_templates_icons__WEBPACK_IMPORTED_MODULE_4__["ReduxTemplatesIcon"](),
    event: event // onClick={testing}
    ,
    text: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__["__"])('See Quick Links', redux_templates.i18n)
  }, wp.element.createElement(react_tiny_fab__WEBPACK_IMPORTED_MODULE_0__["Action"], {
    style: actionButtonStyles,
    text: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__["__"])('Get Support', redux_templates.i18n),
    onClick: e => {
      window.open('https://wordpress.org/support/plugin/redux-framework/#new-topic-0', '_blank');
    }
  }, wp.element.createElement("i", {
    className: "far fa-question-circle "
  })), wp.element.createElement(react_tiny_fab__WEBPACK_IMPORTED_MODULE_0__["Action"], {
    style: actionButtonStyles,
    text: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__["__"])('Join our Community', redux_templates.i18n),
    onClick: e => {
      window.open('https://www.facebook.com/groups/reduxframework', '_blank');
    }
  }, wp.element.createElement("i", {
    className: "fa fa-comments"
  })), redux_templates.mokama === '1' && wp.element.createElement(react_tiny_fab__WEBPACK_IMPORTED_MODULE_0__["Action"], {
    style: actionButtonStyles,
    text: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__["__"])('Visit our Website', redux_templates.i18n),
    onClick: e => {
      window.open(redux_templates.u + 'tinyfab', '_blank');
    }
  }, wp.element.createElement("i", {
    className: "fas fa-external-link-alt"
  })), redux_templates.mokama !== '1' && wp.element.createElement(react_tiny_fab__WEBPACK_IMPORTED_MODULE_0__["Action"], {
    style: {
      backgroundColor: '#00a7e5'
    },
    text: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__["__"])('Upgrade to Redux Pro', redux_templates.i18n),
    onClick: e => {
      window.open(redux_templates.u + 'help_bubble', '_blank');
    }
  }, wp.element.createElement("i", {
    className: "fa fa-star"
  })));
}

/***/ }),

/***/ "./redux-templates/src/components/fab-wrapper/styles.scss":
/*!****************************************************************!*\
  !*** ./redux-templates/src/components/fab-wrapper/styles.scss ***!
  \****************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../node_modules/css-loader/dist/cjs.js!../../../../node_modules/sass-loader/dist/cjs.js!./styles.scss */ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/components/fab-wrapper/styles.scss");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./redux-templates/src/components/multiple-item/index.js":
/*!***************************************************************!*\
  !*** ./redux-templates/src/components/multiple-item/index.js ***!
  \***************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _button_group__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../button-group */ "./redux-templates/src/components/button-group/index.js");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _redux_templates_stores_dependencyHelper__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ~redux-templates/stores/dependencyHelper */ "./redux-templates/src/stores/dependencyHelper.js");
/* harmony import */ var _redux_templates_components_safe_image_load__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ~redux-templates/components/safe-image-load */ "./redux-templates/src/components/safe-image-load/index.js");
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./style.scss */ "./redux-templates/src/components/multiple-item/style.scss");
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_style_scss__WEBPACK_IMPORTED_MODULE_4__);

const __ = wp.i18n.__;





const MultipleItem = props => {
  const data = props.data,
        onSelectCollection = props.onSelectCollection;
  const pages = data.pages,
        homepageData = data.homepageData,
        ID = data.ID,
        name = data.name;

  const _ref = homepageData || {},
        image = _ref.image;

  return wp.element.createElement("div", {
    className: "redux-templates-multiple-template-box"
  }, wp.element.createElement("div", {
    className: "multiple-template-view",
    onClick: () => onSelectCollection(ID)
  }, wp.element.createElement("div", {
    className: "redux-templates-box-shadow"
  }, wp.element.createElement("div", {
    className: "redux-templates-default-template-image"
  }, wp.element.createElement(_redux_templates_components_safe_image_load__WEBPACK_IMPORTED_MODULE_3__["default"], {
    url: image,
    alt: __('Default Template', redux_templates.i18n)
  }), Object(_redux_templates_stores_dependencyHelper__WEBPACK_IMPORTED_MODULE_2__["requiresPro"])(data) && wp.element.createElement("span", {
    className: "redux-templates-pro-badge"
  }, __('Premium', redux_templates.i18n)), !Object(_redux_templates_stores_dependencyHelper__WEBPACK_IMPORTED_MODULE_2__["requiresPro"])(data) && Object(_redux_templates_stores_dependencyHelper__WEBPACK_IMPORTED_MODULE_2__["requiresInstall"])(data) && wp.element.createElement("div", {
    className: "redux-templates-missing-badge"
  }, wp.element.createElement("i", {
    className: "fas fa-exclamation-triangle"
  }))), wp.element.createElement("div", {
    className: "redux-templates-button-overlay"
  }, Object(_redux_templates_stores_dependencyHelper__WEBPACK_IMPORTED_MODULE_2__["requiresPro"])(data) && wp.element.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__["Tooltip"], {
    text: __('Premium Requirements', redux_templates.i18n),
    position: "bottom",
    key: data.source + data.source_id
  }, wp.element.createElement("span", {
    className: "redux-templates-pro-badge"
  }, __('Premium', redux_templates.i18n))), !Object(_redux_templates_stores_dependencyHelper__WEBPACK_IMPORTED_MODULE_2__["requiresPro"])(data) && Object(_redux_templates_stores_dependencyHelper__WEBPACK_IMPORTED_MODULE_2__["requiresInstall"])(data) && wp.element.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__["Tooltip"], {
    text: __('Not Installed', redux_templates.i18n),
    position: "bottom",
    key: data.source + data.source_id
  }, wp.element.createElement("div", {
    className: "redux-templates-missing-badge"
  }, wp.element.createElement("i", {
    className: "fas fa-exclamation-triangle"
  }))), wp.element.createElement("div", {
    className: "redux-templates-import-button-group"
  }, wp.element.createElement("div", {
    className: "action-buttons"
  }, wp.element.createElement("a", {
    className: "redux-templates-button download-button"
  }, __('View Templates', redux_templates.i18n)))))), wp.element.createElement("div", {
    className: "redux-templates-tmpl-info"
  }, wp.element.createElement("h5", {
    className: "redux-templates-tmpl-title",
    dangerouslySetInnerHTML: {
      __html: name
    }
  }), wp.element.createElement("span", {
    className: "redux-templates-temp-count"
  }, pages ? pages.length : 0, " ", __('Templates', redux_templates.i18n)))));
};

/* harmony default export */ __webpack_exports__["default"] = (MultipleItem);

/***/ }),

/***/ "./redux-templates/src/components/multiple-item/style.scss":
/*!*****************************************************************!*\
  !*** ./redux-templates/src/components/multiple-item/style.scss ***!
  \*****************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../node_modules/css-loader/dist/cjs.js!../../../../node_modules/sass-loader/dist/cjs.js!./style.scss */ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/components/multiple-item/style.scss");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./redux-templates/src/components/pagination/index.js":
/*!************************************************************!*\
  !*** ./redux-templates/src/components/pagination/index.js ***!
  \************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./style.scss */ "./redux-templates/src/components/pagination/style.scss");
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_style_scss__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _stores_helper__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../stores/helper */ "./redux-templates/src/stores/helper.js");
function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }

function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }

function _iterableToArrayLimit(arr, i) { if (typeof Symbol === "undefined" || !(Symbol.iterator in Object(arr))) return; var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }

function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }

const _wp$element = wp.element,
      useState = _wp$element.useState,
      useEffect = _wp$element.useEffect,
      Fragment = _wp$element.Fragment;
const compose = wp.compose.compose;
const _wp$data = wp.data,
      withDispatch = _wp$data.withDispatch,
      withSelect = _wp$data.withSelect;
const __ = wp.i18n.__;



function Pagination(props) {
  const currentPage = props.currentPage,
        pageData = props.pageData,
        columns = props.columns;
  const setCurrentPage = props.setCurrentPage;

  const _useState = useState(1),
        _useState2 = _slicedToArray(_useState, 2),
        totalPages = _useState2[0],
        setTotalPages = _useState2[1];

  const _useState3 = useState('tablenav-pages-navspan button'),
        _useState4 = _slicedToArray(_useState3, 2),
        firstButtonClass = _useState4[0],
        setFirstButtonClass = _useState4[1];

  const _useState5 = useState('tablenav-pages-navspan button'),
        _useState6 = _slicedToArray(_useState5, 2),
        prevButtonClass = _useState6[0],
        setPrevButtonClass = _useState6[1];

  const _useState7 = useState('tablenav-pages-navspan button'),
        _useState8 = _slicedToArray(_useState7, 2),
        nextButtonClass = _useState8[0],
        setNextButtonClass = _useState8[1];

  const _useState9 = useState('tablenav-pages-navspan button'),
        _useState10 = _slicedToArray(_useState9, 2),
        lastButtonClass = _useState10[0],
        setLastButtonClass = _useState10[1];

  useEffect(() => {
    const enabledClassname = 'tablenav-pages-navspan button ';
    const disabledClassname = 'tablenav-pages-navspan button disabled';
    setFirstButtonClass(currentPage === 0 ? disabledClassname : enabledClassname);
    setPrevButtonClass(currentPage === 0 ? disabledClassname : enabledClassname);
    setNextButtonClass(currentPage === totalPages - 1 ? disabledClassname : enabledClassname);
    setLastButtonClass(currentPage === totalPages - 1 ? disabledClassname : enabledClassname);
  }, [currentPage, totalPages]);
  useEffect(() => {
    let colStr = columns === '' ? 'medium' : columns;
    setTotalPages(Math.ceil(pageData.length / _stores_helper__WEBPACK_IMPORTED_MODULE_1__["pageSizeMap"][colStr]));
  }, [pageData]);

  const gotoPage = (pageNum, className) => {
    if (className.indexOf('disabled') > 0) return;
    document.getElementById('modalContent').scrollTop = 0;
    setCurrentPage(pageNum);
  };

  return wp.element.createElement(Fragment, null, totalPages > 0 && wp.element.createElement("div", {
    className: "tablenav-pages"
  }, wp.element.createElement("span", {
    className: "displaying-num"
  }, pageData.length, " items"), wp.element.createElement("span", {
    className: "pagination-links"
  }, wp.element.createElement("span", {
    className: firstButtonClass,
    "aria-hidden": "true",
    onClick: () => gotoPage(0, firstButtonClass)
  }, "\xAB"), wp.element.createElement("span", {
    className: prevButtonClass,
    "aria-hidden": "true",
    onClick: () => gotoPage(currentPage - 1, prevButtonClass)
  }, "\u2039"), wp.element.createElement("span", {
    className: "screen-reader-text"
  }, __('Current Page', redux_templates.i18n)), wp.element.createElement("span", {
    id: "table-paging",
    className: "paging-input"
  }, wp.element.createElement("span", {
    className: "tablenav-paging-text"
  }, currentPage + 1, " of ", wp.element.createElement("span", {
    className: "total-pages"
  }, totalPages))), wp.element.createElement("span", {
    className: nextButtonClass,
    "aria-hidden": "true",
    onClick: () => gotoPage(currentPage + 1, nextButtonClass)
  }, "\u203A"), wp.element.createElement("span", {
    className: lastButtonClass,
    "aria-hidden": "true",
    onClick: () => gotoPage(totalPages - 1, lastButtonClass)
  }, "\xBB"))));
}

/* harmony default export */ __webpack_exports__["default"] = (compose([withDispatch(dispatch => {
  const _dispatch = dispatch('redux-templates/sectionslist'),
        setCurrentPage = _dispatch.setCurrentPage;

  return {
    setCurrentPage
  };
}), withSelect(select => {
  const _select = select('redux-templates/sectionslist'),
        getCurrentPage = _select.getCurrentPage,
        getPageData = _select.getPageData,
        getColumns = _select.getColumns;

  return {
    currentPage: getCurrentPage(),
    pageData: getPageData(),
    columns: getColumns()
  };
})])(Pagination));

/***/ }),

/***/ "./redux-templates/src/components/pagination/style.scss":
/*!**************************************************************!*\
  !*** ./redux-templates/src/components/pagination/style.scss ***!
  \**************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../node_modules/css-loader/dist/cjs.js!../../../../node_modules/sass-loader/dist/cjs.js!./style.scss */ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/components/pagination/style.scss");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./redux-templates/src/components/preview-import-button/index.js":
/*!***********************************************************************!*\
  !*** ./redux-templates/src/components/preview-import-button/index.js ***!
  \***********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _redux_templates_stores_actionHelper__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ~redux-templates/stores/actionHelper */ "./redux-templates/src/stores/actionHelper.js");
/* harmony import */ var _redux_templates_challenge_tooltip_ChallengeDot__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ~redux-templates/challenge/tooltip/ChallengeDot */ "./redux-templates/src/challenge/tooltip/ChallengeDot.js");
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./style.scss */ "./redux-templates/src/components/preview-import-button/style.scss");
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_style_scss__WEBPACK_IMPORTED_MODULE_3__);

const compose = wp.compose.compose;
const _wp$data = wp.data,
      withDispatch = _wp$data.withDispatch,
      withSelect = _wp$data.withSelect;




function PreviewImportButton(props) {
  const data = props.data,
        index = props.index,
        pageData = props.pageData;
  const setImportingTemplate = props.setImportingTemplate,
        tourActiveButtonGroup = props.tourActiveButtonGroup;
  let spinner = null;

  const triggerImportTemplate = data => {
    if (spinner === null) {
      spinner = data.ID;
      setImportingTemplate(data);
    }
  };

  return wp.element.createElement("div", {
    className: "action-buttons"
  }, pageData[index] && pageData[index]['source'] !== 'wp_block_patterns' && wp.element.createElement("a", {
    className: "redux-templates-button preview-button",
    target: "_blank",
    onClick: () => Object(_redux_templates_stores_actionHelper__WEBPACK_IMPORTED_MODULE_1__["openSitePreviewModal"])(index, pageData)
  }, wp.element.createElement("i", {
    className: "fa fa-share"
  }), " ", Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])('Preview', redux_templates.i18n)), wp.element.createElement("a", {
    className: "redux-templates-button download-button",
    onClick: () => triggerImportTemplate(data)
  }, wp.element.createElement("i", {
    className: "fas fa-download"
  }), Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])('Import', redux_templates.i18n)), tourActiveButtonGroup && tourActiveButtonGroup.ID === pageData[index].ID && wp.element.createElement(_redux_templates_challenge_tooltip_ChallengeDot__WEBPACK_IMPORTED_MODULE_2__["default"], {
    step: 4
  }));
}

/* harmony default export */ __webpack_exports__["default"] = (compose([withDispatch(dispatch => {
  const _dispatch = dispatch('redux-templates/sectionslist'),
        setImportingTemplate = _dispatch.setImportingTemplate;

  return {
    setImportingTemplate
  };
}), withSelect((select, props) => {
  const _select = select('redux-templates/sectionslist'),
        getTourActiveButtonGroup = _select.getTourActiveButtonGroup;

  return {
    tourActiveButtonGroup: getTourActiveButtonGroup()
  };
})])(PreviewImportButton));

/***/ }),

/***/ "./redux-templates/src/components/preview-import-button/style.scss":
/*!*************************************************************************!*\
  !*** ./redux-templates/src/components/preview-import-button/style.scss ***!
  \*************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../node_modules/css-loader/dist/cjs.js!../../../../node_modules/sass-loader/dist/cjs.js!./style.scss */ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/components/preview-import-button/style.scss");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./redux-templates/src/components/safe-image-load/index.js":
/*!*****************************************************************!*\
  !*** ./redux-templates/src/components/safe-image-load/index.js ***!
  \*****************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return SafeImageLoad; });
/* harmony import */ var react_load_image__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react-load-image */ "./node_modules/react-load-image/lib/index.js");
/* harmony import */ var react_load_image__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react_load_image__WEBPACK_IMPORTED_MODULE_0__);
const Spinner = wp.components.Spinner;

const placeholderImage = redux_templates.plugin + 'assets/img/reduxtemplates-medium.jpg';
const spinnerStyle = {
  height: 120,
  display: 'flex',
  alignItems: 'top',
  paddingTop: '40px',
  justifyContent: 'center',
  background: '#fff'
};
function SafeImageLoad({
  url,
  alt,
  className
}) {
  return wp.element.createElement(react_load_image__WEBPACK_IMPORTED_MODULE_0___default.a, {
    src: url
  }, wp.element.createElement("img", {
    alt: alt,
    className: className
  }), wp.element.createElement("img", {
    src: placeholderImage,
    alt: alt,
    className: className
  }), wp.element.createElement("div", {
    style: spinnerStyle
  }, wp.element.createElement(Spinner, null)));
}

/***/ }),

/***/ "./redux-templates/src/components/single-item/index.js":
/*!*************************************************************!*\
  !*** ./redux-templates/src/components/single-item/index.js ***!
  \*************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _button_group__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../button-group */ "./redux-templates/src/components/button-group/index.js");
/* harmony import */ var _redux_templates_components_safe_image_load__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ~redux-templates/components/safe-image-load */ "./redux-templates/src/components/safe-image-load/index.js");
/* harmony import */ var _background_image__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../background-image */ "./redux-templates/src/components/background-image/index.js");
/* harmony import */ var _redux_templates_stores_dependencyHelper__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ~redux-templates/stores/dependencyHelper */ "./redux-templates/src/stores/dependencyHelper.js");
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./style.scss */ "./redux-templates/src/components/single-item/style.scss");
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_style_scss__WEBPACK_IMPORTED_MODULE_5__);
function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }

function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }

function _iterableToArrayLimit(arr, i) { if (typeof Symbol === "undefined" || !(Symbol.iterator in Object(arr))) return; var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }

function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }


const __ = wp.i18n.__;
const withSelect = wp.data.withSelect;
const _wp$element = wp.element,
      useState = _wp$element.useState,
      useEffect = _wp$element.useEffect;






function SingleItem(props) {
  // Decoupling props
  const pageData = props.pageData,
        tourActiveButtonGroup = props.tourActiveButtonGroup,
        index = props.index;

  const _useState = useState(null),
        _useState2 = _slicedToArray(_useState, 2),
        data = _useState2[0],
        setData = _useState2[1]; // const {ID, image, url, pro, source, requirements} = data;


  const _useState3 = useState('redux-templates-single-item-inner redux-templates-item-wrapper '),
        _useState4 = _slicedToArray(_useState3, 2),
        innerClassname = _useState4[0],
        setInnerClassname = _useState4[1];

  useEffect(() => {
    if (pageData) setData(pageData[index]);
  }, [index, pageData]);
  useEffect(() => {
    setInnerClassname(pageData && pageData[index] && tourActiveButtonGroup && tourActiveButtonGroup.ID === pageData[index].ID ? 'redux-templates-single-item-inner redux-templates-item-wrapper focused' : 'redux-templates-single-item-inner redux-templates-item-wrapper');
  }, [tourActiveButtonGroup, pageData, index]);
  if (!data) return null;
  return wp.element.createElement("div", {
    className: "redux-templates-single-section-item"
  }, wp.element.createElement("div", {
    className: innerClassname
  }, wp.element.createElement("div", {
    className: "redux-templates-default-template-image"
  }, data.source !== 'wp_block_patterns' && wp.element.createElement(_redux_templates_components_safe_image_load__WEBPACK_IMPORTED_MODULE_2__["default"], {
    url: data.image
  }), data.source === 'wp_block_patterns' && wp.element.createElement(_background_image__WEBPACK_IMPORTED_MODULE_3__["default"], {
    data: data
  }), Object(_redux_templates_stores_dependencyHelper__WEBPACK_IMPORTED_MODULE_4__["requiresPro"])(data) && wp.element.createElement("span", {
    className: "redux-templates-pro-badge"
  }, __('Premium', redux_templates.i18n)), !Object(_redux_templates_stores_dependencyHelper__WEBPACK_IMPORTED_MODULE_4__["requiresPro"])(data) && Object(_redux_templates_stores_dependencyHelper__WEBPACK_IMPORTED_MODULE_4__["requiresInstall"])(data) && wp.element.createElement("span", {
    className: "redux-templates-missing-badge"
  }, wp.element.createElement("i", {
    className: "fas fa-exclamation-triangle"
  })), wp.element.createElement("div", {
    className: "redux-templates-tmpl-title"
  }, data.name)), wp.element.createElement("div", {
    className: "redux-templates-button-overlay"
  }, Object(_redux_templates_stores_dependencyHelper__WEBPACK_IMPORTED_MODULE_4__["requiresPro"])(data) && wp.element.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__["Tooltip"], {
    text: __('Premium Requirements', redux_templates.i18n),
    position: "bottom",
    key: data.source + data.source_id
  }, wp.element.createElement("div", {
    className: "redux-templates-pro-badge"
  }, __('Premium', redux_templates.i18n))), !Object(_redux_templates_stores_dependencyHelper__WEBPACK_IMPORTED_MODULE_4__["requiresPro"])(data) && Object(_redux_templates_stores_dependencyHelper__WEBPACK_IMPORTED_MODULE_4__["requiresInstall"])(data) && wp.element.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__["Tooltip"], {
    text: __('Not Installed', redux_templates.i18n),
    position: "bottom",
    key: data.source + data.source_id
  }, wp.element.createElement("div", {
    className: "redux-templates-missing-badge"
  }, wp.element.createElement("i", {
    className: "fas fa-exclamation-triangle"
  }))), wp.element.createElement(_button_group__WEBPACK_IMPORTED_MODULE_1__["default"], {
    index: index,
    showDependencyBlock: true,
    data: data,
    pageData: pageData
  }))));
}

/* harmony default export */ __webpack_exports__["default"] = (withSelect((select, props) => {
  const _select = select('redux-templates/sectionslist'),
        getTourActiveButtonGroup = _select.getTourActiveButtonGroup,
        getPageData = _select.getPageData;

  return {
    pageData: getPageData(),
    tourActiveButtonGroup: getTourActiveButtonGroup()
  };
})(SingleItem));

/***/ }),

/***/ "./redux-templates/src/components/single-item/style.scss":
/*!***************************************************************!*\
  !*** ./redux-templates/src/components/single-item/style.scss ***!
  \***************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../node_modules/css-loader/dist/cjs.js!../../../../node_modules/sass-loader/dist/cjs.js!./style.scss */ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/components/single-item/style.scss");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./redux-templates/src/components/tab-header/index.js":
/*!************************************************************!*\
  !*** ./redux-templates/src/components/tab-header/index.js ***!
  \************************************************************/
/*! exports provided: TabHeader, default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "TabHeader", function() { return TabHeader; });
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/compose */ "@wordpress/compose");
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_compose__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _modal_manager__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../modal-manager */ "./redux-templates/src/modal-manager/index.js");
/* harmony import */ var _redux_templates_challenge_tooltip_ChallengeDot__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ~redux-templates/challenge/tooltip/ChallengeDot */ "./redux-templates/src/challenge/tooltip/ChallengeDot.js");





function TabHeader(props) {
  const activeItemType = props.activeItemType,
        searchContext = props.searchContext,
        activeCollection = props.activeCollection,
        isChallengeOpen = props.isChallengeOpen;
  const setActiveItemType = props.setActiveItemType,
        setSearchContext = props.setSearchContext,
        setChallengeOpen = props.setChallengeOpen,
        clearSearch = props.clearSearch;

  const isActive = itemType => {
    return activeItemType === itemType ? 'active' : '';
  };

  const onSearchContextUpdate = e => {
    if (activeItemType !== 'saved') setSearchContext(e.target.value);
  };

  const changeTab = tabName => {
    if (document.getElementById('modalContent')) document.getElementById('modalContent').scrollTop = 0;
    setActiveItemType(tabName);
  };

  const closeModal = () => {
    if (isChallengeOpen === false) {
      _modal_manager__WEBPACK_IMPORTED_MODULE_3__["ModalManager"].close();
    }
  };

  return wp.element.createElement("div", {
    className: "redux-templates-builder-modal-header"
  }, wp.element.createElement("div", {
    className: "template-search-box"
  }, (activeItemType !== 'collection' || activeCollection === null) && activeItemType !== 'saved' && wp.element.createElement("div", null, wp.element.createElement("input", {
    type: "text",
    placeholder: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])('Search for a template', redux_templates.i18n),
    className: "form-control",
    value: searchContext,
    onChange: onSearchContextUpdate
  }), wp.element.createElement(_redux_templates_challenge_tooltip_ChallengeDot__WEBPACK_IMPORTED_MODULE_4__["default"], {
    step: 1
  }), wp.element.createElement("i", {
    className: "fas fa-search"
  }))), wp.element.createElement("div", {
    className: "redux-templates-template-list-header",
    "data-tut": "tour__navigation"
  }, wp.element.createElement("button", {
    className: isActive('section'),
    onClick: e => changeTab('section')
  }, " ", Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])('Sections', redux_templates.i18n), " "), wp.element.createElement("button", {
    className: isActive('page'),
    onClick: e => changeTab('page')
  }, " ", Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])('Templates', redux_templates.i18n), " "), wp.element.createElement("button", {
    className: isActive('collection'),
    onClick: e => changeTab('collection')
  }, " ", Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])('Template Kits', redux_templates.i18n), " "), wp.element.createElement("button", {
    className: isActive('saved'),
    onClick: e => changeTab('saved')
  }, " ", Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])('Saved', redux_templates.i18n), " "), wp.element.createElement(_redux_templates_challenge_tooltip_ChallengeDot__WEBPACK_IMPORTED_MODULE_4__["default"], {
    step: 0
  }), wp.element.createElement("button", {
    className: "redux-templates-builder-close-modal",
    onClick: closeModal
  }, wp.element.createElement("svg", {
    width: "24",
    height: "24",
    xmlns: "http://www.w3.org/2000/svg",
    viewBox: "0 0 24 24",
    role: "img",
    "aria-hidden": "true",
    focusable: "false"
  }, wp.element.createElement("path", {
    d: "M13 11.9l3.3-3.4-1.1-1-3.2 3.3-3.2-3.3-1.1 1 3.3 3.4-3.5 3.6 1 1L12 13l3.5 3.5 1-1z"
  })))));
}
/* harmony default export */ __webpack_exports__["default"] = (Object(_wordpress_compose__WEBPACK_IMPORTED_MODULE_1__["compose"])([Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_2__["withDispatch"])(dispatch => {
  const _dispatch = dispatch('redux-templates/sectionslist'),
        setActiveItemType = _dispatch.setActiveItemType,
        setSearchContext = _dispatch.setSearchContext,
        clearSearch = _dispatch.clearSearch;

  return {
    setActiveItemType,
    setSearchContext,
    clearSearch
  };
}), Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_2__["withSelect"])((select, props) => {
  const _select = select('redux-templates/sectionslist'),
        getActiveItemType = _select.getActiveItemType,
        getSearchContext = _select.getSearchContext,
        getActiveCollection = _select.getActiveCollection,
        getChallengeOpen = _select.getChallengeOpen;

  return {
    activeItemType: getActiveItemType(),
    searchContext: getSearchContext(),
    activeCollection: getActiveCollection(),
    isChallengeOpen: getChallengeOpen()
  };
})])(TabHeader));

/***/ }),

/***/ "./redux-templates/src/components/template-change/index.js":
/*!*****************************************************************!*\
  !*** ./redux-templates/src/components/template-change/index.js ***!
  \*****************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
const compose = wp.compose.compose;
const withSelect = wp.data.withSelect;


function TemplateChange(props) {
  const template = props.template;
  Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["useEffect"])(() => {
    if (template.includes('redux-templates_')) {
      document.body.className += ' redux-template';
    } else {
      document.querySelector('body').classList.remove('redux-template');
    }
  }, [template]);
  return wp.element.createElement("div", null);
}

/* harmony default export */ __webpack_exports__["default"] = (compose([withSelect(select => {
  const _select = select('core/editor'),
        getEditedPostAttribute = _select.getEditedPostAttribute;

  return {
    template: getEditedPostAttribute('template')
  };
})])(TemplateChange));

/***/ }),

/***/ "./redux-templates/src/components/template-list-subheader/images/view-few.svg":
/*!************************************************************************************!*\
  !*** ./redux-templates/src/components/template-list-subheader/images/view-few.svg ***!
  \************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
function _extends() { _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; return _extends.apply(this, arguments); }



var _ref = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  d: "M38 12H12v26h26V12z"
});

var SvgViewFew = function SvgViewFew(props) {
  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("svg", _extends({
    viewBox: "0 0 50 50",
    xmlns: "http://www.w3.org/2000/svg"
  }, props), _ref);
};

/* harmony default export */ __webpack_exports__["default"] = (SvgViewFew);

/***/ }),

/***/ "./redux-templates/src/components/template-list-subheader/images/view-many.svg":
/*!*************************************************************************************!*\
  !*** ./redux-templates/src/components/template-list-subheader/images/view-many.svg ***!
  \*************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
function _extends() { _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; return _extends.apply(this, arguments); }



var _ref = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  d: "M12.5 12.5H0V0h12.5v12.5zM31.2 0H18.8v12.5h12.5V0zM50 0H37.5v12.5H50V0zM12.5 18.8H0v12.5h12.5V18.8zm18.7 0H18.8v12.5h12.5V18.8zm18.8 0H37.5v12.5H50V18.8zM12.5 37.5H0V50h12.5V37.5zm18.7 0H18.8V50h12.5V37.5zm18.8 0H37.5V50H50V37.5z"
});

var SvgViewMany = function SvgViewMany(props) {
  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("svg", _extends({
    viewBox: "0 0 50 50",
    xmlns: "http://www.w3.org/2000/svg"
  }, props), _ref);
};

/* harmony default export */ __webpack_exports__["default"] = (SvgViewMany);

/***/ }),

/***/ "./redux-templates/src/components/template-list-subheader/images/view-normal.svg":
/*!***************************************************************************************!*\
  !*** ./redux-templates/src/components/template-list-subheader/images/view-normal.svg ***!
  \***************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
function _extends() { _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; return _extends.apply(this, arguments); }



var _ref = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  d: "M21.1 5.3H5.3v15.8h15.8V5.3zm23.6 0H28.9v15.8h15.8V5.3zM21.1 28.9H5.3v15.8h15.8V28.9zm23.6 0H28.9v15.8h15.8V28.9z"
});

var SvgViewNormal = function SvgViewNormal(props) {
  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("svg", _extends({
    viewBox: "0 0 50 50",
    xmlns: "http://www.w3.org/2000/svg"
  }, props), _ref);
};

/* harmony default export */ __webpack_exports__["default"] = (SvgViewNormal);

/***/ }),

/***/ "./redux-templates/src/components/template-list-subheader/index.js":
/*!*************************************************************************!*\
  !*** ./redux-templates/src/components/template-list-subheader/index.js ***!
  \*************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _redux_templates_challenge_tooltip_ChallengeDot__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ~redux-templates/challenge/tooltip/ChallengeDot */ "./redux-templates/src/challenge/tooltip/ChallengeDot.js");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _images_view_few_svg__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./images/view-few.svg */ "./redux-templates/src/components/template-list-subheader/images/view-few.svg");
/* harmony import */ var _images_view_many_svg__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./images/view-many.svg */ "./redux-templates/src/components/template-list-subheader/images/view-many.svg");
/* harmony import */ var _images_view_normal_svg__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./images/view-normal.svg */ "./redux-templates/src/components/template-list-subheader/images/view-normal.svg");
/* harmony import */ var _redux_templates_stores_actionHelper__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ~redux-templates/stores/actionHelper */ "./redux-templates/src/stores/actionHelper.js");
/* harmony import */ var _redux_templates_icons__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ~redux-templates/icons */ "./redux-templates/src/icons/index.js");
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./style.scss */ "./redux-templates/src/components/template-list-subheader/style.scss");
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(_style_scss__WEBPACK_IMPORTED_MODULE_7__);
function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }

function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }

function _iterableToArrayLimit(arr, i) { if (typeof Symbol === "undefined" || !(Symbol.iterator in Object(arr))) return; var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }

function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }

const _wp$i18n = wp.i18n,
      __ = _wp$i18n.__,
      sprintf = _wp$i18n.sprintf;
const compose = wp.compose.compose;
const _wp$data = wp.data,
      withDispatch = _wp$data.withDispatch,
      withSelect = _wp$data.withSelect;
const _wp$element = wp.element,
      useState = _wp$element.useState,
      useEffect = _wp$element.useEffect;









function TemplateListSubHeader(props) {
  const itemType = props.itemType,
        sortBy = props.sortBy,
        activeCollection = props.activeCollection,
        challengePassed = props.challengePassed,
        pageData = props.pageData,
        columns = props.columns,
        loading = props.loading;
  const setSortBy = props.setSortBy,
        setColumns = props.setColumns,
        setChallengeOpen = props.setChallengeOpen,
        setChallengeListExpanded = props.setChallengeListExpanded;

  const _useState = useState('far fa-question-circle tour-icon'),
        _useState2 = _slicedToArray(_useState, 2),
        triggerTourClassname = _useState2[0],
        setTriggerTourClassname = _useState2[1];

  useEffect(() => {
    setTriggerTourClassname(challengePassed ? 'fas fa-trophy tour-icon' : 'fas fa-map-signs tour-icon');
  }, [challengePassed]);

  const itemTypeLabel = () => {
    if (itemType === 'section') return __('Sections', redux_templates.i18n);
    if (itemType === 'page') return __('Templates', redux_templates.i18n);
    if (itemType === 'collection' && activeCollection === null) return __('Template Kits', redux_templates.i18n);
    if (itemType === 'collection' && activeCollection !== null) return __('Sections', redux_templates.i18n);
  };

  const dataLength = pageData ? pageData.length : '';
  let pageTitle = '';

  if (loading === false && dataLength && dataLength !== 0) {
    pageTitle = wp.element.createElement("span", null, dataLength, " ", itemTypeLabel());
  }

  return wp.element.createElement("div", {
    className: "redux-templates-template-list-sub-header"
  }, wp.element.createElement("h4", null, pageTitle, wp.element.createElement(_redux_templates_challenge_tooltip_ChallengeDot__WEBPACK_IMPORTED_MODULE_0__["default"], {
    step: 3
  })), wp.element.createElement("div", {
    className: "redux-templates-template-filters"
  }, wp.element.createElement("div", {
    className: "trial_notice"
  }, !redux_templates.mokama && wp.element.createElement("div", {
    style: {
      verticalAlign: 'middle'
    }
  }, wp.element.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__["Notice"], {
    status: "info",
    isDismissible: false
  }, wp.element.createElement("strong", {
    style: {
      display: 'inline-block',
      marginRight: '10px',
      verticalAlign: 'middle'
    }
  }, redux_templates.left <= 0 && wp.element.createElement(React.Fragment, null, sprintf(__('Trial Ended: %d/%d Imported', redux_templates.i18n), 5 - redux_templates.left, 5)), redux_templates.left > 0 && wp.element.createElement(React.Fragment, null, sprintf(__('Trial: %d/%d Imports Remaining', redux_templates.i18n), redux_templates.left, 5))), wp.element.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__["Button"], {
    isPrimary: true,
    isSmall: true,
    icon: _redux_templates_icons__WEBPACK_IMPORTED_MODULE_6__["redux"],
    label: __('Upgrade to Redux Pro', redux_templates.i18n),
    onClick: () => window.open(redux_templates.u + 'subheader', '_blank')
  }, "Get Pro")))), wp.element.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__["Button"], {
    icon: wp.element.createElement("i", {
      className: triggerTourClassname
    }),
    label: __('Take the Redux Challenge', redux_templates.i18n),
    onClick: () => {
      setChallengeOpen(true);
      setChallengeListExpanded(true);
    }
  }), wp.element.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__["Button"], {
    icon: "image-rotate",
    label: __('Refresh Library', redux_templates.i18n),
    className: "refresh-library",
    onClick: _redux_templates_stores_actionHelper__WEBPACK_IMPORTED_MODULE_5__["reloadLibrary"]
  }), wp.element.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__["Button"], {
    icon: wp.element.createElement(_images_view_few_svg__WEBPACK_IMPORTED_MODULE_2__["default"], {
      width: "18",
      height: "18"
    }),
    className: columns === 'large' ? 'is-active' : '',
    label: __('Large preview', redux_templates.i18n),
    onClick: () => setColumns('large')
  }), wp.element.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__["Button"], {
    icon: wp.element.createElement(_images_view_normal_svg__WEBPACK_IMPORTED_MODULE_4__["default"], {
      width: "18",
      height: "18"
    }),
    className: columns === '' ? 'is-active' : '',
    label: __('Medium preview', redux_templates.i18n),
    onClick: e => setColumns('')
  }), wp.element.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__["Button"], {
    icon: wp.element.createElement(_images_view_many_svg__WEBPACK_IMPORTED_MODULE_3__["default"], {
      width: "18",
      height: "18"
    }),
    className: columns === 'small' ? 'is-active' : '',
    label: __('Small preview', redux_templates.i18n),
    onClick: e => setColumns('small')
  }), wp.element.createElement("div", {
    className: ""
  }, wp.element.createElement("select", {
    name: "sortBy",
    id: "sortBy",
    value: sortBy,
    onChange: e => setSortBy(e.target.value)
  }, wp.element.createElement("option", {
    value: "name"
  }, __('Name', redux_templates.i18n)), wp.element.createElement("option", {
    value: "updated"
  }, __('Updated', redux_templates.i18n))))));
}

/* harmony default export */ __webpack_exports__["default"] = (compose([withDispatch(dispatch => {
  const _dispatch = dispatch('redux-templates/sectionslist'),
        setLibrary = _dispatch.setLibrary,
        setActivePriceFilter = _dispatch.setActivePriceFilter,
        setActiveCollection = _dispatch.setActiveCollection,
        setSortBy = _dispatch.setSortBy,
        setColumns = _dispatch.setColumns,
        setChallengeOpen = _dispatch.setChallengeOpen,
        setChallengeListExpanded = _dispatch.setChallengeListExpanded;

  return {
    setLibrary,
    setActivePriceFilter,
    setActiveCollection,
    setSortBy,
    setColumns,
    setChallengeOpen,
    setChallengeListExpanded
  };
}), withSelect((select, props) => {
  const _select = select('redux-templates/sectionslist'),
        fetchLibraryFromAPI = _select.fetchLibraryFromAPI,
        getActiveItemType = _select.getActiveItemType,
        getColumns = _select.getColumns,
        getPageData = _select.getPageData,
        getActiveCollection = _select.getActiveCollection,
        getStatistics = _select.getStatistics,
        getSortBy = _select.getSortBy,
        getLoading = _select.getLoading,
        getChallengePassed = _select.getChallengePassed;

  return {
    fetchLibraryFromAPI,
    itemType: getActiveItemType(),
    pageData: getPageData(),
    columns: getColumns(),
    statistics: getStatistics(),
    sortBy: getSortBy(),
    activeCollection: getActiveCollection(),
    loading: getLoading(),
    challengePassed: getChallengePassed()
  };
})])(TemplateListSubHeader));

/***/ }),

/***/ "./redux-templates/src/components/template-list-subheader/style.scss":
/*!***************************************************************************!*\
  !*** ./redux-templates/src/components/template-list-subheader/style.scss ***!
  \***************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../node_modules/css-loader/dist/cjs.js!../../../../node_modules/sass-loader/dist/cjs.js!./style.scss */ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/components/template-list-subheader/style.scss");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./redux-templates/src/components/welcome-guide/images.js":
/*!****************************************************************!*\
  !*** ./redux-templates/src/components/welcome-guide/images.js ***!
  \****************************************************************/
/*! exports provided: GuideImage1, GuideImage2, GuideImage3 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "GuideImage1", function() { return GuideImage1; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "GuideImage2", function() { return GuideImage2; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "GuideImage3", function() { return GuideImage3; });
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__);
function _extends() { _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; return _extends.apply(this, arguments); }

/**
 * WordPress dependencies
 */

const GuideImage1 = props => wp.element.createElement(React.Fragment, null, wp.element.createElement("img", _extends({
  className: "redux-edit-post-welcome-guide__image redux-edit-post-welcome-guide__image__prm-np",
  alt: "",
  src: `${redux_templates.plugin}assets/img/welcome-guide-1.png`
}, props)));
const GuideImage2 = props => wp.element.createElement(React.Fragment, null, wp.element.createElement("img", _extends({
  className: "redux-edit-post-welcome-guide__image redux-edit-post-welcome-guide__image__prm-np",
  alt: "",
  src: `${redux_templates.plugin}assets/img/welcome-guide-2.gif`
}, props)));
const GuideImage3 = props => wp.element.createElement(React.Fragment, null, wp.element.createElement("img", _extends({
  className: "redux-edit-post-welcome-guide__image redux-edit-post-welcome-guide__image__prm-np",
  alt: "",
  src: `${redux_templates.plugin}assets/img/welcome-guide-3.png`
}, props)));

/***/ }),

/***/ "./redux-templates/src/components/welcome-guide/index.js":
/*!***************************************************************!*\
  !*** ./redux-templates/src/components/welcome-guide/index.js ***!
  \***************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return WelcomeGuide; });
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _images__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./images */ "./redux-templates/src/components/welcome-guide/images.js");
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./style.scss */ "./redux-templates/src/components/welcome-guide/style.scss");
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_style_scss__WEBPACK_IMPORTED_MODULE_4__);
function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }

function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }

function _iterableToArrayLimit(arr, i) { if (typeof Symbol === "undefined" || !(Symbol.iterator in Object(arr))) return; var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }

function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }

/**
 * WordPress dependencies
 */
const useState = wp.element.useState;


const _wp = wp,
      apiFetch = _wp.apiFetch;

/**
 * Internal dependencies
 */



function WelcomeGuide() {
  const _useState = useState(true),
        _useState2 = _slicedToArray(_useState, 2),
        isOpen = _useState2[0],
        setIsOpen = _useState2[1];

  const isActive = Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_0__["useSelect"])(select => select('core/edit-post').isFeatureActive('welcomeGuide'), []);

  if (isActive) {
    // Don't want to show during the WP guide.
    delete redux_templates.welcome; // In fact, we don't want to show it until the next page load!

    return null;
  }

  if (!isOpen || 'undefined' === typeof redux_templates.welcome) {
    return null;
  }

  return wp.element.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__["Guide"], {
    className: "redux-edit-post-welcome-guide",
    contentLabel: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])('Say hello to the Redux template library', redux_templates.i18n),
    onFinish: () => {
      setIsOpen(false);
      const options = {
        method: 'POST',
        path: 'redux/v1/templates/welcome/?uid=' + window.userSettings.uid
      };
      apiFetch(options).then(response => {}).catch(error => {});
    },
    pages: [{
      image: wp.element.createElement(_images__WEBPACK_IMPORTED_MODULE_3__["GuideImage1"], null),
      content: wp.element.createElement(React.Fragment, null, wp.element.createElement("h1", {
        className: "redux-edit-post-welcome-guide__heading"
      }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])('Try the Redux Template Library', redux_templates.i18n)), wp.element.createElement("h3", {
        className: "redux-edit-post-welcome-guide__text"
      }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])('Redux brings you over 1,000 importable templates and blocks that allow you to build Gutenberg powered pages and websites in minutes not days.', redux_templates.i18n)))
    }, {
      image: wp.element.createElement(_images__WEBPACK_IMPORTED_MODULE_3__["GuideImage2"], null),
      content: wp.element.createElement(React.Fragment, null, wp.element.createElement("h1", {
        className: "redux-edit-post-welcome-guide__heading"
      }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])('Using the Template Library', redux_templates.i18n)), wp.element.createElement("h3", {
        className: "redux-edit-post-welcome-guide__text"
      }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])('To use the template library click on the library button then pick your favourite template and import! Redux allows you to import beautiful Gutenberg pages in seconds.', redux_templates.i18n)))
    }, {
      image: wp.element.createElement(_images__WEBPACK_IMPORTED_MODULE_3__["GuideImage3"], null),
      content: wp.element.createElement(React.Fragment, null, wp.element.createElement("h1", {
        className: "redux-edit-post-welcome-guide__heading"
      }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])('Import 5 templates for free or go Pro!', redux_templates.i18n)), wp.element.createElement("h3", {
        className: "redux-edit-post-welcome-guide__text"
      }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])('Redux allows you 5 free imports or you can go Pro now and import unlimited templates for just $49/year (limited time only).', redux_templates.i18n), wp.element.createElement("br", null), wp.element.createElement("br", null), wp.element.createElement("center", null, wp.element.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__["ExternalLink"], {
        href: `${redux_templates.u}welcome-guide`
      }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])('Learn more at Redux.io', redux_templates.i18n)))))
    }]
  });
}

/***/ }),

/***/ "./redux-templates/src/components/welcome-guide/style.scss":
/*!*****************************************************************!*\
  !*** ./redux-templates/src/components/welcome-guide/style.scss ***!
  \*****************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../node_modules/css-loader/dist/cjs.js!../../../../node_modules/sass-loader/dist/cjs.js!./style.scss */ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/components/welcome-guide/style.scss");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./redux-templates/src/custom-css/editor.js":
/*!**************************************************!*\
  !*** ./redux-templates/src/custom-css/editor.js ***!
  \**************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/**
 * WordPress dependencies.
 */
const __ = wp.i18n.__;
const _wp$element = wp.element,
      Fragment = _wp$element.Fragment,
      useEffect = _wp$element.useEffect,
      useRef = _wp$element.useRef;

const CSSEditor = ({
  attributes,
  setAttributes,
  clientId
}) => {
  useEffect(() => {
    let classes = getClassName();

    if (attributes.customCSS) {
      const generatedCSS = attributes.customCSS.replace(/.ticss-[a-zA-Z0-9_-]*/g, 'selector');
      customCSSRef.current = generatedCSS;
    } else {
      customCSSRef.current = 'selector {\n}\n';
    }

    editorRef.current = wp.CodeMirror(document.getElementById('redux-css-editor'), {
      value: customCSSRef.current,
      autoCloseBrackets: true,
      continueComments: true,
      lineNumbers: true,
      lineWrapping: true,
      matchBrackets: true,
      lint: true,
      gutters: ['CodeMirror-lint-markers'],
      styleActiveLine: true,
      styleActiveSelected: true,
      extraKeys: {
        'Ctrl-Space': 'autocomplete',
        'Alt-F': 'findPersistent',
        'Cmd-F': 'findPersistent'
      }
    });
    editorRef.current.on('change', () => {
      const regex = new RegExp('selector', 'g');
      const generatedCSS = editorRef.current.getValue().replace(regex, `.${classArRef.current}`);
      customCSSRef.current = generatedCSS;

      if ('selector {\n}\n'.replace(/\s+/g, '') === customCSSRef.current.replace(/\s+/g, '')) {
        return setAttributes({
          customCSS: null
        });
      }

      setAttributes({
        customCSS: customCSSRef.current
      });
    });
  }, []);
  useEffect(() => {
    let classes = getClassName();
    setAttributes({
      hasCustomCSS: true,
      className: classes
    });
  }, [attributes]);

  const getClassName = () => {
    let classes;
    const uniqueId = clientId.substr(0, 8);

    if (null !== customCSSRef.current && 'selector {\n}\n'.replace(/\s+/g, '') === customCSSRef.current.replace(/\s+/g, '')) {
      return attributes.className;
    }

    if (attributes.className) {
      classes = attributes.className;

      if (!classes.includes('ticss-')) {
        classes = classes.split(' ');
        classes.push(`ticss-${uniqueId}`);
        classes = classes.join(' ');
      }

      classArRef.current = classes.split(' ');
      classArRef.current = classArRef.current.find(i => i.includes('ticss'));
    } else {
      classes = `ticss-${uniqueId}`;
      classArRef.current = classes;
    }

    return classes;
  };

  const editorRef = useRef(null);
  const customCSSRef = useRef(null);
  const classArRef = useRef(null);
  return wp.element.createElement(Fragment, null, wp.element.createElement("p", null, __('Add your custom CSS.')), wp.element.createElement("div", {
    id: "redux-css-editor",
    className: "redux-css-editor"
  }), wp.element.createElement("p", null, __('Use'), " ", wp.element.createElement("code", null, "selector"), " ", __('to target block wrapper.')), wp.element.createElement("p", null, __('')), wp.element.createElement("p", null, __('Example:')), wp.element.createElement("pre", {
    className: "redux-css-editor-help"
  }, 'selector {\n    background: #000;\n}\n\nselector img {\n    border-radius: 100%;\n}'), wp.element.createElement("p", null, __('You can also use other CSS syntax here, such as media queries.')));
};

/* harmony default export */ __webpack_exports__["default"] = (CSSEditor);

/***/ }),

/***/ "./redux-templates/src/custom-css/index.js":
/*!*************************************************!*\
  !*** ./redux-templates/src/custom-css/index.js ***!
  \*************************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./style.scss */ "./redux-templates/src/custom-css/style.scss");
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_style_scss__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _editor_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./editor.js */ "./redux-templates/src/custom-css/editor.js");
/* harmony import */ var _inject_css_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./inject-css.js */ "./redux-templates/src/custom-css/inject-css.js");
/* harmony import */ var _inject_css_js__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_inject_css_js__WEBPACK_IMPORTED_MODULE_2__);
/**
 * WordPress dependencies.
 */
const _lodash = lodash,
      assign = _lodash.assign;
const __ = wp.i18n.__;
const hasBlockSupport = wp.blocks.hasBlockSupport;
const PanelBody = wp.components.PanelBody;
const createHigherOrderComponent = wp.compose.createHigherOrderComponent;

const _ref = wp.blockEditor || wp.editor,
      InspectorControls = _ref.InspectorControls;

const Fragment = wp.element.Fragment;
const _wp$hooks = wp.hooks,
      addFilter = _wp$hooks.addFilter,
      removeFilter = _wp$hooks.removeFilter;
/**
 * Internal dependencies.
 */





const addAttribute = settings => {
  if (hasBlockSupport(settings, 'customClassName', true)) {
    settings.attributes = assign(settings.attributes, {
      hasCustomCSS: {
        type: 'boolean',
        default: false
      },
      customCSS: {
        type: 'string',
        default: null
      }
    });
  }

  return settings;
};

const withInspectorControls = createHigherOrderComponent(BlockEdit => {
  return props => {
    const hasCustomClassName = hasBlockSupport(props.name, 'customClassName', true);

    if (hasCustomClassName && props.isSelected) {
      return wp.element.createElement(Fragment, null, wp.element.createElement(BlockEdit, props), wp.element.createElement(InspectorControls, null, wp.element.createElement(PanelBody, {
        title: __('Custom CSS'),
        icon: wp.element.createElement("i", {
          className: 'fa fa'
        }),
        initialOpen: false
      }, wp.element.createElement(_editor_js__WEBPACK_IMPORTED_MODULE_1__["default"], {
        clientId: props.clientId,
        setAttributes: props.setAttributes,
        attributes: props.attributes
      }))));
    }

    return wp.element.createElement(BlockEdit, props);
  };
}, 'withInspectorControl'); // Remove block-css fields.

removeFilter('blocks.registerBlockType', 'themeisle-custom-css/attribute');
removeFilter('editor.BlockEdit', 'themeisle-custom-css/with-inspector-controls');
addFilter('blocks.registerBlockType', 'redux-custom-css/attribute', addAttribute);
addFilter('editor.BlockEdit', 'redux-custom-css/with-inspector-controls', withInspectorControls);

/***/ }),

/***/ "./redux-templates/src/custom-css/inject-css.js":
/*!******************************************************!*\
  !*** ./redux-templates/src/custom-css/inject-css.js ***!
  \******************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

/**
 * WordPress dependencies.
 */
const __ = wp.i18n.__;
const parse = wp.blocks.parse;
const _wp$data = wp.data,
      select = _wp$data.select,
      subscribe = _wp$data.subscribe;

const addStyle = style => {
  let element = document.getElementById('redux-css-editor-styles');

  if (null === element) {
    element = document.createElement('style');
    element.setAttribute('type', 'text/css');
    element.setAttribute('id', 'redux-css-editor-styles');
    document.getElementsByTagName('head')[0].appendChild(element);
  }

  if (element.textContent === style) {
    return null;
  }

  return element.textContent = style;
};

let style = '';

const cycleBlocks = (blocks, reusableBlocks) => {
  blocks.forEach(block => {
    if (block.attributes.hasCustomCSS) {
      if (block.attributes.customCSS && null !== block.attributes.customCSS) {
        style += block.attributes.customCSS + '\n';
      }
    }

    if ('core/block' === block.name && null !== reusableBlocks) {
      let reBlocks = reusableBlocks.find(i => block.attributes.ref === i.id);

      if (reBlocks) {
        reBlocks = parse(reBlocks.content.raw);
        cycleBlocks(reBlocks, reusableBlocks);
      }

      ;
    }

    if (undefined !== block.innerBlocks && 0 < block.innerBlocks.length) {
      cycleBlocks(block.innerBlocks, reusableBlocks);
    }
  });
};

const subscribed = subscribe(() => {
  style = '';

  const _ref = select('core/block-editor') || select('core/editor'),
        getBlocks = _ref.getBlocks;

  const blocks = getBlocks();
  const reusableBlocks = select('core').getEntityRecords('postType', 'wp_block');
  cycleBlocks(blocks, reusableBlocks);
  addStyle(style);
});

/***/ }),

/***/ "./redux-templates/src/custom-css/style.scss":
/*!***************************************************!*\
  !*** ./redux-templates/src/custom-css/style.scss ***!
  \***************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../node_modules/css-loader/dist/cjs.js!../../../node_modules/sass-loader/dist/cjs.js!./style.scss */ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/custom-css/style.scss");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./redux-templates/src/editor.scss":
/*!*****************************************!*\
  !*** ./redux-templates/src/editor.scss ***!
  \*****************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../node_modules/css-loader/dist/cjs.js!../../node_modules/sass-loader/dist/cjs.js!./editor.scss */ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/editor.scss");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./redux-templates/src/icons/images/acf-blocks.svg":
/*!*********************************************************!*\
  !*** ./redux-templates/src/icons/images/acf-blocks.svg ***!
  \*********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
function _extends() { _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; return _extends.apply(this, arguments); }



var _ref = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  d: "M10.5 19.8v-.2V10c0-.2.1-.2.2-.3 1.7-.7 3.5-1.4 5.2-2.2 1.3-.5 2.6-1.1 3.8-1.6.3-.1.3-.1.3.2v9.1c0 .1 0 .2-.2.3-.9.4-1.9.9-2.8 1.3-.8.4-1.7.8-2.5 1.2-1 .5-1.9.9-2.9 1.4-.3.1-.7.2-1.1.4zm1-5.4v3.7c0 .2 0 .2.2.1 2.4-1.1 4.7-2.2 7.1-3.4.1-.1.2-.1.2-.3V7.4c0-.2 0-.2-.2-.1-2.4 1-4.7 2-7.1 3-.1.1-.2.1-.2.2v3.9zM19.4 4.2c-1-.4-2-.9-3-1.3-1.2-.5-2.4-1-3.7-1.6C11.8 1 10.9.6 10 .2c-.1 0-.1-.1-.2 0-.6.3-1.1.5-1.7.7-.7.3-1.4.7-2.1 1-.7.3-1.5.6-2.2 1-.7.3-1.3.6-2 .9-.6.1-1.1.3-1.7.6.2.1.4.2.6.2 1.9.8 3.8 1.6 5.7 2.5 1.1.4 2.2.9 3.2 1.4.1.1.2.1.4 0 .4-.2.8-.3 1.1-.5 1.5-.6 3-1.3 4.5-1.9 1.1-.5 2.2-.9 3.4-1.4.2-.1.5-.2.7-.3-.1-.1-.2-.1-.3-.2zm-5.9-.8h-1.1c-.1 0-.2 0-.2.2 0 .5 0 .5.5.5h.7c.1 0 .1 0 .1.1v.5c0 .1 0 .1-.1.1h-1c-.1 0-.2 0-.2.2v1c0 .1 0 .1-.1.1h-.6c-.1 0-.1 0-.1-.1v-.5c-.3.3-.5.4-.8.5-.3 0-.6 0-.9-.1 0 0-.1-.1-.2 0h-.1c-.2.1-.4.1-.6.1-.1 0-.2 0-.2-.1-.2-.4-.2-.4-.6-.4h-.6c-.1 0-.1 0-.2.1-.1.4-.1.4-.5.4h-.3c-.1 0-.2 0-.1-.1.3-.7.6-1.4.9-2.2.1-.3.3-.6.4-1 0-.1.1-.1.2-.1.4 0 .4 0 .6.4.1.2.2.5.3.7.3-.6.7-.9 1.3-1 .6-.1 1.1.1 1.6.5v-.4c0-.1 0-.1.1-.1h1.9c.1 0 .1 0 .1.1v.5c0 .1-.1.1-.2.1zM9.5 10c0-.1 0-.2-.1-.2-.3-.1-.5-.2-.7-.3-2.4-1-4.7-2-7.1-3-.5-.2-1-.4-1.6-.7v9.4c0 .1 0 .2.1.2.2.1.3.1.5.2L3 16.8c1.3.6 2.5 1.2 3.8 1.8.8.4 1.7.8 2.5 1.2.2.1.2.1.2-.1v-7V10zm-1.2 8.2c-2.4-1.1-4.8-2.3-7.2-3.4-.1 0-.1 0-.1-.1V7.5c0-.1 0-.2.2-.1l7.2 3c.1 0 .1.1.1.2v7.6c0 .1 0 .1-.2 0z"
});

var _ref2 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  d: "M11.4 4.3c0 .1 0 .1 0 0 0 .2 0 .3-.2.3-.1 0-.2 0-.2.2-.2.4-.6.6-1 .5-.5-.2-.8-.5-.8-1 0-.4.3-.8.7-.9.4-.1.8.1 1 .5 0 .1.1.1.2.1.3.1.3.1.3.3zM7.8 4c.1 0 .1 0 0 0 .2.3.3.5.3.7h-.6c.2-.2.3-.5.3-.7zM7.6 15.5v.1c.1.2.1.3-.1.5v.1c.1.1.1.2.1.4v.1h-.1c-.1-.1-.3-.2-.3-.3v-.2c.1.1.1.3.3.3 0-.1-.1-.3-.3-.4 0 0-.1 0-.1-.1-.1-.1-.2 0-.2.1 0 .2.1.3.4.4.1 0 .1 0 .1.1v.1c.1 0 .2.1.2.1s.1.1 0 .1h-.1c-.4-.2-.8-.4-1.3-.6l-.1-.1c0-.3-.2-.5-.4-.6-.1-.1-.3-.1-.4 0l-.1.1h.1c.4.1.7.2.7.5v.1s-.1 0-.1-.1c-.1-.2-.2-.3-.4-.3 0 .1 0 .1.1.1h.1v.1h-.1c-.2-.1-.3-.1-.5-.2-.1-.1-.1-.1 0-.2l.1-.1c-.1 0-.2-.1-.3 0l-.1.1c-.1.2-.2.1-.2 0-.2-.4-.2-.4-.4-.5 0 .1.1.2.1.3 0 .1 0 .1-.1.1s-.1 0-.2-.1c-.1 0-.2 0-.1-.2s-.2-.3-.3-.3c0 0-.1.1-.1 0v-.1c0-.1 0-.1.1-.1.2 0 .3.1.4.2 0 .1.1.1.1.2h.1v-.1c-.1-.3-.4-.5-.7-.5-.1 0-.2 0-.2.2 0 .1 0 .1-.1.1-.4-.2-.9-.4-1.3-.6 0 0-.1 0-.1-.1s.1 0 .1 0c.1 0 .2.2.2 0 0 0 .1 0 .1.1.1.1.1.1.2 0 .1 0 .1-.1.1-.2-.1-.2-.2-.3-.5-.4-.1 0-.1 0-.2.1v.1H2s0-.1.1-.1v.1c0 .1-.1.1-.2.1h-.3c-.1 0-.1-.3 0-.3.1-.1.1-.2 0-.3-.1-.1-.1-.3-.1-.4 0-.1 0-.1.1-.1s0 .1 0 .1c-.1.2 0 .4.2.6.1.1.2.1.3.2.1.1.2.2.2.4 0 .1 0 .1.1.1.1.1.2.2.2.4 0 .1.1.1.1 0 .1-.2.3-.2.5-.2 0-.1 0-.1-.1-.1-.5-.5-.9-1.1-1.2-1.7-.2-.5-.3-1-.2-1.6 0-.1.1-.2.1-.3 0-.1 0-.2-.1-.2-.2-.1-.3-.3-.4-.5V10c.1-.3 0-.6.1-1 0-.1 0-.1.1-.1.5.1.5.2.5.3v.6c0 .1.1.2.2.2s.2.1.3 0v-.1c0-.1-.1-.2-.2-.2s-.1 0-.1-.1c.2 0 .5.2.4.4 0 .1-.2.2-.3.1 0 0-.1-.1-.1 0-.1 0-.2-.1-.2 0v.3l.1.1c.2.2.2.2.4 0l.1-.1c.3-.3.3-.4.1-.8 0-.1-.1-.2-.2-.4.1 0 .1.1.2.1.1.3.3.5.6.6.1.1.2.1.3 0-.1-.1-.2-.1-.4-.2s-.4-.3-.4-.5H3c0 .1.2.2.3.4.1-.1.1-.2.1-.2.1 0 .1-.1.2 0s0 .1 0 .1c-.1.1-.1.3 0 .4.1.1.2.1.4 0 .2-.2.4-.2.6 0 .1.1.2.2.4.2s.4.2.5.4c.1.1.1.2.2.3 0 .1.1.2.2.2 0 0 .1 0 .2-.1s0-.2-.1-.2v-.1s.1 0 .1.1.1.1.1.2c0 .2.1.2.2.1h.2c.1 0 .2.1.1.2 0 .1-.1.1-.2.1s-.2-.1-.4-.1c-.1 0-.1-.1-.2 0 0 .1 0 .1.1.1.2.2.4.2.7.1.1 0 .1-.1.2 0 0 .1 0 .1-.1.1-.2.2-.2.5-.1.8l.3.6c.1 0 .2.1.4.1.1 0 .2 0 .2-.2 0-.1 0-.2-.1-.2-.1.1.1.2-.1.3-.1 0-.2 0-.2-.1s-.1-.2 0-.2c.1-.1.1.1.3.1 0-.1-.2-.2-.3-.2-.2-.2-.3-.3-.3-.5s.1-.2.3-.2c.1 0 .1.1.2.1v.1h-.1c-.1 0-.2-.2-.2 0-.1.1 0 .2.1.3.1.1.2.1.3.2.1.1.1 0 .1-.1v-.6-.1c.1 0 .1.1.1.1V13c0 .1-.2.2-.3.2 0 0-.1-.1-.1 0v.1c.2.4.2.8.1 1.2-.1.6-.4 1.1-1.1 1.2-.1 0-.2.1-.4 0 .3.2.5.4.6.7 0 .1.1.1.1 0s.1-.2.2-.1c.1 0 .1 0 .1-.1.1-.2.2-.2.3-.1.2.1.4.1.5-.1.1-.1 0-.2-.1-.3-.1-.2-.1-.2 0-.2zm-3.9-4.4c-.1-.1-.1 0-.2 0-.2.2-.4.4-.3.7.2-.1.4-.2.5-.3.1 0 .1-.1.1 0s0 .1-.1.1c-.1.1-.3.1-.4.2-.1.2-.2.2-.1.4 0 0 0 .1.1.1.5.7.9 1.4 1.4 2 .1.1.2.1.3.1.5-.2.9-.5 1.4-.7.1-.1.1-.1 0-.2-.2-.3-.4-.5-.6-.8 0 0-.1 0 0-.1h.1c0 .1.1.3.2.4.1.1.2.3.3.4 0-.5-.2-.8-.4-1.2h.1c.2.4.4.8.5 1.3v.1c.1 0 .1-.1.2-.1s0-.1 0-.1c-.1-.2-.1-.4-.2-.5-.1-.2-.2-.5-.4-.7-.1-.2-.1-.2-.2-.1s-.2.1-.3.2c0 0-.1.1-.1 0v-.1l.2-.2c.2-.1.2-.2.1-.4l-.6-.6c-.1-.1-.2-.2-.4-.1h-.1c.2.1.4.3.6.4.3.2.4.4.4.5-.3-.2-.5-.5-.9-.7.2.2.3.4.4.6 0 0 .1.1 0 .1h-.1c-.1-.2-.3-.4-.4-.6-.1-.1-.1-.1-.2-.1-.1.1-.2.1-.3.2 0 0-.1.1-.1 0s0-.1.1-.1c.1-.1.3-.1.4-.2-.1 0-.1 0-.2-.1-.2 0-.4-.1-.7-.1-.1.2-.1.2-.1.3zm2.8 2.7c-.5.2-1 .5-1.5.7 0 0-.1 0 0 0 0 0 0 .1.1.1.3.1.6.1.9-.1.2-.1.5-.3.5-.7zm-3.4-1.5c0 .2 0 .4.1.5.2.6.6 1.1 1.2 1.5h.1v-.1c-.2-.2-.3-.4-.5-.6-.3-.4-.6-.9-.9-1.3zm-.2-2.4c.1.3-.1.4-.2.6-.2.2-.3.5-.4.7 0 .2.1.2.2.2s.1-.1.1-.2c.1-.3.2-.5.4-.7.1-.1.1-.2.1-.3 0 0-.1-.1-.2-.3zm4.3 3.8c0-.4-.1-.7-.3-1.1-.1-.3-.4-.6-.3-1-.3.1-.3.2-.2.5.2.3.4.7.4 1.1.1.3.2.5.4.5zM3 9.9c0 .1.1.1.1.2.1.2.3.3.5.3h.7c.1 0 .2 0 .2-.1s-.1-.1-.2-.1c-.2-.1-.4-.1-.6-.1-.2 0-.4 0-.7-.2zm3.6 3.8c-.1.4-.1.7-.4.9-.1 0-.2.1-.2.1 0 .1.2.1.2.1.1 0 .1.1.2 0 .2-.1.3-.3.3-.5 0-.1.1-.2.1-.3 0 0-.1-.1-.2-.3zM3 12c-.1 0-.1.1-.2.1s-.1.1-.1.1c.1.3.2.7.4 1 .1.2.3.3.5.4-.4-.5-.6-1-.6-1.6zm3.5-.5c-.2-.1-.4-.1-.6-.3-.2-.3-.5-.5-.8-.7 0 0-.1-.1-.1 0v.1c.1.2.2.2.3.3.2.2.5.4.7.6.2.3.3.2.5 0zm-1.7 3.3c0 .2.1.3.2.2h.2c.5 0 .9.1 1.3.4h.2c.1-.1 0-.1-.1-.1-.2-.2-.5-.3-.8-.3-.3-.1-.5 0-.8-.1-.1-.2-.2-.2-.2-.1zm-1.9-1.4c0 .2.2.2.3.3.5.2.8.5 1.2.9.1.1.2.3.3.4 0-.4 0-.4-.2-.5-.2-.1-.4-.3-.6-.5-.2-.2-.5-.4-.8-.5-.1-.1-.1-.1-.2-.1zm.1-.1c-.2-.4-.3-.7-.4-1.1 0-.1-.1-.2-.2-.3h-.1c0 .1 0 .2.1.3 0 .3.2.6.3.9.1.2.2.2.3.2zm4.2 1c.1-.1-.1-.1-.2-.2-.1 0-.1.1-.1.1-.1.2-.2.5-.3.6-.1.2-.1.2 0 .2s.2.2.2.1c.2-.2.4-.5.4-.8zm-2.6-3.4c-.1-.1-.1-.3-.3-.3-.2-.1-.5-.1-.7-.1 0 0-.1 0-.1.1v.3c0 .1.1.1.2 0 .2-.1.4-.1.5 0h.4zm-1.7 1c0-.1.1-.1.1-.2 0-.3.2-.5.4-.7.1 0 .1-.1.1-.1 0-.1-.3-.2-.4-.1-.2.1-.3.3-.4.6 0 .1-.1.2 0 .3.1 0 .2.1.2.2zm2.6 3.3c.2 0 .5.1.7.2.1 0 .2.1.2 0s-.1-.1-.1-.1h-.1c-.4-.2-.8-.3-1.2-.1 0-.1 0 0 0 0s0 .1.1 0h.4zm-2.4-1.4c0 .1.1.2.2.3.5.2.8.5 1.1.9 0 0 0 .1.1.1V15c-.3-.6-.8-1-1.4-1.2zm1.8-3.1v-.4c0-.1-.1-.1-.1-.1h-.1c-.1.2-.1.3 0 .5 0 .1.1.1.2.1s0 0 0-.1zm.3 4.7c.3.1.6.2.9.1-.3-.2-.6-.2-.9-.1zm-2.4-3.6c0-.1-.2-.3-.4-.3l-.1.1.3.3c.2.1.2.1.2-.1zM7.1 14h.1v-.1c-.1-.1-.1-.2-.2-.2h-.2v.2s.1.1.3.1zm-3.6.3c.1.2.6.6.8.6-.1-.2-.6-.6-.8-.6zm1.2.8c-.1 0-.1.1-.1.2s.1.3.2.3c0 0 .1-.1.1-.2 0-.2-.1-.3-.2-.3zM4.1 10c.1 0 .3.1.4.1h.1V10c-.2-.2-.4-.2-.5 0zm1.3.6c0-.2-.2-.4-.4-.4h-.1v.1c.2.1.3.2.5.3z"
});

var _ref3 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  d: "M2 11.8c-.1 0-.1-.1-.1-.2v-.8-.1H2c0 .1.2.1.2.2-.2-.1-.2 0-.2.2v.7zM7.7 13.9v.5s0 .1-.1.1 0 0 0-.1v-.7c0-.1 0-.2-.1-.3l-.1-.1c0-.1.1 0 .1 0 .2 0 .2.1.2.3v.3zM2.2 10.2c.1 0 .2.1.2.2s0 .1-.1.1-.2-.1-.2-.2c-.1-.1 0-.1.1-.1zM7.2 11.4c.1 0 .3.3.2.4 0 0 0 .1-.1 0 0 0-.1-.1-.1-.4 0 .1 0 0 0 0zM6 10.7c0 .1.1.1 0 .2h-.2c-.1-.1-.1-.1-.1-.2s.1-.1.2-.1l.1.1zM2.9 9.2c-.1-.1-.2-.2-.4-.3 0 0-.1 0 0-.1h.1c.2.2.4.3.6.4v.1c-.1 0-.2-.1-.3-.1zM2.1 9.2c0-.1.1-.2.1-.2.1 0 .2.1.2.2s-.1.1-.2.1c-.1.1-.1 0-.1-.1zM7.1 11.1c-.1 0-.2-.1-.3-.1-.1-.1-.3-.1-.4-.2 0 0-.1 0 0-.1h.1c.1.1.3.2.6.4 0-.1 0-.1 0 0zM7.7 11.4v.1c-.1 0-.1-.1-.1-.1 0-.1-.2-.2-.3-.2 0 0-.1 0-.1-.1s.1 0 .1 0c.1.1.2.1.2.1l.2.2zM3.9 9.8s0 .1 0 0c-.2.1-.3 0-.3-.1s0-.1.1-.1.2 0 .2.2zM2.4 8.8c-.1 0-.2 0-.2-.1-.1 0-.2 0-.2.1h-.1v-.1-.2c.1-.1.1 0 .1 0 .2.2.3.2.4.3zM7.4 15.7c-.2 0-.3-.1-.4-.1v-.1h.1c.1 0 .2.1.3.2zM7.6 15.5c-.1-.1-.2-.1-.3-.2 0 0-.1 0 0-.1 0-.1.1 0 .1 0 .1 0 .2.1.3.1-.1.1 0 .2-.1.2zM2.2 13.1c.1.1.2.1.3.2 0 0 .1 0 0 .1h-.1s-.2-.1-.2-.3c-.1.1-.1.1 0 0-.1.1-.1.1 0 0zM2.3 12.9c0 .1 0 .1 0 0-.2 0-.3-.1-.4-.1v-.1c.2.1.3.1.4.2zM14.1 16.3c-.6 0-1-.1-1.5-.5-.3-.3-.5-.6-.6-.9 0-.3-.1-.6 0-.9 0-.6.2-1.1.4-1.6s.5-.9.8-1.3c.4-.4.8-.8 1.3-1.1.4-.3.9-.5 1.3-.6.9-.2 1.7-.1 2.3.7.2.3.3.6.4 1v.5c0 .7-.2 1.4-.5 2-.3.7-.8 1.3-1.4 1.8-.5.5-1.2.8-1.9 1-.2-.1-.4-.1-.6-.1zm4.3-4.9c0-.5-.2-1.1-.8-1.5-.4-.3-.9-.4-1.3-.4-.3 0-.6.1-.8.2-.5.2-.9.4-1.3.7-.4.3-.8.7-1.1 1.1-.4.5-.6 1-.8 1.6-.1.5-.2 1.1 0 1.6.3.9.9 1.3 1.7 1.4.4.1.7 0 1.1-.1.7-.2 1.2-.6 1.7-1 .4-.4.8-.8 1.1-1.3.2-.7.5-1.3.5-2.3zM4.5 12.7c0 .2 0 .4.2.6.1.1.2.1.3.1.1-.1.1-.2.1-.3-.1-.1-.1-.2-.2-.1 0 0-.1.1-.1 0v-.1l.1-.1c.2.2.3.2.5.1h.1v.1s-.1.1-.2.1-.1 0-.1.1 0 .3-.2.3-.3-.1-.4-.2c-.3-.2-.3-.5-.3-.8v-.1c0-.3.2-.4.5-.2.2.1.3.2.4.4v.1h-.1c-.1-.1-.1-.2-.2-.3-.1-.1-.2-.2-.4-.1-.1 0-.1.2-.1.3.1 0 .1.1.1.1zM6.6 13.3c-.1-.4-.2-.8-.5-1.1.3.1.6.8.5 1.1zM3.7 11.1c.1.1.2.3.3.5-.2-.2-.3-.3-.3-.5zM2.8 11.4c.1-.2.2-.4.4-.6h.1v.1c-.2.2-.3.4-.3.6h-.1c-.1 0 0 0-.1-.1z"
});

var _ref4 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  d: "M13 12.2c.1-.3.3-.5.4-.7.3-.4.6-.7.9-.9.4-.3.8-.5 1.2-.6.5-.1.9-.1 1.3 0 0 0 .1 0 .1.1s-.1.1-.1.1c-.4.3-.4.7-.1 1.1.3.3.3.6.2 1-.1.5-.3 1.1-.4 1.6-.1-.1-.1-.2-.2-.4-.2-.5-.4-1.1-.6-1.6-.1-.1-.1-.3-.2-.4-.1-.1 0-.1.1-.2s.2-.1.3-.2c0 0 .1-.1 0-.1 0-.1-.1 0-.1 0-.3.2-.7.3-1 .4-.1 0-.3.1-.4.1-.1 0-.1.1-.1.2s.1.1.2 0c.2-.1.3 0 .3.2l.3.6c.1.1 0 .3 0 .4-.1.2-.1.5-.2.7-.1.3-.2.6-.3 1-.2-.3-.3-.6-.4-.9l-.6-1.5c0-.1 0-.2.1-.2s.2-.1.2-.1v-.1c0-.1-.1 0-.1 0-.2.2-.5.3-.8.4z"
});

var _ref5 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  d: "M15.3 13.1c.3.6.5 1.2.8 1.9v.1c-.4.3-.9.5-1.4.6-.2 0-.2 0-.2-.2.2-.6.4-1.3.6-1.9.1-.1.1-.3.2-.5zM14.1 15.7c-.5-.1-.9-.2-1.1-.6-.4-.5-.5-1-.4-1.6 0-.2.1-.4.1-.7 0 0 0-.1.1-.1l.1.1c.2.4.4.8.5 1.2.2.6.4 1.1.7 1.7zM16.7 14.6c.1-.4.2-.8.4-1.2l.6-1.8c.1-.3.2-.6.1-.9.2.2.2.4.3.6.1.5 0 1.1-.1 1.6-.1.4-.4.8-.6 1.2-.4.1-.5.3-.7.5z"
});

var SvgAcfBlocks = function SvgAcfBlocks(props) {
  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("svg", _extends({
    viewBox: "0 0 20 20",
    xmlns: "http://www.w3.org/2000/svg"
  }, props), _ref, _ref2, _ref3, _ref4, _ref5);
};

/* harmony default export */ __webpack_exports__["default"] = (SvgAcfBlocks);

/***/ }),

/***/ "./redux-templates/src/icons/images/advanced-custom-fields.svg":
/*!*********************************************************************!*\
  !*** ./redux-templates/src/icons/images/advanced-custom-fields.svg ***!
  \*********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
function _extends() { _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; return _extends.apply(this, arguments); }



var _ref = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  d: "M4.4 9l.8 2.1H3.7c0-.2.3-.8.4-1.1l.3-1s0 .1 0 0zm8.7.3h1v1.5h-1c-.1.5-.5 1-1 1.3-1.6 1.1-3.9.1-4-2.1 0-1.3 1-2.3 2.1-2.5 1.3-.2 2.6.6 2.9 1.8zM0 14.5h2.3l.5-1.3c0-.1 0 0 .1-.1H6c0 .2.5 1.2.5 1.3h2.4l-.1-.3c.7.2 1.2.4 2.1.4 1 0 2-.5 2.6-1l.3-.3.3-.3v1.6h2.2v-1.7-1.7h3.5v-2h-3.5v-.8-.8H20V5.4h-5.9V7c-.4-.4-.3-.4-.8-.7-1-.7-2.4-1-3.6-.7-.9.2-1.6.6-2.2 1.2l-.2.2c-.3.2-.7.9-.8 1.3l-.1.1c-.1-.3-.8-1.8-.9-2.3-.1-.2-.2-.5-.3-.7H3.6L0 14.5z",
  fillRule: "evenodd",
  clipRule: "evenodd"
});

var SvgAdvancedCustomFields = function SvgAdvancedCustomFields(props) {
  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("svg", _extends({
    viewBox: "0 0 20 20",
    xmlns: "http://www.w3.org/2000/svg"
  }, props), _ref);
};

/* harmony default export */ __webpack_exports__["default"] = (SvgAdvancedCustomFields);

/***/ }),

/***/ "./redux-templates/src/icons/images/advanced-gutenberg-blocks.svg":
/*!************************************************************************!*\
  !*** ./redux-templates/src/icons/images/advanced-gutenberg-blocks.svg ***!
  \************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
function _extends() { _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; return _extends.apply(this, arguments); }



var _ref = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  d: "M2 2h5v11H2V2zm6 0h5v5H8V2zm6 0h4v16h-4V2zM8 8h5v5H8V8zm-6 6h11v4H2v-4z"
});

var SvgAdvancedGutenbergBlocks = function SvgAdvancedGutenbergBlocks(props) {
  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("svg", _extends({
    xmlns: "http://www.w3.org/2000/svg"
  }, props), _ref);
};

/* harmony default export */ __webpack_exports__["default"] = (SvgAdvancedGutenbergBlocks);

/***/ }),

/***/ "./redux-templates/src/icons/images/atomic-blocks.svg":
/*!************************************************************!*\
  !*** ./redux-templates/src/icons/images/atomic-blocks.svg ***!
  \************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
function _extends() { _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; return _extends.apply(this, arguments); }



var _ref = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  d: "M6.3 13.9h1.3l.7-1.9h3.3l.7 1.9h1.3l-3-7.8H9.3l-3 7.8zM10 7.8l1.2 3H8.8l1.2-3z"
});

var _ref2 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  className: "atomic-blocks_svg__st0",
  d: "M18.9 5.7c.4-.5.6-1.2.6-1.9 0-1.9-1.5-3.4-3.3-3.4-.7 0-1.4.2-1.9.6-1.3-.6-2.7-1-4.2-1C4.6 0 .2 4.4.2 9.9s4.4 9.9 9.9 9.9S20 15.4 20 9.9c-.1-1.4-.5-2.9-1.1-4.2zM15.1 2h1.1c.5 0 1.1.3 1.1.9 0 .4-.3.6-.3.6s.5.2.5.8c0 .6-.5 1-1.1 1h-1.2V2zM10 18.2c-4.5 0-8.2-3.7-8.2-8.2 0-4.5 3.7-8.2 8.2-8.2 1.1 0 2.2.2 3.1.6-.2.4-.3.9-.3 1.4 0 1.9 1.5 3.4 3.3 3.4.5 0 1-.1 1.4-.3.4 1 .6 2 .6 3.1.1 4.5-3.6 8.2-8.1 8.2z"
});

var _ref3 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  className: "atomic-blocks_svg__st0",
  d: "M16.7 3c0-.3-.2-.4-.5-.4h-.5v.8h.5c.3 0 .5-.1.5-.4zM16.8 4.4c0-.3-.2-.4-.5-.4h-.6v.9h.6c.3-.1.5-.2.5-.5z"
});

var SvgAtomicBlocks = function SvgAtomicBlocks(props) {
  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("svg", _extends({
    viewBox: "0 0 20 20",
    xmlns: "http://www.w3.org/2000/svg"
  }, props), _ref, _ref2, _ref3);
};

/* harmony default export */ __webpack_exports__["default"] = (SvgAtomicBlocks);

/***/ }),

/***/ "./redux-templates/src/icons/images/block-options.svg":
/*!************************************************************!*\
  !*** ./redux-templates/src/icons/images/block-options.svg ***!
  \************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
function _extends() { _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; return _extends.apply(this, arguments); }



var _ref = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  d: "M6.8 19.5c-.6.1-.8-.4-1-.8-1.5-1.8-3-3.6-4.5-5.3-.4-.5-.8-.9-1.2-1.4-.1-.2-.1-.4-.1-.6.3-1 .7-2 1.1-3 .7-2 1.5-3.9 2.2-5.9.1-.3.2-.4.5-.5 3-.5 5.9-1.1 8.9-1.5.6-.1 1 0 1.4.5 1 1.3 2 2.5 3 3.7.9 1.1 1.8 2.1 2.7 3.2.2.2.3.4.2.7-.5 1.3-.9 2.5-1.4 3.8-.6 1.7-1.2 3.3-1.9 5-.1.3-.2.4-.5.4-2.7.4-5.3.9-8 1.4-.5.1-.9.2-1.4.3zm-2.5-3.4h.9c2.4-.3 4.7-.5 7.1-.8.4 0 .5-.2.6-.6.4-2.1 1.3-4.1 2.7-5.7.2-.2.2-.3 0-.5-.9-1.1-1.9-2.2-2.8-3.4-.2-.2-.3-.2-.6-.1-1.9 1.1-4 1.6-6.2 1.6-.3 0-.5.1-.5.4-.5 1.8-1 3.5-1.6 5.3-.2.8-.5 1.6-.7 2.4 1.1-.8 2.1-1.7 3.1-2.5.5-.4.8-.9.8-1.5 0-.3.1-.6.2-.9.7-1.2 2.3-1 3.1-.4.2.2 0 .2-.1.3-.4.1-.9.3-1.3.4-.3.1-.4.3-.4.6s.1.6.3.8c.2.2.3.3.6.2.4-.2.9-.3 1.3-.4.1 0 .2-.1.3 0 .1.1 0 .2 0 .3-.4 1-1.4 1.7-2.5 1.5-.5-.1-.9 0-1.2.4-.2.2-.4.3-.6.5-.9.6-1.7 1.4-2.5 2.1zm14.5-8.6c-.1-.1-.2-.2-.2-.3-.5-.6-1.1-1.3-1.6-1.9-.8-.9-1.6-1.9-2.4-2.8-.1-.1-.2-.3-.4-.1-.4.4-.9.7-1.3 1.1-.1.1-.1.2 0 .3 1.4 1.6 2.7 3.3 4.1 4.9.1.2.2.2.4 0 .4-.4.8-.7 1.3-1.1-.1 0 0-.1.1-.1z"
});

var SvgBlockOptions = function SvgBlockOptions(props) {
  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("svg", _extends({
    viewBox: "0 0 20 20",
    xmlns: "http://www.w3.org/2000/svg"
  }, props), _ref);
};

/* harmony default export */ __webpack_exports__["default"] = (SvgBlockOptions);

/***/ }),

/***/ "./redux-templates/src/icons/images/block-slider.svg":
/*!***********************************************************!*\
  !*** ./redux-templates/src/icons/images/block-slider.svg ***!
  \***********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
function _extends() { _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; return _extends.apply(this, arguments); }



var _ref = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("style", null, ".block-slider_svg__st0{fill:#6171b5}");

var _ref2 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  className: "block-slider_svg__st0",
  d: "M2.9 16.2c0-1.2.2-2.3.4-3.5 0-.2.1-.5.3-.7.2-.3.5-.6.9-.6.3 0 .3.5.3.7.6 1.7 1.4 3.2 2.7 4.5.4.4.8.7 1.2.9.2.2.5.2.3.5-.1.3-.1.8-.6.8-1.8 0-3.5-.3-5.2-1-.3-.1-.4-.3-.3-.6v-1zM17 3.8c0 1.3-.2 2.6-.5 4-.1.6-.4.8-.9.9-.4.1-.6.1-.6-.4-.2-2-1-3.8-2.4-5.3l-.3-.3c-.2-.2-.7-.3-.5-.7.2-.4.5-.7 1-.7.4 0 .7 0 1.1.1.9.2 1.7.5 2.5.7.4.1.7.4.6.9v.8z"
});

var _ref3 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  d: "M16.1 11.4c-1-1.5-2.3-2.7-3.6-4-1-.9-1.8-2-2.3-3.3-.5-1.4-.6-2.8.8-4.1-2.1 0-3.9.6-5.4 1.8-2.4 2-3 4.9-1.3 7.5.5.8 1.1 1.6 1.8 2.4 1.3 1.4 2.5 2.7 3.5 4.3.8 1.2 1.3 2.5.5 4 1.7 0 3.2-.5 4.6-1.4 2.1-1.5 3.5-4.2 1.4-7.2zm-2.7 7c-.2 0-.4-.1-.4-.3 0-.2.2-.3.4-.3s.3.1.3.3c0 .2-.1.3-.3.3zm1-.6c-.2 0-.3-.2-.3-.4s.1-.3.3-.3c.2 0 .3.1.3.3 0 .2-.1.4-.3.4zm.7-.9c-.2 0-.3-.2-.3-.4s.2-.3.4-.3.3.2.3.3c-.1.3-.2.4-.4.4z",
  fill: "#1b214c"
});

var SvgBlockSlider = function SvgBlockSlider(props) {
  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("svg", _extends({
    id: "block-slider_svg__Layer_1",
    viewBox: "0 0 20 20",
    xmlSpace: "preserve",
    xmlns: "http://www.w3.org/2000/svg"
  }, props), _ref, _ref2, _ref3);
};

/* harmony default export */ __webpack_exports__["default"] = (SvgBlockSlider);

/***/ }),

/***/ "./redux-templates/src/icons/images/coblocks.svg":
/*!*******************************************************!*\
  !*** ./redux-templates/src/icons/images/coblocks.svg ***!
  \*******************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
function _extends() { _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; return _extends.apply(this, arguments); }



var _ref = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  d: "M5.64.986l8.602-.002c1.626 0 2.217.17 2.813.489a3.342 3.342 0 011.387 1.382c.32.596.493 1.187.5 2.818l.042 8.62c.008 1.63-.158 2.222-.474 2.818a3.297 3.297 0 01-1.373 1.383c-.593.319-1.182.489-2.809.489l-8.6.001c-1.627 0-2.218-.169-2.814-.488a3.342 3.342 0 01-1.387-1.382c-.32-.596-.493-1.187-.5-2.818l-.042-8.62c-.008-1.63.158-2.222.474-2.818a3.297 3.297 0 011.373-1.383C3.425 1.156 4.014.986 5.64.986zm-.656 2.998a1 1 0 00-1 1v10a1 1 0 001 1h3a1 1 0 001-1v-10a1 1 0 00-1-1zm7 7a1 1 0 00-1 1v3a1 1 0 001 1h3a1 1 0 001-1v-3a1 1 0 00-1-1zm0-7a1 1 0 00-1 1v3a1 1 0 001 1h3a1 1 0 001-1v-3a1 1 0 00-1-1z"
});

var SvgCoblocks = function SvgCoblocks(props) {
  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("svg", _extends({
    xmlns: "http://www.w3.org/2000/svg"
  }, props), _ref);
};

/* harmony default export */ __webpack_exports__["default"] = (SvgCoblocks);

/***/ }),

/***/ "./redux-templates/src/icons/images/creative-blocks.svg":
/*!**************************************************************!*\
  !*** ./redux-templates/src/icons/images/creative-blocks.svg ***!
  \**************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
function _extends() { _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; return _extends.apply(this, arguments); }



var _ref = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  d: "M2.264 39.566L38.219 5.16l16.519 53.558-38.079 38.281zM69.376 112.381l35.607-35.607-46.088-13.51-38.006 38.006zM106.68 70.824L61.239 57.415 43.697 2.22 72.022 8.8l4.6 13.991 16.77 3.792 13.288 44.241zM64.633 53.949l35.664 10.522-10.291-34.264-16.699-3.775-4.604-13.999-18.641-4.337 14.571 45.853z"
});

var SvgCreativeBlocks = function SvgCreativeBlocks(props) {
  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("svg", _extends({
    xmlns: "http://www.w3.org/2000/svg"
  }, props), _ref);
};

/* harmony default export */ __webpack_exports__["default"] = (SvgCreativeBlocks);

/***/ }),

/***/ "./redux-templates/src/icons/images/editorplus.svg":
/*!*********************************************************!*\
  !*** ./redux-templates/src/icons/images/editorplus.svg ***!
  \*********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
function _extends() { _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; return _extends.apply(this, arguments); }



var _ref = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  d: "M17.4 0c1.1.1 1.9.5 2.3 1.5.5 1 .3 2-.5 2.8-1.6 1.7-7.5 7.6-8.9 8.9-.6.6-.7 1.5-1.1 2.2-.4.9-.8 1.9-1.1 2.9-.1.8-1.3.7-1.3.7l-5.4.7c-.4.1-.9.5-1.3.1-.3-.3.1-1.2.1-1.2l1-6.5s.3-.3.5-.4c1.5-.6 2.9-1.1 4.4-1.7.2 0 .4-.2.5-.3L15.4.9c.6-.5 1.2-.9 2-.9zM1.3 19c.5.1.8.1 1.1 0 1.3-.3 2.6-.4 3.9-.6 1 0 1.5-.4 1.8-1.4.7-2.2 1.4-4.4 3.4-5.8.3-.2.3-.5 0-.8-.5-.4-.9-.9-1.3-1.3-1.1-1-1.1-1-2.2.1-.5.5-.9 1.1-1.6 1.4-1.3.5-2.7 1.1-4.1 1.6-.5.2-.6.4-.7.8-.3 1.7-.5 3.3-.8 5 0 .2-.1.4.1.7 1-1 2-2 3-3.1.2-.2.2-.5.2-.7-.1-.7.4-1.3 1.1-1.3.6 0 1.2.6 1.2 1.2s-.6 1.1-1.2 1c-.3 0-.5 0-.8.2-1 .9-2 1.9-3.1 3zM17.4.8c-.6 0-1 .1-1.4.5-2 2-4 4.1-6.1 6.1-.3.2-.2.4 0 .6l2.1 2.1c.3.3.4.2.7 0l1.9-1.9L18.8 4c.5-.5.7-1.6.4-2.2-.4-.7-1-1.1-1.8-1z"
});

var SvgEditorplus = function SvgEditorplus(props) {
  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("svg", _extends({
    viewBox: "0 0 20 20",
    xmlns: "http://www.w3.org/2000/svg"
  }, props), _ref);
};

/* harmony default export */ __webpack_exports__["default"] = (SvgEditorplus);

/***/ }),

/***/ "./redux-templates/src/icons/images/elegant-blocks.svg":
/*!*************************************************************!*\
  !*** ./redux-templates/src/icons/images/elegant-blocks.svg ***!
  \*************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
function _extends() { _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; return _extends.apply(this, arguments); }



var _ref = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  d: "M1.04 4.76L9.2 7.44v11.68l-8.12-2.76m10.16 2.8l8.2-2.8V4.84L11.28 7.4m-1.04-1.36l7.52-2.44-7.52-2.28L2.52 3.6"
});

var SvgElegantBlocks = function SvgElegantBlocks(props) {
  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("svg", _extends({
    xmlns: "http://www.w3.org/2000/svg"
  }, props), _ref);
};

/* harmony default export */ __webpack_exports__["default"] = (SvgElegantBlocks);

/***/ }),

/***/ "./redux-templates/src/icons/images/enhanced-blocks.svg":
/*!**************************************************************!*\
  !*** ./redux-templates/src/icons/images/enhanced-blocks.svg ***!
  \**************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
function _extends() { _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; return _extends.apply(this, arguments); }



var _ref = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("circle", {
  cx: 210.86,
  cy: 44.87,
  r: 12.79
});

var _ref2 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  d: "M100.85 0A100.85 100.85 0 000 100.85V256h155.15A100.85 100.85 0 00256 155.15V0zm-59 149.49l-8.29 8.28a4.83 4.83 0 11-6.83-6.83l8.28-8.28a4.83 4.83 0 116.84 6.83zm34.86 53.16a9.31 9.31 0 010-13.13l10.69-10.69A9.28 9.28 0 01100.57 192l-10.69 10.65a9.31 9.31 0 01-13.13 0zm35.92 18.92l-7.41 7.43a5.75 5.75 0 01-8.13-8.13l7.45-7.45a5.75 5.75 0 018.13 8.13zM123.24 211a4.84 4.84 0 01-6.83 0l-1.23-1.24a4.81 4.81 0 010-6.8l31.07-31.48a8.4 8.4 0 000-11.88l-.89-.89a8.4 8.4 0 00-11.88 0l-16.94 16.94a8.41 8.41 0 01-11.89 0l-1.06-1.06a8.41 8.41 0 010-11.89l15.08-15.08a8.4 8.4 0 000-11.88l-.89-.89a8.41 8.41 0 00-11.89 0L90.8 149.93a8.42 8.42 0 01-13-1.35 8.61 8.61 0 011.33-10.76l16.71-16.71a8.41 8.41 0 000-11.89l-.88-.88a8.4 8.4 0 00-11.89 0L51.8 139.56a4.83 4.83 0 01-6.8-6.83l50.13-50.15 78.26 78.27zm52.54-52.54L97.52 80.19l3.28-3.28 78.27 78.26zm48.34-95.19a22.15 22.15 0 01-11.47 6.1 28.49 28.49 0 00-8.17 2.82 65.85 65.85 0 00-10.35 7.18c-10.3 11.06-12.26 20-12.25 26 0 16 14.07 26.16 9.68 37.63-1.91 5-6.41 7.87-9.38 9.38L143 113.18l-39.28-39.24c1.51-3 4.39-7.47 9.38-9.38 11.47-4.39 21.58 9.68 37.63 9.68 6 0 15-1.95 26-12.25a75.27 75.27 0 005.62-7.69 31.65 31.65 0 004.17-11 22.25 22.25 0 1137.58 19.94z"
});

var SvgEnhancedBlocks = function SvgEnhancedBlocks(props) {
  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("svg", _extends({
    viewBox: "0 0 256 256",
    xmlns: "http://www.w3.org/2000/svg"
  }, props), _ref, _ref2);
};

/* harmony default export */ __webpack_exports__["default"] = (SvgEnhancedBlocks);

/***/ }),

/***/ "./redux-templates/src/icons/images/essential-blocks.svg":
/*!***************************************************************!*\
  !*** ./redux-templates/src/icons/images/essential-blocks.svg ***!
  \***************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
function _extends() { _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; return _extends.apply(this, arguments); }



var _ref = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("defs", null, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("filter", {
  filterUnits: "objectBoundingBox",
  id: "essential-blocks_svg__a"
}, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("feOffset", {
  dy: 15,
  in: "SourceAlpha",
  result: "shadowOffsetOuter1"
}), /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("feGaussianBlur", {
  stdDeviation: 11,
  in: "shadowOffsetOuter1",
  result: "shadowBlurOuter1"
}), /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("feColorMatrix", {
  values: "0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0",
  in: "shadowBlurOuter1"
})), /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  d: "M135.028 96h103.944l.497.216h.373c6.217 0 11.273 2.774 15.169 8.322 1.326 2.305 1.989 4.683 1.989 7.133 0 5.98-3.398 10.59-10.195 13.833-2.321.937-4.642 1.405-6.963 1.405H133.909c-4.973 0-9.366-1.981-13.179-5.944-2.487-2.882-3.73-5.836-3.73-8.862v-1.297c0-4.755 2.735-8.826 8.206-12.212 2.984-1.585 5.927-2.378 8.828-2.378h.497l.497-.216zm1.242 77.273l58.078.21c3.862 0 7.995 1.544 12.397 4.632 3.503 3.158 5.255 6.596 5.255 10.316 0 5.193-3.1 9.438-9.298 12.736-3.054 1.404-6.019 2.106-8.893 2.106h-58.752c-5.75 0-10.735-2.246-14.958-6.737-2.066-2.526-3.099-5.053-3.099-7.58v-1.262c0-4.772 3.19-8.877 9.567-12.316 3.234-1.263 6.289-1.895 9.163-1.895h.27c.18 0 .27-.07.27-.21zM241.632 173h.736c5.685 0 10.14 2.968 13.369 8.905.842 2.12 1.263 4.17 1.263 6.148 0 5.936-2.912 10.495-8.737 13.675-2.105.848-3.965 1.272-5.579 1.272h-1.473c-4.772 0-8.843-2.509-12.211-7.527-1.333-2.544-2-5.052-2-7.526 0-5.795 2.877-10.318 8.632-13.569 2.175-.919 4.175-1.378 6-1.378zm-106.604 75.727h103.944c0 .142 1.284.318 3.854.53 4.31.919 7.584 2.58 9.822 4.983 2.901 2.897 4.352 6.219 4.352 9.964 0 5.725-3.398 10.248-10.195 13.57-2.073.635-3.482.953-4.228.953H131.423c-2.901 0-6.383-1.66-10.444-4.982-2.653-3.039-3.979-6.113-3.979-9.223v-.954c0-5.23 3.108-9.505 9.325-12.827 1.824-.918 4.642-1.554 8.455-1.908 0-.07.083-.106.248-.106z",
  id: "essential-blocks_svg__b"
}));

var _ref2 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("g", {
  fill: "none",
  fillRule: "evenodd"
}, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("circle", {
  fill: "#FFF",
  cx: 187,
  cy: 187,
  r: 187
}), /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("use", {
  fill: "#000",
  filter: "url(#essential-blocks_svg__a)",
  xlinkHref: "#essential-blocks_svg__b"
}), /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("use", {
  fill: "#23282D",
  xlinkHref: "#essential-blocks_svg__b"
}));

var SvgEssentialBlocks = function SvgEssentialBlocks(props) {
  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("svg", _extends({
    xmlns: "http://www.w3.org/2000/svg"
  }, props), _ref, _ref2);
};

/* harmony default export */ __webpack_exports__["default"] = (SvgEssentialBlocks);

/***/ }),

/***/ "./redux-templates/src/icons/images/forms-gutenberg.svg":
/*!**************************************************************!*\
  !*** ./redux-templates/src/icons/images/forms-gutenberg.svg ***!
  \**************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
function _extends() { _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; return _extends.apply(this, arguments); }



var _ref = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("style", null, ".forms-gutenberg_svg__st5{opacity:.1}.forms-gutenberg_svg__st6{opacity:.16}.forms-gutenberg_svg__st7{opacity:.6}.forms-gutenberg_svg__st8{fill:#fff}.forms-gutenberg_svg__st9{opacity:.3}");

var _ref2 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("linearGradient", {
  id: "forms-gutenberg_svg__SVGID_1_",
  gradientUnits: "userSpaceOnUse",
  x1: 23.101,
  y1: 4.596,
  x2: 17.855,
  y2: 19.01,
  gradientTransform: "matrix(1 0 0 -1 0 33)"
}, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("stop", {
  offset: 0,
  stopColor: "#4facfe"
}), /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("stop", {
  offset: 1,
  stopColor: "#00f2fe"
}));

var _ref3 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  d: "M29 13.1l-1-2.8-5.2 1.9c0 .1.1.3.1.4.1.2.1.5.1.7 0 .1 0 .3.1.4v.8c0 .5.1 1 0 1.6v1c-.1.5-.1 1-.3 1.6h.1c-.1.5-.2 1-.5 1.4-.1.4-.3.8-.4 1.2-.2.4-.3.7-.5 1.1-.1.2-.2.4-.3.5-.1.2-.2.3-.3.5-.2.4-.5.7-.7 1-.1.2-.3.4-.4.5-.1.1-.2.3-.3.4-.1.1-.2.3-.3.4l-.9.9-.4.4c-.3.2-.6.5-.9.7-.1.1-.2.2-.3.2-.2.2-.4.3-.7.5-.1.1-.2.1-.2.2-.2.1-.3.2-.5.3-.2.1-.3.2-.5.3l-.6.3c-.2.1-.3.2-.5.2-.1 0-.1.1-.2.1 0 0-.1 0-.1.1-.1 0-.2.1-.3.1-.2.1-.5.2-.7.3 1.8.3 3.7.4 5.5 0 1.8-.3 3.6-1 5.2-2 2.2-1.3 3.9-3.2 5.1-5.3 1.2-2.1 1.7-4.5 1.5-6.8-.1-1.2-.3-2.1-.7-3.1z",
  fill: "url(#forms-gutenberg_svg__SVGID_1_)"
});

var _ref4 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("linearGradient", {
  id: "forms-gutenberg_svg__SVGID_2_",
  gradientUnits: "userSpaceOnUse",
  x1: 20.054,
  y1: 13.455,
  x2: 18.011,
  y2: 19.068,
  gradientTransform: "matrix(1 0 0 -1 0 33)"
}, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("stop", {
  offset: 0,
  stopColor: "#00c6fb"
}), /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("stop", {
  offset: 1,
  stopColor: "#005bea"
}));

var _ref5 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  d: "M22.8 12.2l-6.4 2.3c-.4.1-.8.4-1.1.6-.3.3-.6.6-.8 1-.2.4-.3.8-.4 1.2 0 .4 0 .8.2 1.2.1.4.4.7.6 1 .3.3.6.5 1 .7.4.2.8.2 1.3.2.4 0 .9-.1 1.3-.2l4.4-1.6c.5-2.2.4-4.4-.1-6.4z",
  fill: "url(#forms-gutenberg_svg__SVGID_2_)"
});

var _ref6 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("linearGradient", {
  id: "forms-gutenberg_svg__SVGID_3_",
  gradientUnits: "userSpaceOnUse",
  x1: 15.53,
  y1: 4.084,
  x2: 8.873,
  y2: 22.373,
  gradientTransform: "matrix(1 0 0 -1 0 33)"
}, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("stop", {
  offset: 0,
  stopColor: "#89f7fe"
}), /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("stop", {
  offset: 1,
  stopColor: "#66a6ff"
}));

var _ref7 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  d: "M22.5 19.9c-.4.9-1 1.7-1.7 2.4s-1.6 1.2-2.6 1.6c-.4.2-.9.3-1.4.4-.5.1-.9.1-1.4.1-1.4 0-2.7-.4-3.8-1.1-.2-.1-.4-.2-.7-.3-2-1-3.7-2.5-4.9-4.3-1.2-1.8-1.8-3.9-1.9-6v-.2c-1.8 2.9-2.3 6.2-1.5 9.2s2.9 5.6 5.8 7.2c1.2.6 2.5 1.1 3.8 1.3 3.2-1.2 6-3.2 7.9-5.8 1.1-1.5 1.9-2.9 2.4-4.5z",
  fill: "url(#forms-gutenberg_svg__SVGID_3_)"
});

var _ref8 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("linearGradient", {
  id: "forms-gutenberg_svg__SVGID_4_",
  gradientUnits: "userSpaceOnUse",
  x1: 19.95,
  y1: 23.365,
  x2: 17.904,
  y2: 28.987,
  gradientTransform: "matrix(1 0 0 -1 0 33)"
}, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("stop", {
  offset: 0,
  stopColor: "#4facfe"
}), /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("stop", {
  offset: 1,
  stopColor: "#00f2fe"
}));

var _ref9 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  d: "M24.5 1.6l-5.9 2.1c0 .1-.1.2-.1.3-.1.3-.2.5-.2.8-.2.6-.5 1.3-.8 1.9-.1.1-.1.2-.2.4v.1c-.1.2-.2.3-.3.5-.1.2-.2.3-.3.5-.1.1-.2.3-.3.4-.1.1-.1.2-.2.3l-.2.2c-.1.1-.2.2-.2.3l-.1.1c-.1.1-.2.3-.3.4v.1c-.1.1-.2.3-.4.4-.1.1-.2.3-.3.4 0 .1-.1.1-.1.1l-.3.3-.4.4-.3.3c.1-.1.3-.1.4-.2l6.4-2.3 1.2-.6c1.3-.8 2.3-1.9 2.9-3.3.4-1.2.4-2.6 0-3.9z",
  fill: "url(#forms-gutenberg_svg__SVGID_4_)"
});

var _ref10 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("linearGradient", {
  id: "forms-gutenberg_svg__SVGID_5_",
  gradientUnits: "userSpaceOnUse",
  x1: 15.998,
  y1: 11.362,
  x2: 10.556,
  y2: 26.312,
  gradientTransform: "matrix(1 0 0 -1 0 33)"
}, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("stop", {
  offset: 0,
  stopColor: "#00c6fb"
}), /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("stop", {
  offset: 1,
  stopColor: "#005bea"
}));

var _ref11 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  d: "M18.6 3.8l-6.8 2.5c-3 1.1-5.6 3.1-7.3 5.7-.1.1-.2.3-.3.4v1.1c0 .2 0 .4.1.6 0 .1 0 .2.1.3 0 .1.1.3.1.4 0 .1.1.2.1.3v.2l.3.9c0 .1.1.1.1.2s0 .1.1.2v.1s0 .1.1.1c0 .1.1.2.2.4 0 0 0 .1.1.1.1.2.1.3.2.5.1.1.1.2.2.3 0 .1.1.1.1.2.2.3.3.5.5.8.1.1.1.2.2.2.3.4.6.8 1 1.2l.3.3c.2.2.5.4.7.6.1.1.2.2.3.2.3.2.6.5.9.7.2.1.3.2.5.3.2.1.3.2.5.3 0 0 .1 0 .1.1.2.1.3.2.5.2-1.1-.7-1.9-1.7-2.4-2.9-.1-.8-.2-1.6-.1-2.5 0-.8.3-1.7.7-2.5.8-1.4 2.1-2.6 3.7-3.3l.3-.3.4-.4.3-.3.1-.1c.1-.1.2-.2.3-.4l.4-.4V10c.1-.1.2-.3.3-.4l.1-.1c.1-.1.2-.2.2-.3L16 9c.1-.1.1-.2.2-.3.1-.1.2-.3.3-.4.1-.2.2-.3.3-.5.1-.2.2-.3.3-.5v-.1c.1-.1.1-.2.2-.4.3-.6.6-1.2.8-1.9.1-.3.2-.5.2-.8.2-.1.3-.2.3-.3z",
  fill: "url(#forms-gutenberg_svg__SVGID_5_)"
});

var _ref12 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("g", {
  className: "forms-gutenberg_svg__st5"
}, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  d: "M18.2 23.8c-.4.2-.9.3-1.4.4-2 1.7-4.7 3.2-8.4 4.6 1.2.6 2.5 1.1 3.8 1.3 3.2-1.2 6-3.2 7.9-5.8 1-1.4 1.8-2.8 2.3-4.4-.4.9-1 1.7-1.7 2.4s-1.6 1.2-2.5 1.5z"
}));

var _ref13 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("g", {
  className: "forms-gutenberg_svg__st6"
}, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  d: "M22.8 12.2l-.6.2c.2 2.3-.3 4.7-1.4 6.9l2-.7c.6-2.2.5-4.4 0-6.4z"
}));

var _ref14 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("g", {
  className: "forms-gutenberg_svg__st5"
}, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  d: "M7.2 8.9c-1.1.9-2 1.9-2.8 3.1-.1.1-.2.3-.3.4v1.1c0 .2 0 .4.1.6 0 .1 0 .2.1.3 0 .1.1.3.1.4 0 .1.1.2.1.3v.2l.3.9c0 .1.1.1.1.2s0 .1.1.2v.1s0 .1.1.1c0 .1.1.2.2.4 0 0 0 .1.1.1.1.2.1.3.2.5.1.1.1.2.2.3 0 .1.1.1.1.2.2.3.3.5.5.8.1.1.1.2.2.2.3.4.6.8 1 1.2l.3.3c.2.2.5.4.7.6.1.1.2.2.3.2.3.2.6.5.9.7.2.1.3.2.5.3.2.1.3.2.5.3 0 0 .1 0 .1.1.2.1.3.2.5.2-1.1-.7-1.9-1.7-2.4-2.9C7.4 18 6 14.4 6.9 9.1c.3-.1.3-.2.3-.2z"
}));

var _ref15 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("g", {
  className: "forms-gutenberg_svg__st5"
}, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  d: "M22.5 2.4l-3.9 1.4c0 .1-.1.2-.1.3-.1.3-.2.5-.2.8-.2.6-.5 1.3-.8 1.9-.1.1-.1.2-.2.4v.1c-.1.2-.2.3-.3.5-.1.2-.2.3-.3.5-.1.1-.2.3-.3.4-.1.1-.1.2-.2.3-.1 0-.1 0-.2.1s-.2.2-.2.3l-.1.1c-.1.1-.2.3-.3.4v.1c-.1.1-.2.3-.4.4-.1.1-.2.3-.3.4 0 .1-.1.1-.1.1l-.3.3-.4.4-.3.3c.1-.1.3-.1.4-.2l1.7-.6c2.9-2.4 5.2-5.3 6.8-8.5-.1 0-.1-.1 0-.2z"
}));

var _ref16 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("g", {
  className: "forms-gutenberg_svg__st7"
}, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("g", {
  className: "forms-gutenberg_svg__st7"
}, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  className: "forms-gutenberg_svg__st8",
  d: "M22.1 21.1c-.8 1.9-2 3.6-3.5 5.1-1.5 1.5-3.3 2.7-5.3 3.6.1 0 .2-.1.5-.2.2-.1.4-.1.6-.2.3-.1.5-.2.8-.3.1-.1.3-.1.4-.2.1-.1.3-.2.4-.3l.9-.6c.3-.2.6-.4.9-.7.3-.3.6-.5.9-.8.3-.3.6-.6.8-.9.3-.3.5-.6.7-.9l.6-.9c.2-.3.3-.5.5-.8.1-.3.2-.5.3-.7.1-.2.1-.4.2-.6.2-.4.2-.6.3-.6z"
})));

var _ref17 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("g", {
  className: "forms-gutenberg_svg__st7"
}, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("g", {
  className: "forms-gutenberg_svg__st7"
}, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  className: "forms-gutenberg_svg__st8",
  d: "M18.6 3.8l-.2.4-.5 1-.3.6c-.1.2-.2.5-.4.7L16.3 8c-.3.5-.6 1-.9 1.4-.1.2-.2.4-.4.6-.2.2-.3.4-.4.6-.3.4-.5.7-.7.9-.1.2-.2.3-.2.3 2.4-2.3 4.1-5 4.9-8z"
})));

var _ref18 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("g", {
  className: "forms-gutenberg_svg__st7"
}, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("g", {
  className: "forms-gutenberg_svg__st7"
}, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  className: "forms-gutenberg_svg__st8",
  d: "M4.9 16.2c-.4-1.2-.7-2.4-.7-3.6v.6c0 .5 0 1 .1 1.5.1.7.3 1.4.5 2 .1.3.2.7.4 1 .2.4.4.7.6 1.1.2.3.5.7.7 1 .1.2.3.3.4.5l.4.4c.3.3.6.5.9.7.3.2.5.4.8.6.2.2.5.3.8.4l.6.3c.3.1.5.2.5.2-2.8-1.5-4.9-3.8-6-6.7z"
})));

var _ref19 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("g", {
  className: "forms-gutenberg_svg__st9"
}, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  d: "M24.5 1.6L11.8 6.2c-3 1.1-5.6 3.1-7.3 5.7.3-.4.6-.7.9-1.1.3-.4.6-.7 1-1 .3-.3.6-.5.9-.8-.1 0 0 0 0 0 .4-.3.8-.6 1.1-.8.4-.3.8-.5 1.2-.7.5-.3 1-.5 1.4-.7.4-.2.9-.3 1.3-.5l1.4-.5 2.7-1 2.7-1c1.1-.4 2.1-.8 3.2-1.2.7-.2 1.3-.5 2-.8l.1.2.1.5.1.5v1l-.1.5-.1.5-.2.5-.2.6-.2.5-.4.4-.3.4-.4.4-.4.4-.4.4-.5.3c1.3-.8 2.3-1.9 2.9-3.3.6-1.3.6-2.7.2-4z"
}));

var _ref20 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("g", {
  className: "forms-gutenberg_svg__st9"
}, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  d: "M29 13.1l-1-2.8-4.5 1.6c.8-.2 1.5-.5 2.3-.7.7-.2 1.4-.4 2.1-.7l.7 2.2c.1.4.3.8.4 1.2l.3 1.2c.1.4.1.8.1 1.2v1.2c0 .4-.1.8-.1 1.2-.1.4-.1.8-.3 1.2-.1.4-.2.8-.4 1.2-.1.2-.2.4-.2.6l-.3.6-.3.6-.3.6c-.1.2-.2.4-.3.5-.1.2-.2.4-.4.5l-.4.5-.4.4c-.1.2-.3.3-.4.5l-.5.5c-.3.3-.7.6-1 .9-.3.3-.7.5-1.1.8 2.2-1.3 3.9-3.2 5.1-5.3 1.2-2.1 1.7-4.5 1.5-6.8 0-1-.2-1.9-.6-2.9z"
}));

var _ref21 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("text", {
  transform: "translate(300 167.5)",
  fontFamily: "MyriadPro-Regular",
  fontSize: 12
}, "32");

var SvgFormsGutenberg = function SvgFormsGutenberg(props) {
  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("svg", _extends({
    id: "forms-gutenberg_svg__Layer_1",
    viewBox: "0 0 32 32",
    xmlSpace: "preserve",
    xmlns: "http://www.w3.org/2000/svg"
  }, props), _ref, _ref2, _ref3, _ref4, _ref5, _ref6, _ref7, _ref8, _ref9, _ref10, _ref11, _ref12, _ref13, _ref14, _ref15, _ref16, _ref17, _ref18, _ref19, _ref20, _ref21);
};

/* harmony default export */ __webpack_exports__["default"] = (SvgFormsGutenberg);

/***/ }),

/***/ "./redux-templates/src/icons/images/getwid.svg":
/*!*****************************************************!*\
  !*** ./redux-templates/src/icons/images/getwid.svg ***!
  \*****************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
function _extends() { _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; return _extends.apply(this, arguments); }



var _ref = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  fill: "#b3c315",
  d: "M70 54h41v18H70z"
});

var _ref2 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  fill: "#96a40a",
  d: "M65 54h5v18h-5z"
});

var _ref3 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  fill: "#b3c315",
  d: "M94 50h13v3H94z"
});

var _ref4 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  fill: "#96a40a",
  d: "M89 50h5v3h-5z"
});

var _ref5 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  fill: "#b3c315",
  d: "M74 50h13v3H74z"
});

var _ref6 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  fill: "#96a40a",
  d: "M69 50h5v3h-5z"
});

var _ref7 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  fill: "#b977c7",
  d: "M72 92h13v3H72z"
});

var _ref8 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  fill: "#9e5cac",
  d: "M67 92h5v3h-5z"
});

var _ref9 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  fill: "#b977c7",
  d: "M51 92h13v3H51z"
});

var _ref10 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  fill: "#9e5cac",
  d: "M46 92h5v3h-5z"
});

var _ref11 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  fill: "#49c3f5",
  d: "M92 75h19v19H92z"
});

var _ref12 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  fill: "#109ad4",
  d: "M87 75h5v19h-5z"
});

var _ref13 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  d: "M25 97h86l-18 19H43L25 97z",
  fill: "#b977c7"
});

var _ref14 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  fill: "#9e5cac",
  d: "M20 97l18 19h5L25 97z"
});

var _ref15 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  d: "M111 36H25l18-19h50l18 19z",
  fill: "#f1b014"
});

var _ref16 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  fill: "#f09108",
  d: "M43 17h-5L20 36h5z"
});

var _ref17 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  fill: "#f1b014",
  d: "M51 12h14v3H51z"
});

var _ref18 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  fill: "#f09108",
  d: "M46 12h5v3h-5z"
});

var _ref19 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  fill: "#f1b014",
  d: "M73 12h14v3H73z"
});

var _ref20 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  fill: "#f09108",
  d: "M68 12h5v3h-5z"
});

var _ref21 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  fill: "#49c3f5",
  d: "M25 39h18v26H25z"
});

var _ref22 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  fill: "#109ad4",
  d: "M20 39h5v26h-5z"
});

var _ref23 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  fill: "#d25e4d",
  d: "M20 68h5v26h-5z"
});

var _ref24 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  fill: "#e67666",
  d: "M25 68h18v26H25z"
});

var SvgGetwid = function SvgGetwid(props) {
  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("svg", _extends({
    viewBox: "0 0 128 128",
    xmlns: "http://www.w3.org/2000/svg"
  }, props), _ref, _ref2, _ref3, _ref4, _ref5, _ref6, _ref7, _ref8, _ref9, _ref10, _ref11, _ref12, _ref13, _ref14, _ref15, _ref16, _ref17, _ref18, _ref19, _ref20, _ref21, _ref22, _ref23, _ref24);
};

/* harmony default export */ __webpack_exports__["default"] = (SvgGetwid);

/***/ }),

/***/ "./redux-templates/src/icons/images/ghostkit.svg":
/*!*******************************************************!*\
  !*** ./redux-templates/src/icons/images/ghostkit.svg ***!
  \*******************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
function _extends() { _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; return _extends.apply(this, arguments); }



var _ref = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  d: "M201 94.59h12.5H201zm-146 0h12.5H55zm20.038 115.868c-6.137-3.161-13.675-.749-16.836 5.388-3.162 6.137-.75 13.675 5.387 16.837l11.45-22.225zm117.373 22.225c6.137-3.162 8.549-10.7 5.387-16.837-3.161-6.137-10.699-8.549-16.836-5.388l11.449 22.225zM128 33.5c33.318 0 60.5 27.256 60.5 61.09h25c0-47.45-38.185-86.09-85.5-86.09v25zm60.5 61.09c0 33.835-27.182 61.091-60.5 61.091v25c47.315 0 85.5-38.639 85.5-86.09h-25zM128 155.682c-33.319 0-60.5-27.256-60.5-61.09h-25c0 47.451 38.185 86.09 85.5 86.09v-25zm-60.5-61.09C67.5 60.756 94.681 33.5 128 33.5v-25c-47.315 0-85.5 38.64-85.5 86.09h25zm-3.91 138.092c40.937 21.089 87.883 21.089 128.821 0l-11.449-22.225c-33.754 17.389-72.17 17.389-105.924 0L63.59 232.683z"
});

var SvgGhostkit = function SvgGhostkit(props) {
  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("svg", _extends({
    xmlns: "http://www.w3.org/2000/svg"
  }, props), _ref);
};

/* harmony default export */ __webpack_exports__["default"] = (SvgGhostkit);

/***/ }),

/***/ "./redux-templates/src/icons/images/guteblock.svg":
/*!********************************************************!*\
  !*** ./redux-templates/src/icons/images/guteblock.svg ***!
  \********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
function _extends() { _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; return _extends.apply(this, arguments); }



var _ref = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("style", null);

var _ref2 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("g", {
  id: "guteblock_svg__prefix__Layer_1"
}, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("linearGradient", {
  id: "guteblock_svg__prefix__SVGID_1_",
  gradientUnits: "userSpaceOnUse",
  x1: 68.035,
  y1: 10.602,
  x2: 33.222,
  y2: 86.651
}, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("stop", {
  offset: 0,
  stopColor: "#ffd500"
}), /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("stop", {
  offset: 0.258,
  stopColor: "#ff683e"
}), /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("stop", {
  offset: 0.498,
  stopColor: "#ff1d6b"
}), /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("stop", {
  offset: 0.781,
  stopColor: "#5d25cd"
}), /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("stop", {
  offset: 1,
  stopColor: "#3eb9fa"
})), /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  d: "M31.95 17.49L17.49 31.95c-9.14 9.14-9.14 23.96 0 33.1L34.94 82.5c9.14 9.14 23.96 9.14 33.1 0L82.5 68.04c9.14-9.14 9.14-23.96 0-33.1L65.06 17.49c-9.15-9.14-23.96-9.14-33.11 0z",
  fill: "url(#guteblock_svg__prefix__SVGID_1_)"
}), /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  d: "M65.81 36.74c-3.94 0-7.64 1.53-10.43 4.32l-5.85 5.85a6.488 6.488 0 000 9.17 6.488 6.488 0 009.17 0l5.85-5.85c.43-.43.92-.52 1.26-.52.34 0 .83.09 1.26.52.43.43.52.92.52 1.26 0 .34-.09.83-.52 1.26L52.98 66.83c-.05.05-.1.1-.14.15-.44.47-.96.58-1.32.58-.36.01-.88-.08-1.33-.54L32.98 49.81c-.69-.69-.72-1.79-.09-2.52l14.17-14.17c.05-.05.1-.1.14-.15.67-.71 1.78-.73 2.54-.04l.98.95c2.57 2.49 6.68 2.43 9.17-.14 2.49-2.57 2.43-6.68-.14-9.17l-1.03-1c-.03-.03-.05-.05-.08-.07-5.94-5.57-15.25-5.33-20.82.51l-14.2 14.2c-.05.05-.1.1-.14.15a14.744 14.744 0 00.33 20.61l17.21 17.22c2.8 2.81 6.51 4.34 10.47 4.34h.24c4.01-.06 7.73-1.69 10.49-4.59l14.02-14.02c2.79-2.79 4.32-6.49 4.32-10.43 0-3.94-1.53-7.64-4.32-10.43a14.668 14.668 0 00-10.43-4.32z",
  fill: "#fff"
}));

var SvgGuteblock = function SvgGuteblock(props) {
  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("svg", _extends({
    viewBox: "0 0 100 100",
    "aria-hidden": "true",
    xmlns: "http://www.w3.org/2000/svg"
  }, props), _ref, _ref2);
};

/* harmony default export */ __webpack_exports__["default"] = (SvgGuteblock);

/***/ }),

/***/ "./redux-templates/src/icons/images/gutentor.svg":
/*!*******************************************************!*\
  !*** ./redux-templates/src/icons/images/gutentor.svg ***!
  \*******************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
function _extends() { _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; return _extends.apply(this, arguments); }



var _ref = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  d: "M17.388 5.75l-6.716-3.91a1.173 1.173 0 00-1.104 0l-.184.092c0 .046.046.092.046.138v2.3c0 .138-.092.276-.276.276h-2.3c-.138 0-.276-.092-.276-.276v-.828l-.506.276v2.53a.297.297 0 01-.276.276H3.22a.297.297 0 01-.276-.276v-.736l-.138.092c-.322.184-.552.598-.552.966v1.15H3.68c.138 0 .23.092.23.23v2.208c0 .138-.092.23-.23.23H2.254v3.956c0 .368.23.782.552.966l6.716 3.91c.322.184.782.184 1.104 0l6.762-3.864c.322-.184.552-.598.552-.966V6.67c0-.322-.23-.736-.552-.92zm-3.358 7.038l-3.864 2.3L6.21 12.88l-.092-4.508 3.864-2.3 3.68 2.07-.46.782-3.22-1.794-2.944 1.748.046 3.45 2.99 1.656 2.944-1.748v-.644l-2.668-.046v-.92l3.542.092v2.07zM2.3 5.014a.099.099 0 01-.092.092h-.782c-.046 0-.046-.046-.046-.092v-.736c0-.046 0-.092.046-.092h.736c.092 0 .138.046.138.092v.736zM1.518 7.222c0 .092-.046.138-.138.138H.138C.046 7.36 0 7.314 0 7.222V5.98c0-.092.046-.138.138-.138H1.38c.092 0 .138.046.138.138v1.242z"
});

var _ref2 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  d: "M3.496 4.048h2.162V6.21H3.496zM7.038 2.254h1.978v1.978H7.038zM3.68 3.174c0 .046-.046.138-.138.138H2.346c-.092 0-.138-.092-.138-.138V1.932c0-.092.046-.138.138-.138h1.196c.092 0 .138.092.138.138v1.242zM6.164 2.622c0 .092-.046.138-.138.138H4.83c-.092 0-.138-.046-.138-.138V1.426c0-.092.046-.138.138-.138h1.196c.092 0 .138.046.138.138v1.196zM8.05 1.38a.099.099 0 01-.092.092H6.9a.099.099 0 01-.092-.092V.322c0-.046.046-.092.092-.092h1.012c.046 0 .092.046.092.092V1.38zM1.564 8.28H3.45v1.886H1.564z"
});

var SvgGutentor = function SvgGutentor(props) {
  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("svg", _extends({
    viewBox: "-1.1 -0.2 20 20",
    xmlns: "http://www.w3.org/2000/svg"
  }, props), _ref, _ref2);
};

/* harmony default export */ __webpack_exports__["default"] = (SvgGutentor);

/***/ }),

/***/ "./redux-templates/src/icons/images/kadence-blocks.svg":
/*!*************************************************************!*\
  !*** ./redux-templates/src/icons/images/kadence-blocks.svg ***!
  \*************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
function _extends() { _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; return _extends.apply(this, arguments); }



var _ref = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  d: "M40.145 6.164L7.855 41.837V6.164h32.29z",
  fillOpacity: 0.502,
  fillRule: "nonzero"
});

var _ref2 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  d: "M40.145 41.836L7.855 6.163v35.673h32.29z",
  fillRule: "nonzero"
});

var SvgKadenceBlocks = function SvgKadenceBlocks(props) {
  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("svg", _extends({
    viewBox: "0 0 48 48",
    fillRule: "evenodd",
    clipRule: "evenodd",
    strokeLinejoin: "round",
    strokeMiterlimit: 1.414,
    xmlns: "http://www.w3.org/2000/svg"
  }, props), _ref, _ref2);
};

/* harmony default export */ __webpack_exports__["default"] = (SvgKadenceBlocks);

/***/ }),

/***/ "./redux-templates/src/icons/images/kioken-blocks.svg":
/*!************************************************************!*\
  !*** ./redux-templates/src/icons/images/kioken-blocks.svg ***!
  \************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
function _extends() { _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; return _extends.apply(this, arguments); }



var _ref = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  d: "M23.706 7.854l.004.01c.734 2.723.003 5.708-1.395 8.169-1.669 2.927-4.184 5.357-7.464 6.256-4.204 1.164-7.863-.293-10.944-3.149C-.097 15.427-1.665 9.8 2.303 5.352a17.352 17.352 0 015.683-4.009A14.566 14.566 0 0112.498.077c1.734-.184 3.298-.075 4.885.732a14.601 14.601 0 013.615 2.583 9.982 9.982 0 012.708 4.462zm-12.659 4.272a.03.03 0 01.025.012l2.536 3.432c.25.338.66.54 1.1.54h1.573c.246 0 .485-.075.682-.213.502-.353.605-1.02.228-1.49l-2.343-2.924a1.188 1.188 0 01.05-1.558l2.045-2.26a.91.91 0 00.24-.61c0-.523-.453-.946-1.011-.946H14.66a1.37 1.37 0 00-1.07.502l-2.534 3.173a.032.032 0 01-.025.012c-.009 0-.016-.007-.016-.015V7.359c0-.69-.598-1.25-1.336-1.25h-.925c-.739 0-.81.56-.81 1.25v7.5c0 .69.071 1.25.81 1.25h.94c.738 0 1.337-.56 1.337-1.25v-2.718c0-.008.007-.015.015-.015z"
});

var SvgKiokenBlocks = function SvgKiokenBlocks(props) {
  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("svg", _extends({
    viewBox: "0 -0.5 24 24",
    xmlns: "http://www.w3.org/2000/svg"
  }, props), _ref);
};

/* harmony default export */ __webpack_exports__["default"] = (SvgKiokenBlocks);

/***/ }),

/***/ "./redux-templates/src/icons/images/otter-blocks.svg":
/*!***********************************************************!*\
  !*** ./redux-templates/src/icons/images/otter-blocks.svg ***!
  \***********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
function _extends() { _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; return _extends.apply(this, arguments); }



var _ref = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  d: "M19.831 7.877l.001-.009-.001-.009a3.675 3.675 0 01-.132-.247l-.057-.115c-.277-.498-.381-.99-1.033-1.064h-.048a.91.91 0 00-.908.862v.002c.674.126 1.252.278 1.813.468l-.092-.027.283.096.147.053s.028 0 .028-.011z"
});

var _ref2 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  d: "M23.982 13.574a68.843 68.843 0 00-.39-7.112l.026.299.07-.019a1.1 1.1 0 00.052-2.09l-.008-.002h-.026a2.306 2.306 0 01-1.149-.861l-.005-.007C19.852-.178 14.3.001 14.3.001S8.75-.178 6.05 3.782c-.28.401-.676.704-1.14.862l-.016.005a1.097 1.097 0 00-.754 1.04v.026-.001l-.001.034c0 .493.335.907.789 1.029l.007.002.045.011a65.7 65.7 0 00-.364 6.801v.012s-9.493 13.012-1.277 17.515c4.733 2.431 6.881-.769 6.881-.769s1.397-1.661-1.784-3.355v-4.609a.638.638 0 01.625-.628h1.212v-.59c0-.275.223-.498.498-.498h1.665a.498.498 0 01.496.498v.59h2.721v-.59c0-.275.223-.498.498-.498h1.665c.271.005.49.226.49.498v.59h1.209c.349 0 .633.28.639.627v4.584c-3.193 1.703-1.784 3.355-1.784 3.355s2.148 3.193 6.879.769c8.222-4.503-1.269-17.515-1.269-17.515zm-1.396-3.313a6.398 6.398 0 01-1.563 3.797l.007-.008c-1.703 2.01-4.407 3.249-6.721 4.432-2.325-1.177-5.026-2.416-6.736-4.432a6.43 6.43 0 01-1.555-3.769l-.001-.02c-.126-2.22.583-5.929 3.044-6.74 2.416-.788 3.947 1.288 4.494 2.227a.863.863 0 001.488.004l.002-.004c.551-.932 2.08-3.008 4.494-2.22 2.474.805 3.174 4.513 3.046 6.734z"
});

var _ref3 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  d: "M19.463 10.087h-.028c-.192.026-.121.251-.047.356.254.349.407.787.407 1.26v.018-.001a1.277 1.277 0 01-.633 1.1l-.006.003c-.739.426-1.377-.145-2.054-.398a7.5 7.5 0 00-2.42-.455h-.009v-1.033a4.886 4.886 0 002.551-1.486l.004-.004a.916.916 0 00-.158-1.383l-.003-.002a4.738 4.738 0 00-2.764-.881 4.752 4.752 0 00-2.819.92l.013-.009a.9.9 0 00-.146 1.317l-.001-.001a4.906 4.906 0 002.553 1.53l.033.007v1.05a8.061 8.061 0 00-2.118.343l.057-.015a5.578 5.578 0 00-.908.358l.033-.015c-.519.26-1.037.436-1.58.121a1.218 1.218 0 01-.617-1.058v-.007-.007c0-.47.153-.905.411-1.257l-.004.006c.047-.068.089-.17.026-.241s-.189 0-.27.03a1.592 1.592 0 00-.479.381l-.002.002a1.716 1.716 0 00-.394 1.097v.011-.001a1.93 1.93 0 00.964 1.651l.009.005c.296.178.654.283 1.036.283.364 0 .706-.095 1.001-.263l-.01.005a6.51 6.51 0 013.225-.728h-.01.03c1.277 0 2.382.266 3.266.775.27.159.594.253.94.253h.003c.355-.002.688-.098.974-.265l-.009.005a2.028 2.028 0 001.007-1.75v-.004l.002-.086c0-.625-.34-1.171-.846-1.462l-.008-.004a.388.388 0 00-.199-.07h-.001zm-6.362-1.256c-.238.213-.468.581-.832.345a.933.933 0 01-.161-.136.352.352 0 01.081-.555l.002-.001c.594-.309 1.203-.543 1.884-.49-.324.281-.649.56-.973.837z"
});

var _ref4 = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  d: "M15.89 13.578a1.988 1.988 0 01-3.173.005l-.004-.005a.36.36 0 10-.576.427 2.707 2.707 0 004.323.007l.005-.007a.362.362 0 00-.072-.498l-.001-.001a.356.356 0 00-.501.071l-.001.001zM18.507 11.707a.35.35 0 11-.702 0 .35.35 0 01.702 0zM17.389 11.049a.35.35 0 11-.702 0 .35.35 0 01.702 0zM10.798 11.707a.35.35 0 11-.702 0 .35.35 0 01.702 0zM11.918 11.049a.35.35 0 11-.702 0 .35.35 0 01.702 0zM8.773 7.877l-.002-.009.002-.009c.047-.081.089-.164.132-.247.019-.038.036-.079.057-.115.275-.498.379-.99 1.033-1.064h.046c.487 0 .884.382.91.862v.002c-.678.124-1.261.277-1.827.468l.092-.027-.275.096-.1.036-.045.017s-.023 0-.023-.011z"
});

var SvgOtterBlocks = function SvgOtterBlocks(props) {
  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("svg", _extends({
    viewBox: "0 0 29 32",
    xmlns: "http://www.w3.org/2000/svg"
  }, props), _ref, _ref2, _ref3, _ref4);
};

/* harmony default export */ __webpack_exports__["default"] = (SvgOtterBlocks);

/***/ }),

/***/ "./redux-templates/src/icons/images/qodeblock.svg":
/*!********************************************************!*\
  !*** ./redux-templates/src/icons/images/qodeblock.svg ***!
  \********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
function _extends() { _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; return _extends.apply(this, arguments); }



var _ref = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("g", {
  fill: "none",
  fillRule: "evenodd"
}, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  d: "M0 0h21v21H0z"
}), /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  d: "M8.296 9.925c.014.013.029.022.042.034l-2.456 2.455A5.376 5.376 0 014.697 10.9C4.232 10.024 4 9.02 4 7.884c0-1.134.232-2.15.697-3.045.21-.402.456-.76.732-1.081l2.514 2.514c-.245.432-.375.966-.375 1.612 0 .902.243 1.582.728 2.04zm7.782-7.707v12.19l-4.393-4.394c.053-.044.108-.08.159-.13.499-.485.749-1.172.749-2.06 0-.68-.15-1.24-.441-1.679l3.926-3.927z",
  fill: "#444"
}), /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  d: "M12.593 11.7c-.793 1.312-1.928 1.968-3.405 1.968a4.878 4.878 0 01-2.614-.728 4.966 4.966 0 01-.691-.525L8.338 9.96c.478.433 1.054.654 1.732.654.614 0 1.15-.207 1.615-.598l4.393 4.393V19h-3.485v-7.3zm3.485-9.597v.116l-3.926 3.927a2.476 2.476 0 00-2.082-1.09c-.684 0-1.272.242-1.764.727-.144.143-.26.31-.363.49L5.43 3.759a5.031 5.031 0 011.155-1.01A4.795 4.795 0 019.188 2c1.531 0 2.666.588 3.405 1.764V2.103h3.485z",
  fill: "#000"
}));

var SvgQodeblock = function SvgQodeblock(props) {
  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("svg", _extends({
    xmlns: "http://www.w3.org/2000/svg"
  }, props), _ref);
};

/* harmony default export */ __webpack_exports__["default"] = (SvgQodeblock);

/***/ }),

/***/ "./redux-templates/src/icons/images/qubely.svg":
/*!*****************************************************!*\
  !*** ./redux-templates/src/icons/images/qubely.svg ***!
  \*****************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
function _extends() { _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; return _extends.apply(this, arguments); }



var _ref = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  d: "M17.38 8.8c0-2.42-.88-4.4-2.53-6.05C13.2 1.1 11.11.22 8.8.22s-4.4.88-6.05 2.53C1.1 4.4.22 6.38.22 8.8s.88 4.4 2.53 6.05c1.65 1.65 3.63 2.53 6.05 2.53.99 0 1.98-.11 2.86-.44l-2.42-2.53c-.11-.11-.33-.22-.44-.22-1.54 0-2.75-.55-3.74-1.54-1.1-.99-1.54-2.31-1.54-3.85s.55-2.86 1.54-3.85c.99-.99 2.2-1.54 3.74-1.54s2.75.55 3.74 1.54c.99.99 1.54 2.31 1.54 3.85 0 .77-.11 1.54-.44 2.2-.22.55-.88.66-1.32.22-1.21-1.21-3.08-1.32-4.4-.22l2.75 2.86 2.31 2.42c.99.99 2.64 1.1 3.74.11l.33-.33-1.43-1.43c-.22-.22-.22-.44 0-.66a8.383 8.383 0 001.76-5.17z"
});

var SvgQubely = function SvgQubely(props) {
  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("svg", _extends({
    viewBox: "-1 -1 20 20",
    xmlns: "http://www.w3.org/2000/svg"
  }, props), _ref);
};

/* harmony default export */ __webpack_exports__["default"] = (SvgQubely);

/***/ }),

/***/ "./redux-templates/src/icons/images/snow-monkey-blocks.svg":
/*!*****************************************************************!*\
  !*** ./redux-templates/src/icons/images/snow-monkey-blocks.svg ***!
  \*****************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
function _extends() { _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; return _extends.apply(this, arguments); }



var _ref = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  d: "M13.982 16.711a4.48 4.48 0 01-7.965 0A4.972 4.972 0 0110 14.709c1.629 0 3.074.789 3.982 2.002zm-.748-7.657c-.314 2.56 1.248 2.919 1.248 5.603a4.5 4.5 0 01-.205 1.344 5.635 5.635 0 00-8.554 0 4.5 4.5 0 01-.205-1.344c0-2.684 1.563-3.043 1.247-5.603C6.403 6.126 2.45 6.589 2.45 3.72A2.857 2.857 0 015.308.862C7.017.862 8.073 2.42 10 2.42c1.926 0 2.982-1.558 4.691-1.558a2.857 2.857 0 012.857 2.858c.001 2.869-3.952 2.406-4.314 5.334zM8.557 4.107h-.708a.9.9 0 01-.901.9.9.9 0 01-.901-.9h-.708a1.609 1.609 0 103.218 0zm.979 7.141a.568.568 0 00-.566-.568.567.567 0 10.566.568zm2.062 0a.569.569 0 00-.568-.568.567.567 0 10.568.568zm3.062-7.141h-.707a.9.9 0 01-1.802 0h-.707a1.61 1.61 0 003.216 0z"
});

var SvgSnowMonkeyBlocks = function SvgSnowMonkeyBlocks(props) {
  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("svg", _extends({
    xmlns: "http://www.w3.org/2000/svg"
  }, props), _ref);
};

/* harmony default export */ __webpack_exports__["default"] = (SvgSnowMonkeyBlocks);

/***/ }),

/***/ "./redux-templates/src/icons/images/stackable-ultimate-gutenberg-blocks.svg":
/*!**********************************************************************************!*\
  !*** ./redux-templates/src/icons/images/stackable-ultimate-gutenberg-blocks.svg ***!
  \**********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
function _extends() { _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; return _extends.apply(this, arguments); }



var _ref = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  d: "M64.08 136L23 176.66a4.75 4.75 0 003.53 8.15l86.91.14zM177.91 128.39a17 17 0 00-5-12.07L71.39 14.72 26.61 59.5a17 17 0 00-5 12.05 17 17 0 005 12.05l101.55 101.6v-.07l44.76-44.76a17 17 0 005-12zM172.95 14.69H86.12l49.42 49.62 40.92-41.16a5 5 0 00-3.51-8.46z"
});

var SvgStackableUltimateGutenbergBlocks = function SvgStackableUltimateGutenbergBlocks(props) {
  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("svg", _extends({
    viewBox: "0 0 200 200",
    xmlns: "http://www.w3.org/2000/svg"
  }, props), _ref);
};

/* harmony default export */ __webpack_exports__["default"] = (SvgStackableUltimateGutenbergBlocks);

/***/ }),

/***/ "./redux-templates/src/icons/images/ultimate-addons-for-gutenberg.svg":
/*!****************************************************************************!*\
  !*** ./redux-templates/src/icons/images/ultimate-addons-for-gutenberg.svg ***!
  \****************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
function _extends() { _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; return _extends.apply(this, arguments); }



var _ref = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  d: "M26.9.3C12.2.3.3 12.2.3 26.8s11.9 26.5 26.5 26.5c14.7 0 26.5-11.9 26.5-26.5S41.5.3 26.9.3zm-2 27.7c0 1.1-.1 2.2-.4 3.2-.3 1-.7 1.8-1.3 2.6-.6.7-1.3 1.3-2.2 1.7-.9.4-2 .6-3.2.6-1.3 0-2.4-.2-3.3-.7-.9-.4-1.7-1-2.2-1.8-.6-.7-1-1.6-1.3-2.6-.3-1-.4-2-.4-3.1v-8.3h3.8V28c0 .6.1 1.2.2 1.8s.3 1.1.6 1.5c.3.4.6.8 1.1 1.1s1 .4 1.6.4 1.2-.1 1.6-.4.8-.6 1.1-1.1c.3-.4.5-1 .6-1.5.1-.6.2-1.2.2-1.8v-8.3h3.8c-.3 0-.3 8.3-.3 8.3zm17.2 8H39v-1.6c-1.2 1.1-2.7 1.7-4.3 1.7-1.1 0-2.1-.2-3-.6-.9-.4-1.8-1-2.5-1.7s-1.3-1.6-1.7-2.6c-.4-1-.6-2.2-.6-3.4 0-1.1.2-2.2.6-3.2.4-1 1-1.9 1.7-2.6.7-.7 1.6-1.3 2.6-1.7 1-.4 2.1-.6 3.2-.6 1.5 0 2.8.3 4 1 1.1.6 2 1.5 2.5 2.6l-2.8 2.1c-.4-.7-.9-1.3-1.6-1.7-.7-.4-1.4-.6-2.3-.6-.6 0-1.2.1-1.7.4s-1 .6-1.3 1.1-.7 1-.8 1.6c-.2.6-.3 1.2-.3 1.9s.1 1.4.3 1.9c.2.6.5 1.1.9 1.5.4.4.8.8 1.4 1 .5.2 1.1.4 1.8.4 1.5 0 2.8-.7 4-2v-.5h-3.2v-2.7h6.3c-.1-.2-.1 8.3-.1 8.3z"
});

var SvgUltimateAddonsForGutenberg = function SvgUltimateAddonsForGutenberg(props) {
  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("svg", _extends({
    viewBox: "0 0 54 54",
    xmlns: "http://www.w3.org/2000/svg"
  }, props), _ref);
};

/* harmony default export */ __webpack_exports__["default"] = (SvgUltimateAddonsForGutenberg);

/***/ }),

/***/ "./redux-templates/src/icons/images/ultimate-blocks.svg":
/*!**************************************************************!*\
  !*** ./redux-templates/src/icons/images/ultimate-blocks.svg ***!
  \**************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
function _extends() { _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; return _extends.apply(this, arguments); }



var _ref = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  d: "M18.9 10v4.1c0 .7-.3 1.2-.9 1.6-2.4 1.4-4.7 2.7-7.1 4.1-.6.4-1.3.4-1.9 0-2.3-1.4-4.6-2.8-7-4.1-.6-.4-.9-.9-.9-1.6V6c0-.7.3-1.2.9-1.6C4.4 3 6.7 1.6 9.1.3c.6-.4 1.3-.4 1.9 0 2.3 1.3 4.6 2.7 7 4 .6.4.9.9.9 1.6V10zm-8.7-6c-.5 0-1 .2-1.5.4-.3.2-.7.4-1.1.6-.9.6-1.8 1.1-2.8 1.7-.2.1-.4.4-.4.7 0 .3.2.5.4.6.3.2.6.1.9 0C7 7.3 8.2 6.5 9.5 5.8c.8-.4 1.7-.2 2.1.5.4.7.1 1.6-.6 2.1-.5.3-1 .6-1.4.8-.8.5-1.6.9-2.5 1.4-.3.2-.4.5-.4.8.1.3.3.6.6.6.2 0 .4 0 .6-.1 1.3-.7 2.5-1.5 3.8-2.2.7-.4 1.6-.2 2 .4.5.7.3 1.7-.5 2.2-1.3.7-2.6 1.5-3.8 2.2-.4.2-.5.7-.3 1.1.2.4.7.5 1.1.3 1.3-.8 2.6-1.5 4-2.3 1.2-.7 1.7-2.1 1.3-3.4-.3-1.1-1-1.8-2.1-2.1-.1 0-.2-.1-.1-.2.1-.2.1-.4.1-.6C13.4 5.5 12 4 10.2 4zm-5.8 7.3c0 .2 0 .4.1.7.5 2 2.7 3 4.5 1.9 1.3-.7 2.6-1.5 3.8-2.2.4-.2.5-.7.3-1.1-.2-.4-.7-.5-1.1-.3-.4.2-.7.4-1.1.6-.9.5-1.8 1-2.7 1.6-.6.3-1.1.3-1.7-.1-.9-.6-.8-1.9.2-2.5 1.3-.7 2.6-1.5 3.8-2.2.5-.2.7-.7.4-1-.2-.4-.7-.5-1.1-.3-1.3.7-2.6 1.5-3.9 2.2-1 .6-1.4 1.5-1.5 2.7z"
});

var SvgUltimateBlocks = function SvgUltimateBlocks(props) {
  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("svg", _extends({
    viewBox: "0 0 20 20",
    xmlns: "http://www.w3.org/2000/svg"
  }, props), _ref);
};

/* harmony default export */ __webpack_exports__["default"] = (SvgUltimateBlocks);

/***/ }),

/***/ "./redux-templates/src/icons/images/ultimate-post.svg":
/*!************************************************************!*\
  !*** ./redux-templates/src/icons/images/ultimate-post.svg ***!
  \************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
function _extends() { _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; return _extends.apply(this, arguments); }



var _ref = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  d: "M9 0C4 0 0 4 0 9s4 9 9 9 9-4 9-9-4-9-9-9zM6.5 12.6c-.1.1-.1.1-.2.1l-2 .1c-.2 0-.3-.1-.4-.3l-.1-2c0-.1 0-.2.1-.2l5.5-6.4c.1-.1.3-.2.5 0L12 5.7c.1.1.2.3 0 .5l-5.5 6.4zm7.5.2H9.5c-.2 0-.4-.2-.4-.5 0-.2.2-.5.4-.5H14c.2 0 .4.2.4.5s-.2.5-.4.5zm0-1.8h-2.8c-.2 0-.4-.2-.4-.5 0-.2.2-.5.4-.5H14c.2 0 .4.2.4.5s-.2.5-.4.5zm.1-1.8h-1.2c-.2 0-.3-.2-.3-.5s.1-.5.3-.5h1.2c.2 0 .3.2.3.5s-.1.5-.3.5z"
});

var SvgUltimatePost = function SvgUltimatePost(props) {
  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("svg", _extends({
    viewBox: "0 0 18 18",
    xmlns: "http://www.w3.org/2000/svg"
  }, props), _ref);
};

/* harmony default export */ __webpack_exports__["default"] = (SvgUltimatePost);

/***/ }),

/***/ "./redux-templates/src/icons/images/wordpress.svg":
/*!********************************************************!*\
  !*** ./redux-templates/src/icons/images/wordpress.svg ***!
  \********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
function _extends() { _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; return _extends.apply(this, arguments); }



var _ref = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("path", {
  d: "M20 10c0-5.51-4.49-10-10-10C4.48 0 0 4.49 0 10c0 5.52 4.48 10 10 10 5.51 0 10-4.48 10-10zM7.78 15.37L4.37 6.22c.55-.02 1.17-.08 1.17-.08.5-.06.44-1.13-.06-1.11 0 0-1.45.11-2.37.11-.18 0-.37 0-.58-.01A8.87 8.87 0 0110 1.11c2.33 0 4.45.87 6.05 2.34-.68-.11-1.65.39-1.65 1.58 0 .74.45 1.36.9 2.1.35.61.55 1.36.55 2.46 0 1.49-1.4 5-1.4 5l-3.03-8.37c.54-.02.82-.17.82-.17.5-.05.44-1.25-.06-1.22 0 0-1.44.12-2.38.12-.87 0-2.33-.12-2.33-.12-.5-.03-.56 1.2-.06 1.22l.92.08 1.26 3.41zM17.41 10c.24-.64.74-1.87.43-4.25.7 1.29 1.05 2.71 1.05 4.25 0 3.29-1.73 6.24-4.4 7.78.97-2.59 1.94-5.2 2.92-7.78zM6.1 18.09C3.12 16.65 1.11 13.53 1.11 10c0-1.3.23-2.48.72-3.59C3.25 10.3 4.67 14.2 6.1 18.09zm4.03-6.63l2.58 6.98c-.86.29-1.76.45-2.71.45-.79 0-1.57-.11-2.29-.33.81-2.38 1.62-4.74 2.42-7.1z"
});

var SvgWordpress = function SvgWordpress(props) {
  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default.a.createElement("svg", _extends({
    viewBox: "-2 -2 24 24",
    xmlns: "http://www.w3.org/2000/svg"
  }, props), _ref);
};

/* harmony default export */ __webpack_exports__["default"] = (SvgWordpress);

/***/ }),

/***/ "./redux-templates/src/icons/index.js":
/*!********************************************!*\
  !*** ./redux-templates/src/icons/index.js ***!
  \********************************************/
/*! exports provided: redux, acfblocks, atomicblocks, advancedcustomfields, advancedgutenbergblocks, blockoptions, blockslider, coblocks, creativeblocks, editorplus, elegantblocks, enhancedblocks, essentialblocks, formsgutenberg, getwid, ghostkit, guteblock, gutenbergblock, gutentor, kadenceblocks, kiokenblocks, otterblocks, qodeblock, qubely, snowmonkeyblocks, stackableultimategutenbergblocks, ultimateaddonsforgutenberg, ultimateblocks, ultimatepost, wordpress, colorizeIcon, thirdPartyIcon, ReduxTemplatesIcon, ReduxTemplatesIconColor, ReduxTemplatesIconColorize, core */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "redux", function() { return redux; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "acfblocks", function() { return acfblocks; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "atomicblocks", function() { return atomicblocks; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "advancedcustomfields", function() { return advancedcustomfields; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "advancedgutenbergblocks", function() { return advancedgutenbergblocks; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "blockoptions", function() { return blockoptions; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "blockslider", function() { return blockslider; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "coblocks", function() { return coblocks; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "creativeblocks", function() { return creativeblocks; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "editorplus", function() { return editorplus; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "elegantblocks", function() { return elegantblocks; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "enhancedblocks", function() { return enhancedblocks; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "essentialblocks", function() { return essentialblocks; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "formsgutenberg", function() { return formsgutenberg; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getwid", function() { return getwid; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "ghostkit", function() { return ghostkit; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "guteblock", function() { return guteblock; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "gutenbergblock", function() { return gutenbergblock; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "gutentor", function() { return gutentor; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "kadenceblocks", function() { return kadenceblocks; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "kiokenblocks", function() { return kiokenblocks; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "otterblocks", function() { return otterblocks; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "qodeblock", function() { return qodeblock; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "qubely", function() { return qubely; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "snowmonkeyblocks", function() { return snowmonkeyblocks; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "stackableultimategutenbergblocks", function() { return stackableultimategutenbergblocks; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "ultimateaddonsforgutenberg", function() { return ultimateaddonsforgutenberg; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "ultimateblocks", function() { return ultimateblocks; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "ultimatepost", function() { return ultimatepost; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "wordpress", function() { return wordpress; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "colorizeIcon", function() { return colorizeIcon; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "thirdPartyIcon", function() { return thirdPartyIcon; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "ReduxTemplatesIcon", function() { return ReduxTemplatesIcon; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "ReduxTemplatesIconColor", function() { return ReduxTemplatesIconColor; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "ReduxTemplatesIconColorize", function() { return ReduxTemplatesIconColorize; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "core", function() { return core; });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _assets_img_icon_svg__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../assets/img/icon.svg */ "./redux-templates/assets/img/icon.svg");
/* harmony import */ var _images_acf_blocks_svg__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./images/acf-blocks.svg */ "./redux-templates/src/icons/images/acf-blocks.svg");
/* harmony import */ var _images_atomic_blocks_svg__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./images/atomic-blocks.svg */ "./redux-templates/src/icons/images/atomic-blocks.svg");
/* harmony import */ var _images_advanced_custom_fields_svg__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./images/advanced-custom-fields.svg */ "./redux-templates/src/icons/images/advanced-custom-fields.svg");
/* harmony import */ var _images_advanced_gutenberg_blocks_svg__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./images/advanced-gutenberg-blocks.svg */ "./redux-templates/src/icons/images/advanced-gutenberg-blocks.svg");
/* harmony import */ var _images_block_options_svg__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./images/block-options.svg */ "./redux-templates/src/icons/images/block-options.svg");
/* harmony import */ var _images_block_slider_svg__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./images/block-slider.svg */ "./redux-templates/src/icons/images/block-slider.svg");
/* harmony import */ var _images_coblocks_svg__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./images/coblocks.svg */ "./redux-templates/src/icons/images/coblocks.svg");
/* harmony import */ var _images_creative_blocks_svg__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./images/creative-blocks.svg */ "./redux-templates/src/icons/images/creative-blocks.svg");
/* harmony import */ var _images_editorplus_svg__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ./images/editorplus.svg */ "./redux-templates/src/icons/images/editorplus.svg");
/* harmony import */ var _images_elegant_blocks_svg__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ./images/elegant-blocks.svg */ "./redux-templates/src/icons/images/elegant-blocks.svg");
/* harmony import */ var _images_enhanced_blocks_svg__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! ./images/enhanced-blocks.svg */ "./redux-templates/src/icons/images/enhanced-blocks.svg");
/* harmony import */ var _images_essential_blocks_svg__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(/*! ./images/essential-blocks.svg */ "./redux-templates/src/icons/images/essential-blocks.svg");
/* harmony import */ var _images_forms_gutenberg_svg__WEBPACK_IMPORTED_MODULE_14__ = __webpack_require__(/*! ./images/forms-gutenberg.svg */ "./redux-templates/src/icons/images/forms-gutenberg.svg");
/* harmony import */ var _images_getwid_svg__WEBPACK_IMPORTED_MODULE_15__ = __webpack_require__(/*! ./images/getwid.svg */ "./redux-templates/src/icons/images/getwid.svg");
/* harmony import */ var _images_ghostkit_svg__WEBPACK_IMPORTED_MODULE_16__ = __webpack_require__(/*! ./images/ghostkit.svg */ "./redux-templates/src/icons/images/ghostkit.svg");
/* harmony import */ var _images_guteblock_svg__WEBPACK_IMPORTED_MODULE_17__ = __webpack_require__(/*! ./images/guteblock.svg */ "./redux-templates/src/icons/images/guteblock.svg");
/* harmony import */ var _images_gutentor_svg__WEBPACK_IMPORTED_MODULE_18__ = __webpack_require__(/*! ./images/gutentor.svg */ "./redux-templates/src/icons/images/gutentor.svg");
/* harmony import */ var _images_kadence_blocks_svg__WEBPACK_IMPORTED_MODULE_19__ = __webpack_require__(/*! ./images/kadence-blocks.svg */ "./redux-templates/src/icons/images/kadence-blocks.svg");
/* harmony import */ var _images_kioken_blocks_svg__WEBPACK_IMPORTED_MODULE_20__ = __webpack_require__(/*! ./images/kioken-blocks.svg */ "./redux-templates/src/icons/images/kioken-blocks.svg");
/* harmony import */ var _images_otter_blocks_svg__WEBPACK_IMPORTED_MODULE_21__ = __webpack_require__(/*! ./images/otter-blocks.svg */ "./redux-templates/src/icons/images/otter-blocks.svg");
/* harmony import */ var _images_qodeblock_svg__WEBPACK_IMPORTED_MODULE_22__ = __webpack_require__(/*! ./images/qodeblock.svg */ "./redux-templates/src/icons/images/qodeblock.svg");
/* harmony import */ var _images_qubely_svg__WEBPACK_IMPORTED_MODULE_23__ = __webpack_require__(/*! ./images/qubely.svg */ "./redux-templates/src/icons/images/qubely.svg");
/* harmony import */ var _images_snow_monkey_blocks_svg__WEBPACK_IMPORTED_MODULE_24__ = __webpack_require__(/*! ./images/snow-monkey-blocks.svg */ "./redux-templates/src/icons/images/snow-monkey-blocks.svg");
/* harmony import */ var _images_stackable_ultimate_gutenberg_blocks_svg__WEBPACK_IMPORTED_MODULE_25__ = __webpack_require__(/*! ./images/stackable-ultimate-gutenberg-blocks.svg */ "./redux-templates/src/icons/images/stackable-ultimate-gutenberg-blocks.svg");
/* harmony import */ var _images_ultimate_addons_for_gutenberg_svg__WEBPACK_IMPORTED_MODULE_26__ = __webpack_require__(/*! ./images/ultimate-addons-for-gutenberg.svg */ "./redux-templates/src/icons/images/ultimate-addons-for-gutenberg.svg");
/* harmony import */ var _images_ultimate_blocks_svg__WEBPACK_IMPORTED_MODULE_27__ = __webpack_require__(/*! ./images/ultimate-blocks.svg */ "./redux-templates/src/icons/images/ultimate-blocks.svg");
/* harmony import */ var _images_ultimate_post_svg__WEBPACK_IMPORTED_MODULE_28__ = __webpack_require__(/*! ./images/ultimate-post.svg */ "./redux-templates/src/icons/images/ultimate-post.svg");
/* harmony import */ var _images_wordpress_svg__WEBPACK_IMPORTED_MODULE_29__ = __webpack_require__(/*! ./images/wordpress.svg */ "./redux-templates/src/icons/images/wordpress.svg");
/* harmony import */ var _assets_img_icon_color_svg__WEBPACK_IMPORTED_MODULE_30__ = __webpack_require__(/*! ../../assets/img/icon-color.svg */ "./redux-templates/assets/img/icon-color.svg");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_31__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_31___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_31__);
/* harmony import */ var _wordpress_dom_ready__WEBPACK_IMPORTED_MODULE_32__ = __webpack_require__(/*! @wordpress/dom-ready */ "./node_modules/@wordpress/dom-ready/build-module/index.js");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_33__ = __webpack_require__(/*! @wordpress/blocks */ "@wordpress/blocks");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_33___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_33__);
/**
 * External dependencies
 */

















 // import SVGGutenbergBlock from './images/gutenberg-blocks.png'












 // export const gutentor = () => {
// 	return <SVGGutentorIcon width="20" height="20"/>
// }

const redux = () => {
  return wp.element.createElement(_assets_img_icon_svg__WEBPACK_IMPORTED_MODULE_1__["default"], {
    width: "20",
    height: "20"
  });
};
const acfblocks = () => {
  return wp.element.createElement(_images_acf_blocks_svg__WEBPACK_IMPORTED_MODULE_2__["default"], {
    width: "20",
    height: "20"
  });
};
const atomicblocks = () => {
  return wp.element.createElement(_images_atomic_blocks_svg__WEBPACK_IMPORTED_MODULE_3__["default"], {
    width: "20",
    height: "20"
  });
};
const advancedcustomfields = () => {
  return wp.element.createElement(_images_advanced_custom_fields_svg__WEBPACK_IMPORTED_MODULE_4__["default"], {
    width: "20",
    height: "20"
  });
};
const advancedgutenbergblocks = () => {
  return wp.element.createElement(_images_advanced_gutenberg_blocks_svg__WEBPACK_IMPORTED_MODULE_5__["default"], {
    width: "20",
    height: "20"
  });
};
const blockoptions = () => {
  return wp.element.createElement(_images_block_options_svg__WEBPACK_IMPORTED_MODULE_6__["default"], {
    width: "20",
    height: "20"
  });
};
const blockslider = () => {
  return wp.element.createElement(_images_block_slider_svg__WEBPACK_IMPORTED_MODULE_7__["default"], {
    width: "20",
    height: "20"
  });
};
const coblocks = () => {
  return wp.element.createElement(_images_coblocks_svg__WEBPACK_IMPORTED_MODULE_8__["default"], {
    width: "20",
    height: "20"
  });
};
const creativeblocks = () => {
  return wp.element.createElement(_images_creative_blocks_svg__WEBPACK_IMPORTED_MODULE_9__["default"], {
    width: "20",
    height: "20"
  });
};
const editorplus = () => {
  return wp.element.createElement(_images_editorplus_svg__WEBPACK_IMPORTED_MODULE_10__["default"], {
    width: "20",
    height: "20"
  });
};
const elegantblocks = () => {
  return wp.element.createElement(_images_elegant_blocks_svg__WEBPACK_IMPORTED_MODULE_11__["default"], {
    width: "20",
    height: "20"
  });
};
const enhancedblocks = () => {
  return wp.element.createElement(_images_enhanced_blocks_svg__WEBPACK_IMPORTED_MODULE_12__["default"], {
    width: "20",
    height: "20"
  });
};
const essentialblocks = () => {
  return wp.element.createElement(_images_essential_blocks_svg__WEBPACK_IMPORTED_MODULE_13__["default"], {
    width: "20",
    height: "20"
  });
};
const formsgutenberg = () => {
  return wp.element.createElement(_images_forms_gutenberg_svg__WEBPACK_IMPORTED_MODULE_14__["default"], {
    width: "20",
    height: "20"
  });
};
const getwid = () => {
  return wp.element.createElement(_images_getwid_svg__WEBPACK_IMPORTED_MODULE_15__["default"], {
    width: "20",
    height: "20"
  });
};
const ghostkit = () => {
  return wp.element.createElement(_images_ghostkit_svg__WEBPACK_IMPORTED_MODULE_16__["default"], {
    width: "20",
    height: "20"
  });
};
const guteblock = () => {
  return wp.element.createElement(_images_guteblock_svg__WEBPACK_IMPORTED_MODULE_17__["default"], {
    width: "20",
    height: "20"
  });
};
const gutenbergblock = () => {
  return wp.element.createElement(SVGGutenbergBlock, {
    width: "20",
    height: "20"
  });
};
const gutentor = () => {
  return wp.element.createElement(_images_gutentor_svg__WEBPACK_IMPORTED_MODULE_18__["default"], {
    width: "20",
    height: "20"
  });
};
const kadenceblocks = () => {
  return wp.element.createElement(_images_kadence_blocks_svg__WEBPACK_IMPORTED_MODULE_19__["default"], {
    width: "20",
    height: "20"
  });
};
const kiokenblocks = () => {
  return wp.element.createElement(_images_kioken_blocks_svg__WEBPACK_IMPORTED_MODULE_20__["default"], {
    width: "20",
    height: "20"
  });
};
const otterblocks = () => {
  return wp.element.createElement(_images_otter_blocks_svg__WEBPACK_IMPORTED_MODULE_21__["default"], {
    width: "20",
    height: "20"
  });
};
const qodeblock = () => {
  return wp.element.createElement(_images_qodeblock_svg__WEBPACK_IMPORTED_MODULE_22__["default"], {
    width: "20",
    height: "20"
  });
};
const qubely = () => {
  return wp.element.createElement(_images_qubely_svg__WEBPACK_IMPORTED_MODULE_23__["default"], {
    width: "20",
    height: "20"
  });
};
const snowmonkeyblocks = () => {
  return wp.element.createElement(_images_snow_monkey_blocks_svg__WEBPACK_IMPORTED_MODULE_24__["default"], {
    width: "20",
    height: "20"
  });
};
const stackableultimategutenbergblocks = () => {
  return wp.element.createElement(_images_stackable_ultimate_gutenberg_blocks_svg__WEBPACK_IMPORTED_MODULE_25__["default"], {
    width: "20",
    height: "20"
  });
};
const ultimateaddonsforgutenberg = () => {
  return wp.element.createElement(_images_ultimate_addons_for_gutenberg_svg__WEBPACK_IMPORTED_MODULE_26__["default"], {
    width: "20",
    height: "20"
  });
};
const ultimateblocks = () => {
  return wp.element.createElement(_images_ultimate_blocks_svg__WEBPACK_IMPORTED_MODULE_27__["default"], {
    width: "20",
    height: "20"
  });
};
const ultimatepost = () => {
  return wp.element.createElement(_images_ultimate_post_svg__WEBPACK_IMPORTED_MODULE_28__["default"], {
    width: "20",
    height: "20"
  });
};
const wordpress = () => {
  return wp.element.createElement(_images_wordpress_svg__WEBPACK_IMPORTED_MODULE_29__["default"], {
    width: "20",
    height: "20"
  });
};

 //
//
// export const reqSvgs = require.context ( './images/third-party', true, /\.svg$/ )
//
// export const reqSvgsKeys = reqSvgs.keys()
//
// const iconLoader = (path) => import(path);
//
// export const icons = {
// 	'redux': iconLoader('../../assets/img/icon.svg'),
// 	'forms-gutenberg': iconLoader('./images/forms-gutenberg.svg')
// }
//
// export const svgs = reqSvgs
// 	.keys ()
// 	.reduce ( ( images, path ) => {
// 		images[path.replace('./', '').replace('.svg', '')] = reqSvgs ( path )
// 		return images
// 	}, {} )
//
// function importAll(r) {
// 	let images = {};
// 	r.keys().map((item, index) => { images[item.replace('./', '').replace('.svg', '')] = r(item); });
// 	return images;
// }
// export const images = importAll(require.context( './images/third-party', false, /\.(svg)$/));

/**
 * WordPress dependencies
 */




const colorizeIcon = SvgIcon => {
  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_31__["cloneElement"])(SvgIcon, {
    fill: 'url(#redux-gradient)',
    className: 'redux-icon-gradient'
  });
};
const thirdPartyIcon = icon => {
  if (icon) {
    return wp.element.createElement("icon", {
      width: "20",
      height: "20"
    });
  }
}; // Add an icon to our block category.

if (typeof window.wp.blocks !== 'undefined' && typeof window.wp.blocks.updateCategory !== 'undefined') {
  Object(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_33__["updateCategory"])(redux_templates.i18n, {
    icon: colorizeIcon(wp.element.createElement(_assets_img_icon_svg__WEBPACK_IMPORTED_MODULE_1__["default"], {
      className: "components-panel__icon",
      width: "20",
      height: "20"
    }))
  });
} // Add our SVG gradient placeholder definition that we'll reuse.


Object(_wordpress_dom_ready__WEBPACK_IMPORTED_MODULE_32__["default"])(() => {
  const redux_templatesGradient = document.createElement('DIV');
  document.querySelector('body').appendChild(redux_templatesGradient);
  Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_31__["render"])(wp.element.createElement("svg", {
    xmlns: "http://www.w3.org/2000/svg",
    className: "redux-gradient",
    height: "0",
    width: "0",
    style: {
      opacity: 0
    }
  }, wp.element.createElement("defs", null, wp.element.createElement("linearGradient", {
    id: "redux-gradient"
  }, wp.element.createElement("stop", {
    offset: "0%",
    stopColor: "#8c33da",
    stopOpacity: "1"
  }), wp.element.createElement("stop", {
    offset: "100%",
    stopColor: "#f34957",
    stopOpacity: "1"
  })))), redux_templatesGradient);
});
const ReduxTemplatesIcon = () => {
  return wp.element.createElement(_assets_img_icon_svg__WEBPACK_IMPORTED_MODULE_1__["default"], {
    width: "20",
    height: "20"
  });
};
const ReduxTemplatesIconColor = () => {
  return wp.element.createElement(_assets_img_icon_color_svg__WEBPACK_IMPORTED_MODULE_30__["default"], {
    width: "20",
    height: "20"
  });
};
const ReduxTemplatesIconColorize = () => {
  return colorizeIcon(wp.element.createElement(_assets_img_icon_svg__WEBPACK_IMPORTED_MODULE_1__["default"], {
    width: "20",
    height: "20"
  }));
};
const core = () => {
  return wp.element.createElement(_images_wordpress_svg__WEBPACK_IMPORTED_MODULE_29__["default"], {
    width: "20",
    height: "20"
  });
}; //
// export const AdvancedGutenbergBlocks = () => {
// 	return <SVGAdvancedGutenbergBlocksIcon width="20" height="20"/>
// }
// export const advancedgutenbergblocks = () => <AdvancedGutenbergBlocks/>
//
// export const AdvancedGutenberg = () => {
// 	return <SVGAdvancedGutenbergIcon width="20" height="20"/>
// }
// export const advancedgutenbergIcon = () => <AdvancedGutenberg/>
//
// export const AtomicBlocks = () => {
// 	return <SVGAtomicBlocksIcon width="20" height="20"/>
// }
// export const atomicblocks = () => <AtomicBlocks/>
//
// export const CoBlocks = () => {
// 	return <SVGCoBlocksIcon width="20" height="20"/>
// }
// export const Coblocks = () => <CoBlocks/>
// export const coblocks = () => <CoBlocks/>
//
// export const Stackable = () => {
// 	return <SVGStackableIcon width="20" height="20"/>
// }
// export const stackable = () => <Stackable/>
// export const stackableultimategutenbergblocks = () => <Stackable/>
//
// export const Qubely = () => {
// 	return <SVGQubelyIcon width="20" height="20"/>
// }
// export const qubely = () => <Qubely/>
//
// export const Kioken = () => {
//     return <SVGKiokenIcon width="20" height="20"/>
// }
// export const kioken = () => <Kioken/>
// export const kiokenblocks = () => <Kioken/>
//
// export const kadenceblocks = () => {
// 	return <SVGKadenceIcon width="20" height="20"/>
// }
//
// export const CreativeBlocks = () => {
// 	return <SVGCreativeBlocksIcon width="20" height="20"/>
// }
// export const creativeblocks = () => <CreativeBlocks/>
// export const qb = () => <CreativeBlocks/>
//
// export const EssentialBlocks = () => {
// 	return <SVGEssentialBlocksIcon width="20" height="20"/>
// }
// export const essentialblocks = () => <EssentialBlocks/>
// export const eb = () => <EssentialBlocks/>
//
// export const UltimateAddonsForGutenberg = () => {
// 	return <SVGUltimateAddonsForGutenbergIcon width="20" height="20"/>
// }
// export const ultimateaddonsforgutenberg = () => <UltimateAddonsForGutenberg/>
//
//
// export const UltimateBlocks = () => {
// 	return <SVGUltimateBlocksIcon width="20" height="20"/>
// }
// export const ultimateblocks = () => <UltimateBlocks/>
//
// export const gutentor = () => {
// 	return <SVGGutentorIcon width="20" height="20"/>
// }
//
//
// export const GutenbergForms = () => {
// 	return <SVGGutenbergFormsIcon width="20" height="20"/>
// }
// export const gutenbergforms = () => <GutenbergForms/>
// export const formsgutenberg = () => <GutenbergForms/>
//

/***/ }),

/***/ "./redux-templates/src/index.js":
/*!**************************************!*\
  !*** ./redux-templates/src/index.js ***!
  \**************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_dom_ready__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/dom-ready */ "./node_modules/@wordpress/dom-ready/build-module/index.js");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _editor_scss__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./editor.scss */ "./redux-templates/src/editor.scss");
/* harmony import */ var _editor_scss__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_editor_scss__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _blocks_blocks__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./blocks/blocks */ "./redux-templates/src/blocks/blocks.js");
/* harmony import */ var _plugins_sidebar_share__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./plugins/sidebar-share */ "./redux-templates/src/plugins/sidebar-share/index.js");
/* harmony import */ var _plugins_share_block_btn__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./plugins/share-block-btn */ "./redux-templates/src/plugins/share-block-btn/index.js");
/* harmony import */ var _plugins_export__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./plugins/export */ "./redux-templates/src/plugins/export/index.js");
/* harmony import */ var _plugins_export_page_menu_item__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./plugins/export-page-menu-item */ "./redux-templates/src/plugins/export-page-menu-item/index.js");
/* harmony import */ var _plugins_library_context_menu_item__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./plugins/library-context-menu-item */ "./redux-templates/src/plugins/library-context-menu-item/index.js");
/* harmony import */ var _challenge_tooltip_TooltipBox__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./challenge/tooltip/TooltipBox */ "./redux-templates/src/challenge/tooltip/TooltipBox.js");
/* harmony import */ var _stores_helper__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ./stores/helper */ "./redux-templates/src/stores/helper.js");
/* harmony import */ var _challenge__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ./challenge */ "./redux-templates/src/challenge/index.js");
/* harmony import */ var _modal_manager__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! ./modal-manager */ "./redux-templates/src/modal-manager/index.js");
/* harmony import */ var _modal_library__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(/*! ./modal-library */ "./redux-templates/src/modal-library/index.js");
/* harmony import */ var _components_welcome_guide__WEBPACK_IMPORTED_MODULE_14__ = __webpack_require__(/*! ./components/welcome-guide */ "./redux-templates/src/components/welcome-guide/index.js");
/* harmony import */ var _components_template_change__WEBPACK_IMPORTED_MODULE_15__ = __webpack_require__(/*! ./components/template-change */ "./redux-templates/src/components/template-change/index.js");
/* harmony import */ var _custom_css__WEBPACK_IMPORTED_MODULE_16__ = __webpack_require__(/*! ./custom-css */ "./redux-templates/src/custom-css/index.js");
/**
 * Library Button
 */

/**
 * WordPress dependencies
 */


/**
 * External dependencies
 */
















Object(_wordpress_dom_ready__WEBPACK_IMPORTED_MODULE_0__["default"])(() => {
  setTimeout(() => {
    const challengeDiv = document.createElement('div');
    challengeDiv.className = 'challenge-tooltip-holder';
    document.body.appendChild(challengeDiv);
    const challengeWrapperDiv = document.createElement('div');
    challengeWrapperDiv.className = 'challenge-wrapper';
    document.body.appendChild(challengeWrapperDiv);

    if (window.location.hash == '#redux_challenge=1') {
      window.location.hash = '';
      _modal_manager__WEBPACK_IMPORTED_MODULE_12__["ModalManager"].open(wp.element.createElement(_modal_library__WEBPACK_IMPORTED_MODULE_13__["default"], null));
    }

    if (window.location.hash == '#redux_templates=1') {
      window.location.hash = '';
      _modal_manager__WEBPACK_IMPORTED_MODULE_12__["ModalManager"].open(wp.element.createElement(_modal_library__WEBPACK_IMPORTED_MODULE_13__["default"], null));
    } // For frontenberg, we open the dialog automatically.


    if (document.body.classList.contains('wp-admin') === false) {
      _modal_manager__WEBPACK_IMPORTED_MODULE_12__["ModalManager"].open(wp.element.createElement(_modal_library__WEBPACK_IMPORTED_MODULE_13__["default"], null));
    }

    Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__["render"])(wp.element.createElement(_challenge__WEBPACK_IMPORTED_MODULE_11__["default"], null), challengeWrapperDiv);
    Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__["render"])(wp.element.createElement(_challenge_tooltip_TooltipBox__WEBPACK_IMPORTED_MODULE_9__["default"], null), challengeDiv);
    Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__["render"])(wp.element.createElement(_components_welcome_guide__WEBPACK_IMPORTED_MODULE_14__["default"], null), challengeDiv);
    Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__["render"])(wp.element.createElement(_components_template_change__WEBPACK_IMPORTED_MODULE_15__["default"], null), challengeDiv);
    Object(_stores_helper__WEBPACK_IMPORTED_MODULE_10__["handlingLocalStorageData"])();
  }, 500);
});

/***/ }),

/***/ "./redux-templates/src/modal-feedback/index.js":
/*!*****************************************************!*\
  !*** ./redux-templates/src/modal-feedback/index.js ***!
  \*****************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _redux_templates_modal_manager__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ~redux-templates/modal-manager */ "./redux-templates/src/modal-manager/index.js");
/* harmony import */ var _rjsf_core__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @rjsf/core */ "./node_modules/@rjsf/core/dist/es/index.js");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_3__);
function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }

function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }

function _iterableToArrayLimit(arr, i) { if (typeof Symbol === "undefined" || !(Symbol.iterator in Object(arr))) return; var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }

function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }

/**
 * WordPress dependencies
 */




const useState = wp.element.useState;
const _wp = wp,
      apiFetch = _wp.apiFetch;

function FeedbackDialog(props) {
  const title = props.title,
        description = props.description,
        schema = props.schema,
        uiSchema = props.uiSchema,
        headerImage = props.headerImage,
        headerIcon = props.headerIcon,
        data = props.data,
        ignoreData = props.ignoreData,
        endpoint = props.endpoint,
        width = props.width,
        buttonLabel = props.buttonLabel;
  const closeModal = props.closeModal,
        onSuccess = props.onSuccess;

  const _useState = useState(false),
        _useState2 = _slicedToArray(_useState, 2),
        loading = _useState2[0],
        setLoading = _useState2[1];

  const _useState3 = useState(null),
        _useState4 = _slicedToArray(_useState3, 2),
        errorMessage = _useState4[0],
        setErrorMessage = _useState4[1];

  const onSubmit = ({
    formData
  }) => {
    const path = `redux/v1/templates/${endpoint ? endpoint : 'feedback'}`;
    if (loading) return;
    setLoading(true);
    apiFetch({
      path,
      method: 'POST',
      data: ignoreData ? formData : _objectSpread(_objectSpread({}, data), formData)
    }).then(data => {
      setLoading(false);

      if (data.success) {
        setErrorMessage(null);
        if (onSuccess) onSuccess(data);else onCloseModal();
      } else {
        console.log('There was an error: ', data);
        setErrorMessage(Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])('An unexpected error occured, please try again later.', redux_templates.i18n));
      }
    }).catch(err => {
      setLoading(false);
      console.log('There was an error: ', err);
      setErrorMessage(Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])('An unexpected error occured, please try again later.', redux_templates.i18n));
    });
  };

  const onCloseModal = () => {
    if (closeModal) closeModal();else _redux_templates_modal_manager__WEBPACK_IMPORTED_MODULE_1__["ModalManager"].closeFeedback();
  };

  const style = width ? {
    width
  } : null;
  const wrapperClassname = width ? 'redux-templates-modal-wrapper feedback-popup-wrapper less-margin' : 'redux-templates-modal-wrapper feedback-popup-wrapper';
  return wp.element.createElement("div", {
    className: "redux-templates-modal-overlay"
  }, wp.element.createElement("div", {
    className: wrapperClassname,
    style: style
  }, wp.element.createElement("div", {
    className: "feedback-popup-header feedback-popup-header-contact"
  }, headerImage, headerIcon, wp.element.createElement("a", {
    className: "feedback-popup-close",
    onClick: onCloseModal
  }, wp.element.createElement("i", {
    className: "fas fa-times"
  }))), wp.element.createElement("div", {
    className: "feedback-popup-content"
  }, wp.element.createElement("h3", null, title), errorMessage && wp.element.createElement("p", {
    className: "error-message"
  }, errorMessage), wp.element.createElement("p", null, description), wp.element.createElement("div", {
    className: "col-wrapper"
  }, wp.element.createElement(_rjsf_core__WEBPACK_IMPORTED_MODULE_2__["default"], {
    schema: schema,
    uiSchema: uiSchema,
    onSubmit: onSubmit
  }, wp.element.createElement("button", {
    className: "feedback-popup-btn feedback-popup-rate-btn",
    type: "submit"
  }, loading && wp.element.createElement("i", {
    className: "fas fa-spinner fa-pulse"
  }), buttonLabel)), data && data.editor_blocks && wp.element.createElement("div", {
    className: "preview-panel"
  }, wp.element.createElement("div", {
    className: "redux-templates-block-preview-hover"
  }), wp.element.createElement(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_3__["BlockPreview"], {
    blocks: data.editor_blocks
  })))), " "));
}

/* harmony default export */ __webpack_exports__["default"] = (FeedbackDialog);

/***/ }),

/***/ "./redux-templates/src/modal-import-wizard/ImportingStep.js":
/*!******************************************************************!*\
  !*** ./redux-templates/src/modal-import-wizard/ImportingStep.js ***!
  \******************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return ImportingStep; });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var react_text_transition__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react-text-transition */ "./node_modules/react-text-transition/dist/index.js");
/* harmony import */ var react_text_transition__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(react_text_transition__WEBPACK_IMPORTED_MODULE_1__);
function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }

function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }

function _iterableToArrayLimit(arr, i) { if (typeof Symbol === "undefined" || !(Symbol.iterator in Object(arr))) return; var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }

function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }


const _wp$element = wp.element,
      useState = _wp$element.useState,
      useEffect = _wp$element.useEffect,
      useRef = _wp$element.useRef;
const Spinner = wp.components.Spinner;

const __ = wp.i18n.__;
const MESSAGE_DELAY_MILLISECONDS = 4000;
const MESSAGES_LIST = [__('Please wait while your template is prepared.', redux_templates.i18n), __('Fetching the template.', redux_templates.i18n), __('We\'re getting closer now.', redux_templates.i18n), __('Wow, this is taking a long time.', redux_templates.i18n), __('Gah, this should be done by now!', redux_templates.i18n), __('Really, this should be done soon.', redux_templates.i18n), __('Are you sure your internet is working?!', redux_templates.i18n), __('Give up, it looks like it didn\'t work...', redux_templates.i18n)];

function useInterval(callback, delay) {
  const savedCallback = useRef(); // Remember the latest callback.

  useEffect(() => {
    savedCallback.current = callback;
  }, [callback]); // Set up the interval.

  useEffect(() => {
    function tick() {
      savedCallback.current();
    }

    if (delay !== null) {
      let id = setInterval(tick, delay);
      return () => clearInterval(id);
    }
  }, [delay]);
}

function ImportingStep(props) {
  const _useState = useState(0),
        _useState2 = _slicedToArray(_useState, 2),
        messageIndex = _useState2[0],
        setMessageIndex = _useState2[1];

  const _useState3 = useState(MESSAGES_LIST[0]),
        _useState4 = _slicedToArray(_useState3, 2),
        loadingMessage = _useState4[0],
        setLoadingMessage = _useState4[1];

  useInterval(() => {
    if (messageIndex === MESSAGES_LIST.length) return;
    setMessageIndex(messageIndex => messageIndex + 1);
    setLoadingMessage([MESSAGES_LIST[messageIndex + 1]]);
  }, MESSAGE_DELAY_MILLISECONDS);
  return wp.element.createElement("div", {
    className: "redux-templates-modal-body"
  }, wp.element.createElement("div", {
    className: "redux-templates-import-wizard-spinner-wrapper"
  }, wp.element.createElement(react_text_transition__WEBPACK_IMPORTED_MODULE_1___default.a, {
    text: loadingMessage,
    springConfig: react_text_transition__WEBPACK_IMPORTED_MODULE_1__["presets"].gentle
  }), wp.element.createElement(Spinner, null)));
}
;

/***/ }),

/***/ "./redux-templates/src/modal-import-wizard/InstallPluginStep.js":
/*!**********************************************************************!*\
  !*** ./redux-templates/src/modal-import-wizard/InstallPluginStep.js ***!
  \**********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _redux_templates_stores_dependencyHelper__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ~redux-templates/stores/dependencyHelper */ "./redux-templates/src/stores/dependencyHelper.js");
function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }

function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }

function _iterableToArrayLimit(arr, i) { if (typeof Symbol === "undefined" || !(Symbol.iterator in Object(arr))) return; var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }

function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }


const _wp = wp,
      apiFetch = _wp.apiFetch;
const compose = wp.compose.compose;
const withDispatch = wp.data.withDispatch;
const _wp$element = wp.element,
      Fragment = _wp$element.Fragment,
      useState = _wp$element.useState;
const __ = wp.i18n.__;

function InstallPluginStep(props) {
  const missingPlugins = props.missingPlugins,
        toNextStep = props.toNextStep,
        onCloseWizard = props.onCloseWizard;
  const setInstalledDependencies = props.setInstalledDependencies;

  const _useState = useState(null),
        _useState2 = _slicedToArray(_useState, 2),
        installingPlugin = _useState2[0],
        setInstallingPlugin = _useState2[1];

  const _useState3 = useState([]),
        _useState4 = _slicedToArray(_useState3, 2),
        installedList = _useState4[0],
        setInstalledList = _useState4[1];

  const _useState5 = useState([]),
        _useState6 = _slicedToArray(_useState5, 2),
        failedList = _useState6[0],
        setFailedList = _useState6[1];

  const _useState7 = useState(missingPlugins),
        _useState8 = _slicedToArray(_useState7, 2),
        waitingList = _useState8[0],
        setWaitingList = _useState8[1];

  const preInstallInit = () => {
    setInstalledList([]);
    setFailedList([]);
    setWaitingList(missingPlugins);
    setInstallingPlugin(null);
    setInstalledDependencies(false);
  };

  const onInstallPlugins = async () => {
    preInstallInit();
    let localInstalledList = [];
    let localFailedList = [];
    let localWaitingList = [...waitingList];

    for (let pluginKey of missingPlugins) {
      const pluginInstance = redux_templates.supported_plugins[pluginKey];
      localWaitingList = localWaitingList.filter(key => key !== pluginKey);
      setWaitingList(localWaitingList);

      if (!pluginKey || !pluginInstance) {
        setInstallingPlugin(null);
        break;
      }

      setInstallingPlugin(_objectSpread(_objectSpread({}, pluginInstance), {}, {
        pluginKey
      }));
      const reduxProSurfix = pluginInstance.redux_pro ? '&redux_pro=1' : '';
      await apiFetch({
        path: 'redux/v1/templates/plugin-install?slug=' + pluginKey + reduxProSurfix
      }).then(res => {
        if (res.success) {
          setInstalledDependencies(true);
          localInstalledList = [...localInstalledList, pluginKey];
          setInstalledList(localInstalledList);
          if (localWaitingList.length === 0) setInstallingPlugin(null);
        } else {
          localFailedList = [...localFailedList, pluginKey];
          setFailedList(localFailedList);
          if (localWaitingList.length === 0) setInstallingPlugin(null);
        }
      }).catch(res => {
        localFailedList = [...localFailedList, pluginKey];
        setFailedList(localFailedList);
        if (localWaitingList.length === 0) setInstallingPlugin(null);
      });
    }
  };

  if (waitingList.length === 0 && failedList.length === 0 && installingPlugin === null) toNextStep();
  return wp.element.createElement(Fragment, null, wp.element.createElement("div", {
    className: "redux-templates-modal-body"
  }, wp.element.createElement("h5", null, __('Install Required Plugins', redux_templates.i18n)), wp.element.createElement("p", null, __('Plugins needed to import this template are missing. Required plugins will be installed and activated automatically.', redux_templates.i18n)), installingPlugin === null && failedList.length > 0 && wp.element.createElement("p", {
    className: "error installError"
  }, __('The following plugin(s) failed to install properly. Please manually install them yourself before attempting another import.', redux_templates.i18n)), wp.element.createElement("ul", {
    className: "redux-templates-import-progress"
  }, missingPlugins && missingPlugins.map(pluginKey => {
    let plugin = Object(_redux_templates_stores_dependencyHelper__WEBPACK_IMPORTED_MODULE_0__["pluginInfo"])(pluginKey);
    if (installingPlugin && installingPlugin.pluginKey === pluginKey) return wp.element.createElement("li", {
      className: "installing",
      key: installingPlugin.pluginKey
    }, installingPlugin.name, wp.element.createElement("i", {
      className: "fas fa-spinner fa-pulse"
    }));
    if (failedList.includes(pluginKey)) return wp.element.createElement("li", {
      className: "failure",
      key: pluginKey
    }, plugin.name, " ", wp.element.createElement("a", {
      href: plugin.url,
      target: "_blank"
    }, wp.element.createElement("i", {
      className: "fas fa-external-link-alt"
    })));
    if (waitingList.includes(pluginKey)) return wp.element.createElement("li", {
      className: "todo",
      key: pluginKey
    }, plugin.name, " ", plugin.url && wp.element.createElement("a", {
      href: plugin.url,
      target: "_blank"
    }, wp.element.createElement("i", {
      className: "fas fa-external-link-alt"
    })));
    if (installedList.includes(pluginKey)) return wp.element.createElement("li", {
      className: "success",
      key: pluginKey
    }, plugin.name, " ", wp.element.createElement("i", {
      className: "fas fa-check-square"
    }));
  }))), wp.element.createElement("div", {
    className: "redux-templates-modal-footer"
  }, waitingList.length !== 0 && wp.element.createElement("button", {
    className: "button button-primary",
    disabled: installingPlugin !== null,
    onClick: () => onInstallPlugins()
  }, installingPlugin !== null && wp.element.createElement("i", {
    className: "fas fa-spinner fa-pulse"
  }), wp.element.createElement("span", null, __('Install', redux_templates.i18n))), wp.element.createElement("button", {
    className: "button button-secondary",
    disabled: installingPlugin !== null,
    onClick: onCloseWizard
  }, __('Cancel', redux_templates.i18n))));
}

/* harmony default export */ __webpack_exports__["default"] = (compose([withDispatch(dispatch => {
  const _dispatch = dispatch('redux-templates/sectionslist'),
        setInstalledDependencies = _dispatch.setInstalledDependencies;

  return {
    setInstalledDependencies
  };
})])(InstallPluginStep));

/***/ }),

/***/ "./redux-templates/src/modal-import-wizard/OptionStep.js":
/*!***************************************************************!*\
  !*** ./redux-templates/src/modal-import-wizard/OptionStep.js ***!
  \***************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _redux_templates_stores_dependencyHelper__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ~redux-templates/stores/dependencyHelper */ "./redux-templates/src/stores/dependencyHelper.js");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);


const _wp = wp,
      apiFetch = _wp.apiFetch;
const compose = wp.compose.compose;
const withDispatch = wp.data.withDispatch;
const _wp$element = wp.element,
      Fragment = _wp$element.Fragment,
      useState = _wp$element.useState;
const __ = wp.i18n.__;

function OptionStep(props) {
  const setImportToAppend = props.setImportToAppend,
        toNextStep = props.toNextStep,
        onCloseWizard = props.onCloseWizard;

  const onNextStep = isToAppend => {
    setImportToAppend(isToAppend);
    toNextStep();
  };

  return wp.element.createElement(Fragment, null, wp.element.createElement("div", {
    className: "redux-templates-modal-body"
  }, wp.element.createElement("h5", null, __('Append or Replace', redux_templates.i18n)), wp.element.createElement("p", null, __('You have existing content on this page. How would you like to handle the import of this page template?', redux_templates.i18n)), wp.element.createElement("div", {
    style: {
      textAlign: 'center',
      marginTop: '30px'
    }
  }, wp.element.createElement("button", {
    className: "button button-primary",
    onClick: () => onNextStep(true),
    style: {
      marginRight: '10px'
    }
  }, __('Append to Content', redux_templates.i18n)), wp.element.createElement("button", {
    className: "button button-primary",
    onClick: () => onNextStep(false)
  }, __('Replace all Content', redux_templates.i18n)))), wp.element.createElement("div", {
    className: "redux-templates-modal-footer"
  }, wp.element.createElement("button", {
    className: "button button-secondary",
    onClick: onCloseWizard
  }, __('Cancel', redux_templates.i18n))));
}

/* harmony default export */ __webpack_exports__["default"] = (compose([withDispatch(dispatch => {
  const _dispatch = dispatch('redux-templates/sectionslist'),
        setImportToAppend = _dispatch.setImportToAppend;

  return {
    setImportToAppend
  };
})])(OptionStep));

/***/ }),

/***/ "./redux-templates/src/modal-import-wizard/ProPluginsStep.js":
/*!*******************************************************************!*\
  !*** ./redux-templates/src/modal-import-wizard/ProPluginsStep.js ***!
  \*******************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return ProPluginStep; });
/* harmony import */ var _ReduxTemplatesPremiumBox__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ReduxTemplatesPremiumBox */ "./redux-templates/src/modal-import-wizard/ReduxTemplatesPremiumBox.js");
/* harmony import */ var _redux_templates_stores_dependencyHelper__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ~redux-templates/stores/dependencyHelper */ "./redux-templates/src/stores/dependencyHelper.js");
const Fragment = wp.element.Fragment;
const __ = wp.i18n.__;


const REDUXTEMPLATES_PRO_KEY = 'redux-pro';
function ProPluginStep(props) {
  const missingPros = props.missingPros,
        onCloseWizard = props.onCloseWizard;
  if (missingPros.indexOf(REDUXTEMPLATES_PRO_KEY) >= 0) return wp.element.createElement(_ReduxTemplatesPremiumBox__WEBPACK_IMPORTED_MODULE_0__["default"], null);
  return wp.element.createElement(Fragment, null, wp.element.createElement("div", {
    className: "redux-templates-modal-body"
  }, wp.element.createElement("h5", null, __('Additional Plugins Required', redux_templates.i18n)), wp.element.createElement("p", null, __('The following premium plugin(s) are required to import this template:', redux_templates.i18n)), wp.element.createElement("ul", {
    className: "redux-templates-import-progress"
  }, missingPros.map(pluginKey => {
    let plugin = Object(_redux_templates_stores_dependencyHelper__WEBPACK_IMPORTED_MODULE_1__["pluginInfo"])(pluginKey);
    return wp.element.createElement("li", {
      className: "installing",
      key: pluginKey
    }, plugin.name, " ", plugin.url && wp.element.createElement("a", {
      href: plugin.url,
      target: "_blank"
    }, wp.element.createElement("i", {
      className: "fas fa-external-link-alt"
    })));
  }))), wp.element.createElement("div", {
    className: "redux-templates-modal-footer"
  }, wp.element.createElement("a", {
    className: "button button-secondary",
    onClick: onCloseWizard
  }, __('Close', redux_templates.i18n))));
}

/***/ }),

/***/ "./redux-templates/src/modal-import-wizard/ReduxTemplatesActivateBox.js":
/*!******************************************************************************!*\
  !*** ./redux-templates/src/modal-import-wizard/ReduxTemplatesActivateBox.js ***!
  \******************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return ReduxTemplatesActivateBox; });
const __ = wp.i18n.__;
function ReduxTemplatesActivateBox({
  onActivateRedux,
  activating
}) {
  return wp.element.createElement("div", {
    className: "redux-templates-modal-body"
  }, wp.element.createElement("div", {
    className: "section-box premium-box"
  }, wp.element.createElement("h3", null, __('Registration Required to Import Templates', redux_templates.i18n)), wp.element.createElement("p", null, __(' Register now to import templates from the Redux template library in a single click.', redux_templates.i18n)), wp.element.createElement("ul", null, wp.element.createElement("li", null, wp.element.createElement("strong", null, __('Unlimited', redux_templates.i18n)), " ", __('use of our free templates.', redux_templates.i18n)), wp.element.createElement("li", null, wp.element.createElement("strong", null, __('Updates', redux_templates.i18n)), " ", __('to the library.', redux_templates.i18n)), wp.element.createElement("li", null, wp.element.createElement("strong", null, __('Google Fonts', redux_templates.i18n)), " ", __('manual updates.', redux_templates.i18n))), wp.element.createElement("p", null, wp.element.createElement("button", {
    className: "button button-primary",
    disabled: activating,
    onClick: () => onActivateRedux()
  }, activating && wp.element.createElement("i", {
    className: "fas fa-spinner fa-pulse",
    style: {
      marginRight: '5px'
    }
  }), wp.element.createElement("span", null, __('Register for Free', redux_templates.i18n)))), wp.element.createElement("p", {
    style: {
      fontSize: '1.1em'
    }
  }, wp.element.createElement("small", null, wp.element.createElement("em", {
    dangerouslySetInnerHTML: {
      __html: redux_templates.tos
    }
  })))));
}

/***/ }),

/***/ "./redux-templates/src/modal-import-wizard/ReduxTemplatesPremiumActivate.js":
/*!**********************************************************************************!*\
  !*** ./redux-templates/src/modal-import-wizard/ReduxTemplatesPremiumActivate.js ***!
  \**********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return ReduxTemplatesPremiumActivate; });
function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }

function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }

function _iterableToArrayLimit(arr, i) { if (typeof Symbol === "undefined" || !(Symbol.iterator in Object(arr))) return; var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }

function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }

const _wp$i18n = wp.i18n,
      __ = _wp$i18n.__,
      sprintf = _wp$i18n.sprintf;
const useState = wp.element.useState;
const _wp = wp,
      apiFetch = _wp.apiFetch;
function ReduxTemplatesPremiumActivate(props) {
  const _useState = useState(null),
        _useState2 = _slicedToArray(_useState, 2),
        installing = _useState2[0],
        setInstalling = _useState2[1];

  const _useState3 = useState(__('Enter your API key to activate and install your copy of Redux Pro.', redux_templates.i18n)),
        _useState4 = _slicedToArray(_useState3, 2),
        status = _useState4[0],
        setStatus = _useState4[1];

  const _useState5 = useState(''),
        _useState6 = _slicedToArray(_useState5, 2),
        statusType = _useState6[0],
        setStatusType = _useState6[1];

  const _useState7 = useState(atob(redux_templates.key)),
        _useState8 = _slicedToArray(_useState7, 2),
        proKey = _useState8[0],
        setProKey = _useState8[1];

  const toPluginStep = props.toPluginStep;

  const installReduxPlugin = async () => {
    if (!redux_templates.supported_plugins['redux-framework'].plugin) {
      setStatus(__('Installing the redux-framework plugin.', redux_templates.i18n));
      await apiFetch({
        path: 'redux/v1/templates/plugin-install?slug=redux-framework'
      }).then(res => {
        if (res.success) {
          installReduxPro();
        } else {
          setStatus(__('Install of Redux Pro failed, please try again.', redux_templates.i18n));
          setInstalling(null);
        }
      }).catch(res => {
        setStatus(__('There seems to be an API issue. Please contact Redux support or try again.', redux_templates.i18n));
        setInstalling(null);
      });
    } else {
      installReduxPro();
    }
  };

  const installReduxPro = async () => {
    if (!redux_templates.supported_plugins['redux-pro'].version) {
      setStatus(__('Installing Redux Pro.', redux_templates.i18n));
      await apiFetch({
        path: 'redux/v1/templates/plugin-install?slug=redux-pro&redux_pro=1'
      }).then(res => {
        if (res.success) {
          setStatus(__('Redux Pro successfully installed!', redux_templates.i18n));
          redux_templates.mokama = true;
          delete redux_templates.left;
          toPluginStep();
        } else {
          setStatus(__('Install of Redux Pro failed, please try again.', redux_templates.i18n));
        }
      }).catch(res => {
        setStatus(__('There seems to be an API issue. Please contact Redux support or try again.', redux_templates.i18n));
        setInstalling(null);
      });
    } else {
      redux_templates.mokama = true;
      delete redux_templates.left;
      toPluginStep();
    }
  };

  const activateKey = async () => {
    setInstalling(true);
    setStatus(__('Validating license key.', redux_templates.i18n));
    await apiFetch({
      path: 'redux/v1/templates/license?key=' + proKey
    }).then(res => {
      if (res.success) {
        setStatus(__('API key activated, installing Redux Pro.', redux_templates.i18n));
        installReduxPlugin();
      } else {
        if (res.msg) {
          setStatus(res.msg);
        } else {
          setStatus(__('License key failed to activate. Please try again.', redux_templates.i18n));
        }

        setInstalling(null);
      }
    }).catch(res => {
      setStatus(__('There seems to be an API issue. Please contact Redux support or try again.', redux_templates.i18n));
      setInstalling(null);
    });
  };

  const updateProKey = e => {
    setProKey(e.target.value);
  };

  return wp.element.createElement("div", {
    className: "redux-templates-modal-body"
  }, wp.element.createElement("div", {
    className: "section-box premium-box"
  }, wp.element.createElement("h3", null, __('Activate Redux Pro', redux_templates.i18n)), wp.element.createElement("div", {
    className: statusType
  }, status), wp.element.createElement("p", null, wp.element.createElement("input", {
    type: "text",
    placeholder: __('Enter your subscription key', redux_templates.i18n),
    value: proKey,
    onChange: updateProKey,
    disabled: installing !== null
  })), wp.element.createElement("p", {
    className: "subscription_key"
  }, wp.element.createElement("button", {
    className: "redux-pro-activate-button",
    "aria-label": "Activate my key",
    disabled: installing !== null,
    onClick: () => activateKey()
  }, installing !== null && wp.element.createElement("i", {
    className: "fas fa-spinner fa-pulse"
  }), wp.element.createElement("span", null, __('Activate & Install Pro', redux_templates.i18n)))), wp.element.createElement("p", {
    style: {
      fontSize: '1.1em',
      paddingTop: '10px'
    }
  }, wp.element.createElement("small", null, wp.element.createElement("em", {
    dangerouslySetInnerHTML: {
      __html: redux_templates.tos.replace(__('Register', redux_templates.i18n), __('Activate', redux_templates.i18n))
    }
  }), " ", wp.element.createElement("br", null), wp.element.createElement("span", {
    dangerouslySetInnerHTML: {
      __html: sprintf(__('Visit the <a href="%s" target="_blank">account dashboard</a> to recover your subscription key.', redux_templates.i18n), 'https://redux.io/account/?utm_source=plugin-installation&utm_campaign=import_wizard&utm_medium=appsero&utm_content=activate_pro')
    }
  })))));
}

/***/ }),

/***/ "./redux-templates/src/modal-import-wizard/ReduxTemplatesPremiumBox.js":
/*!*****************************************************************************!*\
  !*** ./redux-templates/src/modal-import-wizard/ReduxTemplatesPremiumBox.js ***!
  \*****************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return ReduxTemplatesPremiumBox; });
const __ = wp.i18n.__;
function ReduxTemplatesPremiumBox(props) {
  const toProActivateStep = props.toProActivateStep;

  const onNextStep = () => {
    toProActivateStep();
  };

  return wp.element.createElement("div", {
    className: "redux-templates-modal-body"
  }, wp.element.createElement("div", {
    className: "section-box premium-box"
  }, wp.element.createElement("h3", null, __('Upgrade to Redux Pro', redux_templates.i18n)), wp.element.createElement("p", null, __('Thanks for giving our library a try! Upgrade to Redux Pro to unlock even more designs and to continue using our library.', redux_templates.i18n)), wp.element.createElement("p", null, wp.element.createElement("a", {
    href: redux_templates.u + 'import_wizard',
    className: "redux-templates-upgrade-button",
    title: "{__('Redux Pro', redux_templates.i18n)}",
    target: "_blank"
  }, __('Upgrade Now Just $49', redux_templates.i18n)), wp.element.createElement("small", null, wp.element.createElement("em", null, "Limited time only"))), wp.element.createElement("p", {
    className: "subscription_key_button"
  }, wp.element.createElement("button", {
    type: "button",
    className: "components-button",
    "aria-label": "I have a subscription key",
    onClick: () => onNextStep()
  }, "I have a subscription key"))));
}

/***/ }),

/***/ "./redux-templates/src/modal-import-wizard/index.js":
/*!**********************************************************!*\
  !*** ./redux-templates/src/modal-import-wizard/index.js ***!
  \**********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _InstallPluginStep__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./InstallPluginStep */ "./redux-templates/src/modal-import-wizard/InstallPluginStep.js");
/* harmony import */ var _ProPluginsStep__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ProPluginsStep */ "./redux-templates/src/modal-import-wizard/ProPluginsStep.js");
/* harmony import */ var _OptionStep__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./OptionStep */ "./redux-templates/src/modal-import-wizard/OptionStep.js");
/* harmony import */ var _ImportingStep__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./ImportingStep */ "./redux-templates/src/modal-import-wizard/ImportingStep.js");
/* harmony import */ var _ReduxTemplatesPremiumBox__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./ReduxTemplatesPremiumBox */ "./redux-templates/src/modal-import-wizard/ReduxTemplatesPremiumBox.js");
/* harmony import */ var _ReduxTemplatesPremiumActivate__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./ReduxTemplatesPremiumActivate */ "./redux-templates/src/modal-import-wizard/ReduxTemplatesPremiumActivate.js");
/* harmony import */ var _ReduxTemplatesActivateBox__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./ReduxTemplatesActivateBox */ "./redux-templates/src/modal-import-wizard/ReduxTemplatesActivateBox.js");
/* harmony import */ var _redux_templates_stores_dependencyHelper__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ~redux-templates/stores/dependencyHelper */ "./redux-templates/src/stores/dependencyHelper.js");
/* harmony import */ var _modals_scss__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ../modals.scss */ "./redux-templates/src/modals.scss");
/* harmony import */ var _modals_scss__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(_modals_scss__WEBPACK_IMPORTED_MODULE_8__);
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./style.scss */ "./redux-templates/src/modal-import-wizard/style.scss");
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_9___default = /*#__PURE__*/__webpack_require__.n(_style_scss__WEBPACK_IMPORTED_MODULE_9__);
function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }

function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }

function _iterableToArrayLimit(arr, i) { if (typeof Symbol === "undefined" || !(Symbol.iterator in Object(arr))) return; var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }

function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }

const __ = wp.i18n.__;
const compose = wp.compose.compose;
const _wp$data = wp.data,
      withDispatch = _wp$data.withDispatch,
      withSelect = _wp$data.withSelect;
const _wp$element = wp.element,
      useState = _wp$element.useState,
      useEffect = _wp$element.useEffect;
const _wp = wp,
      apiFetch = _wp.apiFetch;










const PRO_STEP = 0;
const PLUGIN_STEP = 1;
const OPTION_STEP = 2;
const IMPORT_STEP = 3;
const REDUX_PRO_STEP = -10;
const REDUX_PRO_ACTIVATE_STEP = -9;
const REDUX_ACTIVATE_STEP = 999;
const tourPlugins = ['qubely', 'kioken-blocks'];

function ImportWizard(props) {
  const startImportTemplate = props.startImportTemplate,
        setImportingTemplate = props.setImportingTemplate,
        setActivateDialogDisplay = props.setActivateDialogDisplay,
        appendErrorMessage = props.appendErrorMessage;
  const isChallengeOpen = props.isChallengeOpen,
        importingTemplate = props.importingTemplate,
        activateDialogDisplay = props.activateDialogDisplay,
        isPostEmpty = props.isPostEmpty,
        isInstalledDependencies = props.isInstalledDependencies;

  const _useState = useState(PRO_STEP),
        _useState2 = _slicedToArray(_useState, 2),
        currentStep = _useState2[0],
        setCurrentStep = _useState2[1];

  const _useState3 = useState(false),
        _useState4 = _slicedToArray(_useState3, 2),
        importing = _useState4[0],
        setImporting = _useState4[1];

  const _useState5 = useState(false),
        _useState6 = _slicedToArray(_useState5, 2),
        activating = _useState6[0],
        setActivating = _useState6[1];

  const _useState7 = useState([]),
        _useState8 = _slicedToArray(_useState7, 2),
        missingPlugins = _useState8[0],
        setMissingPlugins = _useState8[1];

  useEffect(() => {
    if (importingTemplate) {
      if (!importingTemplate.proDependenciesMissing) {
        importingTemplate.proDependenciesMissing = [];
      }

      if (!importingTemplate.installDependenciesMissing) {
        importingTemplate.installDependenciesMissing = [];
      } // IMPORTANT First check: can you use redux pro?


      const leftTry = isNaN(redux_templates.left) === false ? parseInt(redux_templates.left) : 0;

      if (!!redux_templates.mokama === false && leftTry < 1 && currentStep !== REDUX_PRO_ACTIVATE_STEP) {
        if (currentStep !== REDUX_ACTIVATE_STEP) {
          setCurrentStep(REDUX_PRO_STEP);
          return;
        }
      }
      /* Redux pro check */


      if (Object(_redux_templates_stores_dependencyHelper__WEBPACK_IMPORTED_MODULE_7__["requiresReduxPro"])(importingTemplate)) {
        if (currentStep !== REDUX_PRO_ACTIVATE_STEP) setCurrentStep(REDUX_PRO_STEP);
        return;
      } // Start with Pro step
      // When all OK with Pro Step, move to Plugin Step, on the way, prepare reduxProMergedPlugins.


      if (importingTemplate && currentStep === PRO_STEP && Object(_redux_templates_stores_dependencyHelper__WEBPACK_IMPORTED_MODULE_7__["requiresPro"])(importingTemplate) === false) {
        setCurrentStep(PLUGIN_STEP);

        if (Object(_redux_templates_stores_dependencyHelper__WEBPACK_IMPORTED_MODULE_7__["isReduxProInstalled"])()) {
          setMissingPlugins([].concat(importingTemplate.proDependenciesMissing, importingTemplate.installDependenciesMissing).filter(plugin => plugin));
        } else {
          setMissingPlugins(importingTemplate.installDependenciesMissing.filter(plugin => plugin));
        }
      }

      if (importingTemplate && currentStep === PLUGIN_STEP && Object(_redux_templates_stores_dependencyHelper__WEBPACK_IMPORTED_MODULE_7__["requiresInstall"])(importingTemplate) === false) if (isPostEmpty === false) setCurrentStep(OPTION_STEP);else setCurrentStep(IMPORT_STEP);
      if (importingTemplate && currentStep === OPTION_STEP && isPostEmpty === true) setCurrentStep(IMPORT_STEP);

      if (importingTemplate && currentStep === IMPORT_STEP && importing === false) {
        setImporting(true);

        try {
          startImportTemplate();
        } catch (e) {
          console.log('importing exception', e);
          setImporting(false);
          setCurrentStep(PLUGIN_STEP);
          setImportingTemplate(null);
        }
      }
    }
  }, [importingTemplate, currentStep, activateDialogDisplay]); // Activate dialog display

  useEffect(() => {
    if (activateDialogDisplay === true) {
      // Activate dialog hard reset case
      setCurrentStep(REDUX_ACTIVATE_STEP);
      setActivateDialogDisplay(false);
    }
  }, [activateDialogDisplay]); // On the initial loading

  useEffect(() => {
    setActivateDialogDisplay(false);
  }, []);

  const toNextStep = () => {
    if (isChallengeOpen) return;
    setCurrentStep(currentStep + 1);
  };

  const toPluginStep = () => {
    setCurrentStep(PRO_STEP);
  };

  const toProActivateStep = () => {
    setCurrentStep(REDUX_PRO_ACTIVATE_STEP);
  };

  const onCloseWizard = () => {
    if (isChallengeOpen) return; // When in tour mode, we don't accept mouse event.

    if (importing) return;
    setCurrentStep(PLUGIN_STEP);
    setImportingTemplate(null);
  };

  const activateReduxTracking = () => {
    setActivating(true);
    apiFetch({
      path: 'redux/v1/templates/activate'
    }).then(response => {
      if (response.success) {
        redux_templates.left = response.data.left;
      }

      setCurrentStep(PRO_STEP);
      setActivating(false);
    }).catch(error => {
      appendErrorMessage(error.code + ' : ' + error.message);
      setCurrentStep(PRO_STEP);
      setActivating(false);
    });
  };

  if (isChallengeOpen) {
    // exception handling for tour mode
    if (currentStep !== PLUGIN_STEP) setCurrentStep(PLUGIN_STEP);
  }

  if (!importingTemplate) return null;
  return wp.element.createElement("div", {
    className: "redux-templates-modal-overlay"
  }, wp.element.createElement("div", {
    className: "redux-templates-modal-wrapper",
    "data-tut": "tour__import_wizard"
  }, wp.element.createElement("div", {
    className: "redux-templates-modal-header"
  }, wp.element.createElement("h3", null, __('Import Wizard', redux_templates.i18n)), wp.element.createElement("button", {
    className: "redux-templates-modal-close",
    onClick: onCloseWizard
  }, wp.element.createElement("i", {
    className: 'fas fa-times'
  }))), wp.element.createElement("div", {
    className: "redux-templates-importmodal-content"
  }, currentStep === PRO_STEP && Object(_redux_templates_stores_dependencyHelper__WEBPACK_IMPORTED_MODULE_7__["requiresPro"])(importingTemplate) && wp.element.createElement(_ProPluginsStep__WEBPACK_IMPORTED_MODULE_1__["default"], {
    missingPros: importingTemplate.proDependenciesMissing,
    onCloseWizard: onCloseWizard
  }), currentStep === PLUGIN_STEP && wp.element.createElement(_InstallPluginStep__WEBPACK_IMPORTED_MODULE_0__["default"], {
    missingPlugins: isChallengeOpen ? tourPlugins : missingPlugins,
    toNextStep: toNextStep,
    onCloseWizard: onCloseWizard
  }), currentStep === OPTION_STEP && wp.element.createElement(_OptionStep__WEBPACK_IMPORTED_MODULE_2__["default"], {
    toNextStep: toNextStep,
    onCloseWizard: onCloseWizard
  }), currentStep === IMPORT_STEP && wp.element.createElement(_ImportingStep__WEBPACK_IMPORTED_MODULE_3__["default"], null), currentStep === REDUX_ACTIVATE_STEP && wp.element.createElement(_ReduxTemplatesActivateBox__WEBPACK_IMPORTED_MODULE_6__["default"], {
    onActivateRedux: activateReduxTracking,
    activating: activating
  }), currentStep === REDUX_PRO_ACTIVATE_STEP && wp.element.createElement(_ReduxTemplatesPremiumActivate__WEBPACK_IMPORTED_MODULE_5__["default"], {
    toPluginStep: toPluginStep
  }), currentStep === REDUX_PRO_STEP && wp.element.createElement(_ReduxTemplatesPremiumBox__WEBPACK_IMPORTED_MODULE_4__["default"], {
    toProActivateStep: toProActivateStep
  }), isInstalledDependencies && wp.element.createElement("iframe", {
    src: "./",
    width: "0",
    height: "0"
  }))));
}

/* harmony default export */ __webpack_exports__["default"] = (compose([withDispatch(dispatch => {
  const _dispatch = dispatch('redux-templates/sectionslist'),
        setImportingTemplate = _dispatch.setImportingTemplate,
        setActivateDialogDisplay = _dispatch.setActivateDialogDisplay,
        appendErrorMessage = _dispatch.appendErrorMessage;

  return {
    setImportingTemplate,
    setActivateDialogDisplay,
    appendErrorMessage
  };
}), withSelect((select, props) => {
  const _select = select('redux-templates/sectionslist'),
        getChallengeOpen = _select.getChallengeOpen,
        getImportingTemplate = _select.getImportingTemplate,
        getActivateDialogDisplay = _select.getActivateDialogDisplay,
        getInstalledDependencies = _select.getInstalledDependencies;

  const _select2 = select('core/editor'),
        isEditedPostEmpty = _select2.isEditedPostEmpty;

  return {
    isChallengeOpen: getChallengeOpen(),
    importingTemplate: getImportingTemplate(),
    activateDialogDisplay: getActivateDialogDisplay(),
    isPostEmpty: isEditedPostEmpty(),
    isInstalledDependencies: getInstalledDependencies()
  };
})])(ImportWizard));

/***/ }),

/***/ "./redux-templates/src/modal-import-wizard/style.scss":
/*!************************************************************!*\
  !*** ./redux-templates/src/modal-import-wizard/style.scss ***!
  \************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../node_modules/css-loader/dist/cjs.js!../../../node_modules/sass-loader/dist/cjs.js!./style.scss */ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/modal-import-wizard/style.scss");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./redux-templates/src/modal-library/index.js":
/*!****************************************************!*\
  !*** ./redux-templates/src/modal-library/index.js ***!
  \****************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _stores__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../stores */ "./redux-templates/src/stores/index.js");
/* harmony import */ var _modal_manager__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../modal-manager */ "./redux-templates/src/modal-manager/index.js");
/* harmony import */ var _components_tab_header__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../components/tab-header */ "./redux-templates/src/components/tab-header/index.js");
/* harmony import */ var _layout_with_sidebar__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./layout-with-sidebar */ "./redux-templates/src/modal-library/layout-with-sidebar/index.js");
/* harmony import */ var _view_collection__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./view-collection */ "./redux-templates/src/modal-library/view-collection/index.js");
/* harmony import */ var _view_saved__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./view-saved */ "./redux-templates/src/modal-library/view-saved/index.js");
/* harmony import */ var _modal_import_wizard__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../modal-import-wizard */ "./redux-templates/src/modal-import-wizard/index.js");
/* harmony import */ var _modal_promotor_score__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../modal-promotor-score */ "./redux-templates/src/modal-promotor-score/index.js");
/* harmony import */ var _components_error_notice__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ../components/error-notice */ "./redux-templates/src/components/error-notice/index.js");
/* harmony import */ var _redux_templates_challenge_final_templates__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ~redux-templates/challenge/final-templates */ "./redux-templates/src/challenge/final-templates/index.js");
/* harmony import */ var _components_fab_wrapper__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ../components/fab-wrapper */ "./redux-templates/src/components/fab-wrapper/index.js");
/* harmony import */ var _redux_templates_stores_actionHelper__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ~redux-templates/stores/actionHelper */ "./redux-templates/src/stores/actionHelper.js");
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! ./style.scss */ "./redux-templates/src/modal-library/style.scss");
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_12___default = /*#__PURE__*/__webpack_require__.n(_style_scss__WEBPACK_IMPORTED_MODULE_12__);
function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }

function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }

function _iterableToArrayLimit(arr, i) { if (typeof Symbol === "undefined" || !(Symbol.iterator in Object(arr))) return; var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }

function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }

const compose = wp.compose.compose;
const _wp$data = wp.data,
      withDispatch = _wp$data.withDispatch,
      withSelect = _wp$data.withSelect;
const _wp$element = wp.element,
      useState = _wp$element.useState,
      useEffect = _wp$element.useEffect;














function LibraryModal(props) {
  const fetchLibraryFromAPI = props.fetchLibraryFromAPI,
        activeCollection = props.activeCollection,
        activeItemType = props.activeItemType,
        errorMessages = props.errorMessages,
        importingTemplate = props.importingTemplate,
        challengeFinalStatus = props.challengeFinalStatus,
        isChallengeOpen = props.isChallengeOpen,
        setLoading = props.setLoading,
        setImportingTemplate = props.setImportingTemplate,
        clearSearch = props.clearSearch,
        clearState = props.clearState;

  const _useState = useState(false),
        _useState2 = _slicedToArray(_useState, 2),
        loaded = _useState2[0],
        setLoaded = _useState2[1];

  const _useState3 = useState(false),
        _useState4 = _slicedToArray(_useState3, 2),
        escKeyPressed = _useState4[0],
        setEscKeyPressed = _useState4[1];

  const _useState5 = useState(false),
        _useState6 = _slicedToArray(_useState5, 2),
        isPSModalVisible = _useState6[0],
        setPSModalVisible = _useState6[1];

  let stateLibrary = null;
  useEffect(() => {
    clearState();
    stateLibrary = fetchLibraryFromAPI();

    if (stateLibrary === null && loaded === false) {
      // One to be called at first.
      setLoading(true);
      setLoaded(true);
    }

    setPSModalVisible(!!redux_templates.nps);

    const handleKeyUp = ({
      keyCode
    }) => {
      if (keyCode === 27) {
        setEscKeyPressed(true);
      }
    };

    document.addEventListener('keyup', handleKeyUp);
    return () => {
      document.removeEventListener('keyup', handleKeyUp);
    };
  }, []);
  useEffect(() => {
    if (escKeyPressed) {
      setEscKeyPressed(false);

      if (_modal_manager__WEBPACK_IMPORTED_MODULE_1__["ModalManager"].isCustomizerOpened()) {
        _modal_manager__WEBPACK_IMPORTED_MODULE_1__["ModalManager"].closeCustomizer();
      } else {
        if (importingTemplate) setImportingTemplate(null);else {
          _modal_manager__WEBPACK_IMPORTED_MODULE_1__["ModalManager"].close();
        }
      }
    }
  }, [escKeyPressed]);

  const hasSidebar = () => {
    return (activeItemType !== 'collection' || activeCollection === null) && activeItemType !== 'saved';
  }; // read block data to import and give the control to actual import


  const processImport = () => {
    if (importingTemplate) Object(_redux_templates_stores_actionHelper__WEBPACK_IMPORTED_MODULE_11__["processImportHelper"])();
  };

  return wp.element.createElement(_modal_manager__WEBPACK_IMPORTED_MODULE_1__["Modal"], {
    className: "redux-templates-builder-modal-pages-list",
    customClass: "redux-templates-builder-modal-template-list",
    openTimeoutMS: 0,
    closeTimeoutMS: 0
  }, wp.element.createElement(_components_tab_header__WEBPACK_IMPORTED_MODULE_2__["default"], null), errorMessages && errorMessages.length > 0 && wp.element.createElement(_components_error_notice__WEBPACK_IMPORTED_MODULE_8__["default"], {
    errorMessages: errorMessages
  }), wp.element.createElement("div", {
    className: "redux-templates-collections-modal-body"
  }, hasSidebar() && wp.element.createElement(_layout_with_sidebar__WEBPACK_IMPORTED_MODULE_3__["default"], null), hasSidebar() === false && activeItemType === 'collection' && wp.element.createElement(_view_collection__WEBPACK_IMPORTED_MODULE_4__["default"], null), hasSidebar() === false && activeItemType !== 'collection' && wp.element.createElement(_view_saved__WEBPACK_IMPORTED_MODULE_5__["default"], null)), importingTemplate && wp.element.createElement(_modal_import_wizard__WEBPACK_IMPORTED_MODULE_6__["default"], {
    startImportTemplate: processImport
  }), isPSModalVisible && wp.element.createElement(_modal_promotor_score__WEBPACK_IMPORTED_MODULE_7__["default"], {
    propOnClose: () => setPSModalVisible(false)
  }), challengeFinalStatus !== '' && wp.element.createElement(_redux_templates_challenge_final_templates__WEBPACK_IMPORTED_MODULE_9__["default"], {
    finalStatus: challengeFinalStatus
  }), !isChallengeOpen && wp.element.createElement(_components_fab_wrapper__WEBPACK_IMPORTED_MODULE_10__["default"], null));
}

/* harmony default export */ __webpack_exports__["default"] = (compose([withDispatch(dispatch => {
  const _dispatch = dispatch('redux-templates/sectionslist'),
        setLoading = _dispatch.setLoading,
        setLibrary = _dispatch.setLibrary,
        setImportingTemplate = _dispatch.setImportingTemplate,
        clearSearch = _dispatch.clearSearch,
        clearState = _dispatch.clearState;

  return {
    setLoading,
    setLibrary,
    setImportingTemplate,
    clearSearch,
    clearState
  };
}), withSelect(select => {
  const _select = select('redux-templates/sectionslist'),
        fetchLibraryFromAPI = _select.fetchLibraryFromAPI,
        getActiveCollection = _select.getActiveCollection,
        getActiveItemType = _select.getActiveItemType,
        getErrorMessages = _select.getErrorMessages,
        getImportingTemplate = _select.getImportingTemplate,
        getChallengeOpen = _select.getChallengeOpen,
        getChallengeFinalStatus = _select.getChallengeFinalStatus;

  return {
    fetchLibraryFromAPI,
    activeCollection: getActiveCollection(),
    activeItemType: getActiveItemType(),
    errorMessages: getErrorMessages(),
    importingTemplate: getImportingTemplate(),
    challengeFinalStatus: getChallengeFinalStatus(),
    isChallengeOpen: getChallengeOpen()
  };
})])(LibraryModal));

/***/ }),

/***/ "./redux-templates/src/modal-library/layout-with-sidebar/index.js":
/*!************************************************************************!*\
  !*** ./redux-templates/src/modal-library/layout-with-sidebar/index.js ***!
  \************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return WithSidebarCollection; });
/* harmony import */ var _sidebar__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../sidebar */ "./redux-templates/src/modal-library/sidebar/index.js");
/* harmony import */ var _redux_templates_components_template_list_subheader__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ~redux-templates/components/template-list-subheader */ "./redux-templates/src/components/template-list-subheader/index.js");
/* harmony import */ var _view_template_list__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../view-template-list */ "./redux-templates/src/modal-library/view-template-list/index.js");
const Fragment = wp.element.Fragment;



function WithSidebarCollection(props) {
  return wp.element.createElement(Fragment, null, wp.element.createElement("div", {
    id: "redux-templates-collection-modal-sidebar",
    className: "redux-templates-collection-modal-sidebar"
  }, wp.element.createElement(_sidebar__WEBPACK_IMPORTED_MODULE_0__["default"], null)), wp.element.createElement("div", {
    className: "redux-templates-collection-modal-content-area",
    "data-tut": "tour__main_body",
    id: "modalContent"
  }, wp.element.createElement(_redux_templates_components_template_list_subheader__WEBPACK_IMPORTED_MODULE_1__["default"], null), wp.element.createElement(_view_template_list__WEBPACK_IMPORTED_MODULE_2__["default"], null)));
}

/***/ }),

/***/ "./redux-templates/src/modal-library/sidebar/categoryFilter.js":
/*!*********************************************************************!*\
  !*** ./redux-templates/src/modal-library/sidebar/categoryFilter.js ***!
  \*********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var lodash_uniq__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! lodash/uniq */ "./node_modules/lodash/uniq.js");
/* harmony import */ var lodash_uniq__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(lodash_uniq__WEBPACK_IMPORTED_MODULE_0__);
const compose = wp.compose.compose;
const _wp$data = wp.data,
      withDispatch = _wp$data.withDispatch,
      withSelect = _wp$data.withSelect;
const __ = wp.i18n.__;


function CategoryFilter(props) {
  const categoryData = props.categoryData,
        activeCategory = props.activeCategory,
        activePriceFilter = props.activePriceFilter,
        loading = props.loading,
        itemType = props.itemType;
  const setActiveCategory = props.setActiveCategory; // On the top, All Block, All Template, All Template Kit etc

  const itemTypeLabel = () => {
    if (itemType === 'section') return __('Section', redux_templates.i18n);
    if (itemType === 'page') return __('Template', redux_templates.i18n);
    if (itemType === 'collection') return __('Template Kit', redux_templates.i18n);
  };

  const totalItemCountLabel = () => {
    let totalArr = [],
        filteredArr = [];
    categoryData.forEach(category => {
      if (category.hasOwnProperty('filteredData')) filteredArr = [...filteredArr, ...category.filteredData];
      totalArr = [...totalArr, ...category.ids];
    });
    return activePriceFilter !== '' ? lodash_uniq__WEBPACK_IMPORTED_MODULE_0___default()(filteredArr).length + '/' + lodash_uniq__WEBPACK_IMPORTED_MODULE_0___default()(totalArr).length : lodash_uniq__WEBPACK_IMPORTED_MODULE_0___default()(totalArr).length;
  };

  const isDisabledCategory = data => data && (data.hasOwnProperty('filteredData') && data.filteredData.length === 0 || data.ids.length === 0);

  const onChangeCategory = data => {
    if (isDisabledCategory(data)) return;
    setActiveCategory(data.slug);
  }; // Give the selected category(activeCategory) label className as "active"


  const activeClassname = data => {
    const categoryLabel = data ? data.slug : '';
    if (isDisabledCategory(data)) return 'disabled';
    return activeCategory === categoryLabel ? 'active' : '';
  };

  return wp.element.createElement("div", null, wp.element.createElement("h3", null, __('Categories', redux_templates.i18n)), !loading && wp.element.createElement("ul", {
    className: "redux-templates-sidebar-categories"
  }, categoryData.length > 0 && wp.element.createElement("li", {
    className: activeClassname(null),
    onClick: () => setActiveCategory('')
  }, __('All', redux_templates.i18n), " ", itemTypeLabel(), "s ", wp.element.createElement("span", null, totalItemCountLabel())), categoryData && categoryData.map((data, index) => wp.element.createElement("li", {
    className: activeClassname(data),
    onClick: () => onChangeCategory(data),
    key: index
  }, data.name, wp.element.createElement("span", null, " ", data.hasOwnProperty('filteredData') && activePriceFilter !== '' ? data.filteredData.length : data.ids.length, " ")))));
}

/* harmony default export */ __webpack_exports__["default"] = (compose([withDispatch(dispatch => {
  const _dispatch = dispatch('redux-templates/sectionslist'),
        setActiveCategory = _dispatch.setActiveCategory;

  return {
    setActiveCategory
  };
}), withSelect((select, props) => {
  const _select = select('redux-templates/sectionslist'),
        getCategoryData = _select.getCategoryData,
        getActiveCategory = _select.getActiveCategory,
        getActiveItemType = _select.getActiveItemType,
        getLoading = _select.getLoading;

  return {
    categoryData: getCategoryData(),
    activeCategory: getActiveCategory(),
    itemType: getActiveItemType(),
    loading: getLoading()
  };
})])(CategoryFilter));

/***/ }),

/***/ "./redux-templates/src/modal-library/sidebar/dependencyFilter.js":
/*!***********************************************************************!*\
  !*** ./redux-templates/src/modal-library/sidebar/dependencyFilter.js ***!
  \***********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _redux_templates_challenge_tooltip_ChallengeDot__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ~redux-templates/challenge/tooltip/ChallengeDot */ "./redux-templates/src/challenge/tooltip/ChallengeDot.js");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _dependencyFilterRow__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./dependencyFilterRow */ "./redux-templates/src/modal-library/sidebar/dependencyFilterRow.js");
/* harmony import */ var _redux_templates_stores_dependencyHelper__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ~redux-templates/stores/dependencyHelper */ "./redux-templates/src/stores/dependencyHelper.js");
/* harmony import */ var _redux_templates_stores_helper__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ~redux-templates/stores/helper */ "./redux-templates/src/stores/helper.js");
function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

const Fragment = wp.element.Fragment;
const compose = wp.compose.compose;
const _wp$data = wp.data,
      withDispatch = _wp$data.withDispatch,
      withSelect = _wp$data.withSelect;
const __ = wp.i18n.__;






function DependencyFilter(props) {
  const dependencyFilters = props.dependencyFilters,
        activeItemType = props.activeItemType,
        loading = props.loading,
        wholePlugins = props.wholePlugins,
        thirdPartyPlugins = props.thirdPartyPlugins,
        dependencyFilterRule = props.dependencyFilterRule;
  const setDependencyFilters = props.setDependencyFilters,
        selectDependencies = props.selectDependencies,
        setDependencyFilterRule = props.setDependencyFilterRule; // Give the selected category(activeCategory) label className as "active"

  const isNoneChecked = () => {
    if (dependencyFilters.hasOwnProperty(_redux_templates_stores_helper__WEBPACK_IMPORTED_MODULE_4__["NONE_KEY"])) return dependencyFilters[_redux_templates_stores_helper__WEBPACK_IMPORTED_MODULE_4__["NONE_KEY"]].hasOwnProperty('value') ? dependencyFilters[_redux_templates_stores_helper__WEBPACK_IMPORTED_MODULE_4__["NONE_KEY"]].value : dependencyFilters[_redux_templates_stores_helper__WEBPACK_IMPORTED_MODULE_4__["NONE_KEY"]];
    return false;
  };

  const toggleNoneChecked = () => {
    setDependencyFilters(_objectSpread(_objectSpread({}, dependencyFilters), {}, {
      [_redux_templates_stores_helper__WEBPACK_IMPORTED_MODULE_4__["NONE_KEY"]]: {
        value: dependencyFilters[_redux_templates_stores_helper__WEBPACK_IMPORTED_MODULE_4__["NONE_KEY"]].value === false,
        disabled: dependencyFilters[_redux_templates_stores_helper__WEBPACK_IMPORTED_MODULE_4__["NONE_KEY"]]['disabled'] === true
      }
    }));
  };

  return wp.element.createElement(Fragment, null, !loading && wholePlugins && wp.element.createElement("div", {
    id: "redux-templates-filter-dependencies",
    "data-tut": "tour__filter_dependencies"
  }, wp.element.createElement(React.Fragment, null, wp.element.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__["ButtonGroup"], {
    style: {
      float: 'right'
    }
  }, wp.element.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__["Tooltip"], {
    text: __('Templates which contain blocks from any of the selected plugins.', redux_templates.i18n),
    position: "top center"
  }, wp.element.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__["Button"], {
    isSmall: true,
    isPrimary: dependencyFilterRule === false,
    isSecondary: dependencyFilterRule,
    onClick: () => setDependencyFilterRule(false),
    disabled: activeItemType === 'collection'
  }, __('Any', redux_templates.i18n))), wp.element.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__["Tooltip"], {
    text: __('Templates that only contain blocks from the selected plugins.', redux_templates.i18n),
    position: "top center"
  }, wp.element.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__["Button"], {
    isSmall: true,
    isSecondary: dependencyFilterRule === false,
    isPrimary: dependencyFilterRule,
    onClick: () => setDependencyFilterRule(true),
    disabled: activeItemType === 'collection'
  }, __('Only', redux_templates.i18n)))), wp.element.createElement("h3", null, __('Required Plugins', redux_templates.i18n), " ")), wp.element.createElement("div", {
    className: "redux-templates-select-actions"
  }, wp.element.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__["Tooltip"], {
    text: __('Select All', redux_templates.i18n),
    position: "top center"
  }, wp.element.createElement("a", {
    href: "#",
    onClick: () => selectDependencies('all')
  }, __('All', redux_templates.i18n))), wp.element.createElement("span", null, "\xA0 / \xA0"), wp.element.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__["Tooltip"], {
    text: __('Installed Dependencies', redux_templates.i18n),
    position: "top center"
  }, wp.element.createElement("a", {
    href: "#",
    onClick: () => selectDependencies('installed')
  }, __('Installed', redux_templates.i18n))), wp.element.createElement("span", null, "\xA0 / \xA0"), wp.element.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__["Tooltip"], {
    text: __('Native Blocks Only', redux_templates.i18n),
    position: "top center"
  }, wp.element.createElement("a", {
    href: "#",
    onClick: () => selectDependencies('none')
  }, __('None', redux_templates.i18n))), wp.element.createElement(_redux_templates_challenge_tooltip_ChallengeDot__WEBPACK_IMPORTED_MODULE_0__["default"], {
    step: 2
  })), wp.element.createElement("ul", {
    className: "redux-templates-sidebar-dependencies"
  }, loading === false && wp.element.createElement("li", {
    style: {
      display: activeItemType === 'collection' ? 'none' : ''
    }
  }, wp.element.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__["CheckboxControl"], {
    label: __('Native', redux_templates.i18n),
    checked: isNoneChecked(),
    onChange: toggleNoneChecked
  }), wp.element.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__["Tooltip"], {
    text: __('Only default WordPress blocks used.', redux_templates.i18n),
    position: "top center"
  }, wp.element.createElement("span", {
    style: {
      float: 'right',
      marginRight: '2px'
    }
  }, wp.element.createElement("i", {
    className: "fa fa-info-circle"
  })))), Object.keys(dependencyFilters).filter(pluginKey => wholePlugins.indexOf(pluginKey) !== -1 || pluginKey === _redux_templates_stores_helper__WEBPACK_IMPORTED_MODULE_4__["REDUXTEMPLATES_PRO_KEY"]).sort((a, b) => {
    const pluginInstanceA = Object(_redux_templates_stores_dependencyHelper__WEBPACK_IMPORTED_MODULE_3__["pluginInfo"])(a);
    const pluginInstanceB = Object(_redux_templates_stores_dependencyHelper__WEBPACK_IMPORTED_MODULE_3__["pluginInfo"])(b);
    if (!pluginInstanceB.name || pluginInstanceA.name < pluginInstanceB.name) return -1;
    if (!pluginInstanceA.name || pluginInstanceA.name > pluginInstanceB.name) return 1;
    return 0;
  }).map(pluginKey => wp.element.createElement(_dependencyFilterRow__WEBPACK_IMPORTED_MODULE_2__["default"], {
    key: pluginKey,
    pluginKey: pluginKey
  }))), wp.element.createElement("div", {
    role: "group",
    className: "components-button-group",
    style: {
      float: 'right'
    }
  }, wp.element.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__["Tooltip"], {
    text: __('Third-party premium plugins required.', redux_templates.i18n),
    position: "top center"
  }, wp.element.createElement("span", {
    style: {
      float: 'right',
      marginRight: '15px',
      cursor: 'pointer'
    }
  }, wp.element.createElement("i", {
    className: "fa fa-info-circle"
  })))), wp.element.createElement("h3", null, "Third-Party Premium"), wp.element.createElement("ul", {
    className: "redux-templates-sidebar-dependencies"
  }, thirdPartyPlugins.sort((a, b) => {
    const pluginInstanceA = Object(_redux_templates_stores_dependencyHelper__WEBPACK_IMPORTED_MODULE_3__["pluginInfo"])(a);
    const pluginInstanceB = Object(_redux_templates_stores_dependencyHelper__WEBPACK_IMPORTED_MODULE_3__["pluginInfo"])(b);
    if (!pluginInstanceB.name || pluginInstanceA.name < pluginInstanceB.name) return -1;
    if (!pluginInstanceA.name || pluginInstanceA.name > pluginInstanceB.name) return 1;
    return 0;
  }).map(pluginKey => wp.element.createElement(_dependencyFilterRow__WEBPACK_IMPORTED_MODULE_2__["default"], {
    key: pluginKey,
    pluginKey: pluginKey
  })))));
}

/* harmony default export */ __webpack_exports__["default"] = (compose([withDispatch(dispatch => {
  const _dispatch = dispatch('redux-templates/sectionslist'),
        setDependencyFilters = _dispatch.setDependencyFilters,
        selectDependencies = _dispatch.selectDependencies,
        setDependencyFilterRule = _dispatch.setDependencyFilterRule;

  return {
    setDependencyFilters,
    selectDependencies,
    setDependencyFilterRule
  };
}), withSelect(select => {
  const _select = select('redux-templates/sectionslist'),
        getDependencyFiltersStatistics = _select.getDependencyFiltersStatistics,
        getLoading = _select.getLoading,
        getActiveItemType = _select.getActiveItemType,
        getWholePlugins = _select.getWholePlugins,
        getThirdPartyPlugins = _select.getThirdPartyPlugins,
        getDependencyFilterRule = _select.getDependencyFilterRule;

  return {
    loading: getLoading(),
    dependencyFilters: getDependencyFiltersStatistics(),
    wholePlugins: getWholePlugins(),
    thirdPartyPlugins: getThirdPartyPlugins(),
    dependencyFilterRule: getDependencyFilterRule(),
    activeItemType: getActiveItemType()
  };
})])(DependencyFilter));

/***/ }),

/***/ "./redux-templates/src/modal-library/sidebar/dependencyFilterRow.js":
/*!**************************************************************************!*\
  !*** ./redux-templates/src/modal-library/sidebar/dependencyFilterRow.js ***!
  \**************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _redux_templates_stores_dependencyHelper__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ~redux-templates/stores/dependencyHelper */ "./redux-templates/src/stores/dependencyHelper.js");
/* harmony import */ var _redux_templates_stores_helper__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ~redux-templates/stores/helper */ "./redux-templates/src/stores/helper.js");
function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }

function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }

function _iterableToArrayLimit(arr, i) { if (typeof Symbol === "undefined" || !(Symbol.iterator in Object(arr))) return; var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }

function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }


const _wp$element = wp.element,
      useState = _wp$element.useState,
      useEffect = _wp$element.useEffect,
      useRef = _wp$element.useRef;
const compose = wp.compose.compose;
const _wp$data = wp.data,
      withDispatch = _wp$data.withDispatch,
      withSelect = _wp$data.withSelect;
const __ = wp.i18n.__;



const specialPlugins = ['gutenberghub.com', 'editorplus'];

function DependencyFilterRow(props) {
  const pluginKey = props.pluginKey,
        dependencyFilters = props.dependencyFilters;
  const setDependencyFilters = props.setDependencyFilters;

  const _useState = useState(false),
        _useState2 = _slicedToArray(_useState, 2),
        isValidPlugin = _useState2[0],
        setIsValidPlugin = _useState2[1];

  const _useState3 = useState(false),
        _useState4 = _slicedToArray(_useState3, 2),
        isChecked = _useState4[0],
        setIsChecked = _useState4[1];

  const _useState5 = useState(''),
        _useState6 = _slicedToArray(_useState5, 2),
        pluginInstanceURL = _useState6[0],
        setPluginInstanceURL = _useState6[1];

  const _useState7 = useState(''),
        _useState8 = _slicedToArray(_useState7, 2),
        pluginInstanceName = _useState8[0],
        setPluginInstanceName = _useState8[1];

  const _useState9 = useState(''),
        _useState10 = _slicedToArray(_useState9, 2),
        pluginClassname = _useState10[0],
        setPluginClassname = _useState10[1];

  useEffect(() => {
    const pluginInstance = Object(_redux_templates_stores_dependencyHelper__WEBPACK_IMPORTED_MODULE_2__["pluginInfo"])(pluginKey);

    if (!pluginKey || pluginKey === _redux_templates_stores_helper__WEBPACK_IMPORTED_MODULE_3__["NONE_KEY"]) {
      setIsValidPlugin(false);
      return;
    }

    if (!pluginInstance || pluginInstance.name == null) {
      setIsValidPlugin(false);
      return;
    }

    setPluginInstanceURL(pluginInstance.url);
    setPluginInstanceName(pluginInstance.name);
    setIsValidPlugin(true);
  }, [pluginKey]);
  useEffect(() => {
    const pluginInstance = Object(_redux_templates_stores_dependencyHelper__WEBPACK_IMPORTED_MODULE_2__["pluginInfo"])(pluginKey);
    if (!dependencyFilters) return;

    if (dependencyFilters.hasOwnProperty(pluginKey)) {
      if (dependencyFilters[pluginKey].disabled) setIsChecked(false);else setIsChecked(dependencyFilters[pluginKey].hasOwnProperty('value') ? dependencyFilters[pluginKey].value : dependencyFilters[pluginKey]);
    } else setIsChecked(false);

    let pluginClassnameList = [];
    pluginClassnameList.push(!pluginInstance.version && !('no_plugin' in pluginInstance) ? 'missing-dependency' : '');
    pluginClassnameList.push(!dependencyFilters[pluginKey] || dependencyFilters[pluginKey].disabled ? 'disabled' : '');
    setPluginClassname(pluginClassnameList.join(' '));
  }, [JSON.stringify(dependencyFilters)]);

  const toggleChecked = () => {
    // disable check first
    if (dependencyFilters[pluginKey] === null || dependencyFilters[pluginKey] === undefined || dependencyFilters[pluginKey].disabled) return; // reflect on the item click event.

    let newDependencyFilters = _objectSpread(_objectSpread({}, dependencyFilters), {}, {
      [pluginKey]: {
        value: dependencyFilters[pluginKey].value === false,
        disabled: dependencyFilters[pluginKey]['disabled'] === true
      }
    }); // gutenberg.com, EditorPlus check


    if (specialPlugins.includes(pluginKey)) {
      specialPlugins.forEach(plugin => {
        newDependencyFilters = _objectSpread(_objectSpread({}, newDependencyFilters), {}, {
          [plugin]: {
            value: dependencyFilters[pluginKey].value === false,
            disabled: dependencyFilters[plugin]['disabled'] === true
          }
        });
      });
    } // if no item is selected, activate native, other wise conider to deactivate native
    // let valueCount = groupBy(Object.keys(newDependencyFilters), key => (newDependencyFilters[key] === true || newDependencyFilters[key].value === true));


    setDependencyFilters(_objectSpread({}, newDependencyFilters));
  };

  if (isValidPlugin === false) return null;
  return wp.element.createElement("li", {
    className: pluginClassname
  }, wp.element.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__["CheckboxControl"], {
    label: pluginInstanceName,
    checked: isChecked,
    onChange: toggleChecked
  }), pluginInstanceURL ? wp.element.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__["Tooltip"], {
    position: 'top center',
    text: __('Visit Plugin Website', redux_templates.i18n)
  }, wp.element.createElement("span", null, wp.element.createElement("a", {
    href: pluginInstanceURL,
    target: "_blank"
  }, wp.element.createElement("i", {
    className: "fas fa-external-link-alt"
  })))) : null, pluginClassname.includes('missing-dependency') && wp.element.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__["Tooltip"], {
    position: 'top center',
    text: __('Not Installed', redux_templates.i18n)
  }, wp.element.createElement("span", {
    className: "redux-icon-wrapper"
  }, wp.element.createElement("i", {
    className: "fa fa-exclamation-triangle"
  }))));
}

/* harmony default export */ __webpack_exports__["default"] = (compose([withDispatch(dispatch => {
  const _dispatch = dispatch('redux-templates/sectionslist'),
        setDependencyFilters = _dispatch.setDependencyFilters;

  return {
    setDependencyFilters
  };
}), withSelect(select => {
  const _select = select('redux-templates/sectionslist'),
        getDependencyFiltersStatistics = _select.getDependencyFiltersStatistics,
        getLoading = _select.getLoading,
        getActiveCategory = _select.getActiveCategory;

  return {
    loading: getLoading(),
    dependencyFilters: getDependencyFiltersStatistics(),
    activeCategory: getActiveCategory()
  };
})])(DependencyFilterRow));

/***/ }),

/***/ "./redux-templates/src/modal-library/sidebar/index.js":
/*!************************************************************!*\
  !*** ./redux-templates/src/modal-library/sidebar/index.js ***!
  \************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./style.scss */ "./redux-templates/src/modal-library/sidebar/style.scss");
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_style_scss__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _priceFilter__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./priceFilter */ "./redux-templates/src/modal-library/sidebar/priceFilter.js");
/* harmony import */ var _categoryFilter__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./categoryFilter */ "./redux-templates/src/modal-library/sidebar/categoryFilter.js");
/* harmony import */ var _dependencyFilter__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./dependencyFilter */ "./redux-templates/src/modal-library/sidebar/dependencyFilter.js");
const withSelect = wp.data.withSelect;





function Sidebar(props) {
  const itemType = props.itemType,
        layer = props.layer,
        loading = props.loading;

  const hasSidebar = () => {
    return itemType !== 'collection' || layer === null;
  };

  return wp.element.createElement("div", null, hasSidebar() && wp.element.createElement(React.Fragment, null, wp.element.createElement(_priceFilter__WEBPACK_IMPORTED_MODULE_1__["default"], null), wp.element.createElement("div", {
    className: "redux-templates-modal-sidebar-content"
  }, wp.element.createElement(_categoryFilter__WEBPACK_IMPORTED_MODULE_2__["default"], null), wp.element.createElement(_dependencyFilter__WEBPACK_IMPORTED_MODULE_3__["default"], null))));
}

/* harmony default export */ __webpack_exports__["default"] = (withSelect(select => {
  const _select = select('redux-templates/sectionslist'),
        getActiveItemType = _select.getActiveItemType,
        getActiveCollection = _select.getActiveCollection;

  return {
    itemType: getActiveItemType(),
    layer: getActiveCollection()
  };
})(Sidebar));

/***/ }),

/***/ "./redux-templates/src/modal-library/sidebar/priceFilter.js":
/*!******************************************************************!*\
  !*** ./redux-templates/src/modal-library/sidebar/priceFilter.js ***!
  \******************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
const compose = wp.compose.compose;
const _wp$data = wp.data,
      withDispatch = _wp$data.withDispatch,
      withSelect = _wp$data.withSelect;
const __ = wp.i18n.__;

function PriceFilter(props) {
  const setActivePriceFilter = props.setActivePriceFilter,
        activePriceFilter = props.activePriceFilter,
        statistics = props.statistics;

  const getClassnames = priceFilter => {
    let classNames = [];
    classNames.push(priceFilter === activePriceFilter ? 'active' : '');
    classNames.push(noStatistics(priceFilter) ? 'disabled' : '');
    return classNames.join(' ');
  };

  const noStatistics = priceFilter => {
    if (priceFilter === '') return false;
    if (priceFilter === 'free') return !statistics['false'] || statistics['false'] < 1;else return !statistics['true'] || statistics['true'] < 1;
  };

  return wp.element.createElement("div", {
    className: "redux-templates-template-filter-button-group"
  }, wp.element.createElement("button", {
    onClick: () => setActivePriceFilter(''),
    className: getClassnames('')
  }, __('All', redux_templates.i18n)), wp.element.createElement("button", {
    onClick: () => setActivePriceFilter('free'),
    className: getClassnames('free'),
    disabled: noStatistics('free')
  }, __('Free', redux_templates.i18n)), wp.element.createElement("button", {
    onClick: () => setActivePriceFilter('pro'),
    className: getClassnames('pro'),
    disabled: noStatistics('pro')
  }, wp.element.createElement("img", {
    src: redux_templates.plugin + 'assets/img/icon-premium.svg',
    alt: ""
  }), __('Premium', redux_templates.i18n)));
}

/* harmony default export */ __webpack_exports__["default"] = (compose([withDispatch(dispatch => {
  const _dispatch = dispatch('redux-templates/sectionslist'),
        setActivePriceFilter = _dispatch.setActivePriceFilter;

  return {
    setActivePriceFilter
  };
}), withSelect((select, props) => {
  const _select = select('redux-templates/sectionslist'),
        getStatistics = _select.getStatistics,
        getActivePriceFilter = _select.getActivePriceFilter;

  return {
    activePriceFilter: getActivePriceFilter(),
    statistics: getStatistics()
  };
})])(PriceFilter));

/***/ }),

/***/ "./redux-templates/src/modal-library/sidebar/style.scss":
/*!**************************************************************!*\
  !*** ./redux-templates/src/modal-library/sidebar/style.scss ***!
  \**************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../node_modules/css-loader/dist/cjs.js!../../../../node_modules/sass-loader/dist/cjs.js!./style.scss */ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/modal-library/sidebar/style.scss");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./redux-templates/src/modal-library/style.scss":
/*!******************************************************!*\
  !*** ./redux-templates/src/modal-library/style.scss ***!
  \******************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../node_modules/css-loader/dist/cjs.js!../../../node_modules/sass-loader/dist/cjs.js!./style.scss */ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/modal-library/style.scss");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./redux-templates/src/modal-library/view-collection/index.js":
/*!********************************************************************!*\
  !*** ./redux-templates/src/modal-library/view-collection/index.js ***!
  \********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./style.scss */ "./redux-templates/src/modal-library/view-collection/style.scss");
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_style_scss__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _redux_templates_components_button_group__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ~redux-templates/components/button-group */ "./redux-templates/src/components/button-group/index.js");
/* harmony import */ var _redux_templates_stores_dependencyHelper__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ~redux-templates/stores/dependencyHelper */ "./redux-templates/src/stores/dependencyHelper.js");
function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }

function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }

function _iterableToArrayLimit(arr, i) { if (typeof Symbol === "undefined" || !(Symbol.iterator in Object(arr))) return; var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }

function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }

const _wp$element = wp.element,
      useState = _wp$element.useState,
      useEffect = _wp$element.useEffect;
const compose = wp.compose.compose;
const _wp$data = wp.data,
      withDispatch = _wp$data.withDispatch,
      withSelect = _wp$data.withSelect;
const __ = wp.i18n.__;



const DURATION_UNIT = 500;
const PREVIEW_PANEL_HEIGHT = 515; // Collection Detail view: preview, item list and import

function CollectionView(props) {
  const pageData = props.pageData,
        activeCollectionData = props.activeCollectionData;
  const setActiveCollection = props.setActiveCollection;

  const _useState = useState(null),
        _useState2 = _slicedToArray(_useState, 2),
        previewData = _useState2[0],
        setPreviewData = _useState2[1];

  const _useState3 = useState(0),
        _useState4 = _slicedToArray(_useState3, 2),
        previewDataIndex = _useState4[0],
        setPreviewDataIndex = _useState4[1];

  const _useState5 = useState('1.5s'),
        _useState6 = _slicedToArray(_useState5, 2),
        transitionDuration = _useState6[0],
        setTransitionDuration = _useState6[1];

  const dataLength = pageData.length; // To be called when switching over

  useEffect(() => {
    if (pageData && pageData[previewDataIndex]) {
      const itemData = pageData[previewDataIndex];
      const backgroundImage = new Image();

      if (itemData.image_full) {
        setPreviewData(_objectSpread(_objectSpread({}, itemData), {}, {
          backgroundImage: itemData.image_full,
          previewImageClassname: 'details-preview has_full'
        }));
        backgroundImage.src = itemData.image_full;
      } else {
        setPreviewData(_objectSpread(_objectSpread({}, itemData), {}, {
          backgroundImage: itemData.image,
          previewImageClassname: 'details-preview has_full'
        }));
        backgroundImage.src = itemData.image;
      }

      backgroundImage.onload = function () {
        setTransitionDuration((backgroundImage.height - PREVIEW_PANEL_HEIGHT) / DURATION_UNIT + 's');
      };
    }
  }, [pageData, previewDataIndex]);
  if (previewData) return wp.element.createElement("div", {
    className: "redux-templates-collection-details-view"
  }, wp.element.createElement("div", {
    className: "redux-templates-collection-details-left"
  }, wp.element.createElement("div", {
    className: "details-back",
    onClick: () => setActiveCollection(null)
  }, wp.element.createElement("span", {
    className: "dashicons dashicons-arrow-left-alt"
  }), "\xA0", __('Back to Template Kits', redux_templates.i18n)), wp.element.createElement("div", {
    className: previewData.previewImageClassname,
    style: {
      backgroundImage: `url('${previewData.backgroundImage}')`,
      transitionDuration
    }
  })), wp.element.createElement("div", {
    className: "redux-templates-collection-details-right"
  }, wp.element.createElement("div", {
    className: "details-title"
  }, wp.element.createElement("h3", null, activeCollectionData.name), wp.element.createElement("span", null, dataLength, " ", __('pages', redux_templates.i18n))), wp.element.createElement("div", {
    className: "details-list"
  }, wp.element.createElement("div", {
    className: "details-inner"
  }, pageData.map((detail, index) => {
    let className = previewData.ID === detail.ID ? 'detail-select detail-select-active' : 'detail-select';
    let divStyle = {
      backgroundImage: 'url(' + detail.image + ')'
    };
    return wp.element.createElement("div", {
      className: className,
      onClick: () => setPreviewDataIndex(index),
      key: index
    }, wp.element.createElement("div", {
      className: "detail-image",
      style: divStyle
    }, Object(_redux_templates_stores_dependencyHelper__WEBPACK_IMPORTED_MODULE_2__["requiresPro"])(detail) && wp.element.createElement("span", {
      className: "pro"
    }, __('Premium', redux_templates.i18n)), !Object(_redux_templates_stores_dependencyHelper__WEBPACK_IMPORTED_MODULE_2__["requiresPro"])(detail) && Object(_redux_templates_stores_dependencyHelper__WEBPACK_IMPORTED_MODULE_2__["requiresInstall"])(detail) && wp.element.createElement("span", {
      className: "install"
    }, wp.element.createElement("i", {
      className: "fas fa-exclamation-triangle"
    })), wp.element.createElement("div", {
      className: "detail-label"
    }, detail.name)));
  })))), wp.element.createElement("div", {
    className: "redux-templates-collection-details-footer"
  }, wp.element.createElement("div", {
    className: "footer-grid"
  }, wp.element.createElement(_redux_templates_components_button_group__WEBPACK_IMPORTED_MODULE_1__["default"], {
    index: previewDataIndex,
    showDependencyBlock: false,
    data: previewData,
    pageData: pageData
  }))));
  return null;
}

/* harmony default export */ __webpack_exports__["default"] = (compose([withDispatch(dispatch => {
  const _dispatch = dispatch('redux-templates/sectionslist'),
        setActiveCollection = _dispatch.setActiveCollection;

  return {
    setActiveCollection
  };
}), withSelect((select, props) => {
  const _select = select('redux-templates/sectionslist'),
        getPageData = _select.getPageData,
        getLoading = _select.getLoading,
        getActiveCollectionData = _select.getActiveCollectionData,
        getActiveItemType = _select.getActiveItemType;

  return {
    pageData: getPageData(),
    loading: getLoading(),
    activeItemType: getActiveItemType(),
    activeCollectionData: getActiveCollectionData()
  };
})])(CollectionView));

/***/ }),

/***/ "./redux-templates/src/modal-library/view-collection/style.scss":
/*!**********************************************************************!*\
  !*** ./redux-templates/src/modal-library/view-collection/style.scss ***!
  \**********************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../node_modules/css-loader/dist/cjs.js!../../../../node_modules/sass-loader/dist/cjs.js!./style.scss */ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/modal-library/view-collection/style.scss");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./redux-templates/src/modal-library/view-saved/index.js":
/*!***************************************************************!*\
  !*** ./redux-templates/src/modal-library/view-saved/index.js ***!
  \***************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./style.scss */ "./redux-templates/src/modal-library/view-saved/style.scss");
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_style_scss__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _modal_manager__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../modal-manager */ "./redux-templates/src/modal-manager/index.js");
/* harmony import */ var lodash_reject__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! lodash/reject */ "./node_modules/lodash/reject.js");
/* harmony import */ var lodash_reject__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(lodash_reject__WEBPACK_IMPORTED_MODULE_3__);
function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }

function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }

function _iterableToArrayLimit(arr, i) { if (typeof Symbol === "undefined" || !(Symbol.iterator in Object(arr))) return; var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }

function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }

const _wp = wp,
      apiFetch = _wp.apiFetch;
const useState = wp.element.useState;
const compose = wp.compose.compose;
const withDispatch = wp.data.withDispatch;
const Spinner = wp.components.Spinner;
const parse = wp.blocks.parse;
const __ = wp.i18n.__;





function SavedView(props) {
  const insertBlocks = props.insertBlocks,
        discardAllErrorMessages = props.discardAllErrorMessages,
        appendErrorMessage = props.appendErrorMessage,
        clearSearch = props.clearSearch;

  const _useState = useState([]),
        _useState2 = _slicedToArray(_useState, 2),
        savedSections = _useState2[0],
        setSavedSections = _useState2[1];

  const _useState3 = useState(false),
        _useState4 = _slicedToArray(_useState3, 2),
        dataLoaded = _useState4[0],
        setDataLoaded = _useState4[1];

  if (dataLoaded === false) {
    // Initial fetch
    apiFetch({
      path: 'redux/v1/templates/get_saved_blocks'
    }).then(response => {
      if (response.success) {
        setSavedSections(response.data);
      } else {
        appendErrorMessage(response.data.error);
      }

      setDataLoaded(true);
    }).catch(error => {
      appendErrorMessage(error.code + ' : ' + error.message);
      setDataLoaded(true);
    });
  } // To display into columns, map data into column-friendly data


  const mapToColumnData = (data, n = 4, balanced = true) => {
    let out = [],
        i;

    for (i = 0; i < n; i++) out[i] = [];

    data.forEach((section, i) => {
      out[i % n].push(section);
    });
    return out;
  }; // saved block import is special


  const importSections = rawData => {
    let pageData = parse(rawData);
    insertBlocks(pageData);
    _modal_manager__WEBPACK_IMPORTED_MODULE_2__["ModalManager"].close(); //close modal
  };

  const deleteSavedSection = (event, sectionID) => {
    event.stopPropagation();
    discardAllErrorMessages();
    const options = {
      method: 'POST',
      path: 'redux/v1/templates/delete_saved_block/?block_id=' + sectionID
    };
    apiFetch(options).then(response => {
      if (response.success) {
        // on successful remove, we will update the blocks as well.
        setSavedSections(lodash_reject__WEBPACK_IMPORTED_MODULE_3___default()(savedSections, {
          'ID': sectionID
        }));
      } else {
        appendErrorMessage(response.data.error);
      }
    }).catch(error => {
      appendErrorMessage(error.code + ' : ' + error.message);
    });
  };

  if (dataLoaded === true) return wp.element.createElement("div", {
    className: "redux-templates-two-sections__grid"
  }, savedSections && savedSections.length > 0 ? mapToColumnData(savedSections).map((column, key) => {
    let sections = column.map((section, i) => {
      let blocks = parse(section.post_content);
      return wp.element.createElement("div", {
        className: "redux-templates-two-section",
        key: i,
        onClick: () => importSections(section.post_content)
      }, wp.element.createElement("div", {
        className: "preview-image-wrapper"
      }, wp.element.createElement(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_0__["BlockPreview"], {
        blocks: blocks
      })), wp.element.createElement("div", {
        className: "saved-section-title"
      }, section.post_title), wp.element.createElement("div", {
        className: "redux-templates-two-section-remove",
        onClick: e => deleteSavedSection(e, section.ID)
      }, wp.element.createElement("i", {
        className: "fas fa-trash"
      })));
    });
    return wp.element.createElement("div", {
      className: "redux-templates-two-sections__grid__column",
      key: key,
      style: {
        width: '25%',
        flexBasis: '25%'
      }
    }, sections);
  }) : wp.element.createElement("div", {
    className: "no-section"
  }, "Nothing here yet, make a reusuable block first."));else return wp.element.createElement("div", null, wp.element.createElement("div", {
    style: {
      height: '600px'
    }
  }, wp.element.createElement("div", {
    className: "redux-templates-modal-loader"
  }, wp.element.createElement(Spinner, null))));
}

/* harmony default export */ __webpack_exports__["default"] = (compose([withDispatch(dispatch => {
  const _dispatch = dispatch('core/block-editor'),
        insertBlocks = _dispatch.insertBlocks;

  const _dispatch2 = dispatch('redux-templates/sectionslist'),
        appendErrorMessage = _dispatch2.appendErrorMessage,
        discardAllErrorMessages = _dispatch2.discardAllErrorMessages;

  return {
    insertBlocks,
    appendErrorMessage,
    discardAllErrorMessages
  };
})])(SavedView));

/***/ }),

/***/ "./redux-templates/src/modal-library/view-saved/style.scss":
/*!*****************************************************************!*\
  !*** ./redux-templates/src/modal-library/view-saved/style.scss ***!
  \*****************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../node_modules/css-loader/dist/cjs.js!../../../../node_modules/sass-loader/dist/cjs.js!./style.scss */ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/modal-library/view-saved/style.scss");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./redux-templates/src/modal-library/view-template-list/index.js":
/*!***********************************************************************!*\
  !*** ./redux-templates/src/modal-library/view-template-list/index.js ***!
  \***********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _components_single_item__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../components/single-item */ "./redux-templates/src/components/single-item/index.js");
/* harmony import */ var _components_multiple_item__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../components/multiple-item */ "./redux-templates/src/components/multiple-item/index.js");
/* harmony import */ var _components_pagination__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../components/pagination */ "./redux-templates/src/components/pagination/index.js");
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./style.scss */ "./redux-templates/src/modal-library/view-template-list/style.scss");
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_style_scss__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _stores_helper__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../../stores/helper */ "./redux-templates/src/stores/helper.js");
function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }

function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }

function _iterableToArrayLimit(arr, i) { if (typeof Symbol === "undefined" || !(Symbol.iterator in Object(arr))) return; var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }

function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }

const _wp$element = wp.element,
      useState = _wp$element.useState,
      useEffect = _wp$element.useEffect;
const compose = wp.compose.compose;
const _wp$data = wp.data,
      withDispatch = _wp$data.withDispatch,
      withSelect = _wp$data.withSelect;
const Spinner = wp.components.Spinner;






function TemplateList(props) {
  const pageData = props.pageData,
        loading = props.loading,
        activeItemType = props.activeItemType,
        activeCollection = props.activeCollection,
        columns = props.columns,
        currentPage = props.currentPage;
  const setActiveCollection = props.setActiveCollection;

  const _useState = useState([]),
        _useState2 = _slicedToArray(_useState, 2),
        columnizedData = _useState2[0],
        setColumnizedData = _useState2[1];

  const _useState3 = useState(false),
        _useState4 = _slicedToArray(_useState3, 2),
        shouldShowPagination = _useState4[0],
        setShouldShowPagination = _useState4[1];

  const getBackgroundImage = url => {
    if (!url) {
      return redux_templates.plugin + 'assets/img/redux-templates-medium.jpg';
    }

    return url;
  };

  const onSelectCollection = collectionID => {
    setActiveCollection(collectionID);
  };

  useEffect(() => {
    let newData = [],
        index = 0;
    let colStr = columns === '' ? 'medium' : columns;
    const columnsCount = _stores_helper__WEBPACK_IMPORTED_MODULE_4__["columnMap"][colStr];
    const pageSize = _stores_helper__WEBPACK_IMPORTED_MODULE_4__["pageSizeMap"][colStr];

    for (let i = 0; i < columnsCount; i++) newData[i] = [];

    if (pageData) {
      const lowerLimit = activeItemType !== 'collection' ? currentPage * pageSize + 1 : 1;
      const upperLimit = activeItemType !== 'collection' ? (currentPage + 1) * pageSize : pageData.length;

      for (index = lowerLimit; index <= upperLimit && index <= pageData.length; index++) {
        newData[(index - 1) % columnsCount].push(_objectSpread(_objectSpread({}, pageData[index - 1]), {}, {
          index: index - 1
        }));
      }
    }

    setColumnizedData(newData);
    setShouldShowPagination(activeItemType !== 'collection' && pageData && pageSize < pageData.length);
  }, [columns, pageData]);
  if (!loading) return wp.element.createElement("div", {
    id: "modalContainer",
    className: "redux-templates-template-list-modal"
  }, wp.element.createElement("div", {
    className: "redux-templates-builder-template-list-container"
  }, wp.element.createElement("div", {
    id: "collections-sections-list",
    className: `redux-templates-builder-page-templates ${columns}`
  }, columnizedData && columnizedData.map((columnData, colIndex) => wp.element.createElement("div", {
    className: "redux-templates-pagelist-column",
    key: colIndex
  }, columnData && columnData.map((data, cellIndex) => activeItemType !== 'collection' || activeCollection !== null ? wp.element.createElement(_components_single_item__WEBPACK_IMPORTED_MODULE_0__["default"], {
    key: cellIndex,
    index: data.index
  }) : wp.element.createElement(_components_multiple_item__WEBPACK_IMPORTED_MODULE_1__["default"], {
    key: cellIndex,
    data: data,
    index: data.index,
    itemType: activeItemType,
    spinner: false,
    onSelectCollection: onSelectCollection
  }))))), shouldShowPagination && wp.element.createElement(_components_pagination__WEBPACK_IMPORTED_MODULE_2__["default"], null)));
  return wp.element.createElement("div", null, wp.element.createElement("div", {
    style: {
      height: '600px'
    }
  }, wp.element.createElement("div", {
    className: "redux-templates-modal-loader"
  }, wp.element.createElement(Spinner, null))));
}

/* harmony default export */ __webpack_exports__["default"] = (compose([withDispatch(dispatch => {
  const _dispatch = dispatch('redux-templates/sectionslist'),
        setActiveCollection = _dispatch.setActiveCollection;

  return {
    setActiveCollection
  };
}), withSelect((select, props) => {
  const _select = select('redux-templates/sectionslist'),
        getPageData = _select.getPageData,
        getLoading = _select.getLoading,
        getColumns = _select.getColumns,
        getActiveItemType = _select.getActiveItemType,
        getActiveCollection = _select.getActiveCollection,
        getCurrentPage = _select.getCurrentPage;

  return {
    pageData: getPageData(),
    loading: getLoading(),
    activeItemType: getActiveItemType(),
    columns: getColumns(),
    activeCollection: getActiveCollection(),
    currentPage: getCurrentPage()
  };
})])(TemplateList));

/***/ }),

/***/ "./redux-templates/src/modal-library/view-template-list/style.scss":
/*!*************************************************************************!*\
  !*** ./redux-templates/src/modal-library/view-template-list/style.scss ***!
  \*************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../node_modules/css-loader/dist/cjs.js!../../../../node_modules/sass-loader/dist/cjs.js!./style.scss */ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/modal-library/view-template-list/style.scss");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./redux-templates/src/modal-manager/index.js":
/*!****************************************************!*\
  !*** ./redux-templates/src/modal-manager/index.js ***!
  \****************************************************/
/*! exports provided: Modal, ModalManager */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "Modal", function() { return Modal; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "ModalManager", function() { return ModalManager; });
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);


var onClose,
    node,
    customizerNode,
    feedbackNode = null;
class Modal extends _wordpress_element__WEBPACK_IMPORTED_MODULE_1__["Component"] {
  constructor(props) {
    super(props);
    this.state = {
      afterOpen: false,
      beforeClose: false
    };
  }

  close() {
    if (!this.props.onRequestClose || this.props.onRequestClose()) {
      if (customizerNode) ModalManager.closeCustomizer();else ModalManager.close();
    }
  }

  componentDidMount() {
    const _this$props = this.props,
          openTimeoutMS = _this$props.openTimeoutMS,
          closeTimeoutMS = _this$props.closeTimeoutMS;
    setTimeout(() => this.setState({
      afterOpen: true
    }), openTimeoutMS ? openTimeoutMS : 150);

    onClose = callback => {
      this.setState({
        beforeClose: true
      }, () => {
        this.closeTimer = setTimeout(callback, closeTimeoutMS ? closeTimeoutMS : 150);
      });
    };
  }

  componentWillUnmount() {
    onClose = null;
    clearTimeout(this.closeTimer);
  }

  render() {
    return wp.element.createElement(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__["Fragment"], null, wp.element.createElement("span", {
      onClick: e => {
        this.close();
      },
      className: 'redux-templates-pagelist-modal-overlay'
    }, "\xA0"), wp.element.createElement("div", {
      className: this.props.compactMode ? 'redux-templates-modal-inner' : 'redux-templates-pagelist-modal-inner',
      onClick: e => e.stopPropagation()
    }, this.props.children));
  }

}
const ModalManager = {
  open(component) {
    if (onClose) {
      this.close(); // throw __('There is already one modal.It must be closed before one new modal will be opened');
    }

    if (!node) {
      node = document.createElement('div');
      node.className = 'redux-templates-builder-modal';
      document.body.appendChild(node);
    }

    wp.element.render(component, node);
    document.body.classList.add('redux-templates-builder-modal-open');
  },

  close() {
    onClose && onClose(() => {
      wp.element.unmountComponentAtNode(node);
      document.body.classList.remove('redux-templates-builder-modal-open');
    });
  },

  openCustomizer(component) {
    if (!customizerNode) {
      customizerNode = document.createElement('div');
      document.body.appendChild(customizerNode);
    }

    wp.element.render(component, customizerNode);
  },

  closeCustomizer() {
    if (customizerNode) {
      wp.element.unmountComponentAtNode(customizerNode);
      customizerNode = false;
    }
  },

  openFeedback(component) {
    feedbackNode = document.getElementsByClassName('feedback-wrapper');

    if (!feedbackNode || feedbackNode.length < 1) {
      feedbackNode = document.createElement('div');
      feedbackNode.className = 'feedback-wrapper';
      document.body.appendChild(feedbackNode);
    } else {
      feedbackNode = feedbackNode[0];
    }

    wp.element.render(component, feedbackNode);
  },

  closeFeedback() {
    if (feedbackNode) {
      wp.element.unmountComponentAtNode(feedbackNode);
      feedbackNode = false;
    }
  },

  isCustomizerOpened() {
    return customizerNode ? true : false;
  },

  hide() {
    document.body.classList.remove('redux-templates-builder-modal-open');
    node.classList.add('hidden');
  },

  show() {
    document.body.classList.add('redux-templates-builder-modal-open');
    if (node) node.classList.remove('hidden');
  }

};

/***/ }),

/***/ "./redux-templates/src/modal-preview/FullyOverlayFooter.js":
/*!*****************************************************************!*\
  !*** ./redux-templates/src/modal-preview/FullyOverlayFooter.js ***!
  \*****************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
const __ = wp.i18n.__;

function FullyOverlayFooter(props) {
  const previewClass = props.previewClass,
        expandedClass = props.expandedClass,
        pro = props.pro;
  const onChangePreviewClass = props.onChangePreviewClass,
        onToggleExpanded = props.onToggleExpanded,
        onImport = props.onImport;
  const previewClassesList = [{
    className: 'preview-desktop',
    screenReaderText: 'Enter desktop preview mode'
  }, {
    className: 'preview-tablet',
    screenReaderText: 'Enter tablet preview mode'
  }, {
    className: 'preview-mobile',
    screenReaderText: 'Enter mobile preview mode'
  }];

  const toggleExpanded = () => {
    let nextStatus = expandedClass === 'collapsed' ? 'expanded' : 'collapsed';
    onToggleExpanded(nextStatus);
  };

  return wp.element.createElement("div", {
    className: "wp-full-overlay-footer"
  }, wp.element.createElement("div", {
    className: "footer-import-button-wrap redux-templates-import-button-group"
  }, pro ? wp.element.createElement("div", {
    className: "action-buttons"
  }, wp.element.createElement("a", {
    className: "redux-templates-button-download",
    target: "_blank",
    href: "http://redux-templates.io/"
  }, wp.element.createElement("i", {
    className: "fas fa-upload"
  }), "\xA0", __('Upgrade to Pro', redux_templates.i18n))) : wp.element.createElement("a", {
    className: "button button-hero hide-if-no-customize button-primary redux-templates-import",
    onClick: onImport
  }, wp.element.createElement("i", {
    className: "fas fa-download"
  }), "\xA0", __('Import', redux_templates.i18n))), wp.element.createElement("button", {
    type: "button",
    className: "collapse-sidebar button",
    onClick: toggleExpanded,
    "aria-expanded": "true",
    "aria-label": "Collapse Sidebar"
  }, wp.element.createElement("span", {
    className: "collapse-sidebar-arrow"
  }), wp.element.createElement("span", {
    className: "collapse-sidebar-label"
  }, __('Collapse', redux_templates.i18n))), wp.element.createElement("div", {
    className: "devices-wrapper"
  }, wp.element.createElement("div", {
    className: "devices"
  }, previewClassesList.map((previewObject, i) => {
    return wp.element.createElement("button", {
      type: "button",
      className: previewClass === previewObject.className ? previewObject.className + ' active' : previewObject.className,
      "aria-pressed": "true",
      key: i,
      onClick: () => onChangePreviewClass(previewObject.className)
    }, wp.element.createElement("span", {
      className: "screen-reader-text"
    }, previewObject.screenReaderText));
  }))));
}

/* harmony default export */ __webpack_exports__["default"] = (FullyOverlayFooter);

/***/ }),

/***/ "./redux-templates/src/modal-preview/FullyOverlayHeader.js":
/*!*****************************************************************!*\
  !*** ./redux-templates/src/modal-preview/FullyOverlayHeader.js ***!
  \*****************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
const __ = wp.i18n.__;

function FullyOverlayHeader(props) {
  const onCloseCustomizer = props.onCloseCustomizer,
        onNextBlock = props.onNextBlock,
        onPrevBlock = props.onPrevBlock,
        onImport = props.onImport,
        pro = props.pro;
  return wp.element.createElement("div", {
    className: "wp-full-overlay-header"
  }, wp.element.createElement("button", {
    className: "close-full-overlay",
    onClick: onCloseCustomizer
  }, wp.element.createElement("span", {
    className: "screen-reader-text"
  }, __('Close', redux_templates.i18n))), wp.element.createElement("button", {
    className: "previous-theme",
    onClick: onPrevBlock
  }, wp.element.createElement("span", {
    className: "screen-reader-text"
  }, __('Previous', redux_templates.i18n))), wp.element.createElement("button", {
    className: "next-theme",
    onClick: onNextBlock
  }, wp.element.createElement("span", {
    className: "screen-reader-text"
  }, __('Next', redux_templates.i18n))), pro === false && wp.element.createElement("a", {
    className: "button hide-if-no-customize button-primary redux-templates-section-import",
    onClick: onImport,
    "data-import": "disabled"
  }, __('Import', redux_templates.i18n)));
}

/* harmony default export */ __webpack_exports__["default"] = (FullyOverlayHeader);

/***/ }),

/***/ "./redux-templates/src/modal-preview/SidebarContent.js":
/*!*************************************************************!*\
  !*** ./redux-templates/src/modal-preview/SidebarContent.js ***!
  \*************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return SidebarContent; });
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_icons__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/icons */ "./node_modules/@wordpress/icons/build-module/index.js");
/* harmony import */ var _redux_templates_icons__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ~redux-templates/icons */ "./redux-templates/src/icons/index.js");
/* harmony import */ var clipboard_copy__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! clipboard-copy */ "./node_modules/clipboard-copy/index.js");
/* harmony import */ var clipboard_copy__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(clipboard_copy__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _redux_templates_components_safe_image_load__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ~redux-templates/components/safe-image-load */ "./redux-templates/src/components/safe-image-load/index.js");
/* harmony import */ var _redux_templates_stores_dependencyHelper__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ~redux-templates/stores/dependencyHelper */ "./redux-templates/src/stores/dependencyHelper.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_6__);
function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }

function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }

function _iterableToArrayLimit(arr, i) { if (typeof Symbol === "undefined" || !(Symbol.iterator in Object(arr))) return; var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }

function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }



const _wp$element = wp.element,
      useState = _wp$element.useState,
      useEffect = _wp$element.useEffect;
const __ = wp.i18n.__;





function SidebarContent(props) {
  const itemData = props.itemData,
        pro = props.pro;
  const hash = itemData.hash,
        name = itemData.name,
        image = itemData.image,
        blocks = itemData.blocks,
        proDependencies = itemData.proDependencies,
        installDependencies = itemData.installDependencies,
        url = itemData.url,
        source = itemData.source;

  const _useState = useState(false),
        _useState2 = _slicedToArray(_useState, 2),
        copied = _useState2[0],
        setCopied = _useState2[1];

  const copyHash = () => {
    clipboard_copy__WEBPACK_IMPORTED_MODULE_3___default()(hash.substring(0, 7));
    setCopied(true);
    setTimeout(function () {
      setCopied(false);
    }, 3500);
  };

  useEffect(() => {
    setCopied(false);
  }, [itemData]);

  if ('redux' === source) {
    const source_instance = redux_templates.supported_plugins['redux-framework'];
  } else {
    const source_instance = redux_templates.supported_plugins[source];
  }

  return wp.element.createElement("div", {
    className: "wp-full-overlay-sidebar-content"
  }, wp.element.createElement("div", {
    className: "install-theme-info"
  }, wp.element.createElement("h3", {
    className: "theme-name"
  }, name, " ", url && wp.element.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__["Tooltip"], {
    position: 'top right',
    text: __('Full Preview', redux_templates.i18n)
  }, wp.element.createElement("a", {
    href: url,
    target: "_blank"
  }, wp.element.createElement("i", {
    className: "fas fa-external-link-alt"
  })))), wp.element.createElement("div", {
    className: "theme-screenshot-wrap"
  }, wp.element.createElement(_redux_templates_components_safe_image_load__WEBPACK_IMPORTED_MODULE_4__["default"], {
    url: image,
    className: "theme-screenshot"
  }), pro ? wp.element.createElement("span", {
    className: "redux-templates-pro-badge"
  }, __('Premium', redux_templates.i18n)) : ''), wp.element.createElement("h5", {
    className: "theme-hash"
  }, wp.element.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__["Tooltip"], {
    position: 'top center',
    text: __('Copy the template identifier', redux_templates.i18n)
  }, wp.element.createElement("div", {
    className: "button-container",
    onClick: copyHash
  }, wp.element.createElement("span", {
    className: "button button-secondary the-copy"
  }, wp.element.createElement("i", {
    className: "fa fa-copy",
    "aria-hidden": "true"
  })), wp.element.createElement("span", {
    className: "button button-secondary the-hash"
  }, hash.substring(0, 7)), copied && wp.element.createElement("span", {
    className: "copied hideMe"
  }, wp.element.createElement("br", null), __('copied', redux_templates.i18n)))))), installDependencies && installDependencies.length > 0 && wp.element.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__["PanelBody"], {
    title: __('Required Plugins', redux_templates.i18n),
    icon: _wordpress_icons__WEBPACK_IMPORTED_MODULE_1__["more"],
    initialOpen: true
  }, wp.element.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__["PanelRow"], {
    className: "requirements-list-div"
  }, wp.element.createElement("div", {
    className: "requirements-list"
  }, wp.element.createElement("ul", null, installDependencies.map(pluginKey => {
    const pluginInstance = redux_templates.supported_plugins[pluginKey];

    if (!pluginInstance) {
      console.log('Missing plugin details for ' + pluginKey);
      return null;
    }

    const plugin_name = pluginKey.replace('-pro', '').replace('-premium', '').replace(/\W/g, '').toLowerCase();

    if ('redux' === plugin_name) {
      return;
    }

    const IconComponent = _redux_templates_icons__WEBPACK_IMPORTED_MODULE_2__[plugin_name];
    return wp.element.createElement("li", {
      key: pluginKey
    }, IconComponent && wp.element.createElement(IconComponent, null), wp.element.createElement("span", {
      className: "redux-templates-dependency-name"
    }, pluginInstance.name), Object(_redux_templates_stores_dependencyHelper__WEBPACK_IMPORTED_MODULE_5__["requiresInstall"])(itemData) && wp.element.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__["Tooltip"], {
      position: 'bottom center',
      text: __('Not Installed', redux_templates.i18n)
    }, wp.element.createElement("div", {
      className: "redux-icon-wrapper"
    }, wp.element.createElement("i", {
      className: "fa fa-exclamation-triangle"
    }))), pluginInstance.url ? wp.element.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__["Tooltip"], {
      position: 'top right',
      text: __('Visit Plugin Website', redux_templates.i18n)
    }, wp.element.createElement("span", {
      className: "pluginURL"
    }, wp.element.createElement("a", {
      href: pluginInstance.url,
      target: "_blank"
    }, wp.element.createElement("i", {
      className: "fas fa-external-link-alt"
    })))) : null);
  }))))), blocks && blocks.length > 0 && wp.element.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__["PanelBody"], {
    title: __('Blocks Used', redux_templates.i18n),
    icon: _wordpress_icons__WEBPACK_IMPORTED_MODULE_1__["more"],
    initialOpen: true
  }, wp.element.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__["PanelRow"], {
    className: "redux-block-pills"
  }, wp.element.createElement("ul", null, blocks.map((block, i) => {
    return wp.element.createElement("li", {
      key: i
    }, wp.element.createElement("span", null, block));
  })))), 'redux' !== source && wp.element.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__["PanelBody"], {
    title: __('Template Details', redux_templates.i18n),
    icon: _wordpress_icons__WEBPACK_IMPORTED_MODULE_1__["more"],
    initialOpen: false
  }, wp.element.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__["PanelRow"], {
    className: "redux-block-pills"
  }, wp.element.createElement("ul", null, 'redux' !== source && wp.element.createElement("li", null, wp.element.createElement("strong", null, "Author"), ": ", source.slice(0, 1).toUpperCase() + source.slice(1, source.length))))));
}

/***/ }),

/***/ "./redux-templates/src/modal-preview/SitePreviewSidebar.js":
/*!*****************************************************************!*\
  !*** ./redux-templates/src/modal-preview/SitePreviewSidebar.js ***!
  \*****************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _FullyOverlayHeader__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./FullyOverlayHeader */ "./redux-templates/src/modal-preview/FullyOverlayHeader.js");
/* harmony import */ var _SidebarContent__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./SidebarContent */ "./redux-templates/src/modal-preview/SidebarContent.js");
/* harmony import */ var _FullyOverlayFooter__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./FullyOverlayFooter */ "./redux-templates/src/modal-preview/FullyOverlayFooter.js");
/* harmony import */ var _stores_helper__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../stores/helper */ "./redux-templates/src/stores/helper.js");





function SitePreviewSidebar(props) {
  const itemData = props.itemData,
        previewClass = props.previewClass,
        expandedClass = props.expandedClass,
        onImport = props.onImport;
  const onCloseCustomizer = props.onCloseCustomizer,
        onChangePreviewClass = props.onChangePreviewClass,
        onToggleExpanded = props.onToggleExpanded,
        onNextBlock = props.onNextBlock,
        onPrevBlock = props.onPrevBlock;
  const isPro = Object(_stores_helper__WEBPACK_IMPORTED_MODULE_3__["isBlockPro"])(itemData.pro, itemData.source);
  return wp.element.createElement("div", {
    className: "wp-full-overlay-sidebar"
  }, wp.element.createElement(_FullyOverlayHeader__WEBPACK_IMPORTED_MODULE_0__["default"], {
    onCloseCustomizer: onCloseCustomizer,
    onNextBlock: onNextBlock,
    onPrevBlock: onPrevBlock,
    pro: isPro,
    onImport: onImport
  }), wp.element.createElement(_SidebarContent__WEBPACK_IMPORTED_MODULE_1__["default"], {
    itemData: itemData,
    pro: isPro
  }), wp.element.createElement(_FullyOverlayFooter__WEBPACK_IMPORTED_MODULE_2__["default"], {
    previewClass: previewClass,
    expandedClass: expandedClass,
    pro: isPro,
    onChangePreviewClass: onChangePreviewClass,
    onToggleExpanded: onToggleExpanded,
    onImport: onImport
  }));
}

/* harmony default export */ __webpack_exports__["default"] = (SitePreviewSidebar);

/***/ }),

/***/ "./redux-templates/src/modal-preview/index.js":
/*!****************************************************!*\
  !*** ./redux-templates/src/modal-preview/index.js ***!
  \****************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _SitePreviewSidebar__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./SitePreviewSidebar */ "./redux-templates/src/modal-preview/SitePreviewSidebar.js");
/* harmony import */ var _modal_manager__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../modal-manager */ "./redux-templates/src/modal-manager/index.js");
/* harmony import */ var _modal_import_wizard__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../modal-import-wizard */ "./redux-templates/src/modal-import-wizard/index.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _redux_templates_components_safe_image_load__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ~redux-templates/components/safe-image-load */ "./redux-templates/src/components/safe-image-load/index.js");
/* harmony import */ var _redux_templates_stores_actionHelper__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ~redux-templates/stores/actionHelper */ "./redux-templates/src/stores/actionHelper.js");
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./style.scss */ "./redux-templates/src/modal-preview/style.scss");
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_style_scss__WEBPACK_IMPORTED_MODULE_6__);
function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }

function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }

function _iterableToArrayLimit(arr, i) { if (typeof Symbol === "undefined" || !(Symbol.iterator in Object(arr))) return; var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }

function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }

const compose = wp.compose.compose;
const _wp$data = wp.data,
      withDispatch = _wp$data.withDispatch,
      withSelect = _wp$data.withSelect;
const _wp$element = wp.element,
      useState = _wp$element.useState,
      useEffect = _wp$element.useEffect,
      useReducer = _wp$element.useReducer;
const Spinner = wp.components.Spinner;







const initialState = {
  currentPageData: null,
  currentIndex: 0,
  itemData: null,
  imageURL: ''
};
const LOADING_RESET = 0;
const IN_PROGRESS = 1;
const FULLY_LOADED = 2;

const previewReducer = (state, action) => {
  let currentPageData;
  let imageURL;

  switch (action.type) {
    case 'INDEX':
      currentPageData = state.currentPageData;
      break;

    case 'DATA':
      currentPageData = action.currentPageData;
      break;
  }

  const itemData = currentPageData[action.currentIndex];
  if (itemData.image_full) imageURL = itemData.image_full;else imageURL = itemData.image;
  return {
    currentPageData,
    currentIndex: action.currentIndex,
    imageURL,
    itemData
  };
};

function PreviewModal(props) {
  const startIndex = props.startIndex,
        currentPageData = props.currentPageData;
  const setImportingTemplate = props.setImportingTemplate,
        importingTemplate = props.importingTemplate;

  const _useReducer = useReducer(previewReducer, initialState),
        _useReducer2 = _slicedToArray(_useReducer, 2),
        state = _useReducer2[0],
        dispatch = _useReducer2[1];

  const _useState = useState('preview-desktop'),
        _useState2 = _slicedToArray(_useState, 2),
        previewClass = _useState2[0],
        setPreviewClass = _useState2[1];

  const _useState3 = useState('expanded'),
        _useState4 = _slicedToArray(_useState3, 2),
        expandedClass = _useState4[0],
        toggleExpanded = _useState4[1];

  const _useState5 = useState(null),
        _useState6 = _slicedToArray(_useState5, 2),
        pressedKey = _useState6[0],
        setPressedKey = _useState6[1];

  const _useState7 = useState(IN_PROGRESS),
        _useState8 = _slicedToArray(_useState7, 2),
        loading = _useState8[0],
        setLoading = _useState8[1];

  const _useState9 = useState('wp-full-overlay sites-preview theme-install-overlay '),
        _useState10 = _slicedToArray(_useState9, 2),
        wrapperClassName = _useState10[0],
        setWrapperClassName = _useState10[1]; // Key event handling : event listener set up


  useEffect(() => {
    const handleKeyDown = ({
      keyCode
    }) => {
      setPressedKey(keyCode);
    };

    window.addEventListener('keydown', handleKeyDown);
    return () => {
      window.removeEventListener('keydown', handleKeyDown);
    };
  }, []); // Key Event handling

  useEffect(() => {
    if (pressedKey !== null) {
      if (pressedKey === 37) onPrevBlock();
      if (pressedKey === 39) onNextBlock();
      setPressedKey(null);
    }
  }, [pressedKey]);
  useEffect(() => {
    if (isNaN(startIndex) === false && currentPageData) dispatch({
      type: 'DATA',
      currentIndex: startIndex,
      currentPageData
    });
  }, [startIndex, currentPageData]); // mobile/desktop preview status and sidebar collapse/expand

  useEffect(() => {
    setWrapperClassName(['wp-full-overlay sites-preview theme-install-overlay ', previewClass, expandedClass].join(' '));
  }, [previewClass, expandedClass]);

  const onCloseCustomizer = () => {
    _modal_manager__WEBPACK_IMPORTED_MODULE_1__["ModalManager"].closeCustomizer();
  };

  const onNextBlock = () => {
    if (state.currentIndex < currentPageData.length - 1) {
      startLoading();
      dispatch({
        type: 'INDEX',
        currentIndex: state.currentIndex + 1
      });
    }
  };

  const onPrevBlock = () => {
    if (state.currentIndex > 0) {
      setLoading();
      dispatch({
        type: 'INDEX',
        currentIndex: state.currentIndex - 1
      });
    }
  };

  const startLoading = () => {
    setLoading(LOADING_RESET);
    setTimeout(() => {
      setLoading(IN_PROGRESS);
    }, 100);
  };

  const importStarterBlock = () => {
    setImportingTemplate(state.itemData);
    _modal_manager__WEBPACK_IMPORTED_MODULE_1__["ModalManager"].closeCustomizer();
  };

  const processImport = () => {
    if (importingTemplate) Object(_redux_templates_stores_actionHelper__WEBPACK_IMPORTED_MODULE_5__["processImportHelper"])();
  }; // Called from iframe upon successful loading


  const hideSpinner = () => {
    setLoading(FULLY_LOADED);
  };

  if (!state || !state.itemData) return null;
  return wp.element.createElement(react__WEBPACK_IMPORTED_MODULE_3__["Fragment"], null, wp.element.createElement("div", {
    className: wrapperClassName,
    style: {
      display: 'block'
    }
  }, wp.element.createElement(_SitePreviewSidebar__WEBPACK_IMPORTED_MODULE_0__["default"], {
    itemData: state.itemData,
    previewClass: previewClass,
    expandedClass: expandedClass,
    onNextBlock: onNextBlock,
    onPrevBlock: onPrevBlock,
    onCloseCustomizer: onCloseCustomizer,
    onToggleExpanded: e => toggleExpanded(e),
    onImport: importStarterBlock,
    onChangePreviewClass: e => setPreviewClass(e)
  }), wp.element.createElement("div", {
    className: "wp-full-overlay-main loaded"
  }, loading < FULLY_LOADED && wp.element.createElement(Spinner, null), state.itemData.url && wp.element.createElement("iframe", {
    src: loading === LOADING_RESET ? '' : state.itemData.url + '?preview=1',
    target: "Preview",
    onLoad: hideSpinner
  }), !state.itemData.url && wp.element.createElement("div", {
    className: "redux-templates-modal-preview-box"
  }, wp.element.createElement(_redux_templates_components_safe_image_load__WEBPACK_IMPORTED_MODULE_4__["default"], {
    url: state.imageURL
  })))), importingTemplate && wp.element.createElement(_modal_import_wizard__WEBPACK_IMPORTED_MODULE_2__["default"], {
    startImportTemplate: processImport
  }));
}

/* harmony default export */ __webpack_exports__["default"] = (compose([withDispatch(dispatch => {
  const _dispatch = dispatch('redux-templates/sectionslist'),
        setImportingTemplate = _dispatch.setImportingTemplate,
        setCustomizerOpened = _dispatch.setCustomizerOpened;

  return {
    setImportingTemplate,
    setCustomizerOpened
  };
}), withSelect((select, props) => {
  const _select = select('redux-templates/sectionslist'),
        getImportingTemplate = _select.getImportingTemplate;

  return {
    importingTemplate: getImportingTemplate()
  };
})])(PreviewModal));

/***/ }),

/***/ "./redux-templates/src/modal-preview/style.scss":
/*!******************************************************!*\
  !*** ./redux-templates/src/modal-preview/style.scss ***!
  \******************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../node_modules/css-loader/dist/cjs.js!../../../node_modules/sass-loader/dist/cjs.js!./style.scss */ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/modal-preview/style.scss");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./redux-templates/src/modal-promotor-score/index.js":
/*!***********************************************************!*\
  !*** ./redux-templates/src/modal-promotor-score/index.js ***!
  \***********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return PromotorScoreModal; });
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/compose */ "@wordpress/compose");
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_compose__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _modals_scss__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../modals.scss */ "./redux-templates/src/modals.scss");
/* harmony import */ var _modals_scss__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_modals_scss__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./style.scss */ "./redux-templates/src/modal-promotor-score/style.scss");
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_style_scss__WEBPACK_IMPORTED_MODULE_3__);
function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }

function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }

function _iterableToArrayLimit(arr, i) { if (typeof Symbol === "undefined" || !(Symbol.iterator in Object(arr))) return; var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }

function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }

const __ = wp.i18n.__;
const _wp = wp,
      apiFetch = _wp.apiFetch;
const dispatch = wp.data.dispatch;
const useState = wp.element.useState;

const _dispatch = dispatch('core/notices'),
      createSuccessNotice = _dispatch.createSuccessNotice,
      createErrorNotice = _dispatch.createErrorNotice;





function PromotorScoreModal(props) {
  const propOnClose = props.propOnClose; // from parent

  const _useState = useState(-1),
        _useState2 = _slicedToArray(_useState, 2),
        score = _useState2[0],
        setScore = _useState2[1];

  const message = useState('');

  const afterPost = response => {
    if (response.success) {
      createSuccessNotice(__('Thanks for your feedback, your input is very much valued.'), {
        type: 'snackbar'
      });
    } else {// createErrorNotice(response.data.message || __('Error'), { type: 'snackbar' });
    }

    delete redux_templates.nps;
  };

  const onCloseWizard = () => {
    apiFetch({
      path: 'redux/v1/templates/nps',
      method: 'POST',
      data: {
        nps: 'no-thanks'
      }
    }).then(afterPost).catch(afterPost);
    propOnClose();
  };

  const submitScore = () => {
    apiFetch({
      path: 'redux/v1/templates/nps',
      method: 'POST',
      data: {
        nps: score + 1
      }
    }).then(afterPost).catch(afterPost);
    propOnClose();
  };

  return wp.element.createElement("div", {
    className: "redux-templates-modal-overlay"
  }, wp.element.createElement("div", {
    className: "redux-templates-modal-wrapper"
  }, wp.element.createElement("div", {
    className: "redux-templates-modal-header"
  }, wp.element.createElement("h3", null, __('Can we ask you a question?', redux_templates.i18n)), wp.element.createElement("button", {
    className: "redux-templates-modal-close",
    onClick: onCloseWizard
  }, wp.element.createElement("i", {
    className: 'fas fa-times'
  }))), wp.element.createElement("div", {
    className: "redux-templates-psmodal-content"
  }, wp.element.createElement("h3", null, redux_templates.nps), wp.element.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__["ButtonGroup"], null, [...Array(10).keys()].map(i => wp.element.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__["Button"], {
    key: i,
    isPrimary: score === i,
    onClick: () => setScore(i)
  }, i + 1))), -1 !== score && score < 5 && wp.element.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__["TextareaControl"] // label="Could you tell us more?"
  , {
    help: "Could you give us more details?",
    value: message // onChange={() => setState( { message } ) }

  })), wp.element.createElement("div", {
    className: "redux-templates-modal-footer nps-footer"
  }, wp.element.createElement("button", {
    className: "button button-primary",
    disabled: -1 === score,
    onClick: () => submitScore()
  }, __('Submit', redux_templates.i18n)), wp.element.createElement("a", {
    href: "#",
    onClick: onCloseWizard
  }, __('Close', redux_templates.i18n)))));
}
;

/***/ }),

/***/ "./redux-templates/src/modal-promotor-score/style.scss":
/*!*************************************************************!*\
  !*** ./redux-templates/src/modal-promotor-score/style.scss ***!
  \*************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../node_modules/css-loader/dist/cjs.js!../../../node_modules/sass-loader/dist/cjs.js!./style.scss */ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/modal-promotor-score/style.scss");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./redux-templates/src/modals.scss":
/*!*****************************************!*\
  !*** ./redux-templates/src/modals.scss ***!
  \*****************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../node_modules/css-loader/dist/cjs.js!../../node_modules/sass-loader/dist/cjs.js!./modals.scss */ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./redux-templates/src/modals.scss");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./redux-templates/src/plugins/export-page-menu-item/index.js":
/*!********************************************************************!*\
  !*** ./redux-templates/src/plugins/export-page-menu-item/index.js ***!
  \********************************************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/compose */ "@wordpress/compose");
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_compose__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _export_file__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../export/file */ "./redux-templates/src/plugins/export/file.js");
/* harmony import */ var _redux_templates_icons__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ~redux-templates/icons */ "./redux-templates/src/icons/index.js");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_5__);
/**
 * WordPress dependencies
 */




const Fragment = wp.element.Fragment;



function ExportPageContentMenuItem({
  createNotice,
  editedPostContent
}) {
  if (!wp.plugins) return null;
  const PluginMoreMenuItem = wp.editPost.PluginMoreMenuItem;

  const exportFullpage = () => {
    const fileContent = JSON.stringify({
      __file: 'core_block',
      content: editedPostContent
    }, null, 2);
    const fileName = 'page-template-export.json';
    Object(_export_file__WEBPACK_IMPORTED_MODULE_3__["download"])(fileName, fileContent, 'application/json');
  };

  return wp.element.createElement(Fragment, null, wp.element.createElement(PluginMoreMenuItem, {
    icon: Object(_redux_templates_icons__WEBPACK_IMPORTED_MODULE_4__["colorizeIcon"])(wp.element.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_5__["Dashicon"], {
      icon: "migrate"
    })),
    role: "menuitemcheckbox",
    onClick: exportFullpage
  }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__["__"])('Export Page', redux_templates.i18n)));
}

const ExportPageContentMenu = Object(_wordpress_compose__WEBPACK_IMPORTED_MODULE_2__["compose"])(Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_0__["withSelect"])(select => ({
  editedPostContent: select('core/editor').getEditedPostAttribute('content')
})), Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_0__["withDispatch"])(dispatch => {
  const _dispatch = dispatch('core/notices'),
        createNotice = _dispatch.createNotice;

  return {
    createNotice
  };
}), Object(_wordpress_compose__WEBPACK_IMPORTED_MODULE_2__["ifCondition"])(({
  editedPostContent
}) => editedPostContent.length > 0))(ExportPageContentMenuItem);

if (wp.plugins) {
  const registerPlugin = wp.plugins.registerPlugin;
  registerPlugin('redux-templates-export-page', {
    render: ExportPageContentMenu
  });
}

/***/ }),

/***/ "./redux-templates/src/plugins/export/export-block-menu-item.js":
/*!**********************************************************************!*\
  !*** ./redux-templates/src/plugins/export/export-block-menu-item.js ***!
  \**********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _reusable__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./reusable */ "./redux-templates/src/plugins/export/reusable.js");
/* harmony import */ var _file__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./file */ "./redux-templates/src/plugins/export/file.js");
/* harmony import */ var _redux_templates_icons__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ~redux-templates/icons */ "./redux-templates/src/icons/index.js");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__);
/**
 * External dependencies
 */

/**
 * Internal dependencies
 */




/**
 * WordPress dependencies
 */

const __ = wp.i18n.__;
const _wp$data = wp.data,
      withSelect = _wp$data.withSelect,
      select = _wp$data.select;
const compose = wp.compose.compose;
const Fragment = wp.element.Fragment;
const withSpokenMessages = wp.components.withSpokenMessages;
const serialize = wp.blocks.serialize;

/**
 * Render plugin
 */

function ExportManager(props) {
  const selectedBlockCount = props.selectedBlockCount,
        selectedBlock = props.selectedBlock,
        selectedBlocks = props.selectedBlocks;
  if (!wp.editPost) return null;
  const PluginBlockSettingsMenuItem = wp.editPost.PluginBlockSettingsMenuItem;

  const saveAsJSON = () => {
    if (selectedBlockCount < 1) {
      return;
    }

    let blocks;
    let title = 'redux_templates/export';

    if (selectedBlockCount === 1) {
      //export as reusable when reusable is selected
      if (selectedBlock.name === 'core/block') {
        Object(_reusable__WEBPACK_IMPORTED_MODULE_1__["default"])(selectedBlock.attributes.ref);
        return;
      }

      blocks = serialize(selectedBlock);
      title = selectedBlock.name;
    }

    if (selectedBlockCount > 1) {
      blocks = serialize(selectedBlocks);
    } //do export magic


    const fileContent = JSON.stringify({
      __file: 'core_block',
      content: blocks
    }, null, 2);
    const fileName = 'block-template-' + Object(lodash__WEBPACK_IMPORTED_MODULE_0__["kebabCase"])(title) + '.json';
    Object(_file__WEBPACK_IMPORTED_MODULE_2__["download"])(fileName, fileContent, 'application/json');
  };

  return wp.element.createElement(Fragment, null, wp.element.createElement(PluginBlockSettingsMenuItem, {
    icon: Object(_redux_templates_icons__WEBPACK_IMPORTED_MODULE_3__["colorizeIcon"])(wp.element.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__["Dashicon"], {
      icon: "migrate"
    })),
    label: __('Export Block', redux_templates.i18n),
    onClick: saveAsJSON
  }));
}

/* harmony default export */ __webpack_exports__["default"] = (compose([withSelect(() => {
  const _select = select('core/block-editor'),
        getSelectedBlockCount = _select.getSelectedBlockCount,
        getSelectedBlock = _select.getSelectedBlock,
        getMultiSelectedBlocks = _select.getMultiSelectedBlocks;

  const _select2 = select('core/block-editor'),
        getBlock = _select2.getBlock;

  return {
    selectedBlockCount: getSelectedBlockCount(),
    selectedBlock: getSelectedBlock(),
    selectedBlocks: getMultiSelectedBlocks(),
    getBlock
  };
}), withSpokenMessages])(ExportManager));

/***/ }),

/***/ "./redux-templates/src/plugins/export/file.js":
/*!****************************************************!*\
  !*** ./redux-templates/src/plugins/export/file.js ***!
  \****************************************************/
/*! exports provided: download */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "download", function() { return download; });
const block_export_json = function (el, type) {
  if (!el) {
    return;
  }

  if (el) {
    t ? t += '.json' : t = 'block.json', 'object' === ('undefined' === typeof e ? 'undefined' : u(e)) && (el = 1 === a.count ? JSON.stringify(e.shift(), void 0, 4) : JSON.stringify(e, void 0, 4));
    var n = new Blob([el], {
      type: 'text/json'
    }),
        o = document.createEvent('MouseEvents'),
        l = document.createElement('a');
    l.download = t, l.href = window.URL.createObjectURL(n), l.dataset.downloadurl = ['text/json', l.download, l.href].join(':'), o.initMouseEvent('click', !0, !1, window, 0, 0, 0, 0, 0, !1, !1, !1, !1, 0, null), l.dispatchEvent(o);
  }
};

const block_export_html = function (el, type) {
  if (!el) {
    return;
  }

  if (el) {
    t ? t += '.json' : t = 'block.json', 'object' === ('undefined' === typeof e ? 'undefined' : u(e)) && (el = 1 === a.count ? JSON.stringify(e.shift(), void 0, 4) : JSON.stringify(e, void 0, 4));
    var n = new Blob([el], {
      type: 'text/json'
    }),
        o = document.createEvent('MouseEvents'),
        l = document.createElement('a');
    l.download = t, l.href = window.URL.createObjectURL(n), l.dataset.downloadurl = ['text/json', l.download, l.href].join(':'), o.initMouseEvent('click', !0, !1, window, 0, 0, 0, 0, 0, !1, !1, !1, !1, 0, null), l.dispatchEvent(o);
  }
};

const block_export_page = function (el, type) {
  if (!el) {
    return;
  }

  if (el) {
    t ? t += '.json' : t = 'block.json', 'object' === ('undefined' === typeof e ? 'undefined' : u(e)) && (el = 1 === a.count ? JSON.stringify(e.shift(), void 0, 4) : JSON.stringify(e, void 0, 4));
    var n = new Blob([el], {
      type: 'text/json'
    }),
        o = document.createEvent('MouseEvents'),
        l = document.createElement('a');
    l.download = t, l.href = window.URL.createObjectURL(n), l.dataset.downloadurl = ['text/json', l.download, l.href].join(':'), o.initMouseEvent('click', !0, !1, window, 0, 0, 0, 0, 0, !1, !1, !1, !1, 0, null), l.dispatchEvent(o);
  }
};
/**
 * Downloads a file.
 *
 * @param {string} fileName    File Name.
 * @param {string} content     File Content.
 * @param {string} contentType File mime type.
 */


function download(fileName, content, contentType) {
  const file = new window.Blob([content], {
    type: contentType
  }); // IE11 can't use the click to download technique
  // we use a specific IE11 technique instead.

  if (window.navigator.msSaveOrOpenBlob) {
    window.navigator.msSaveOrOpenBlob(file, fileName);
  } else {
    const a = document.createElement('a');
    a.href = URL.createObjectURL(file);
    a.download = fileName;
    a.style.display = 'none';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
  }
}

/***/ }),

/***/ "./redux-templates/src/plugins/export/index.js":
/*!*****************************************************!*\
  !*** ./redux-templates/src/plugins/export/index.js ***!
  \*****************************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _export_block_menu_item__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./export-block-menu-item */ "./redux-templates/src/plugins/export/export-block-menu-item.js");


if (wp.plugins) {
  const registerPlugin = wp.plugins.registerPlugin;
  registerPlugin('redux-templates-export', {
    render: _export_block_menu_item__WEBPACK_IMPORTED_MODULE_0__["default"]
  });
}

/***/ }),

/***/ "./redux-templates/src/plugins/export/reusable.js":
/*!********************************************************!*\
  !*** ./redux-templates/src/plugins/export/reusable.js ***!
  \********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _file__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./file */ "./redux-templates/src/plugins/export/file.js");
/**
 * External dependencies
 */

/**
 * Internal dependencies
 */


/**
 * Export a reusable block as a JSON file.
 *
 * @param {number} id
 */

async function exportReusableBlock(id) {
  const postType = await wp.apiFetch({
    path: '/wp/v2/types/wp_block'
  });
  const post = await wp.apiFetch({
    path: '/wp/v2/' + postType.rest_base + '/' + id + '?context=edit'
  });
  const title = post.title.raw;
  const content = post.content.raw;
  const fileContent = JSON.stringify({
    __file: 'wp_block',
    title,
    content
  }, null, 2);
  const fileName = Object(lodash__WEBPACK_IMPORTED_MODULE_0__["kebabCase"])(title) + '.json';
  Object(_file__WEBPACK_IMPORTED_MODULE_1__["download"])(fileName, fileContent, 'application/json');
}

/* harmony default export */ __webpack_exports__["default"] = (exportReusableBlock);

/***/ }),

/***/ "./redux-templates/src/plugins/library-context-menu-item/index.js":
/*!************************************************************************!*\
  !*** ./redux-templates/src/plugins/library-context-menu-item/index.js ***!
  \************************************************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/compose */ "@wordpress/compose");
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_compose__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _modal_manager__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../modal-manager */ "./redux-templates/src/modal-manager/index.js");
/* harmony import */ var _modal_library__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../../modal-library */ "./redux-templates/src/modal-library/index.js");
/* harmony import */ var _redux_templates_icons__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ~redux-templates/icons */ "./redux-templates/src/icons/index.js");
/**
 * WordPress dependencies
 */






const Fragment = wp.element.Fragment;

function OpenLibraryContentMenuItem() {
  if (!wp.plugins) return null;
  const PluginMoreMenuItem = wp.editPost.PluginMoreMenuItem;
  return wp.element.createElement(Fragment, null, wp.element.createElement(PluginMoreMenuItem, {
    icon: Object(_redux_templates_icons__WEBPACK_IMPORTED_MODULE_5__["ReduxTemplatesIconColor"])(),
    role: "menuitemcheckbox",
    onClick: () => {
      _modal_manager__WEBPACK_IMPORTED_MODULE_3__["ModalManager"].open(wp.element.createElement(_modal_library__WEBPACK_IMPORTED_MODULE_4__["default"], null));
    }
  }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__["__"])('Template Library', redux_templates.i18n)));
}

const OpenLibraryContentMenu = Object(_wordpress_compose__WEBPACK_IMPORTED_MODULE_2__["compose"])(Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_0__["withSelect"])(select => ({})), Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_0__["withDispatch"])(dispatch => {}))(OpenLibraryContentMenuItem);

if (wp.plugins) {
  const registerPlugin = wp.plugins.registerPlugin;
  registerPlugin('redux-open-library-context', {
    render: OpenLibraryContentMenu
  });
}

/***/ }),

/***/ "./redux-templates/src/plugins/share-block-btn/buttons.js":
/*!****************************************************************!*\
  !*** ./redux-templates/src/plugins/share-block-btn/buttons.js ***!
  \****************************************************************/
/*! exports provided: ShareBlockButton, default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "ShareBlockButton", function() { return ShareBlockButton; });
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/compose */ "@wordpress/compose");
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_compose__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _wordpress_edit_post__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/edit-post */ "@wordpress/edit-post");
/* harmony import */ var _wordpress_edit_post__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_edit_post__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _icons__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../../icons */ "./redux-templates/src/icons/index.js");
/* harmony import */ var _modal_manager__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../../modal-manager */ "./redux-templates/src/modal-manager/index.js");
/* harmony import */ var _redux_templates_modal_feedback__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ~redux-templates/modal-feedback */ "./redux-templates/src/modal-feedback/index.js");
/* harmony import */ var lodash_sortBy__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! lodash/sortBy */ "./node_modules/lodash/sortBy.js");
/* harmony import */ var lodash_sortBy__WEBPACK_IMPORTED_MODULE_9___default = /*#__PURE__*/__webpack_require__.n(lodash_sortBy__WEBPACK_IMPORTED_MODULE_9__);
/* harmony import */ var lodash_map__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! lodash/map */ "./node_modules/lodash/map.js");
/* harmony import */ var lodash_map__WEBPACK_IMPORTED_MODULE_10___default = /*#__PURE__*/__webpack_require__.n(lodash_map__WEBPACK_IMPORTED_MODULE_10__);
/* harmony import */ var _stores_helper__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ../../stores/helper */ "./redux-templates/src/stores/helper.js");












/**
 * Based on: https://github.com/WordPress/gutenberg/blob/master/packages/editor/src/components/convert-to-group-buttons/convert-button.js
 */

/**
 * Internal dependencies
 */

const options = lodash_sortBy__WEBPACK_IMPORTED_MODULE_9___default()(Object(_stores_helper__WEBPACK_IMPORTED_MODULE_11__["getWithExpiry"])('page_categories_list'), 'label');
const schema = {
  type: 'object',
  properties: {
    title: {
      type: 'string',
      title: 'Block Title'
    },
    category: {
      type: 'string',
      title: 'Category',
      enum: lodash_map__WEBPACK_IMPORTED_MODULE_10___default()(options, 'value'),
      enumNames: lodash_map__WEBPACK_IMPORTED_MODULE_10___default()(options, 'label')
    },
    description: {
      type: 'string',
      title: 'Description'
    }
  }
};
const uiSchema = {
  title: {
    classNames: 'fixed-control'
  },
  category: {
    classNames: 'fixed-control'
  },
  description: {
    'ui:widget': 'textarea'
  }
};
function ShareBlockButton({
  clientIds
}) {
  // Only supported by WP >= 5.3.
  if (!clientIds) {
    return null;
  }

  const onShareBlock = () => {
    const data = {
      postID: Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_3__["select"])('core/editor').getCurrentPostId(),
      editor_blocks: Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_3__["select"])('core/block-editor').getBlocksByClientId(clientIds),
      type: 'block'
    };
    _modal_manager__WEBPACK_IMPORTED_MODULE_7__["ModalManager"].openFeedback(wp.element.createElement(_redux_templates_modal_feedback__WEBPACK_IMPORTED_MODULE_8__["default"], {
      title: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])('Redux Shares', redux_templates.i18n),
      width: 700,
      description: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])('Share this design', redux_templates.i18n),
      schema: schema,
      uiSchema: uiSchema,
      data: data,
      headerImage: wp.element.createElement("i", {
        className: "fas fa-share header-icon"
      }),
      endpoint: "share",
      onSuccess: data => window.open(data.data.url, '_blank'),
      buttonLabel: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])('Submit Template', redux_templates.i18n)
    }));
  };

  return wp.element.createElement(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__["Fragment"], null, wp.element.createElement(_wordpress_edit_post__WEBPACK_IMPORTED_MODULE_5__["PluginBlockSettingsMenuItem"], {
    icon: _icons__WEBPACK_IMPORTED_MODULE_6__["ReduxTemplatesIcon"],
    label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])('Share Block', redux_templates.i18n),
    onClick: onShareBlock
  }));
}
/* harmony default export */ __webpack_exports__["default"] = (Object(_wordpress_compose__WEBPACK_IMPORTED_MODULE_4__["compose"])([Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_3__["withDispatch"])((dispatch, {
  clientIds,
  onToggle = lodash__WEBPACK_IMPORTED_MODULE_0__["noop"],
  blocksSelection = []
}) => {
  const _dispatch = dispatch('core/block-editor'),
        replaceBlocks = _dispatch.replaceBlocks;

  return {
    onExportBlock() {
      if (!blocksSelection.length) {
        return;
      }

      console.log(blocksSelection);
      let blocks = wp.data.select('core/block-editor').getBlocks();
      let fileName = 'blocks.json';
      const title = Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_3__["select"])('core/block-editor').getSelectedBlockName();
      const content = Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_3__["select"])('core/block-editor').getSelectedBlockClientId(); // const content = post.content.raw;

      const fileContent = JSON.stringify({
        __file: 'wp_block',
        title,
        content
      }, null, 2);
      console.log(fileContent); // const theFileName = kebabCase( title ) + '.json';
      //
      // download( theFileName, fileContent, 'application/json' );
      //
      //
      //
      // if (blocksSelection.length == 1) {
      //     fileName = blocksSelection[0].name.replace('/', '_') + '.json'
      // }
      //
      // saveData(blocksSelection, fileName, 'json');

      onToggle();
    }

  };
})])(ShareBlockButton));

/***/ }),

/***/ "./redux-templates/src/plugins/share-block-btn/index.js":
/*!**************************************************************!*\
  !*** ./redux-templates/src/plugins/share-block-btn/index.js ***!
  \**************************************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _buttons__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./buttons */ "./redux-templates/src/plugins/share-block-btn/buttons.js");
/* harmony import */ var _redux_templates_icons__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ~redux-templates/icons */ "./redux-templates/src/icons/index.js");




if (wp.plugins) {
  const registerPlugin = wp.plugins.registerPlugin;
  const Buttons = Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_0__["withSelect"])(select => {
    const _select = select('core/block-editor'),
          getSelectedBlockClientIds = _select.getSelectedBlockClientIds; // Only supported by WP >= 5.3.


    if (!getSelectedBlockClientIds) {
      return {};
    }

    return {
      clientIds: getSelectedBlockClientIds()
    };
  })(_buttons__WEBPACK_IMPORTED_MODULE_1__["default"]); // TODO - Finish this off and show to users.
  // registerPlugin( 'redux-templates-share-block-btn', {
  //     icon: ReduxTemplatesIcon,
  //     render: Buttons,
  // } );
}

/***/ }),

/***/ "./redux-templates/src/plugins/sidebar-share/index.js":
/*!************************************************************!*\
  !*** ./redux-templates/src/plugins/sidebar-share/index.js ***!
  \************************************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _sidebar__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./sidebar */ "./redux-templates/src/plugins/sidebar-share/sidebar.js");
/* harmony import */ var _redux_templates_icons__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ~redux-templates/icons */ "./redux-templates/src/icons/index.js");



if (wp.plugins) {
  const registerPlugin = wp.plugins.registerPlugin;
  registerPlugin('redux-templates-share', {
    icon: _redux_templates_icons__WEBPACK_IMPORTED_MODULE_1__["ReduxTemplatesIcon"],
    render: _sidebar__WEBPACK_IMPORTED_MODULE_0__["default"]
  });
}

/***/ }),

/***/ "./redux-templates/src/plugins/sidebar-share/sidebar.js":
/*!**************************************************************!*\
  !*** ./redux-templates/src/plugins/sidebar-share/sidebar.js ***!
  \**************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var lodash_sortBy__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! lodash/sortBy */ "./node_modules/lodash/sortBy.js");
/* harmony import */ var lodash_sortBy__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(lodash_sortBy__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var lodash_map__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! lodash/map */ "./node_modules/lodash/map.js");
/* harmony import */ var lodash_map__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(lodash_map__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _modal_manager__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../modal-manager */ "./redux-templates/src/modal-manager/index.js");
/* harmony import */ var _redux_templates_modal_feedback__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ~redux-templates/modal-feedback */ "./redux-templates/src/modal-feedback/index.js");
/* harmony import */ var _stores_helper__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../../stores/helper */ "./redux-templates/src/stores/helper.js");
const __ = wp.i18n.__;
const compose = wp.compose.compose;
const _wp$data = wp.data,
      withSelect = _wp$data.withSelect,
      select = _wp$data.select;
const Fragment = wp.element.Fragment;
const PanelBody = wp.components.PanelBody;





const options = lodash_sortBy__WEBPACK_IMPORTED_MODULE_0___default()(Object(_stores_helper__WEBPACK_IMPORTED_MODULE_4__["getWithExpiry"])('section_categories_list'), 'label');
const schema = {
  type: 'object',
  properties: {
    title: {
      type: 'string',
      title: 'Block Title'
    },
    category: {
      type: 'string',
      title: 'Category',
      enum: lodash_map__WEBPACK_IMPORTED_MODULE_1___default()(options, 'value'),
      enumNames: lodash_map__WEBPACK_IMPORTED_MODULE_1___default()(options, 'label')
    },
    description: {
      type: 'string',
      title: 'Description'
    }
  }
};
const uiSchema = {
  title: {
    classNames: 'fixed-control'
  },
  category: {
    classNames: 'fixed-control'
  },
  description: {
    'ui:widget': 'textarea'
  }
};

function Sidebar(props) {
  if (!wp.editPost) return null;
  return null; // TODO - Finish fixing this experience.

  const _wp$editPost = wp.editPost,
        PluginSidebar = _wp$editPost.PluginSidebar,
        PluginSidebarMoreMenuItem = _wp$editPost.PluginSidebarMoreMenuItem;
  const getEditorBlocks = props.getEditorBlocks;

  const onShare = () => {
    const data = {
      postID: select('core/editor').getCurrentPostId(),
      editor_blocks: getEditorBlocks(),
      type: 'page'
    };
    _modal_manager__WEBPACK_IMPORTED_MODULE_2__["ModalManager"].openFeedback(wp.element.createElement(_redux_templates_modal_feedback__WEBPACK_IMPORTED_MODULE_3__["default"], {
      title: __('Redux Shares', redux_templates.i18n),
      description: __('Share this design', redux_templates.i18n),
      schema: schema,
      uiSchema: uiSchema,
      data: data,
      width: 700,
      headerImage: wp.element.createElement("i", {
        className: "fas fa-share header-icon"
      }),
      endpoint: "share",
      onSuccess: data => window.open(data.data.url, '_blank'),
      buttonLabel: __('Submit Template', redux_templates.i18n)
    }));
  };

  return wp.element.createElement(Fragment, null, wp.element.createElement(PluginSidebarMoreMenuItem, {
    target: "redux-templates-share"
  }, __('Redux Template', redux_templates.i18n)), wp.element.createElement(PluginSidebar, {
    name: "redux-templates-share",
    title: __('Redux Shares', redux_templates.i18n)
  }, wp.element.createElement(PanelBody, {
    title: __('Share this Design', redux_templates.i18n),
    initialOpen: true
  }, wp.element.createElement("div", {
    className: "d-flex justify-content-center"
  }, wp.element.createElement("a", {
    className: "button button-primary",
    onClick: onShare
  }, wp.element.createElement("i", {
    className: "fas fa-share"
  }), "\xA0", __('Share this design', redux_templates.i18n))))));
}

/* harmony default export */ __webpack_exports__["default"] = (compose([withSelect(select => {
  const _select = select('core/editor'),
        getEditorBlocks = _select.getEditorBlocks;

  return {
    getEditorBlocks
  };
})])(Sidebar));

/***/ }),

/***/ "./redux-templates/src/stores/actionHelper.js":
/*!****************************************************!*\
  !*** ./redux-templates/src/stores/actionHelper.js ***!
  \****************************************************/
/*! exports provided: handleBlock, processImportHelper, afterImportHandling, reloadLibrary, installedBlocks, installedBlocksTypes, openSitePreviewModal */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "handleBlock", function() { return handleBlock; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "processImportHelper", function() { return processImportHelper; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "afterImportHandling", function() { return afterImportHandling; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "reloadLibrary", function() { return reloadLibrary; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "installedBlocks", function() { return installedBlocks; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "installedBlocksTypes", function() { return installedBlocksTypes; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "openSitePreviewModal", function() { return openSitePreviewModal; });
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _redux_templates_modal_manager__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ~redux-templates/modal-manager */ "./redux-templates/src/modal-manager/index.js");
/* harmony import */ var _modal_preview__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../modal-preview */ "./redux-templates/src/modal-preview/index.js");
/* harmony import */ var _redux_templates_modal_feedback__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ~redux-templates/modal-feedback */ "./redux-templates/src/modal-feedback/index.js");
const _wp$blocks = wp.blocks,
      parse = _wp$blocks.parse,
      createBlock = _wp$blocks.createBlock;
const _wp = wp,
      apiFetch = _wp.apiFetch;
const _wp$data = wp.data,
      dispatch = _wp$data.dispatch,
      select = _wp$data.select;

const _select = select('core/block-editor'),
      getBlockOrder = _select.getBlockOrder;

const _select2 = select('core/blocks'),
      getBlockTypes = _select2.getBlockTypes;

const _dispatch = dispatch('core/editor'),
      savePost = _dispatch.savePost,
      editPost = _dispatch.editPost;

const _dispatch2 = dispatch('core/block-editor'),
      insertBlocks = _dispatch2.insertBlocks,
      removeBlocks = _dispatch2.removeBlocks,
      multiSelect = _dispatch2.multiSelect;

const _dispatch3 = dispatch('core/notices'),
      createSuccessNotice = _dispatch3.createSuccessNotice,
      createErrorNotice = _dispatch3.createErrorNotice,
      createNotice = _dispatch3.createNotice,
      removeNotice = _dispatch3.removeNotice;




 // create Block to import template

const handleBlock = (data, installedDependencies) => {
  let block_data = null;

  if ('template' in data) {
    block_data = parse(data.template);
  } else if ('attributes' in data) {
    if (!('innerBlocks' in data)) {
      data.innerBlocks = [];
    }

    if (!('name' in data)) {
      errorCallback('Template malformed, `name` for block not specified.');
    } // This kind of plugins are not ready to accept before reloading, thus, we save it into localStorage and just reload for now.


    if (installedDependencies === true) {
      window.redux_templates_tempdata = [...window.redux_templates_tempdata, data];
      return null;
    } else {
      block_data = createBlock(data.name, data.attributes, data.innerBlocks);
    }
  } else {
    errorCallback('Template error. Please try again.');
  }

  return block_data;
};
const processImportHelper = () => {
  const _dispatch4 = dispatch('redux-templates/sectionslist'),
        setImportingTemplate = _dispatch4.setImportingTemplate,
        discardAllErrorMessages = _dispatch4.discardAllErrorMessages,
        clearSearch = _dispatch4.clearSearch;

  const type = select('redux-templates/sectionslist').getActiveItemType() === 'section' ? 'sections' : 'pages';
  const data = select('redux-templates/sectionslist').getImportingTemplate();
  const installedDependencies = select('redux-templates/sectionslist').getInstalledDependencies();
  const isImportToAppend = select('redux-templates/sectionslist').getImportToAppend();

  if (type === 'pages') {
    editPost({
      'template': 'redux-templates_full_width'
    });
  } else {
    if ('' === select('core/editor').getEditedPostAttribute('template')) {
      editPost({
        'template': 'redux-templates_contained'
      });
    }
  }

  discardAllErrorMessages();
  let the_url = 'redux/v1/templates/template?type=' + type + '&id=' + data.id + '&uid=' + window.userSettings.uid;

  if ('source' in data) {
    the_url += '&source=' + data.source;
  }

  const options = {
    method: 'GET',
    path: the_url,
    headers: {
      'Content-Type': 'application/json',
      'Registered-Blocks': installedBlocksTypes()
    }
  };

  if (dispatch('core/edit-post') && select('core/edit-post').getEditorMode() === 'text') {
    const _dispatch5 = dispatch('core/edit-post'),
          switchEditorMode = _dispatch5.switchEditorMode;

    switchEditorMode();
  }

  window.redux_templates_tempdata = [];
  apiFetch(options).then(response => {
    // First, let's give user feedback.
    displayNotice(response.data, {
      type: 'snackbar'
    });

    if (isImportToAppend === false) {
      const rootBlocksClientIds = getBlockOrder();
      multiSelect(rootBlocksClientIds[0], rootBlocksClientIds[rootBlocksClientIds.length - 1]);
      removeBlocks(rootBlocksClientIds);
    }

    if (response.success && response.data) {
      let responseBlockData = response.data; // Important: Update left count from the response in case of no Redux PRO

      if (redux_templates.mokama !== '1' && isNaN(responseBlockData.left) === false) redux_templates.left = responseBlockData.left;
      let handledData = [];
      if (responseBlockData.hasOwnProperty('template') || responseBlockData.hasOwnProperty('attributes')) handledData = handleBlock(responseBlockData, installedDependencies);else handledData = Object.keys(responseBlockData).filter(key => key !== 'cache').map(key => handleBlock(responseBlockData[key], installedDependencies));
      localStorage.setItem('importing_data', JSON.stringify(data));
      localStorage.setItem('block_data', JSON.stringify(redux_templates_tempdata));
      localStorage.setItem('is_appending', isImportToAppend);
      insertBlocks(handledData);
      createSuccessNotice('Template inserted', {
        type: 'snackbar'
      });
      if (installedDependencies === true) savePost().then(() => window.location.reload()).catch(() => createErrorNotice('Error while saving the post', {
        type: 'snackbar'
      }));else {
        _redux_templates_modal_manager__WEBPACK_IMPORTED_MODULE_1__["ModalManager"].close();
        _redux_templates_modal_manager__WEBPACK_IMPORTED_MODULE_1__["ModalManager"].closeCustomizer();
        setImportingTemplate(null);
      }
      afterImportHandling(data, handledData);
    } else {
      if (response.success === false) errorCallback(response.data.message);else errorCallback(response.data.error);
    }
  }).catch(error => {
    errorCallback(error.code + ' : ' + error.message);
  });
};

const detectInvalidBlocks = handleBlock => {
  if (Array.isArray(handleBlock) === true) return handleBlock.filter(block => block.isValid === false);
  return handleBlock && handleBlock.isValid === false ? [handleBlock] : null;
}; // used for displaying notice from response data


const displayNotice = (data, options) => {
  if (data && data.message) {
    const noticeType = data.messageType || 'info';
    createNotice(noticeType, data.message, options);
  }
}; // show notice or feedback modal dialog based on imported block valid status


const afterImportHandling = (data, handledBlock) => {
  const invalidBlocks = detectInvalidBlocks(handledBlock); // get the description from the invalid blocks

  let description = '';
  if (invalidBlocks && invalidBlocks.length < 1) description = invalidBlocks.map(block => {
    if (block.validationIssues && Array.isArray(block.validationIssues)) return block.validationIssues.map(error => {
      return sprintf(...error.args);
    }).join('\n');else return null;
  }).join('\n'); // Prepare Form schema object

  const schema = {
    type: 'object',
    properties: {
      theme_plugins: {
        type: 'boolean',
        title: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])('Send theme and plugins', redux_templates.i18n),
        default: true
      },
      send_page_content: {
        type: 'boolean',
        title: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])('Send page content', redux_templates.i18n),
        default: true
      },
      template_id: {
        type: 'string',
        default: data.hash,
        title: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])('Template ID', redux_templates.i18n)
      },
      description: {
        type: 'string',
        default: description,
        title: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])('Description', redux_templates.i18n)
      }
    }
  };
  const uiSchema = {
    description: {
      'ui:widget': 'textarea'
    },
    template_id: {
      'ui:disabled': true,
      classNames: 'fixed-control'
    }
  };
  const feedbackData = {
    content: handledBlock
  };

  if (invalidBlocks && invalidBlocks.length > 0) {
    // in case there
    createNotice('error', 'Please let us know if there was an issue importing this Redux template.', {
      isDismissible: true,
      id: 'redux-templatesimportfeedback',
      actions: [{
        onClick: () => _redux_templates_modal_manager__WEBPACK_IMPORTED_MODULE_1__["ModalManager"].openFeedback(wp.element.createElement(_redux_templates_modal_feedback__WEBPACK_IMPORTED_MODULE_3__["default"], {
          title: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])('Thank you for reporting an issue.', redux_templates.i18n),
          description: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])('We want to make Redux perfect. Please send whatever you are comfortable sending, and we will do our best to resolve the problem.', redux_templates.i18n),
          schema: schema,
          uiSchema: uiSchema,
          data: feedbackData,
          ignoreData: true,
          headerImage: wp.element.createElement("img", {
            className: "header-background",
            src: `${redux_templates.plugin}assets/img/popup-contact.png`
          }),
          buttonLabel: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])('Submit Feedback', redux_templates.i18n)
        })),
        label: 'Report an Issue',
        isPrimary: true
      }]
    });
  }
}; // reload library button handler

const reloadLibrary = () => {
  const _dispatch6 = dispatch('redux-templates/sectionslist'),
        setLoading = _dispatch6.setLoading,
        setLibrary = _dispatch6.setLibrary;

  setLoading(true);
  apiFetch({
    path: 'redux/v1/templates/library?no_cache=1',
    method: 'POST',
    data: {
      'registered_blocks': installedBlocksTypes()
    }
  }).then(newLibrary => {
    setLoading(false);
    setLibrary(newLibrary.data);
  }).catch(error => {
    errorCallback(error);
  });
};
const installedBlocks = () => {
  let installed_blocks = getBlockTypes();
  return Object.keys(installed_blocks).map(key => {
    return installed_blocks[key]['name'];
  });
};
const installedBlocksTypes = () => {
  let installed_blocks = getBlockTypes();
  let names = Object.keys(installed_blocks).map(key => {
    if (!installed_blocks[key]['name'].includes('core')) {
      return installed_blocks[key]['name'].split('/')[0];
    }
  });
  let unique = [...new Set(names)];
  var filtered = unique.filter(function (el) {
    return el;
  });
  return filtered;
};
const openSitePreviewModal = (index, pageData) => {
  _redux_templates_modal_manager__WEBPACK_IMPORTED_MODULE_1__["ModalManager"].openCustomizer(wp.element.createElement(_modal_preview__WEBPACK_IMPORTED_MODULE_2__["default"], {
    startIndex: index,
    currentPageData: pageData
  }));
};

const errorCallback = errorMessage => {
  const _dispatch7 = dispatch('redux-templates/sectionslist'),
        appendErrorMessage = _dispatch7.appendErrorMessage,
        setImportingTemplate = _dispatch7.setImportingTemplate,
        setActivateDialogDisplay = _dispatch7.setActivateDialogDisplay;

  if (errorMessage === 'Please activate Redux') {
    setActivateDialogDisplay(true);
    redux_templates.left = 0;
  } else {
    appendErrorMessage(errorMessage);
    setImportingTemplate(null);
  }
};

/***/ }),

/***/ "./redux-templates/src/stores/actions.js":
/*!***********************************************!*\
  !*** ./redux-templates/src/stores/actions.js ***!
  \***********************************************/
/*! exports provided: actions */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "actions", function() { return actions; });
const actions = {
  setLibrary(library) {
    return {
      type: 'SET_LIBRARY',
      library
    };
  },

  fetchLibraryFromAPI(path) {
    return {
      type: 'FETCH_LIBRARY_FROM_API',
      path
    };
  },

  setActiveItemType(activeItemType) {
    return {
      type: 'SET_ACTIVE_ITEM_TYPE',
      activeItemType
    };
  },

  setActiveCategory(activeCategory) {
    return {
      type: 'SET_ACTIVE_CATEGORY',
      activeCategory
    };
  },

  setActiveCollection(activeCollection) {
    return {
      type: 'SET_ACTIVE_COLLECTION',
      activeCollection
    };
  },

  setActivePriceFilter(activePriceFilter) {
    return {
      type: 'SET_ACTIVE_PRICE_FILTER',
      activePriceFilter
    };
  },

  setSearchContext(searchContext) {
    return {
      type: 'SET_SEARCH_CONTEXT',
      searchContext
    };
  },

  setDependencyFilters(dependencyFilters) {
    return {
      type: 'SET_DEPENDENCY_FILTERS',
      dependencyFilters
    };
  },

  setCurrentPage(currentPage) {
    return {
      type: 'SET_CURRENT_PAGE',
      currentPage
    };
  },

  setLoading(loading) {
    return {
      type: 'SET_LOADING',
      loading
    };
  },

  setColumns(columns) {
    return {
      type: 'SET_COLUMNS',
      columns
    };
  },

  setSortBy(sortBy) {
    return {
      type: 'SET_SORT_BY',
      sortBy
    };
  },

  appendErrorMessage(errorMessage) {
    return {
      type: 'APPEND_ERROR_MESSAGE',
      errorMessage: errorMessage || 'Unknown Error'
    };
  },

  discardAllErrorMessages() {
    return {
      type: 'DISCARD_ALL_ERROR_MESSAGES'
    };
  },

  setInstalledDependencies(installedDependencies) {
    return {
      type: 'SET_INSTALLED_DEPENDENCIES',
      installedDependencies
    };
  },

  setTourOpen(isTourOpen) {
    return {
      type: 'SET_TOUR_OPEN',
      isTourOpen
    };
  },

  setTourActiveButtonGroup(data) {
    return {
      type: 'SET_TOUR_ACTIVE_BUTTON_GROUP',
      data
    };
  },

  setTourPreviewVisible(isVisible) {
    return {
      type: 'SET_PREVIEW_VISIBLE',
      isVisible
    };
  },

  setImportingTemplate(importingTemplate) {
    return {
      type: 'SET_IMPORTING_TEMPLATE',
      importingTemplate
    };
  },

  setChallengeStep(data) {
    return {
      type: 'SET_CHALLENGE_STEP',
      data
    };
  },

  setChallengeOpen(data) {
    return {
      type: 'SET_CHALLENGE_OPEN',
      data
    };
  },

  setChallengeTooltipRect(data) {
    return {
      type: 'SET_CHALLENGE_TOOLTIP_RECT',
      data
    };
  },

  setChallengeFinalStatus(data) {
    return {
      type: 'SET_CHALLENGE_FINAL_STATUS',
      data
    };
  },

  setChallengePassed(data) {
    return {
      type: 'SET_CHALLENGE_PASSED',
      data
    };
  },

  setChallengeListExpanded(data) {
    return {
      type: 'SET_CHALLENGE_LIST_EXPANDED',
      data
    };
  },

  setActivateDialogDisplay(data) {
    return {
      type: 'SET_ACTIVATE_DIALOG_DISPLAY',
      data
    };
  },

  setImportToAppend(data) {
    return {
      type: 'SET_IMPORT_TO_APPEND',
      data
    };
  },

  setDependencyFilterRule(data) {
    return {
      type: 'SET_DEPENDENCY_FILTER_RULE',
      data
    };
  },

  selectDependencies(data) {
    return {
      type: 'SELECT_DEPENDENCIES',
      data
    };
  },

  clearSearch() {
    return {
      type: 'CLEAR_SEARCH'
    };
  },

  clearState() {
    return {
      type: 'CLEAR_STATE'
    };
  }

};

/***/ }),

/***/ "./redux-templates/src/stores/dependencyHelper.js":
/*!********************************************************!*\
  !*** ./redux-templates/src/stores/dependencyHelper.js ***!
  \********************************************************/
/*! exports provided: getPluginInstance, needsPluginInstall, needsPluginPro, pluginInfo, processPlugin, requiresPro, requiresInstall, requiresReduxPro, isPluginReduxProMerged, isTemplateReadyToInstall, isTemplatePremium, isReduxProInstalled */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getPluginInstance", function() { return getPluginInstance; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "needsPluginInstall", function() { return needsPluginInstall; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "needsPluginPro", function() { return needsPluginPro; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "pluginInfo", function() { return pluginInfo; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "processPlugin", function() { return processPlugin; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "requiresPro", function() { return requiresPro; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "requiresInstall", function() { return requiresInstall; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "requiresReduxPro", function() { return requiresReduxPro; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "isPluginReduxProMerged", function() { return isPluginReduxProMerged; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "isTemplateReadyToInstall", function() { return isTemplateReadyToInstall; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "isTemplatePremium", function() { return isTemplatePremium; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "isReduxProInstalled", function() { return isReduxProInstalled; });
function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

const getPluginInstance = pluginKey => {
  if (pluginKey in redux_templates.supported_plugins) {
    return redux_templates.supported_plugins[pluginKey];
  }

  return false; // Deal with unknown plugins
};
const needsPluginInstall = pluginKey => {
  const pluginInstance = getPluginInstance(pluginKey);
  return !pluginInstance || pluginInstance.hasOwnProperty('version') === false;
};
const needsPluginPro = pluginKey => {
  const pluginInstance = getPluginInstance(pluginKey);
  return pluginInstance && pluginInstance.hasOwnProperty('has_pro') && pluginInstance.has_pro && (pluginInstance.hasOwnProperty('is_pro') === false || pluginInstance.is_pro === false);
};
const pluginInfo = pluginKey => {
  let pluginInstance = processPlugin(pluginKey);
  if (!pluginInstance) return {
    name: null,
    slug: null,
    url: null
  };
  return pluginInstance;
};
const processPlugin = pluginKey => {
  let pluginInstance = _objectSpread({}, getPluginInstance(pluginKey));

  if (!pluginInstance) {
    return pluginInstance;
  }

  if ('free_slug' in pluginInstance && pluginInstance['free_slug'] in redux_templates.supported_plugins) {
    let new_instance = _objectSpread({}, getPluginInstance(pluginInstance.free_slug));

    new_instance.free_slug = pluginInstance.free_slug;
    new_instance.name = pluginInstance.name;

    if (!('is_pro' in new_instance)) {
      delete new_instance.version;
    }

    pluginInstance = new_instance;
  }

  pluginInstance.slug = pluginInstance.slug ? pluginInstance.slug : pluginKey;
  return pluginInstance;
};
const requiresPro = data => {
  if (data && data.proDependenciesMissing && data.proDependenciesMissing.length > 0) {
    if (isReduxProInstalled()) {
      // redux pro installed, then skip merged plugins
      return data.proDependenciesMissing.filter(plugin => isPluginReduxProMerged(plugin) === false).length > 0;
    }

    return true;
  }

  return false;
};
const requiresInstall = data => {
  if (data && data.installDependenciesMissing && data.installDependenciesMissing.length > 0) {
    return true;
  }

  if (isReduxProInstalled() && data.proDependenciesMissing) {
    // redux pro installed, then include merged plugins
    return data.proDependenciesMissing.filter(plugin => isPluginReduxProMerged(plugin)).length > 0;
  }

  return false;
}; // Check if redux pro should be installed.

const requiresReduxPro = data => {
  if (!data) return false;
  const missingDependencies = [].concat(data.installDependenciesMissing, data.proDependenciesMissing);
  return missingDependencies.reduce((acc, curKey) => {
    if (isReduxProInstalled() === false && curKey === 'redux-pro') return true;
    return acc || isPluginReduxProMerged(curKey) && isReduxProInstalled() === false; // main logic, above were execpetion handling
  }, false);
};
const isPluginReduxProMerged = pluginKey => {
  const pluginInstance = getPluginInstance(pluginKey);
  return pluginInstance !== false && pluginInstance.redux_pro === true;
};
const isTemplateReadyToInstall = data => {
  return requiresInstall(data) || requiresPro(data) ? false : true;
};
const isTemplatePremium = (data, activeDependencyFilter) => {
  if (data && data.proDependencies !== undefined && data.proDependencies.length > 0) {
    return data.proDependencies.reduce((acc, cur) => {
      if (activeDependencyFilter[cur] === undefined) return false;
      return acc || activeDependencyFilter[cur].value;
    }, false);
  }

  return data && data.proDependenciesMissing !== undefined && data.proDependenciesMissing.length > 0;
};
const isReduxProInstalled = () => {
  const reduxProPluginInstance = redux_templates.supported_plugins['redux-framework'];
  return !!redux_templates.mokama == true || reduxProPluginInstance && reduxProPluginInstance.hasOwnProperty('is_pro');
};

/***/ }),

/***/ "./redux-templates/src/stores/filters.js":
/*!***********************************************!*\
  !*** ./redux-templates/src/stores/filters.js ***!
  \***********************************************/
/*! exports provided: applyCategoryFilter, applySearchFilter, applyHashFilter, applyPriceFilter, applyDependencyFilters, valueOfDependencyFilter, flattenPageData */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "applyCategoryFilter", function() { return applyCategoryFilter; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "applySearchFilter", function() { return applySearchFilter; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "applyHashFilter", function() { return applyHashFilter; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "applyPriceFilter", function() { return applyPriceFilter; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "applyDependencyFilters", function() { return applyDependencyFilters; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "valueOfDependencyFilter", function() { return valueOfDependencyFilter; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "flattenPageData", function() { return flattenPageData; });
/* harmony import */ var _dependencyHelper__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./dependencyHelper */ "./redux-templates/src/stores/dependencyHelper.js");
/* harmony import */ var _helper__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./helper */ "./redux-templates/src/stores/helper.js");


const REDUXTEMPLATES_PRO_KEY = 'redux-pro'; // Just get current Page Data

const applyCategoryFilter = (pageData, activeCategory) => {
  let currentPageData = [];
  let tempDataID = [];

  if (activeCategory && pageData[activeCategory] && Array.isArray(pageData[activeCategory]) && pageData[activeCategory].length > 0) {
    pageData[activeCategory].map(value => {
      if (!(tempDataID.indexOf(value.ID) > -1)) {
        currentPageData.push(value);
        tempDataID.push(value.ID);
      }
    });
  } else for (let key in pageData) {
    Array.isArray(pageData[key]) && pageData[key].map(value => {
      if (!(tempDataID.indexOf(value.ID) > -1)) {
        currentPageData.push(value);
        tempDataID.push(value.ID);
      } else {
        if (value.parentID && !(tempDataID.indexOf(value.ID) > -1)) {
          currentPageData.push(value);
          tempDataID.push(value.ID);
        }
      }
    });
  }

  return currentPageData;
};
const applySearchFilter = (pageData, searchContext) => {
  let lowercasedSearchContext = searchContext.toLowerCase();

  if (Array.isArray(pageData)) {
    return pageData.filter(item => item.name.toLowerCase().indexOf(lowercasedSearchContext) !== -1);
  } else {
    let newPageData = {};
    Object.keys(pageData).forEach(key => {
      newPageData[key] = pageData[key].filter(item => item.name.toLowerCase().indexOf(lowercasedSearchContext) != -1);
    });
    return newPageData;
  }
};
const applyHashFilter = (pageData, searchContext) => {
  let lowercasedSearchContext = searchContext.toLowerCase();

  if (Array.isArray(pageData)) {
    return pageData.filter(item => item.hash && item.hash.toLowerCase().indexOf(lowercasedSearchContext) !== -1);
  } else {
    let newPageData = [];
    Object.keys(pageData).forEach(key => {
      let filteredData = pageData[key].filter(item => item.hash && item.hash.toLowerCase().indexOf(lowercasedSearchContext) !== -1);
      newPageData = [...newPageData, ...filteredData];
    });
    return newPageData;
  }
}; // Apply Price filter afterwards : Should make sure if it is a best practise to split this filtering

const applyPriceFilter = (pageData, activePriceFilter, activeDependencyFilter) => {
  if (activePriceFilter !== '') {
    if (Array.isArray(pageData)) {
      return pageData.filter(item => {
        if (activePriceFilter === 'free') return Object(_dependencyHelper__WEBPACK_IMPORTED_MODULE_0__["isTemplatePremium"])(item, activeDependencyFilter) === false;
        if (activePriceFilter === 'pro') return Object(_dependencyHelper__WEBPACK_IMPORTED_MODULE_0__["isTemplatePremium"])(item, activeDependencyFilter);
      });
    } else {
      let newPageData = {};
      Object.keys(pageData).forEach(key => {
        newPageData[key] = pageData[key].filter(item => {
          if (activePriceFilter === 'free') return Object(_dependencyHelper__WEBPACK_IMPORTED_MODULE_0__["isTemplatePremium"])(item, activeDependencyFilter) === false;
          if (activePriceFilter === 'pro') return Object(_dependencyHelper__WEBPACK_IMPORTED_MODULE_0__["isTemplatePremium"])(item, activeDependencyFilter);
        });
      });
      return newPageData;
    }
  }

  return pageData;
};
const applyDependencyFilters = (pageData, dependencyFilters, dependencyFilterRule) => {
  const truthyDependenciesList = truthyDependencyFiltersList(dependencyFilters);

  if (Array.isArray(pageData)) {
    return pageData.filter(item => isTemplateDependencyFilterIncluded(item, truthyDependenciesList, dependencyFilterRule));
  } else {
    let newPageData = {};
    Object.keys(pageData).forEach(key => {
      newPageData[key] = pageData[key].filter(item => isTemplateDependencyFilterIncluded(item, truthyDependenciesList, dependencyFilterRule));
    });
    return newPageData;
  }
};

const isTemplateDependencyFilterIncluded = (item, truthyDependenciesList, dependencyFilterRule) => {
  // console.log("now", item.dependencies, dependencyFilters);
  // No dependencies at all case
  if (!item.dependencies || Object.keys(item.dependencies).length === 0) return truthyDependenciesList.includes(_helper__WEBPACK_IMPORTED_MODULE_1__["NONE_KEY"]); // Normal dependencies filter check

  const filteredList = item.dependencies.filter(dependency => truthyDependenciesList.includes(dependency));
  return dependencyFilterRule ? item.dependencies.length === filteredList.length : filteredList.length > 0; // filter rule = ture => AND operation
}; // check dependency filter is selected on sidebar
// Input: dependencyFilter={'qubely',


const valueOfDependencyFilter = dependencyFilter => {
  if (dependencyFilter != null && dependencyFilter.hasOwnProperty('value')) return dependencyFilter.value === true;
  return dependencyFilter === true;
};

const truthyDependencyFiltersList = dependencyFilters => {
  return Object.keys(dependencyFilters).filter(key => dependencyFilters[key].value === true);
};

const flattenPageData = pageData => {
  const currentPageData = [];

  if (Array.isArray(pageData) === false) {
    for (let key in pageData) {
      Array.isArray(pageData[key]) && pageData[key].map(value => {
        currentPageData.push(value);
      });
    }

    return currentPageData;
  }

  return pageData;
};

/***/ }),

/***/ "./redux-templates/src/stores/helper.js":
/*!**********************************************!*\
  !*** ./redux-templates/src/stores/helper.js ***!
  \**********************************************/
/*! exports provided: REDUXTEMPLATES_PRO_KEY, NONE_KEY, getCurrentState, categorizeData, parseSectionData, parsePageData, parseCollectionData, getCollectionChildrenData, isBlockPro, missingPro, missingRequirement, setWithExpiry, getWithExpiry, handlingLocalStorageData, columnMap, pageSizeMap, getOnlySelectedDependencyFilters, getDefaultDependencies, getInstalledDependencies, missingPluginsArray, loadChallengeStep, saveChallengeStep */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "REDUXTEMPLATES_PRO_KEY", function() { return REDUXTEMPLATES_PRO_KEY; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "NONE_KEY", function() { return NONE_KEY; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getCurrentState", function() { return getCurrentState; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "categorizeData", function() { return categorizeData; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "parseSectionData", function() { return parseSectionData; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "parsePageData", function() { return parsePageData; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "parseCollectionData", function() { return parseCollectionData; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getCollectionChildrenData", function() { return getCollectionChildrenData; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "isBlockPro", function() { return isBlockPro; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "missingPro", function() { return missingPro; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "missingRequirement", function() { return missingRequirement; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "setWithExpiry", function() { return setWithExpiry; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getWithExpiry", function() { return getWithExpiry; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "handlingLocalStorageData", function() { return handlingLocalStorageData; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "columnMap", function() { return columnMap; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "pageSizeMap", function() { return pageSizeMap; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getOnlySelectedDependencyFilters", function() { return getOnlySelectedDependencyFilters; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getDefaultDependencies", function() { return getDefaultDependencies; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getInstalledDependencies", function() { return getInstalledDependencies; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "missingPluginsArray", function() { return missingPluginsArray; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "loadChallengeStep", function() { return loadChallengeStep; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "saveChallengeStep", function() { return saveChallengeStep; });
/* harmony import */ var lodash_kebabCase__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! lodash/kebabCase */ "./node_modules/lodash/kebabCase.js");
/* harmony import */ var lodash_kebabCase__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(lodash_kebabCase__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var lodash_uniq__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! lodash/uniq */ "./node_modules/lodash/uniq.js");
/* harmony import */ var lodash_uniq__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(lodash_uniq__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var lodash_concat__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! lodash/concat */ "./node_modules/lodash/concat.js");
/* harmony import */ var lodash_concat__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(lodash_concat__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var lodash_flatten__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! lodash/flatten */ "./node_modules/lodash/flatten.js");
/* harmony import */ var lodash_flatten__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(lodash_flatten__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var lodash_sortBy__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! lodash/sortBy */ "./node_modules/lodash/sortBy.js");
/* harmony import */ var lodash_sortBy__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(lodash_sortBy__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var lodash_map__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! lodash/map */ "./node_modules/lodash/map.js");
/* harmony import */ var lodash_map__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(lodash_map__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var lodash_flattenDeep__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! lodash/flattenDeep */ "./node_modules/lodash/flattenDeep.js");
/* harmony import */ var lodash_flattenDeep__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(lodash_flattenDeep__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var _actionHelper__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./actionHelper */ "./redux-templates/src/stores/actionHelper.js");
/* harmony import */ var _dependencyHelper__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./dependencyHelper */ "./redux-templates/src/stores/dependencyHelper.js");
function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }










const createBlock = wp.blocks.createBlock;
const dispatch = wp.data.dispatch;

const _dispatch = dispatch('core/notices'),
      createSuccessNotice = _dispatch.createSuccessNotice;

const _dispatch2 = dispatch('core/block-editor'),
      insertBlocks = _dispatch2.insertBlocks;

const prefix = 'redux_';
const REDUXTEMPLATES_PRO_KEY = 'redux-pro';
const NONE_KEY = 'core';
const EXIPRY_TIME = 5 * 24 * 3600 * 1000;
const getCurrentState = state => state[state.activeItemType]; // Helper function not to be exported

const convertObjectToArray = list => {
  if (!list) return [];
  return Object.keys(list).map(key => {
    return _objectSpread(_objectSpread({}, list[key]), {}, {
      ID: key
    });
  });
}; // parse categories and section data from section server data


const categorizeData = list => {
  let categories = [];
  let data = {};
  list.forEach(item => {
    if (item.categories) {
      item.categories.map(catName => {
        let catSlug = lodash_kebabCase__WEBPACK_IMPORTED_MODULE_0___default()(catName);

        if (catSlug in data) {
          data[catSlug].push(item);
        } else {
          data[catSlug] = [];
          data[catSlug].push(item);
        }

        let index = -1;
        categories.forEach((change, i) => {
          if (catSlug == change.slug) {
            index = i;
            categories[i].ids.push(item.id);
          }
        });

        if (index === -1) {
          categories.push({
            name: catName,
            slug: catSlug,
            ids: [item.id]
          });
        }
      });
    }
  });
  return {
    categories,
    data
  };
};
/* Parse section data */
// - convert `sections` object to array
// - make fullPluginsList from dependency, like ['redux-pro', 'qubely', 'getwid', ...]
// -- filter wholePlugins from fullPluginsList
// -- filter thirdPartyPlugins from fullPluginsList
// - categorize the plugin information and save it to local storage

const parseSectionData = sections => {
  const librarySectionData = convertObjectToArray(sections);
  const fullPluginsList = lodash_uniq__WEBPACK_IMPORTED_MODULE_1___default()(lodash_flattenDeep__WEBPACK_IMPORTED_MODULE_6___default()(lodash_map__WEBPACK_IMPORTED_MODULE_5___default()(librarySectionData, 'dependencies')));
  const wholePlugins = fullPluginsList.filter(pluginKey => !isThirdPartyPlugin(pluginKey));
  const thirdPartyPlugins = fullPluginsList.filter(pluginKey => isThirdPartyPlugin(pluginKey));
  const toBeReturned = categorizeData(librarySectionData);
  const categoriesList = toBeReturned.categories.map(category => {
    return {
      label: category.name,
      value: category.slug
    };
  });
  setWithExpiry('section_categories_list', categoriesList, EXIPRY_TIME);
  return _objectSpread(_objectSpread({}, toBeReturned), {}, {
    wholePlugins,
    thirdPartyPlugins
  });
};
const parsePageData = pages => {
  const libraryPageData = convertObjectToArray(pages);
  const fullPluginsList = lodash_uniq__WEBPACK_IMPORTED_MODULE_1___default()(lodash_flattenDeep__WEBPACK_IMPORTED_MODULE_6___default()(lodash_map__WEBPACK_IMPORTED_MODULE_5___default()(libraryPageData, 'dependencies')));
  const wholePlugins = fullPluginsList.filter(pluginKey => !isThirdPartyPlugin(pluginKey));
  const thirdPartyPlugins = fullPluginsList.filter(pluginKey => isThirdPartyPlugin(pluginKey));
  const toBeReturned = categorizeData(libraryPageData);
  const categoriesList = toBeReturned.categories.map(category => {
    return {
      label: category.name,
      value: category.slug
    };
  });
  setWithExpiry('page_categories_list', categoriesList, EXIPRY_TIME);
  return _objectSpread(_objectSpread({}, toBeReturned), {}, {
    wholePlugins,
    thirdPartyPlugins
  });
};
const parseCollectionData = library => {
  let libraryCollectionData = convertObjectToArray(library.collections); // filter out incomplete data

  libraryCollectionData = libraryCollectionData.filter(collection => collection.pages && collection.pages.length > 0); // After common handling, we need to populate homepage data

  libraryCollectionData = libraryCollectionData.map(collection => {
    if (collection.homepage && library.pages[collection.homepage]) collection.homepageData = library.pages[collection.homepage];else {
      collection.homepageData = library.pages[collection.pages[0]];
    }

    if (collection.pages) {
      collection.installDependenciesMissing = lodash_uniq__WEBPACK_IMPORTED_MODULE_1___default()(lodash_concat__WEBPACK_IMPORTED_MODULE_2___default()(lodash_flatten__WEBPACK_IMPORTED_MODULE_3___default()(collection.pages.map(page => library.pages[page].installDependenciesMissing || []))));
      collection.proDependenciesMissing = lodash_uniq__WEBPACK_IMPORTED_MODULE_1___default()(lodash_concat__WEBPACK_IMPORTED_MODULE_2___default()(lodash_flatten__WEBPACK_IMPORTED_MODULE_3___default()(collection.pages.map(page => library.pages[page].proDependenciesMissing || []))));
    }

    return collection;
  });
  const fullPluginsList = lodash_uniq__WEBPACK_IMPORTED_MODULE_1___default()(lodash_flattenDeep__WEBPACK_IMPORTED_MODULE_6___default()(lodash_map__WEBPACK_IMPORTED_MODULE_5___default()(libraryCollectionData, 'dependencies')));
  const wholePlugins = fullPluginsList.filter(pluginKey => !isThirdPartyPlugin(pluginKey));
  const thirdPartyPlugins = fullPluginsList.filter(pluginKey => isThirdPartyPlugin(pluginKey));
  return _objectSpread(_objectSpread({}, categorizeData(libraryCollectionData)), {}, {
    dependencyFilters: _objectSpread({
      [NONE_KEY]: true
    }, library.dependencies),
    wholePlugins,
    thirdPartyPlugins
  });
}; // one of important function
// get collection children data upon clicking on collection in collections tab
// always homepage page first, sort alphabetically afterward

const getCollectionChildrenData = (library, activeCollection) => {
  let activeCollectionData = library.collections[activeCollection]; // sort page except homepage

  let childrenPages = activeCollectionData.pages.filter(page => page !== activeCollectionData.homepage).map(child => {
    return _objectSpread(_objectSpread({}, library.pages[child]), {}, {
      ID: child
    });
  });
  childrenPages = lodash_sortBy__WEBPACK_IMPORTED_MODULE_4___default()(childrenPages, 'name'); // insert homepage at the beginning of the array

  if (activeCollectionData.homepage && library.pages[activeCollectionData.homepage]) {
    childrenPages.unshift(library.pages[activeCollectionData.homepage]);
  }

  return childrenPages;
}; // Check if the block is pro

const isBlockPro = (pro, source) => {
  if (source && redux_templates.supported_plugins.hasOwnProperty(source)) return pro && !redux_templates.supported_plugins[source].is_pro;else return pro && redux_templates.mokama !== '1';
};
const missingPro = pro => {
  return redux_templates.mokama !== '1' && pro === true;
};
const missingRequirement = (pro, requirements) => {
  if (!requirements) return missingPro(pro);else {
    const supported_plugins = redux_templates.supported_plugins;

    for (let i = 0; i < requirements.length; i++) {
      let requirement = requirements[i];
      if (!supported_plugins.hasOwnProperty(requirement.slug)) return true; // Doesn't have the plugin installed
      else {
          let installedPlugin = supported_plugins[requirement.slug];
          if (Number(requirement.version) > Number(installedPlugin.version) || requirement.pro === true && installedPlugin.pro === false) return true;
        }
    }

    return proCheck;
  }
};
const setWithExpiry = (key, value, ttl) => {
  const prefixedKey = prefix + key;
  const now = new Date(); // `item` is an object which contains the original value
  // as well as the time when it's supposed to expire

  const item = {
    value: value,
    expiry: now.getTime() + ttl
  };
  localStorage.setItem(prefixedKey, JSON.stringify(item));
};
const getWithExpiry = (key, defaultValue = null) => {
  const prefixedKey = prefix + key;
  const itemStr = localStorage.getItem(prefixedKey); // if the item doesn't exist, return null

  if (!itemStr) {
    return defaultValue;
  }

  const item = JSON.parse(itemStr);
  const now = new Date(); // compare the expiry time of the item with the current time

  if (now.getTime() > item.expiry) {
    // If the item is expired, delete the item from storage
    // and return null
    localStorage.removeItem(prefixedKey);
    return defaultValue;
  }

  return item.value;
};
const handlingLocalStorageData = () => {
  try {
    let blockData = localStorage.getItem('block_data');
    if (!blockData || blockData == null) return;
    blockData = JSON.parse(blockData);
    if (!blockData || blockData == null || blockData.length < 1) return;
    blockData = blockData.filter(block => block.name && block.attributes && block.innerBlocks).map(block => {
      if (block.name && block.attributes && block.innerBlocks) return createBlock(block.name, block.attributes, block.innerBlocks);
    });

    if (blockData.length > 0) {
      insertBlocks(blockData);
      createSuccessNotice('Template inserted', {
        type: 'snackbar'
      });
    } // preparing to call after import handling


    let data = localStorage.getItem('importing_data');
    if (!data || data == null) return;
    data = JSON.parse(data);
    Object(_actionHelper__WEBPACK_IMPORTED_MODULE_7__["afterImportHandling"])(data, blockData); // reset the localstorage

    localStorage.setItem('block_data', null);
    localStorage.setItem('importing_data', null);
  } catch (error) {
    alert(error.code + ' : ' + error.message);
  }
};
const columnMap = {
  'large': 2,
  'medium': 3,
  'small': 4
};
const pageSizeMap = {
  'large': 20,
  'medium': 30,
  'small': 40
};
const getOnlySelectedDependencyFilters = dependencyFilters => {
  return Object.keys(dependencyFilters).filter(key => dependencyFilters[key]);
};
/*
Input: dependencies: {getwid: 38, qubely: 82...}
Input: dependencies: ['getwid', 'qubely', ...]
Result: {getwid: {value: true, disabled: true}, }
*/

const getDefaultDependencies = dependencies => {
  const unSupportedPlugins = Object.keys(redux_templates.supported_plugins).filter(key => isPluginProActivated(key) === false);
  return dependencies.reduce((acc, cur) => {
    // special handling for pro plugin not activated.
    let value = true;
    if (isProPlugin(cur) && cur !== REDUXTEMPLATES_PRO_KEY && Object(_dependencyHelper__WEBPACK_IMPORTED_MODULE_8__["isPluginReduxProMerged"])(cur) === false) value = false; // Not including pro plugin in redux templates

    return _objectSpread(_objectSpread({}, acc), {}, {
      [cur]: {
        value,
        disabled: false
      }
    });
  }, {
    [NONE_KEY]: {
      value: true,
      disabled: false
    },
    // Native element is included in default dependencies
    [REDUXTEMPLATES_PRO_KEY]: {
      value: true,
      disabled: false
    } // Redux pro is included in default dependencies

  });
};
const getInstalledDependencies = dependencies => {
  const unSupportedPlugins = Object.keys(redux_templates.supported_plugins).filter(key => isPluginProActivated(key) === false);
  return dependencies.filter(key => key !== NONE_KEY).reduce((acc, cur) => {
    // special handling for pro plugin not activated.
    let value = true;
    const pluginInstance = Object(_dependencyHelper__WEBPACK_IMPORTED_MODULE_8__["getPluginInstance"])(cur);

    if (pluginInstance) {
      if (isProPlugin(cur) && unSupportedPlugins.indexOf(cur) !== -1) value = false;
      if (isProPlugin(cur) === false && pluginInstance.hasOwnProperty('version') === false) value = false;
      if (cur === REDUXTEMPLATES_PRO_KEY) value = false;
    } else value = false;

    return _objectSpread(_objectSpread({}, acc), {}, {
      [cur]: {
        value,
        disabled: false
      }
    });
  }, {
    [NONE_KEY]: {
      value: true,
      disabled: false
    }
  });
};

const isProPlugin = pluginKey => {
  const pluginInstance = Object(_dependencyHelper__WEBPACK_IMPORTED_MODULE_8__["getPluginInstance"])(pluginKey);
  return pluginInstance && pluginInstance.hasOwnProperty('free_slug');
};

const isPluginProActivated = pluginKey => {
  const pluginInstance = Object(_dependencyHelper__WEBPACK_IMPORTED_MODULE_8__["getPluginInstance"])(pluginKey);
  const freePluginInstance = Object(_dependencyHelper__WEBPACK_IMPORTED_MODULE_8__["getPluginInstance"])(pluginInstance.free_slug);
  return freePluginInstance.hasOwnProperty('version') && freePluginInstance.hasOwnProperty('is_pro') && freePluginInstance.is_pro !== false;
};

const missingPluginsArray = () => {
  return Object.keys(redux_templates.supported_plugins).filter(pluginKey => isProPlugin(pluginKey) && isPluginProActivated(pluginKey) === false);
};
/**
 * Get last saved step.
 */

const loadChallengeStep = () => {
  var step = localStorage.getItem('reduxChallengeStep');
  if (step === null) return -1;
  step = parseInt(step, 10);
  return step;
};
/**
 * Save Challenge step.
 */

const saveChallengeStep = step => {
  localStorage.setItem('reduxChallengeStep', step);
};

const isThirdPartyPlugin = pluginKey => {
  const pluginInstance = Object(_dependencyHelper__WEBPACK_IMPORTED_MODULE_8__["getPluginInstance"])(pluginKey);
  return pluginInstance.free_slug && !pluginInstance.redux_pro && !pluginInstance.version;
};

/***/ }),

/***/ "./redux-templates/src/stores/index.js":
/*!*********************************************!*\
  !*** ./redux-templates/src/stores/index.js ***!
  \*********************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _reducer__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./reducer */ "./redux-templates/src/stores/reducer.js");
/* harmony import */ var _actions__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./actions */ "./redux-templates/src/stores/actions.js");
/* harmony import */ var lodash_cloneDeep__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! lodash/cloneDeep */ "./node_modules/lodash/cloneDeep.js");
/* harmony import */ var lodash_cloneDeep__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(lodash_cloneDeep__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var lodash_sortBy__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! lodash/sortBy */ "./node_modules/lodash/sortBy.js");
/* harmony import */ var lodash_sortBy__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(lodash_sortBy__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var lodash_countBy__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! lodash/countBy */ "./node_modules/lodash/countBy.js");
/* harmony import */ var lodash_countBy__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(lodash_countBy__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var lodash_map__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! lodash/map */ "./node_modules/lodash/map.js");
/* harmony import */ var lodash_map__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(lodash_map__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var lodash_flattenDeep__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! lodash/flattenDeep */ "./node_modules/lodash/flattenDeep.js");
/* harmony import */ var lodash_flattenDeep__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(lodash_flattenDeep__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var lodash_uniq__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! lodash/uniq */ "./node_modules/lodash/uniq.js");
/* harmony import */ var lodash_uniq__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(lodash_uniq__WEBPACK_IMPORTED_MODULE_7__);
/* harmony import */ var lodash_uniqBy__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! lodash/uniqBy */ "./node_modules/lodash/uniqBy.js");
/* harmony import */ var lodash_uniqBy__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(lodash_uniqBy__WEBPACK_IMPORTED_MODULE_8__);
/* harmony import */ var _filters__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./filters */ "./redux-templates/src/stores/filters.js");
/* harmony import */ var _helper__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ./helper */ "./redux-templates/src/stores/helper.js");
/* harmony import */ var _dependencyHelper__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ./dependencyHelper */ "./redux-templates/src/stores/dependencyHelper.js");
/* harmony import */ var _actionHelper__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! ./actionHelper */ "./redux-templates/src/stores/actionHelper.js");
function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

const _wp = wp,
      apiFetch = _wp.apiFetch;
const registerStore = wp.data.registerStore;














const getOriginalPageData = state => {
  if (state.activeItemType === 'collection' && state.collection.activeCollection !== null) return Object(_helper__WEBPACK_IMPORTED_MODULE_10__["getCollectionChildrenData"])(state.library, state.collection.activeCollection);
  return Object(_helper__WEBPACK_IMPORTED_MODULE_10__["getCurrentState"])(state).data;
};

const getActivePriceFilter = state => {
  return Object(_helper__WEBPACK_IMPORTED_MODULE_10__["getCurrentState"])(state).priceFilter;
};

const getSearchContext = state => {
  return state.activeItemType !== 'saved' ? Object(_helper__WEBPACK_IMPORTED_MODULE_10__["getCurrentState"])(state).searchContext : null;
};

const getActiveCategory = state => {
  return state[state.activeItemType].activeCategory;
};

const getCurrentPage = state => {
  return state[state.activeItemType].currentPage;
};

const getActiveItemType = state => {
  return state.activeItemType;
}; // get relevant page data, apply category, price, search, dependent filters


const getPageData = (state, applyDependencyFilter = true) => {
  let pageData = getOriginalPageData(state);
  const searchKeyword = getSearchContext(state);
  let hashFilteredData = []; // Hash filter to take priority

  if (state.activeItemType !== 'collection' && searchKeyword.length > 5) hashFilteredData = Object(_filters__WEBPACK_IMPORTED_MODULE_9__["applyHashFilter"])(pageData, searchKeyword); // Full search for pageData

  if (pageData && Object.keys(pageData).length > 0) {
    pageData = Object(_filters__WEBPACK_IMPORTED_MODULE_9__["applySearchFilter"])(pageData, searchKeyword);
    if (applyDependencyFilter) pageData = Object(_filters__WEBPACK_IMPORTED_MODULE_9__["applyDependencyFilters"])(pageData, getDependencyFilters(state), getDependencyFilterRule(state));
    pageData = Object(_filters__WEBPACK_IMPORTED_MODULE_9__["applyPriceFilter"])(pageData, getActivePriceFilter(state), getDependencyFilters(state));

    if (state.collection.activeCollection === null || state.activeItemType !== 'collection') {
      pageData = Object(_filters__WEBPACK_IMPORTED_MODULE_9__["applyCategoryFilter"])(pageData, getActiveCategory(state));
      pageData = lodash_sortBy__WEBPACK_IMPORTED_MODULE_3___default()(pageData, Object(_helper__WEBPACK_IMPORTED_MODULE_10__["getCurrentState"])(state).sortBy);
    }

    return lodash_uniqBy__WEBPACK_IMPORTED_MODULE_8___default()([...pageData, ...hashFilteredData], 'ID');
  }

  return null;
};

const getDependencyFilters = state => {
  return _objectSpread(_objectSpread({}, getAllDependencFilters(state)), Object(_helper__WEBPACK_IMPORTED_MODULE_10__["getCurrentState"])(state).dependencyFilters);
};

const getAllDependencFilters = state => {
  const activeState = state[state.activeItemType || 'section'];
  return [...activeState.wholePlugins, ...activeState.thirdPartyPlugins].reduce((acc, cur) => {
    return _objectSpread(_objectSpread({}, acc), {}, {
      [cur]: {
        value: false
      }
    });
  }, undefined);
};

const getDependencyFiltersStatistics = state => {
  const pageData = Object(_filters__WEBPACK_IMPORTED_MODULE_9__["flattenPageData"])(getOriginalPageData(state));
  const dependentPluginsArray = lodash_uniq__WEBPACK_IMPORTED_MODULE_7___default()(lodash_flattenDeep__WEBPACK_IMPORTED_MODULE_6___default()(lodash_map__WEBPACK_IMPORTED_MODULE_5___default()(pageData, 'dependencies')));
  let dependencyFilters = getDependencyFilters(state);
  Object.keys(dependencyFilters).forEach(plugin => {
    dependencyFilters[plugin] = _objectSpread(_objectSpread({}, dependencyFilters[plugin]), {}, {
      disabled: dependentPluginsArray.indexOf(plugin) === -1
    });
  });
  dependencyFilters[_helper__WEBPACK_IMPORTED_MODULE_10__["NONE_KEY"]] = {
    value: Object(_filters__WEBPACK_IMPORTED_MODULE_9__["valueOfDependencyFilter"])(dependencyFilters[_helper__WEBPACK_IMPORTED_MODULE_10__["NONE_KEY"]]),
    disabled: false
  };
  return dependencyFilters;
};

const getDependencyFilterRule = state => {
  return state[state.activeItemType].dependencyFilterRule;
};

registerStore('redux-templates/sectionslist', {
  reducer: _reducer__WEBPACK_IMPORTED_MODULE_0__["reducer"],
  actions: _actions__WEBPACK_IMPORTED_MODULE_1__["actions"],
  selectors: {
    fetchLibraryFromAPI(state) {
      return state.library;
    },

    receive(state) {
      return state.sections;
    },

    getActivePriceFilter,
    getSearchContext,
    getDependencyFilters,
    getDependencyFiltersStatistics,
    getActiveItemType,
    getCurrentPage,
    getActiveCategory,
    getDependencyFilterRule,

    getWholePlugins(state) {
      return state.activeItemType !== 'saved' ? Object(_helper__WEBPACK_IMPORTED_MODULE_10__["getCurrentState"])(state).wholePlugins : null;
    },

    getThirdPartyPlugins(state) {
      return state.activeItemType !== 'saved' ? Object(_helper__WEBPACK_IMPORTED_MODULE_10__["getCurrentState"])(state).thirdPartyPlugins : null;
    },

    // get categories from currentState, sortBy alphabetically, with the count of pageData within the current category
    getCategoryData(state) {
      let categories = [];
      let pageData = getOriginalPageData(state);

      if (pageData && Object.keys(pageData).length > 0) {
        pageData = Object(_filters__WEBPACK_IMPORTED_MODULE_9__["applySearchFilter"])(pageData, getSearchContext(state));
        pageData = Object(_filters__WEBPACK_IMPORTED_MODULE_9__["applyDependencyFilters"])(pageData, getDependencyFilters(state), getDependencyFilterRule(state));
        pageData = Object(_filters__WEBPACK_IMPORTED_MODULE_9__["applyPriceFilter"])(pageData, getActivePriceFilter(state), getDependencyFilters(state));
      }

      if (state.collection.activeCollection === null || state.activeItemType !== 'collection') {
        categories = lodash_cloneDeep__WEBPACK_IMPORTED_MODULE_2___default()(Object(_helper__WEBPACK_IMPORTED_MODULE_10__["getCurrentState"])(state).categories);
        categories = categories.map(category => {
          const filteredData = lodash_map__WEBPACK_IMPORTED_MODULE_5___default()(pageData[category.slug], 'id');
          return _objectSpread(_objectSpread({}, category), {}, {
            filteredData
          });
        });
      }

      categories = lodash_sortBy__WEBPACK_IMPORTED_MODULE_3___default()(categories, 'name');
      return categories;
    },

    // get relevant page data, apply category, price, search, dependent filters
    getPageData,

    getStatistics(state) {
      let pageData = getOriginalPageData(state);
      let staticsData = {
        true: 0,
        false: 0
      };

      if (pageData && Object.keys(pageData).length > 0) {
        pageData = Object(_filters__WEBPACK_IMPORTED_MODULE_9__["applySearchFilter"])(pageData, getSearchContext(state));
        pageData = Object(_filters__WEBPACK_IMPORTED_MODULE_9__["applyDependencyFilters"])(pageData, getDependencyFilters(state), getDependencyFilterRule(state));
        if (state.collection.activeCollection === null || state.activeItemType !== 'collection') pageData = Object(_filters__WEBPACK_IMPORTED_MODULE_9__["applyCategoryFilter"])(pageData, getActiveCategory(state));
        staticsData = lodash_countBy__WEBPACK_IMPORTED_MODULE_4___default()(pageData, item => Object(_dependencyHelper__WEBPACK_IMPORTED_MODULE_11__["isTemplatePremium"])(item, getDependencyFilters(state)) === true);
      }

      return staticsData;
    },

    getLoading(state) {
      return state.loading;
    },

    getColumns(state) {
      return state.columns;
    },

    getSortBy(state) {
      return Object(_helper__WEBPACK_IMPORTED_MODULE_10__["getCurrentState"])(state).sortBy;
    },

    getActiveCollection(state) {
      return state.collection.activeCollection;
    },

    getActiveCollectionData(state) {
      if (state.library && state.library.collections && state.collection) return state.library.collections[state.collection.activeCollection];
      return null;
    },

    getSaved(state) {
      return state.saved;
    },

    getErrorMessages(state) {
      return state.errorMessages;
    },

    getInstalledDependencies(state) {
      return state.installedDependencies;
    },

    getTourOpen(state) {
      return state.tour.isOpen;
    },

    getTourActiveButtonGroup(state) {
      return state.tour.activeButtonGroup;
    },

    getTourPreviewVisible(state) {
      return state.tour.isPreviewVisible;
    },

    getImportingTemplate(state) {
      return state.importingTemplate;
    },

    getChallengeStep(state) {
      return Object(_helper__WEBPACK_IMPORTED_MODULE_10__["loadChallengeStep"])();
    },

    getChallengeOpen(state) {
      return state.challenge.isOpen;
    },

    getChallengeTooltipRect(state) {
      return state.challenge.tooltipRect;
    },

    getChallengeFinalStatus(state) {
      return state.challenge.finalStatus;
    },

    getChallengePassed(state) {
      return state.challenge.passed;
    },

    getChallengeListExpanded(state) {
      return state.challenge.listExpanded;
    },

    getActivateDialogDisplay(state) {
      return state.activateDialog;
    },

    getImportToAppend(state) {
      return state.isImportToAppend;
    }

  },
  controls: {
    FETCH_LIBRARY_FROM_API(action) {
      return apiFetch({
        path: action.path,
        method: 'POST',
        data: {
          registered_blocks: Object(_actionHelper__WEBPACK_IMPORTED_MODULE_12__["installedBlocksTypes"])()
        }
      });
    },

    FETCH_SAVED_FROM_API(action) {
      return apiFetch({
        path: action.path,
        method: 'POST',
        data: {
          registered_blocks: Object(_actionHelper__WEBPACK_IMPORTED_MODULE_12__["installedBlocksTypes"])()
        }
      });
    }

  },
  resolvers: {
    *fetchLibraryFromAPI(state) {
      try {
        const receiveSectionResult = yield _actions__WEBPACK_IMPORTED_MODULE_1__["actions"].fetchLibraryFromAPI('redux/v1/templates/library');
        return _actions__WEBPACK_IMPORTED_MODULE_1__["actions"].setLibrary(receiveSectionResult.data);
      } catch (error) {
        return _actions__WEBPACK_IMPORTED_MODULE_1__["actions"].appendErrorMessage(error.code + ' ' + error.message);
      }
    }

  },
  initialState: _reducer__WEBPACK_IMPORTED_MODULE_0__["initialState"]
});

/***/ }),

/***/ "./redux-templates/src/stores/reducer.js":
/*!***********************************************!*\
  !*** ./redux-templates/src/stores/reducer.js ***!
  \***********************************************/
/*! exports provided: initialState, reducer */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "initialState", function() { return initialState; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "reducer", function() { return reducer; });
/* harmony import */ var _helper__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./helper */ "./redux-templates/src/stores/helper.js");
function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }




const EXIPRY_TIME = 5 * 24 * 3600 * 1000;
const initialState = {
  loading: false,
  activeItemType: Object(_helper__WEBPACK_IMPORTED_MODULE_0__["getWithExpiry"])('itemType', 'section'),
  library: null,
  columns: Object(_helper__WEBPACK_IMPORTED_MODULE_0__["getWithExpiry"])('column', ''),
  errorMessages: [],
  section: {
    categories: [],
    data: {},
    priceFilter: Object(_helper__WEBPACK_IMPORTED_MODULE_0__["getWithExpiry"])('section_price', ''),
    activeCategory: Object(_helper__WEBPACK_IMPORTED_MODULE_0__["getWithExpiry"])('section_category', ''),
    dependencyFilters: {},
    dependencyFilterRule: Object(_helper__WEBPACK_IMPORTED_MODULE_0__["getWithExpiry"])('section_filterRule', true),
    searchContext: '',
    wholePlugins: [],
    thirdPartyPlugins: [],
    sortBy: Object(_helper__WEBPACK_IMPORTED_MODULE_0__["getWithExpiry"])('section_sort', 'name'),
    currentPage: Object(_helper__WEBPACK_IMPORTED_MODULE_0__["getWithExpiry"])('section_page', 0)
  },
  page: {
    categories: [],
    data: {},
    priceFilter: Object(_helper__WEBPACK_IMPORTED_MODULE_0__["getWithExpiry"])('page_price', ''),
    activeCategory: Object(_helper__WEBPACK_IMPORTED_MODULE_0__["getWithExpiry"])('page_category', ''),
    dependencyFilters: {},
    dependencyFilterRule: Object(_helper__WEBPACK_IMPORTED_MODULE_0__["getWithExpiry"])('page_filterRule', true),
    searchContext: '',
    wholePlugins: [],
    thirdPartyPlugins: [],
    sortBy: Object(_helper__WEBPACK_IMPORTED_MODULE_0__["getWithExpiry"])('page_sort', 'name'),
    currentPage: Object(_helper__WEBPACK_IMPORTED_MODULE_0__["getWithExpiry"])('page_page', 0)
  },
  collection: {
    categories: [],
    data: {},
    priceFilter: Object(_helper__WEBPACK_IMPORTED_MODULE_0__["getWithExpiry"])('collection_price', ''),
    activeCategory: Object(_helper__WEBPACK_IMPORTED_MODULE_0__["getWithExpiry"])('collection_category', 'name'),
    dependencyFilters: {},
    dependencyFilterRule: false,
    searchContext: '',
    wholePlugins: [],
    thirdPartyPlugins: [],
    activeCollection: null,
    sortBy: Object(_helper__WEBPACK_IMPORTED_MODULE_0__["getWithExpiry"])('collection_sort', 'name'),
    currentPage: Object(_helper__WEBPACK_IMPORTED_MODULE_0__["getWithExpiry"])('collection_page', 0)
  },
  installedDependencies: false,
  // used when deciding should or not reload page after importing the template
  isImportToAppend: true,
  // append to or replace the current page content for importing
  tour: {
    isOpen: false,
    activeButtonGroup: null,
    isPreviewVisible: false
  },
  challenge: {
    isOpen: false,
    currentStep: Object(_helper__WEBPACK_IMPORTED_MODULE_0__["loadChallengeStep"])(),
    tooltipRect: {},
    finalStatus: '',
    passed: Object(_helper__WEBPACK_IMPORTED_MODULE_0__["getWithExpiry"])('reduxChallengePassed', false),
    listExpanded: true
  },
  plugins: {},
  importingTemplate: null,
  activateDialog: false
};
const reducer = (state = initialState, action) => {
  switch (action.type) {
    case 'SET_LIBRARY':
      if (!action.library.dependencies) return state;
      redux_templates.supported_plugins = action.library.plugins;
      const dependencies = Object(_helper__WEBPACK_IMPORTED_MODULE_0__["getDefaultDependencies"])(Object.keys(action.library.dependencies));
      const parsedSection = Object(_helper__WEBPACK_IMPORTED_MODULE_0__["parseSectionData"])(action.library.sections);
      const parsedPage = Object(_helper__WEBPACK_IMPORTED_MODULE_0__["parsePageData"])(action.library.pages);
      const parsedCollection = Object(_helper__WEBPACK_IMPORTED_MODULE_0__["parseCollectionData"])(action.library);
      redux_templates.stats = {
        'dependencies': Object.keys(action.library.dependencies).length,
        'pages': Object.keys(action.library.pages).length,
        'sections': Object.keys(action.library.sections).length,
        'collections': Object.keys(action.library.collections).length
      };
      return _objectSpread(_objectSpread({}, state), {}, {
        loading: false,
        library: action.library,
        section: _objectSpread(_objectSpread(_objectSpread({}, state.section), parsedSection), {}, {
          dependencyFilters: Object(_helper__WEBPACK_IMPORTED_MODULE_0__["getWithExpiry"])('section_plugin') ? Object(_helper__WEBPACK_IMPORTED_MODULE_0__["getWithExpiry"])('section_plugin') : dependencies
        }),
        page: _objectSpread(_objectSpread(_objectSpread({}, state.page), parsedPage), {}, {
          dependencyFilters: Object(_helper__WEBPACK_IMPORTED_MODULE_0__["getWithExpiry"])('page_plugin') ? Object(_helper__WEBPACK_IMPORTED_MODULE_0__["getWithExpiry"])('page_plugin') : dependencies
        }),
        collection: _objectSpread(_objectSpread(_objectSpread({}, state.collection), parsedCollection), {}, {
          dependencyFilters: Object(_helper__WEBPACK_IMPORTED_MODULE_0__["getWithExpiry"])('collection_plugin') ? Object(_helper__WEBPACK_IMPORTED_MODULE_0__["getWithExpiry"])('collection_plugin') : dependencies
        })
      });

    case 'SET_ACTIVE_CATEGORY':
      Object(_helper__WEBPACK_IMPORTED_MODULE_0__["setWithExpiry"])(state.activeItemType + '_category', action.activeCategory, EXIPRY_TIME);
      Object(_helper__WEBPACK_IMPORTED_MODULE_0__["setWithExpiry"])(state.activeItemType + '_page', 0, EXIPRY_TIME);
      return _objectSpread(_objectSpread({}, state), {}, {
        [state.activeItemType]: _objectSpread(_objectSpread({}, state[state.activeItemType]), {}, {
          currentPage: 0,
          activeCategory: action.activeCategory
        })
      });

    case 'SET_SEARCH_CONTEXT':
      Object(_helper__WEBPACK_IMPORTED_MODULE_0__["setWithExpiry"])(state.activeItemType + '_search', action.searchContext, EXIPRY_TIME);
      Object(_helper__WEBPACK_IMPORTED_MODULE_0__["setWithExpiry"])(state.activeItemType + '_page', 0, EXIPRY_TIME);
      return _objectSpread(_objectSpread({}, state), {}, {
        [state.activeItemType]: _objectSpread(_objectSpread({}, state[state.activeItemType]), {}, {
          currentPage: 0,
          searchContext: action.searchContext
        })
      });

    case 'SET_ACTIVE_PRICE_FILTER':
      Object(_helper__WEBPACK_IMPORTED_MODULE_0__["setWithExpiry"])(state.activeItemType + '_price', action.activePriceFilter, EXIPRY_TIME);
      Object(_helper__WEBPACK_IMPORTED_MODULE_0__["setWithExpiry"])(state.activeItemType + '_page', 0, EXIPRY_TIME);
      return _objectSpread(_objectSpread({}, state), {}, {
        [state.activeItemType]: _objectSpread(_objectSpread({}, state[state.activeItemType]), {}, {
          currentPage: 0,
          priceFilter: action.activePriceFilter
        })
      });

    case 'SET_ACTIVE_ITEM_TYPE':
      Object(_helper__WEBPACK_IMPORTED_MODULE_0__["setWithExpiry"])('itemType', action.activeItemType, EXIPRY_TIME);
      return _objectSpread(_objectSpread({}, state), {}, {
        activeItemType: action.activeItemType
      });

    case 'SET_DEPENDENCY_FILTERS':
      Object(_helper__WEBPACK_IMPORTED_MODULE_0__["setWithExpiry"])(state.activeItemType + '_plugin', action.dependencyFilters, EXIPRY_TIME);
      Object(_helper__WEBPACK_IMPORTED_MODULE_0__["setWithExpiry"])(state.activeItemType + '_page', 0, EXIPRY_TIME);
      return _objectSpread(_objectSpread({}, state), {}, {
        [state.activeItemType]: _objectSpread(_objectSpread({}, state[state.activeItemType]), {}, {
          currentPage: 0,
          dependencyFilters: action.dependencyFilters
        })
      });

    case 'SET_SORT_BY':
      Object(_helper__WEBPACK_IMPORTED_MODULE_0__["setWithExpiry"])(state.activeItemType + '_sort', action.sortBy, EXIPRY_TIME);
      Object(_helper__WEBPACK_IMPORTED_MODULE_0__["setWithExpiry"])(state.activeItemType + '_page', 0, EXIPRY_TIME);
      return _objectSpread(_objectSpread({}, state), {}, {
        [state.activeItemType]: _objectSpread(_objectSpread({}, state[state.activeItemType]), {}, {
          currentPage: 0,
          sortBy: action.sortBy
        })
      });

    case 'SET_CURRENT_PAGE':
      Object(_helper__WEBPACK_IMPORTED_MODULE_0__["setWithExpiry"])(state.activeItemType + '_page', action.currentPage, EXIPRY_TIME);
      return _objectSpread(_objectSpread({}, state), {}, {
        [state.activeItemType]: _objectSpread(_objectSpread({}, state[state.activeItemType]), {}, {
          currentPage: action.currentPage
        })
      });

    case 'SET_ACTIVE_COLLECTION':
      return _objectSpread(_objectSpread({}, state), {}, {
        collection: _objectSpread(_objectSpread({}, state.collection), {}, {
          activeCollection: action.activeCollection
        })
      });

    case 'SET_LOADING':
      return _objectSpread(_objectSpread({}, state), {}, {
        loading: action.loading
      });

    case 'SET_COLUMNS':
      Object(_helper__WEBPACK_IMPORTED_MODULE_0__["setWithExpiry"])('column', action.columns, EXIPRY_TIME);
      return _objectSpread(_objectSpread({}, state), {}, {
        columns: action.columns
      });

    case 'APPEND_ERROR_MESSAGE':
      return _objectSpread(_objectSpread({}, state), {}, {
        errorMessages: state.errorMessages.concat([action.errorMessage])
      });

    case 'DISCARD_ALL_ERROR_MESSAGES':
      return _objectSpread(_objectSpread({}, state), {}, {
        errorMessages: []
      });

    case 'SET_INSTALLED_DEPENDENCIES':
      return _objectSpread(_objectSpread({}, state), {}, {
        installedDependencies: action.installedDependencies
      });

    case 'SET_TOUR_OPEN':
      return _objectSpread(_objectSpread({}, state), {}, {
        tour: _objectSpread(_objectSpread({}, state.tour), {}, {
          isOpen: action.isTourOpen
        })
      });

    case 'SET_TOUR_ACTIVE_BUTTON_GROUP':
      return _objectSpread(_objectSpread({}, state), {}, {
        tour: _objectSpread(_objectSpread({}, state.tour), {}, {
          activeButtonGroup: action.data
        })
      });

    case 'SET_PREVIEW_VISIBLE':
      return _objectSpread(_objectSpread({}, state), {}, {
        tour: _objectSpread(_objectSpread({}, state.tour), {}, {
          isPreviewVisible: action.isVisible
        })
      });

    case 'SET_IMPORTING_TEMPLATE':
      return _objectSpread(_objectSpread({}, state), {}, {
        importingTemplate: action.importingTemplate
      });

    case 'SET_CHALLENGE_STEP':
      Object(_helper__WEBPACK_IMPORTED_MODULE_0__["saveChallengeStep"])(action.data);
      return _objectSpread(_objectSpread({}, state), {}, {
        challenge: _objectSpread(_objectSpread({}, state.challenge), {}, {
          currentStep: action.data
        })
      });

    case 'SET_CHALLENGE_OPEN':
      return _objectSpread(_objectSpread({}, state), {}, {
        challenge: _objectSpread(_objectSpread({}, state.challenge), {}, {
          isOpen: action.data
        })
      });

    case 'SET_CHALLENGE_TOOLTIP_RECT':
      return _objectSpread(_objectSpread({}, state), {}, {
        challenge: _objectSpread(_objectSpread({}, state.challenge), {}, {
          tooltipRect: action.data
        })
      });

    case 'SET_CHALLENGE_FINAL_STATUS':
      return _objectSpread(_objectSpread({}, state), {}, {
        challenge: _objectSpread(_objectSpread({}, state.challenge), {}, {
          finalStatus: action.data
        })
      });

    case 'SET_CHALLENGE_PASSED':
      Object(_helper__WEBPACK_IMPORTED_MODULE_0__["setWithExpiry"])('reduxChallengePassed', action.data, EXIPRY_TIME);
      return _objectSpread(_objectSpread({}, state), {}, {
        challenge: _objectSpread(_objectSpread({}, state.challenge), {}, {
          passed: action.data
        })
      });

    case 'SET_CHALLENGE_LIST_EXPANDED':
      return _objectSpread(_objectSpread({}, state), {}, {
        challenge: _objectSpread(_objectSpread({}, state.challenge), {}, {
          listExpanded: action.data
        })
      });

    case 'SET_ACTIVATE_DIALOG_DISPLAY':
      return _objectSpread(_objectSpread({}, state), {}, {
        activateDialog: action.data
      });

    case 'SET_IMPORT_TO_APPEND':
      return _objectSpread(_objectSpread({}, state), {}, {
        isImportToAppend: action.data
      });

    case 'SET_DEPENDENCY_FILTER_RULE':
      Object(_helper__WEBPACK_IMPORTED_MODULE_0__["setWithExpiry"])(state.activeItemType + '_filterRule', action.data, EXIPRY_TIME);
      return _objectSpread(_objectSpread({}, state), {}, {
        [state.activeItemType]: _objectSpread(_objectSpread({}, state[state.activeItemType]), {}, {
          dependencyFilterRule: action.data
        })
      });
    // Dependency Shortcut click handler: All, None, Installed and Reset

    case 'SELECT_DEPENDENCIES':
      const types = ['section', 'page', 'collection'];
      let atomHandler;

      switch (action.data) {
        case 'all':
        case 'none':
          const newValue = action.data === 'all';

          atomHandler = plugins => plugins.filter(plugin => [_helper__WEBPACK_IMPORTED_MODULE_0__["NONE_KEY"], 'gutenberghub.com', 'shareablock.com'].includes(plugin) === false).reduce((acc, key) => {
            return _objectSpread(_objectSpread({}, acc), {}, {
              [key]: {
                value: newValue,
                disabled: false
              }
            });
          }, {
            [_helper__WEBPACK_IMPORTED_MODULE_0__["NONE_KEY"]]: {
              value: true,
              disabled: false
            },
            'gutenberghub.com': {
              value: true,
              disabled: false
            },
            'shareablock.com': {
              value: true,
              disabled: false
            }
          });

          break;

        case 'installed':
          atomHandler = plugins => Object(_helper__WEBPACK_IMPORTED_MODULE_0__["getInstalledDependencies"])(plugins);

          break;

        default:
          atomHandler = plugins => Object(_helper__WEBPACK_IMPORTED_MODULE_0__["getDefaultDependencies"])(plugins);

          break;
      }

      const filtered = types.reduce((acc, cur) => {
        // save to the local storage as well
        Object(_helper__WEBPACK_IMPORTED_MODULE_0__["setWithExpiry"])(cur + '_plugin', _objectSpread(_objectSpread({}, state[cur].dependencyFilters), atomHandler(state[cur].wholePlugins)), EXIPRY_TIME);
        return _objectSpread(_objectSpread({}, acc), {}, {
          [cur]: _objectSpread(_objectSpread({}, state[cur]), {}, {
            searchContext: '',
            dependencyFilterRule: cur !== 'collection',
            // We must always use false for collection to get template kits to work.
            dependencyFilters: _objectSpread(_objectSpread({}, state[cur].dependencyFilters), atomHandler(state[cur].wholePlugins))
          })
        });
      }, {});
      return _objectSpread(_objectSpread({}, state), filtered);

    case 'CLEAR_SEARCH':
      return _objectSpread(_objectSpread({}, state), {}, {
        section: _objectSpread(_objectSpread({}, state.section), {}, {
          searchContext: ''
        }),
        page: _objectSpread(_objectSpread({}, state.page), {}, {
          searchContext: ''
        }),
        collection: _objectSpread(_objectSpread({}, state.collection), {}, {
          searchContext: ''
        })
      });

    case 'CLEAR_STATE':
      return _objectSpread(_objectSpread({}, state), {}, {
        section: _objectSpread(_objectSpread({}, state.section), {}, {
          priceFilter: '',
          activeCategory: '',
          searchContext: ''
        }),
        page: _objectSpread(_objectSpread({}, state.page), {}, {
          priceFilter: '',
          activeCategory: '',
          searchContext: ''
        }),
        collection: _objectSpread(_objectSpread({}, state.collection), {}, {
          priceFilter: '',
          activeCategory: '',
          searchContext: ''
        })
      });
  }

  return state;
};

/***/ }),

/***/ "@wordpress/block-editor":
/*!*********************************!*\
  !*** external "wp.blockEditor" ***!
  \*********************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = wp.blockEditor;

/***/ }),

/***/ "@wordpress/blocks":
/*!****************************!*\
  !*** external "wp.blocks" ***!
  \****************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = wp.blocks;

/***/ }),

/***/ "@wordpress/components":
/*!********************************!*\
  !*** external "wp.components" ***!
  \********************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = wp.components;

/***/ }),

/***/ "@wordpress/compose":
/*!*****************************!*\
  !*** external "wp.compose" ***!
  \*****************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = wp.compose;

/***/ }),

/***/ "@wordpress/data":
/*!**************************!*\
  !*** external "wp.data" ***!
  \**************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = wp.data;

/***/ }),

/***/ "@wordpress/edit-post":
/*!******************************!*\
  !*** external "wp.editPost" ***!
  \******************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = wp.editPost;

/***/ }),

/***/ "@wordpress/element":
/*!*****************************!*\
  !*** external "wp.element" ***!
  \*****************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = wp.element;

/***/ }),

/***/ "@wordpress/hooks":
/*!***************************!*\
  !*** external "wp.hooks" ***!
  \***************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = wp.hooks;

/***/ }),

/***/ "@wordpress/i18n":
/*!**************************!*\
  !*** external "wp.i18n" ***!
  \**************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = wp.i18n;

/***/ }),

/***/ "lodash":
/*!*************************!*\
  !*** external "lodash" ***!
  \*************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = lodash;

/***/ }),

/***/ "react":
/*!************************!*\
  !*** external "React" ***!
  \************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = React;

/***/ }),

/***/ "react-dom":
/*!***************************!*\
  !*** external "ReactDOM" ***!
  \***************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = ReactDOM;

/***/ })

/******/ });
//# sourceMappingURL=redux-templates.js.map
