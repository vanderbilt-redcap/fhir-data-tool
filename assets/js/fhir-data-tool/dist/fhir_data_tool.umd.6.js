((typeof self !== 'undefined' ? self : this)["webpackJsonpfhir_data_tool"] = (typeof self !== 'undefined' ? self : this)["webpackJsonpfhir_data_tool"] || []).push([[6],{

/***/ "5991":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"e6a73774-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/endpoints/forms/MedicationOrderFields.vue?vue&type=template&id=854920bc&scoped=true&
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticClass:"medicationorder-params"},_vm._l((_vm.status_list),function(status,index){return _c('div',{key:index},[_c('label',{attrs:{"for":("status-" + index)}},[_vm._v(_vm._s(status))]),_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.checked),expression:"checked"}],attrs:{"type":"checkbox","name":"status","id":("status-" + index)},domProps:{"value":status,"checked":Array.isArray(_vm.checked)?_vm._i(_vm.checked,status)>-1:(_vm.checked)},on:{"change":function($event){var $$a=_vm.checked,$$el=$event.target,$$c=$$el.checked?(true):(false);if(Array.isArray($$a)){var $$v=status,$$i=_vm._i($$a,$$v);if($$el.checked){$$i<0&&(_vm.checked=$$a.concat([$$v]))}else{$$i>-1&&(_vm.checked=$$a.slice(0,$$i).concat($$a.slice($$i+1)))}}else{_vm.checked=$$c}}}})])}),0)}
var staticRenderFns = []


// CONCATENATED MODULE: ./src/components/endpoints/forms/MedicationOrderFields.vue?vue&type=template&id=854920bc&scoped=true&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--12-0!./node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/endpoints/forms/MedicationOrderFields.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
/* harmony default export */ var MedicationOrderFieldsvue_type_script_lang_js_ = ({
  name: 'MedicationOrderFields',
  data: function data() {
    return {
      status_list: ['active', 'completed', 'on-hold', 'stopped'],
      checked: []
    };
  },
  created: function created() {
    var params = this.$store.state.endpoint.params;

    var _ref = params.MedicationOrder || {},
        _ref$status = _ref.status,
        status = _ref$status === void 0 ? [] : _ref$status;

    this.checked = status;
  },
  watch: {
    checked: function checked() {
      var value = {
        status: this.checked
      };
      /* this.checked.forEach(status => params.push(`status=${status}`) )
      const value = params.join('&') */

      this.$store.dispatch('endpoint/setParam', {
        key: 'MedicationOrder',
        value: value
      });
    }
  },
  computed: {
    /* checked: {
      get() {
        const params = this.$store.state.endpoint.params
        const checked = params.MedicationOrder || []
        return checked
      },
      set(value) {
        this.$store.dispatch('endpoint/setParam',{key:'MedicationOrder', value })
      },
    } */
  },
  methods: {}
});
// CONCATENATED MODULE: ./src/components/endpoints/forms/MedicationOrderFields.vue?vue&type=script&lang=js&
 /* harmony default export */ var forms_MedicationOrderFieldsvue_type_script_lang_js_ = (MedicationOrderFieldsvue_type_script_lang_js_); 
// EXTERNAL MODULE: ./node_modules/vue-loader/lib/runtime/componentNormalizer.js
var componentNormalizer = __webpack_require__("2877");

// CONCATENATED MODULE: ./src/components/endpoints/forms/MedicationOrderFields.vue





/* normalize component */

var component = Object(componentNormalizer["a" /* default */])(
  forms_MedicationOrderFieldsvue_type_script_lang_js_,
  render,
  staticRenderFns,
  false,
  null,
  "854920bc",
  null
  
)

/* harmony default export */ var MedicationOrderFields = __webpack_exports__["default"] = (component.exports);

/***/ })

}]);
//# sourceMappingURL=fhir_data_tool.umd.6.js.map