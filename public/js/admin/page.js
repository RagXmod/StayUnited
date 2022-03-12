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
/******/ 	return __webpack_require__(__webpack_require__.s = 2);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/admin/page.js":
/*!************************************!*\
  !*** ./resources/js/admin/page.js ***!
  \************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return Page; });
function _classCallCheck(instance, Constructor) {
  if (!(instance instanceof Constructor)) {
    throw new TypeError("Cannot call a class as a function");
  }
}

function _defineProperties(target, props) {
  for (var i = 0; i < props.length; i++) {
    var descriptor = props[i];
    descriptor.enumerable = descriptor.enumerable || false;
    descriptor.configurable = true;
    if ("value" in descriptor) descriptor.writable = true;
    Object.defineProperty(target, descriptor.key, descriptor);
  }
}

function _createClass(Constructor, protoProps, staticProps) {
  if (protoProps) _defineProperties(Constructor.prototype, protoProps);
  if (staticProps) _defineProperties(Constructor, staticProps);
  return Constructor;
} // Import bootstrap dependencies


var Page =
/*#__PURE__*/
function () {
  /*
   * Auto called when creating a new instance
   *
   */
  function Page() {
    _classCallCheck(this, Page);

    this.objUri = {};
    this.itemId = 'null';
    this.targetTr = null;
    this.domIds = {
      page_table: '#page-table',
      del_modal: '#deleteModal',
      del_btn: ':button.del_btn',
      confirm_del: '#confirm_delete'
    };
    this.delModal = jQuery(this.domIds.del_modal);
    this.loadTables();
    this.deleteModal();
    this.confirmDeletion();
  }

  _createClass(Page, [{
    key: "loadTables",
    value: function loadTables() {
      jQuery(this.domIds.page_table).DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        stateSave: true,
        ajax: window.dcmUri['resource'],
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        pageLength: 25,
        columns: [{
          data: 'title',
          name: 'title',
          title: 'Page Name'
        }, {
          data: 'status_label',
          name: 'status_label',
          title: 'Status'
        }, {
          data: 'action',
          name: 'action',
          title: 'Action',
          sortable: false,
          searchable: false,
          class: 'text-center'
        }]
      });
    }
  }, {
    key: "deleteModal",
    value: function deleteModal() {
      var that = this;
      jQuery(this.domIds.page_table).on('click', this.domIds.del_btn, function (evt) {
        var currentRow = jQuery(this).closest('tr');
        var title = currentRow.attr('title');
        that.itemId = currentRow.attr('id'); // Get record ID.

        that.targetTr = jQuery(this).parents('tr');
        that.delModal.on('shown.bs.modal', function () {
          jQuery('#itemId').html(title);
        });
        that.delModal.modal('show');
      });
    }
  }, {
    key: "confirmDeletion",
    value: function confirmDeletion() {
      var that = this;
      jQuery(this.domIds.confirm_del).on('click', function () {
        if (that.itemId) {
          // request delete here..
          axios.delete(window.dcmUri['resource'] + '/' + that.itemId).then(function (data) {
            that.delModal.on('hidden.bs.modal', function () {
              var table = $(that.domIds.page_table).DataTable(); // Select DataTable by ID.

              table.row(that.targetTr).remove().draw(); // Remove record from DataTable.
            });
            that.delModal.modal('hide');
          }).catch(function (err) {
            var errDescription = jQuery('.description');
            var hasError = jQuery('.hasError');
            errDescription.addClass('d-none');
            hasError.removeClass('d-none').html(err.response.data.errors);
            that.delModal.on('hidden.bs.modal', function () {
              errDescription.removeClass('d-none');
              hasError.addClass('d-none');
            });
          });
        }
      });
    }
  }]);

  return Page;
}(); // Once everything is loaded



jQuery(function () {
  // Create a new instance of Page
  window.dcmPage = new Page();
});

/***/ }),

/***/ 2:
/*!******************************************!*\
  !*** multi ./resources/js/admin/page.js ***!
  \******************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /Users/anthonypillos/Desktop/Projects/v2-googelplayappstore/resources/js/admin/page.js */"./resources/js/admin/page.js");


/***/ })

/******/ });