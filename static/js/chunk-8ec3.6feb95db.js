(window.webpackJsonp=window.webpackJsonp||[]).push([["chunk-8ec3"],{J8Vy:function(e,t,n){},x6fh:function(e,t,n){"use strict";var a=n("J8Vy");n.n(a).a},zo29:function(e,t,n){"use strict";n.r(t);var a=n("14Xm"),r=n.n(a),s=n("D3Ub"),o=n.n(s),c={name:"Info",components:{},data:function(){return{activeName:"first",form:{}}},mounted:function(){this.loadUserInfo()},methods:{loadUserInfo:function(){var e=this;o()(r.a.mark(function t(){var n;return r.a.wrap(function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,e.$service.webMemberGetInfo();case 2:n=t.sent,e.form=n.data;case 4:case"end":return t.stop()}},t,e)}))()},updateUserInfo:function(){var e=this;o()(r.a.mark(function t(){var n;return r.a.wrap(function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,e.$service.webMallUpdateUser(e.form);case 2:n=t.sent,e.form=n.data,e.$message.success(n.msg);case 5:case"end":return t.stop()}},t,e)}))()}}},i=(n("x6fh"),n("KHd+")),f=Object(i.a)(c,function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",{staticClass:"center-container"},[n("el-tabs",{model:{value:e.activeName,callback:function(t){e.activeName=t},expression:"activeName"}},[n("el-tab-pane",{attrs:{label:"基本信息",name:"first"}},[n("el-form",{ref:"form",attrs:{model:e.form,"label-width":"130px",size:"small"}},[n("el-form-item",{attrs:{label:"用户名称"}},[n("el-input",{model:{value:e.form.nickname,callback:function(t){e.$set(e.form,"nickname",t)},expression:"form.nickname"}})],1),e._v(" "),n("el-form-item",{attrs:{label:"手机号码"}},[n("span",[e._v(e._s(e.form.mobile))])]),e._v(" "),n("el-form-item",{attrs:{label:"绑定微信"}},[n("span",[e._v(e._s(e.form.mobile))])]),e._v(" "),n("el-form-item",[n("el-button",{attrs:{type:"primary"},on:{click:e.updateUserInfo}},[e._v("确认修改")])],1)],1)],1)],1)],1)},[],!1,null,"0a221c3e",null);f.options.__file="info.vue";t.default=f.exports}}]);