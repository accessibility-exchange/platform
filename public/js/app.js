(self["webpackChunk_accessibility_exchange_platform"] = self["webpackChunk_accessibility_exchange_platform"] || []).push([["/js/app"],{

/***/ "./resources/js/app.js":
/*!*****************************!*\
  !*** ./resources/js/app.js ***!
  \*****************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var alpinejs__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! alpinejs */ "./node_modules/alpinejs/dist/module.esm.js");
/* harmony import */ var _confirmPassword_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./confirmPassword.js */ "./resources/js/confirmPassword.js");
/* harmony import */ var _dateInput_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./dateInput.js */ "./resources/js/dateInput.js");
__webpack_require__(/*! ./bootstrap */ "./resources/js/bootstrap.js");




window.Alpine = alpinejs__WEBPACK_IMPORTED_MODULE_0__["default"];
alpinejs__WEBPACK_IMPORTED_MODULE_0__["default"].data("confirmPassword", _confirmPassword_js__WEBPACK_IMPORTED_MODULE_1__["default"]);
alpinejs__WEBPACK_IMPORTED_MODULE_0__["default"].data("dateInput", _dateInput_js__WEBPACK_IMPORTED_MODULE_2__["default"]);
alpinejs__WEBPACK_IMPORTED_MODULE_0__["default"].start();

/***/ }),

/***/ "./resources/js/bootstrap.js":
/*!***********************************!*\
  !*** ./resources/js/bootstrap.js ***!
  \***********************************/
/***/ ((__unused_webpack_module, __unused_webpack_exports, __webpack_require__) => {

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */
window.axios = __webpack_require__(/*! axios */ "./node_modules/axios/index.js");
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

/***/ }),

/***/ "./resources/js/confirmPassword.js":
/*!*****************************************!*\
  !*** ./resources/js/confirmPassword.js ***!
  \*****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* global axios */
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (function () {
  var confirmedPasswordStatusRoute = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;
  var confirmPasswordRoute = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;
  return {
    routes: {
      confirmedPasswordStatus: confirmedPasswordStatusRoute,
      confirmPassword: confirmPasswordRoute
    },
    locale: false,
    confirmedPassword: false,
    targetForm: false,
    showingModal: false,
    validationError: false,
    init: function init() {
      var _this = this;

      axios.get(this.routes.confirmedPasswordStatus).then(function (response) {
        if (response.data.confirmed) {
          _this.confirmedPassword = true;
        }
      });
    },
    submitForm: function submitForm(event) {
      if (this.confirmedPassword === true) {
        event.target.submit();
      } else {
        this.targetForm = event.target;
        this.showModal();
      }
    },
    showModal: function showModal() {
      var _this2 = this;

      this.showingModal = true;
      var scrollY = window.scrollY;
      document.body.style.position = "fixed";
      document.body.style.top = "-".concat(scrollY, "px");
      this.$nextTick(function () {
        var elems = document.querySelectorAll("a, button, input, select, textarea, [contenteditable]");
        Array.prototype.forEach.call(elems, function (elem) {
          if (!elem.closest(".modal")) {
            elem.setAttribute("tabindex", "-1");
          }
        });

        _this2.$refs.password.focus();
      });
    },
    hideModal: function hideModal() {
      this.showingModal = false;
      var scrollY = document.body.style.top;
      document.body.style.position = "";
      document.body.style.top = "";
      window.scrollTo(0, parseInt(scrollY || "0") * -1);
      var elems = document.querySelectorAll("a, button, input, select, textarea, [contenteditable]");
      Array.prototype.forEach.call(elems, function (elem) {
        if (!elem.closest(".modal")) {
          elem.removeAttribute("tabindex", "-1");
        }
      });
    },
    confirmPassword: function confirmPassword() {
      var _this3 = this;

      axios.post(this.routes.confirmPassword, {
        password: this.$refs.password.value
      }).then(function () {
        _this3.hideModal();

        _this3.confirmPassword = true;
        _this3.validationError = false;

        _this3.targetForm.submit();
      })["catch"](function () {
        _this3.$refs.password.focus();

        _this3.validationError = true;
      });
    },
    cancel: function cancel() {
      this.hideModal();
    }
  };
});

/***/ }),

/***/ "./resources/js/dateInput.js":
/*!***********************************!*\
  !*** ./resources/js/dateInput.js ***!
  \***********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var luxon__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! luxon */ "./node_modules/luxon/build/cjs-browser/luxon.js");
function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }

function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

function _iterableToArrayLimit(arr, i) { var _i = arr == null ? null : typeof Symbol !== "undefined" && arr[Symbol.iterator] || arr["@@iterator"]; if (_i == null) return; var _arr = []; var _n = true; var _d = false; var _s, _e; try { for (_i = _i.call(arr); !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }

function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (function () {
  return {
    dateTime: false,
    date: "",
    year: "",
    month: "",
    day: "",
    error: false,
    componentsToDate: function componentsToDate() {
      if (this.year && this.month && this.day) {
        var dt = luxon__WEBPACK_IMPORTED_MODULE_0__.DateTime.fromObject({
          month: this.month,
          day: this.day,
          year: this.year
        });

        if (dt.isValid) {
          this.date = dt.toSQLDate();
          this.dateTime = dt;
          this.error = false;
        } else {
          this.dateTime = false;
          this.date = "";
          this.error = true;
        }
      } else {
        this.dateTime = false;
        this.date = "";
      }
    },
    dateToComponents: function dateToComponents(date) {
      if (date) {
        var year, month, day;

        var _date$split = date.split("-");

        var _date$split2 = _slicedToArray(_date$split, 3);

        year = _date$split2[0];
        month = _date$split2[1];
        day = _date$split2[2];
        var dt = luxon__WEBPACK_IMPORTED_MODULE_0__.DateTime.fromObject({
          month: month,
          day: day,
          year: year
        });

        if (dt.isValid) {
          this.date = date;
          this.dateTime = dt;
          this.year = year;
          this.month = month;
          this.day = day;
          this.error = false;
        } else {
          this.date = "";
          this.dateTime = false;
          this.error = true;
        }
      }
    }
  };
});

/***/ }),

/***/ "./resources/css/app.css":
/*!*******************************!*\
  !*** ./resources/css/app.css ***!
  \*******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

},
/******/ __webpack_require__ => { // webpackRuntimeModules
/******/ var __webpack_exec__ = (moduleId) => (__webpack_require__(__webpack_require__.s = moduleId))
/******/ __webpack_require__.O(0, ["css/app","/js/vendor"], () => (__webpack_exec__("./resources/js/app.js"), __webpack_exec__("./resources/css/app.css")));
/******/ var __webpack_exports__ = __webpack_require__.O();
/******/ }
]);