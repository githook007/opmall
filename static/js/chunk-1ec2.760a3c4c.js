(window.webpackJsonp=window.webpackJsonp||[]).push([["chunk-1ec2"],{"/53e":function(e,t,s){"use strict";var r=s("XzIV");s.n(r).a},"FVi+":function(e,t,s){"use strict";s.r(t);var r=s("gDS+"),a=s.n(r),i=s("14Xm"),n=s.n(i),d=s("D3Ub"),o=s.n(d),l={name:"GoodsOrderInfo",components:{OrderHeader:s("XcUV").a},data:function(){return{loading:!1,myAddress:[],provinceList:[],cityList:[],areaList:[],addressFormVisible:!1,addressSelectVisible:!1,addressForm:{name:"",mobile:"",detail:"",provinceCode:"",cityCode:"",areaCode:""},sendWays:["快递配送","到店自提"],sendWay:"快递配送",payWays:["微信支付"],payWay:"微信支付",formData:"",order:{},agreement:!0,orderSn:null}},mounted:function(){this.formData=this.$route.query.form_data||"",this.loadAddressPicker(),this.loadMyAddress(),this.loadOrderInfo()},methods:{showAddressDialog:function(e){this.addressFormVisible=!0,console.log(e),this.addressForm=e?{title:"编辑地址",name:e.name,mobile:e.mobile,detail:e.detail,provinceCode:e.province_id,cityCode:e.city_id,areaCode:e.district_id}:{title:"使用新地址",name:"",mobile:"",detail:"",provinceCode:"",cityCode:"",areaCode:""}},loadOrderInfo:function(){var e=this;this.loading=!0,o()(n.a.mark(function t(){var s;return n.a.wrap(function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,e.$service.webMallGetPreviewOrder({formData:e.formData});case 2:0===(s=t.sent).code?e.order=s.data:e.$message.error(s.msg),e.loading=!1;case 5:case"end":return t.stop()}},t,e)}))()},loadMyAddress:function(){var e=this;o()(n.a.mark(function t(){var s;return n.a.wrap(function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,e.$service.webMallLoadMyAddressList();case 2:0===(s=t.sent).code?e.myAddress=s.data.list:e.$message.error(s.msg);case 4:case"end":return t.stop()}},t,e)}))()},loadAddressPicker:function(){var e=this;o()(n.a.mark(function t(){var s;return n.a.wrap(function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,e.$service.webMallLoadAddressPicker();case 2:s=t.sent,e.provinceList=s.data;case 4:case"end":return t.stop()}},t,e)}))()},provinceSelectChange:function(e){for(var t=0;t<this.provinceList.length;t++)if(this.provinceList[t].id===e){this.cityList=this.provinceList[t].children;break}this.addressForm.provinceCode=e,this.addressForm.cityCode="",this.addressForm.areaCode=""},citySelectChange:function(e){for(var t=0;t<this.cityList.length;t++)if(this.cityList[t].id===e){this.areaList=this.cityList[t].children;break}this.addressForm.cityCode=e,this.addressForm.areaCode=""},setAddress:function(){var e=this;o()(n.a.mark(function t(){var s;return n.a.wrap(function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,e.$service.webMallUpdateAddress(e.addressForm);case 2:0===(s=t.sent).code&&(e.$message.success(s.msg),e.addressFormVisible=!1,e.loadMyAddress());case 4:case"end":return t.stop()}},t,e)}))()},setDefaultAddress:function(e){var t=this;o()(n.a.mark(function s(){var r;return n.a.wrap(function(s){for(;;)switch(s.prev=s.next){case 0:return s.next=2,t.$service.webMallSetDefaultAddress({id:e});case 2:0===(r=s.sent).code?(t.$message.success(r.msg),t.addressFormVisible=!1,t.loadMyAddress()):t.$message.error(r.msg);case 4:case"end":return s.stop()}},s,t)}))()},deleteAddress:function(e){var t=this;this.$confirm("此操作将删除该收货地址, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then(function(){o()(n.a.mark(function s(){var r;return n.a.wrap(function(s){for(;;)switch(s.prev=s.next){case 0:return s.next=2,t.$service.webMallDeleteAddress({id:e});case 2:0===(r=s.sent).code?(t.$message.success(r.msg),t.loadMyAddress()):t.$message.error(r.msg);case 4:case"end":return s.stop()}},s,t)}))()})},bindSelectAddress:function(e){this.order.address=e,this.addressSelectVisible=!1},submitOrder:function(){var e=this;if(this.order.address&&this.order.address.id){var t=JSON.parse(this.formData);t.address_id=this.order.address.id,t.address=this.order.address;for(var s=0;s<this.order.mch_list.length;s++)t.list[s].send_type=this.order.mch_list[s].delivery.send_type,t.list[s].remark=this.order.mch_list[s].remark||"";this.loading=!0,o()(n.a.mark(function s(){var r;return n.a.wrap(function(s){for(;;)switch(s.prev=s.next){case 0:return s.next=2,e.$service.webMallSubmitOrder({formData:a()(t)});case 2:0===(r=s.sent).code?(e.loading=!1,e.$router.push({path:"/goods/orderPay",query:r.data})):e.$message.error(r.msg);case 4:case"end":return s.stop()}},s,e)}))()}else this.$message.error("请选择收货地址")}}},c=(s("GbQ3"),s("/53e"),s("KHd+")),u=Object(c.a)(l,function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("div",{directives:[{name:"loading",rawName:"v-loading",value:e.loading,expression:"loading"}],staticClass:"page"},[s("div",{staticClass:"app-content"},[s("order-header",{attrs:{index:"1"}}),e._v(" "),s("div",{staticClass:"content"},[s("div",{staticClass:"address-box"},[s("h3",[e._v("1. 地址选择")]),e._v(" "),s("el-button",{staticStyle:{"margin-left":"8px"},attrs:{size:"small",type:"primary"},on:{click:function(t){e.addressSelectVisible=!0}}},[e._v("选择收货地址\n        ")]),e._v(" "),e.order.address?s("div",{staticClass:"address"},[s("div",{staticClass:"name"},[s("span",[e._v(e._s(e.order.address.name)+" ")])]),e._v(" "),s("p",{staticClass:"details"},[e._v("\n            "+e._s(e.order.address.province+e.order.address.city+e.order.address.district+e.order.address.detail))]),e._v(" "),s("div",{staticClass:"phone"},[e._v(e._s(e.order.address.mobile))])]):e._e(),e._v(" "),s("el-dialog",{attrs:{visible:e.addressSelectVisible,width:"800px",title:"选择收货地址"},on:{"update:visible":function(t){e.addressSelectVisible=t}}},[s("div",[s("el-button",{staticStyle:{float:"right","margin-bottom":"10px"},attrs:{type:"primary",size:"mini"},on:{click:function(t){e.showAddressDialog()}}},[e._v("使用新地址")]),e._v(" "),s("el-table",{attrs:{data:e.myAddress,border:""}},[s("el-table-column",{attrs:{prop:"address",label:"收货地址","header-align":"center",align:"center","min-width":"220"},scopedSlots:e._u([{key:"default",fn:function(t){return[s("p",{staticStyle:{cursor:"pointer"},on:{click:function(s){e.bindSelectAddress(t.row)}}},[e._v(e._s(t.row.address))])]}}])}),e._v(" "),s("el-table-column",{attrs:{prop:"name",label:"收货人","header-align":"center",align:"center","min-width":"100"}}),e._v(" "),s("el-table-column",{attrs:{prop:"mobile",label:"手机号","header-align":"center",align:"center","min-width":"100"}}),e._v(" "),s("el-table-column",{attrs:{label:"操作","header-align":"center",align:"center","min-width":"180"},scopedSlots:e._u([{key:"default",fn:function(t){return[s("el-button",{attrs:{type:"text"},on:{click:function(s){e.bindSelectAddress(t.row)}}},[e._v("选择")]),e._v(" "),s("el-button",{attrs:{type:"text"},on:{click:function(s){e.showAddressDialog(t.row)}}},[e._v("编辑")]),e._v(" "),"0"===t.row.is_default?s("el-button",{attrs:{type:"text"},on:{click:function(s){e.setDefaultAddress(t.row.id)}}},[e._v("设为默认\n                  ")]):s("el-button",{attrs:{type:"text",disabled:""}},[e._v("默认地址")]),e._v(" "),s("el-button",{attrs:{type:"text"},on:{click:function(s){e.deleteAddress(t.row.id)}}},[e._v("删除")])]}}])})],1)],1),e._v(" "),s("el-dialog",{staticClass:"address-dialog",attrs:{visible:e.addressFormVisible,title:e.addressForm.title,width:"600px","append-to-body":""},on:{"update:visible":function(t){e.addressFormVisible=t}}},[s("el-form",{ref:"addressForm",attrs:{model:e.addressForm,"label-width":"100px"}},[s("el-form-item",{attrs:{label:"收件人"}},[s("el-input",{model:{value:e.addressForm.name,callback:function(t){e.$set(e.addressForm,"name",t)},expression:"addressForm['name']"}})],1),e._v(" "),s("el-form-item",{attrs:{label:"所在地区"}},[1!==e.addressForm.type?s("el-select",{attrs:{placeholder:"请选择省份"},on:{change:e.provinceSelectChange},model:{value:e.addressForm.provinceCode,callback:function(t){e.$set(e.addressForm,"provinceCode",t)},expression:"addressForm['provinceCode']"}},e._l(e.provinceList,function(e){return s("el-option",{key:e.id,attrs:{label:e.name,value:e.id}})})):e._e(),e._v(" "),1!==e.addressForm.type?s("el-select",{attrs:{placeholder:"请选择市"},on:{change:e.citySelectChange},model:{value:e.addressForm.cityCode,callback:function(t){e.$set(e.addressForm,"cityCode",t)},expression:"addressForm['cityCode']"}},e._l(e.cityList,function(e){return s("el-option",{key:e.id,attrs:{label:e.name,value:e.id}})})):e._e(),e._v(" "),1!==e.addressForm.type&&0!=e.areaList.length?s("el-select",{attrs:{placeholder:"请选择区"},model:{value:e.addressForm.areaCode,callback:function(t){e.$set(e.addressForm,"areaCode",t)},expression:"addressForm['areaCode']"}},e._l(e.areaList,function(e){return s("el-option",{key:e.id,attrs:{label:e.name,value:e.id}})})):e._e()],1),e._v(" "),s("el-form-item",{attrs:{label:"详细地址"}},[s("el-input",{attrs:{type:"textarea",rows:"3"},model:{value:e.addressForm.detail,callback:function(t){e.$set(e.addressForm,"detail",t)},expression:"addressForm['detail']"}})],1),e._v(" "),s("el-form-item",{attrs:{label:"手机号码"}},[s("el-input",{model:{value:e.addressForm.mobile,callback:function(t){e.$set(e.addressForm,"mobile",t)},expression:"addressForm['mobile']"}})],1)],1),e._v(" "),s("div",{attrs:{slot:"footer"},slot:"footer"},[s("el-button",{on:{click:function(t){e.addressFormVisible=!1}}},[e._v("取 消")]),e._v(" "),s("el-button",{attrs:{loading:e.loading,type:"primary"},on:{click:e.setAddress}},[e._v("确 定")])],1)],1)],1)],1),e._v(" "),s("div",{staticClass:"goods-box"},[s("h3",[e._v("2. 商品清单")]),e._v(" "),e._l(e.order.mch_list,function(t,r){return s("div",{key:r},[s("el-row",{staticClass:"title"},[e._v("店铺："+e._s(t.mch.name)+"\n            "),s("el-radio-group",{attrs:{size:"small"},model:{value:t.delivery.send_type,callback:function(s){e.$set(t.delivery,"send_type",s)},expression:"item.delivery['send_type']"}},e._l(t.delivery.send_type_list,function(t){return s("el-radio-button",{key:t.value,attrs:{label:t.value}},[e._v("\n                "+e._s(t.name)+"\n              ")])}))],1),e._v(" "),s("el-table",{attrs:{data:t.goods_list,border:""}},[s("el-table-column",{attrs:{prop:"productName","header-align":"center",align:"center","min-width":"100"},scopedSlots:e._u([{key:"default",fn:function(e){return[s("el-image",{staticStyle:{width:"60px",height:"60px"},attrs:{src:e.row.cover_pic,fit:"cover"}})]}}])}),e._v(" "),s("el-table-column",{attrs:{prop:"name",label:"名称","header-align":"center",align:"center","min-width":"200"},scopedSlots:e._u([{key:"default",fn:function(t){return[s("router-link",{attrs:{to:"/goods/details?id="+t.row.id}},[e._v(e._s(t.row.name))])]}}])}),e._v(" "),s("el-table-column",{attrs:{prop:"attr_text",label:"规格信息","header-align":"center",align:"center","min-width":"120"}}),e._v(" "),s("el-table-column",{attrs:{prop:"num",label:"数量","header-align":"center",align:"center","min-width":"100"}}),e._v(" "),s("el-table-column",{attrs:{prop:"unit_price",label:"单价（元）","header-align":"center",align:"center","min-width":"100"}}),e._v(" "),s("el-table-column",{attrs:{prop:"total_price",label:"小计（元）","header-align":"center",align:"center","min-width":"100"}})],1),e._v(" "),s("el-row",{staticClass:"price"},[e._v("总计："),s("span",[e._v(e._s(t.total_price||"0"))]),e._v("元")]),e._v(" "),s("el-input",{attrs:{rows:2,type:"textarea",placeholder:"请输入您的备注~"},model:{value:t.remark,callback:function(s){e.$set(t,"remark",s)},expression:"item['remark']"}})],1)})],2),e._v(" "),s("el-row",{staticClass:"submit"},[e._v("应付款总额："),s("span",[e._v(e._s(e.order.total_price||"0")+"元")]),e._v(" "),s("el-button",{attrs:{disabled:!e.agreement,type:"primary"},on:{click:e.submitOrder}},[e._v("确认订单")])],1)],1)],1)])},[],!1,null,"2d419e34",null);u.options.__file="index.vue";t.default=u.exports},GbQ3:function(e,t,s){"use strict";var r=s("gtiF");s.n(r).a},V2LQ:function(e,t,s){"use strict";var r=s("buCU");s.n(r).a},XcUV:function(e,t,s){"use strict";var r={name:"OrderHeader",props:{index:{type:String,default:"1"}},computed:{}},a=(s("V2LQ"),s("KHd+")),i=Object(a.a)(r,function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("div",{staticClass:"order-header-box"},[s("div",{staticClass:"content"},["1"===e.index?s("div",{staticClass:"title"},[e._v("确认订单")]):e._e(),e._v(" "),"2"===e.index?s("div",{staticClass:"title"},[e._v("付款")]):e._e(),e._v(" "),"3"===e.index?s("div",{staticClass:"title"},[e._v("支付结果")]):e._e(),e._v(" "),s("div",{class:"progress-box step"+e.index},[e._m(0),e._v(" "),e._m(1)])])])},[function(){var e=this.$createElement,t=this._self._c||e;return t("div",{staticClass:"items"},[t("div",{staticClass:"circle"},[this._v("1")]),this._v(" "),t("hr",{staticClass:"line"}),this._v(" "),t("div",{staticClass:"circle"},[this._v("2")]),this._v(" "),t("hr",{staticClass:"line"}),this._v(" "),t("div",{staticClass:"circle"},[this._v("3")])])},function(){var e=this.$createElement,t=this._self._c||e;return t("div",{staticClass:"labels"},[t("div",{staticClass:"label"},[this._v("确认订单")]),this._v(" "),t("div",{staticClass:"label"},[this._v("付款")]),this._v(" "),t("div",{staticClass:"label"},[this._v("支付结果")])])}],!1,null,"62ea8ffc",null);i.options.__file="OrderHeader.vue";t.a=i.exports},XzIV:function(e,t,s){},buCU:function(e,t,s){},"gDS+":function(e,t,s){e.exports={default:s("oh+g"),__esModule:!0}},gtiF:function(e,t,s){},"oh+g":function(e,t,s){var r=s("WEpk"),a=r.JSON||(r.JSON={stringify:JSON.stringify});e.exports=function(e){return a.stringify.apply(a,arguments)}}}]);