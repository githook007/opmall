(window.webpackJsonp=window.webpackJsonp||[]).push([["chunk-77ed","chunk-a4bc"],{"0/f7":function(e,t,a){"use strict";a.r(t);var i=a("gDS+"),l=a.n(i),r=a("14Xm"),s=a.n(r),n=a("P2sY"),o=a.n(n),c=a("D3Ub"),m=a.n(c),u={components:{Agreement:a("nzkD").default},data:function(){return{loading:!1,info:{category:[],setting:{},mch:{review_status:0}},form:{realname:"",mobile:"",wechat:"",username:"",password:"",checkPassword:"",name:"",province_id:"",city_id:[],district_id:"",mch_common_cat_id:"",address:"",service_mobile:"",form_data:""},provinceList:[],cityList:[],areaList:[],agreementDialogVisible:!1,agreementCheck:!1}},mounted:function(){this.loadAddressPicker(),this.loadApplyToBusinessSetting()},methods:{loadApplyToBusinessSetting:function(){var e=this;m()(s.a.mark(function t(){var a,i,l;return s.a.wrap(function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,e.$service.webMallGetApplyToBusinessSetting();case 2:for(a=t.sent,0===(i=a.data).mch.status&&(e.loading=!0),l=0;l<i.setting.form_data.length;l++)"checkbox"===i.setting.form_data[l].key?i.setting.form_data[l].value=[]:i.setting.form_data[l].value="";o()(e.info,i);case 7:case"end":return t.stop()}},t,e)}))()},loadAddressPicker:function(){var e=this;m()(s.a.mark(function t(){var a;return s.a.wrap(function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,e.$service.webMallLoadAddressPicker();case 2:a=t.sent,e.provinceList=a.data;case 4:case"end":return t.stop()}},t,e)}))()},provinceSelectChange:function(e){for(var t=0;t<this.provinceList.length;t++)if(this.provinceList[t].id===e){this.cityList=this.provinceList[t].children;break}this.form.province_id=e,this.form.city_id="",this.form.district_id=""},citySelectChange:function(e){for(var t=0;t<this.cityList.length;t++)if(this.cityList[t].id===e){this.areaList=this.cityList[t].children;break}this.form.city_id=e,this.form.district_id=""},onSubmit:function(){var e=this;this.$refs.form.validate(function(t){if(!t)return console.log("error submit!!"),!1;e.loading=!0,m()(s.a.mark(function t(){var a,i;return s.a.wrap(function(t){for(;;)switch(t.prev=t.next){case 0:return(a=o()({},e.form)).form_data=l()(e.info.setting.form_data),t.next=4,e.$service.webMallApplyToBusiness(a);case 4:i=t.sent,e.loading=!1,0===i.code?(e.$message.success("操作成功~"),e.$router.push("/userCenter")):e.$message.error(i.msg);case 7:case"end":return t.stop()}},t,e)}))()})}}},d=(a("NDbC"),a("KHd+")),f=Object(d.a)(u,function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"app-container"},[a("el-card",{staticClass:"content-edit"},[a("div",{staticClass:"head",attrs:{slot:"header"},slot:"header"},[e._v("申请成为商城入驻商")]),e._v(" "),0===e.info.mch.review_status?e._t("default",[a("div",{staticClass:"text-center"},[a("el-tag",{attrs:{type:"warning"}},[e._v("已申请，请等待审核...")])],1)]):e._e(),e._v(" "),1===e.info.mch.review_status?e._t("default",[a("div",{staticClass:"text-center"},[a("el-tag",{attrs:{type:"primary"}},[e._v("您已是平台入驻商")])],1)]):e._e(),e._v(" "),e._t("default",[a("el-form",{ref:"form",attrs:{model:e.form,"label-width":"130px"}},[a("el-alert",{staticStyle:{"line-height":"20px"},attrs:{closable:!1,title:"基本信息",type:"info"}}),e._v(" "),a("el-form-item",{attrs:{label:"联系人"}},[a("el-input",{model:{value:e.form.realname,callback:function(t){e.$set(e.form,"realname",t)},expression:"form.realname"}})],1),e._v(" "),a("el-form-item",{attrs:{label:"联系电话"}},[a("el-input",{model:{value:e.form.mobile,callback:function(t){e.$set(e.form,"mobile",t)},expression:"form.mobile"}})],1),e._v(" "),a("el-form-item",{attrs:{label:"微信号"}},[a("el-input",{attrs:{maxlength:"18"},model:{value:e.form.wechat,callback:function(t){e.$set(e.form,"wechat",t)},expression:"form.wechat"}})],1),e._v(" "),a("el-alert",{staticStyle:{"line-height":"20px"},attrs:{closable:!1,title:"账号信息",type:"info"}}),e._v(" "),a("el-form-item",{attrs:{label:"账号"}},[a("el-input",{model:{value:e.form.username,callback:function(t){e.$set(e.form,"username",t)},expression:"form.username"}})],1),e._v(" "),a("el-form-item",{attrs:{label:"密码"}},[a("el-input",{model:{value:e.form.password,callback:function(t){e.$set(e.form,"password",t)},expression:"form.password"}})],1),e._v(" "),a("el-form-item",{attrs:{label:"确认密码"}},[a("el-input",{attrs:{maxlength:"18"},model:{value:e.form.checkPassword,callback:function(t){e.$set(e.form,"checkPassword",t)},expression:"form.checkPassword"}})],1),e._v(" "),a("el-alert",{staticStyle:{"line-height":"20px"},attrs:{closable:!1,title:"店铺信息",type:"info"}}),e._v(" "),a("el-form-item",{attrs:{label:"店铺名称"}},[a("el-input",{model:{value:e.form.name,callback:function(t){e.$set(e.form,"name",t)},expression:"form.name"}})],1),e._v(" "),a("el-form-item",{attrs:{label:"所在地区"}},[a("el-select",{attrs:{placeholder:"请选择省份"},on:{change:e.provinceSelectChange},model:{value:e.form.province_id,callback:function(t){e.$set(e.form,"province_id",t)},expression:"form['province_id']"}},e._l(e.provinceList,function(e){return a("el-option",{key:e.id,attrs:{label:e.name,value:e.id}})})),e._v(" "),0!=e.cityList.length?a("el-select",{attrs:{placeholder:"请选择市"},on:{change:e.citySelectChange},model:{value:e.form.city_id,callback:function(t){e.$set(e.form,"city_id",t)},expression:"form['city_id']"}},e._l(e.cityList,function(e){return a("el-option",{key:e.id,attrs:{label:e.name,value:e.id}})})):e._e(),e._v(" "),0!=e.areaList.length?a("el-select",{attrs:{placeholder:"请选择区"},model:{value:e.form.district_id,callback:function(t){e.$set(e.form,"district_id",t)},expression:"form['district_id']"}},e._l(e.areaList,function(e){return a("el-option",{key:e.id,attrs:{label:e.name,value:e.id}})})):e._e()],1),e._v(" "),a("el-form-item",{attrs:{label:"详细地址"}},[a("el-input",{attrs:{rows:3,type:"textarea"},model:{value:e.form.address,callback:function(t){e.$set(e.form,"address",t)},expression:"form.address"}})],1),e._v(" "),a("el-form-item",{attrs:{label:"客服电话"}},[a("el-input",{model:{value:e.form.service_mobile,callback:function(t){e.$set(e.form,"service_mobile",t)},expression:"form.service_mobile"}})],1),e._v(" "),a("el-form-item",{attrs:{label:"所售类目"}},[a("el-select",{attrs:{placeholder:"请选择所售类目"},model:{value:e.form.mch_common_cat_id,callback:function(t){e.$set(e.form,"mch_common_cat_id",t)},expression:"form['mch_common_cat_id']"}},e._l(e.info.category,function(e){return a("el-option",{key:e.id,attrs:{label:e.name,value:e.id}})}))],1),e._v(" "),a("el-alert",{staticStyle:{"line-height":"20px"},attrs:{closable:!1,title:"其他信息",type:"info"}}),e._v(" "),e._t("default",e._l(e.info.setting.form_data,function(t,i){return a("el-form-item",{key:i,attrs:{label:t.name}},["text"===t.key?a("el-input",{model:{value:t.value,callback:function(a){e.$set(t,"value",a)},expression:"item.value"}}):e._e(),e._v(" "),"textarea"===t.key?a("el-input",{attrs:{type:"textarea",rows:"3"},model:{value:t.value,callback:function(a){e.$set(t,"value",a)},expression:"item.value"}}):e._e(),e._v(" "),"date"===t.key?a("el-date-picker",{attrs:{type:"date",placeholder:"选择日期"},model:{value:t.value,callback:function(a){e.$set(t,"value",a)},expression:"item.value"}}):e._e(),e._v(" "),"time"===t.key?a("el-time-picker",{attrs:{"arrow-control":"",placeholder:"选择时间"},model:{value:t.value,callback:function(a){e.$set(t,"value",a)},expression:"item.value"}}):e._e(),e._v(" "),"radio"===t.key?a("el-radio-group",{model:{value:t.value,callback:function(a){e.$set(t,"value",a)},expression:"item.value"}},e._l(t.list,function(t,i){return a("el-radio",{key:i,attrs:{label:t.label}},[e._v(e._s(t.label)+"\n              ")])})):e._e(),e._v(" "),"checkbox"===t.key?a("el-checkbox-group",{model:{value:t.value,callback:function(a){e.$set(t,"value",a)},expression:"item.value"}},e._l(t.list,function(t,i){return a("el-checkbox",{key:i,attrs:{label:t.label}},[e._v("\n                "+e._s(t.label)+"\n              ")])})):e._e(),e._v(" "),"img_upload"===t.key?a("ws-upload-multiple-img",{attrs:{limit:t.num,path:e.form.identityCardPhotoConUrl},model:{value:t.value,callback:function(a){e.$set(t,"value",a)},expression:"item.value"}}):e._e()],1)})),e._v(" "),a("el-form-item",[a("div",{staticClass:"agreement"},[a("el-checkbox",{model:{value:e.agreementCheck,callback:function(t){e.agreementCheck=t},expression:"agreementCheck"}}),e._v(" "),a("div",{staticClass:"info"},[a("span",[e._v("我已阅读并同意")]),e._v(" "),a("el-link",{staticClass:"link",attrs:{to:""},on:{click:function(t){e.agreementDialogVisible=!0}}},[e._v("《入驻商协议》")])],1)],1),e._v(" "),a("el-button",{attrs:{loading:e.loading,type:"primary"},on:{click:e.onSubmit}},[e._v("提交审核")]),e._v(" "),a("router-link",{attrs:{to:"/userCenter"}},[a("el-button",[e._v("返回")])],1)],1)],2)])],2),e._v(" "),a("el-dialog",{staticClass:"agreement-dialog",attrs:{visible:e.agreementDialogVisible,width:"1000px"},on:{"update:visible":function(t){e.agreementDialogVisible=t}}},[a("div",{staticClass:"content"},[a("agreement",{attrs:{context:e.info.setting.desc}})],1)])],1)},[],!1,null,"5edd7018",null);f.options.__file="index.vue";t.default=f.exports},NDbC:function(e,t,a){"use strict";var i=a("gkrn");a.n(i).a},"gDS+":function(e,t,a){e.exports={default:a("oh+g"),__esModule:!0}},gkrn:function(e,t,a){},nzkD:function(e,t,a){"use strict";a.r(t);var i={name:"Agreement",components:{},props:{context:{type:String,default:""}},data:function(){return{}},methods:{}},l=(a("xYFE"),a("KHd+")),r=Object(l.a)(i,function(){var e=this.$createElement,t=this._self._c||e;return t("div",{staticClass:"agreement"},[this._m(0),this._v(" "),t("div",{staticClass:"content"},[t("div",{staticClass:"goods-details ql-editor",domProps:{innerHTML:this._s(this.context)}},[this._v(this._s(this.context))])])])},[function(){var e=this.$createElement,t=this._self._c||e;return t("div",{staticClass:"header"},[t("h2",[this._v("平台用户协议")])])}],!1,null,"efb36b74",null);r.options.__file="agreement.vue";t.default=r.exports},"oh+g":function(e,t,a){var i=a("WEpk"),l=i.JSON||(i.JSON={stringify:JSON.stringify});e.exports=function(e){return l.stringify.apply(l,arguments)}},umP8:function(e,t,a){},xYFE:function(e,t,a){"use strict";var i=a("umP8");a.n(i).a}}]);