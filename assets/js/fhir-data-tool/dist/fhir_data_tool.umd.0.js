((typeof self !== 'undefined' ? self : this)["webpackJsonpfhir_data_tool"] = (typeof self !== 'undefined' ? self : this)["webpackJsonpfhir_data_tool"] || []).push([[0],{

/***/ "9def6":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"e6a73774-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/tables/ResourceTable.vue?vue&type=template&id=38c56c5b&scoped=true&
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{attrs:{"id":"loinc-codes-table"}},[_c('h1',[_vm._v(_vm._s(_vm.title))]),_c('table',{staticClass:"table table-bordered no-table-striped"},[_c('thead',[_c('th',[_vm._v("entry #")]),_vm._l((_vm.headers),function(header,key,index){return _c('th',{key:index},[_vm._v(_vm._s(header))])})],2),_c('tbody',_vm._l((_vm.rows),function(row,index){return _c('tr',{key:index},[_c('td',{style:(_vm.getGroupStyle(row._group))},[_vm._v(_vm._s((index)))]),_vm._l((_vm.headers),function(header,header_key,header_index){return _c('td',{key:header_index,style:(_vm.getGroupStyle(row._group))},[_vm._v(_vm._s((row[header_key])))])})],2)}),0),_c('tfoot')])])}
var staticRenderFns = []


// CONCATENATED MODULE: ./src/components/tables/ResourceTable.vue?vue&type=template&id=38c56c5b&scoped=true&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--12-0!./node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/tables/ResourceTable.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
/* harmony default export */ var ResourceTablevue_type_script_lang_js_ = ({
  name: 'resource-table',
  components: {},
  data: function data() {
    return {
      rotation_delta: 50
    };
  },
  props: {
    title: {
      type: String,
      default: 'Values'
    },
    headers: {
      type: Array | Object,
      default: function _default() {
        return [];
      }
    },
    rows: {
      type: Array,
      default: function _default() {
        return [];
      }
    }
  },
  methods: {
    getGroupStyle: function getGroupStyle(group) {
      if (isNaN(group)) group = 0;
      var style = {
        filter: "hue-rotate(".concat(this.rotation_delta * group, "deg)"),
        backgroundColor: 'rgba(150,0,0,0.2)'
      };
      return style;
    }
  }
});
// CONCATENATED MODULE: ./src/components/tables/ResourceTable.vue?vue&type=script&lang=js&
 /* harmony default export */ var tables_ResourceTablevue_type_script_lang_js_ = (ResourceTablevue_type_script_lang_js_); 
// EXTERNAL MODULE: ./node_modules/vue-loader/lib/runtime/componentNormalizer.js
var componentNormalizer = __webpack_require__("2877");

// CONCATENATED MODULE: ./src/components/tables/ResourceTable.vue





/* normalize component */

var component = Object(componentNormalizer["a" /* default */])(
  tables_ResourceTablevue_type_script_lang_js_,
  render,
  staticRenderFns,
  false,
  null,
  "38c56c5b",
  null
  
)

/* harmony default export */ var ResourceTable = __webpack_exports__["default"] = (component.exports);

/***/ })

}]);
//# sourceMappingURL=fhir_data_tool.umd.0.js.map