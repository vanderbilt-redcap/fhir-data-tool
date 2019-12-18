((typeof self !== 'undefined' ? self : this)["webpackJsonpfhir_data_tool"] = (typeof self !== 'undefined' ? self : this)["webpackJsonpfhir_data_tool"] || []).push([[7],{

/***/ "6175":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"e6a73774-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/endpoints/forms/ObservationFields.vue?vue&type=template&id=059c403e&scoped=true&
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticClass:"observation-params"},[_c('select',{directives:[{name:"model",rawName:"v-model",value:(_vm.current_category),expression:"current_category"}],on:{"change":function($event){var $$selectedVal = Array.prototype.filter.call($event.target.options,function(o){return o.selected}).map(function(o){var val = "_value" in o ? o._value : o.value;return val}); _vm.current_category=$event.target.multiple ? $$selectedVal : $$selectedVal[0]}}},[_c('option',{attrs:{"disabled":"","value":""}},[_vm._v("Please select one")]),_vm._l((_vm.categories),function(category,index){return _c('option',{key:index,domProps:{"value":category}},[_vm._v(_vm._s(category))])})],2)])}
var staticRenderFns = []


// CONCATENATED MODULE: ./src/components/endpoints/forms/ObservationFields.vue?vue&type=template&id=059c403e&scoped=true&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--12-0!./node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/endpoints/forms/ObservationFields.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
/* harmony default export */ var ObservationFieldsvue_type_script_lang_js_ = ({
  name: 'ObservationFields',
  data: function data() {
    return {
      categories: ['laboratory', 'vital-signs', 'social-history']
    };
  },
  computed: {
    current_category: {
      get: function get() {
        var params = this.$store.state.endpoint.params;

        var _ref = params['Observation'] || {},
            category = _ref.category;

        return category;
      },
      set: function set(value) {
        this.$store.dispatch('endpoint/setParam', {
          key: 'Observation',
          value: {
            category: value
          }
        });
      }
    }
  },
  methods: {}
});
// CONCATENATED MODULE: ./src/components/endpoints/forms/ObservationFields.vue?vue&type=script&lang=js&
 /* harmony default export */ var forms_ObservationFieldsvue_type_script_lang_js_ = (ObservationFieldsvue_type_script_lang_js_); 
// EXTERNAL MODULE: ./node_modules/vue-loader/lib/runtime/componentNormalizer.js
var componentNormalizer = __webpack_require__("2877");

// CONCATENATED MODULE: ./src/components/endpoints/forms/ObservationFields.vue





/* normalize component */

var component = Object(componentNormalizer["a" /* default */])(
  forms_ObservationFieldsvue_type_script_lang_js_,
  render,
  staticRenderFns,
  false,
  null,
  "059c403e",
  null
  
)

/* harmony default export */ var ObservationFields = __webpack_exports__["default"] = (component.exports);

/***/ })

}]);
//# sourceMappingURL=fhir_data_tool.umd.7.js.map