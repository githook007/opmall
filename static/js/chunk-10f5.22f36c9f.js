(window.webpackJsonp=window.webpackJsonp||[]).push([["chunk-10f5"],{"2RIg":function(t,e,s){},MvLF:function(t,e,s){"use strict";var a=s("2RIg");s.n(a).a},"rqc/":function(t,e,s){"use strict";s.r(e);var a=s("14Xm"),n=s.n(a),r=s("D3Ub"),i=s.n(r),o={components:{},data:function(){return{loading:!1,activeName:"first",searchParams:{page:1,limit:20,status:"",keyword:"",startTime:"",endTime:""},orderList:[],orderCount:{}}},mounted:function(){this.loadOrderList()},methods:{loadOrderList:function(){var t=this;i()(n.a.mark(function e(){var s;return n.a.wrap(function(e){for(;;)switch(e.prev=e.next){case 0:return t.loading=!0,e.next=3,t.$service.webMallLoadOrderList(t.searchParams);case 3:0===(s=e.sent).code?(t.orderList=s.data.list,t.orderCount=s.data.orderCount):t.$message.error(s.msg),t.loading=!1;case 6:case"end":return e.stop()}},e,t)}))()},bindTypeClick:function(t){this.searchParams.status=t,this.loadOrderList()},bindFinishOrder:function(t){var e=this;i()(n.a.mark(function s(){var a;return n.a.wrap(function(s){for(;;)switch(s.prev=s.next){case 0:return s.next=2,e.$service.webMallOrderFinish({id:t});case 2:0===(a=s.sent).code?(e.$message.success(a.msg),e.loadOrderList()):e.$message.error(a.msg);case 4:case"end":return s.stop()}},s,e)}))()},bindCancelOrder:function(t){var e=this;this.$confirm("此操作将取消该订单, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then(function(){i()(n.a.mark(function s(){var a;return n.a.wrap(function(s){for(;;)switch(s.prev=s.next){case 0:return s.next=2,e.$service.webMallOrderCancel({id:t});case 2:0===(a=s.sent).code?(e.$message.success(a.msg),e.loadOrderList()):e.$message.error(a.msg);case 4:case"end":return s.stop()}},s,e)}))()})},handleCurrentChange:function(t){this.searchParams.page=t,this.loadOrderList()}}},c=(s("MvLF"),s("KHd+")),l=Object(c.a)(o,function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{directives:[{name:"loading",rawName:"v-loading",value:t.loading,expression:"loading"}],staticClass:"center-container"},[s("el-tabs",{model:{value:t.activeName,callback:function(e){t.activeName=e},expression:"activeName"}},[s("el-tab-pane",{attrs:{label:"我的订单",name:"first"}},[s("el-row",{staticClass:"tips"},[s("el-col",{attrs:{span:3}},[t._v("我的交易提醒")]),t._v(" "),s("el-col",{attrs:{span:3}},[s("el-link",{on:{click:function(e){t.bindTypeClick(0)}}},[t._v("全部订单"),s("span",[t._v("（"+t._s(t.orderCount.total_count)+"）")])])],1),t._v(" "),s("el-col",{attrs:{span:3}},[s("el-link",{on:{click:function(e){t.bindTypeClick(1)}}},[t._v("待付款"),s("span",[t._v("（"+t._s(t.orderCount.wait_pay_count)+"）")])])],1),t._v(" "),s("el-col",{attrs:{span:3}},[s("el-link",{on:{click:function(e){t.bindTypeClick(2)}}},[t._v("待发货"),s("span",[t._v("（"+t._s(t.orderCount.wait_deliver_count)+"）")])])],1),t._v(" "),s("el-col",{attrs:{span:3}},[s("el-link",{on:{click:function(e){t.bindTypeClick(3)}}},[t._v("待收货"),s("span",[t._v("（"+t._s(t.orderCount.wait_receive_count)+"）")])])],1),t._v(" "),s("el-col",{attrs:{span:3}},[s("el-link",{on:{click:function(e){t.bindTypeClick(4)}}},[t._v("已成交订单"),s("span",[t._v("（"+t._s(t.orderCount.wait_comment_count)+"）")])])],1)],1),t._v(" "),s("el-row",{staticClass:"tab"},[s("el-col",{attrs:{span:9}},[t._v("产品")]),t._v(" "),s("el-col",{attrs:{span:3}},[t._v("数量")]),t._v(" "),s("el-col",{attrs:{span:3}},[t._v("单价（元）")]),t._v(" "),s("el-col",{attrs:{span:3}},[t._v("小计（元）")]),t._v(" "),s("el-col",{attrs:{span:2}},[t._v("详情")]),t._v(" "),s("el-col",{attrs:{span:2}},[t._v("状态")]),t._v(" "),s("el-col",{attrs:{span:2}},[t._v("操作")])],1),t._v(" "),s("div",{staticClass:"goods-box"},t._l(t.orderList,function(e,a){return s("div",{key:a,staticClass:"goods-list"},[s("div",{staticClass:"shop"},[s("span",[t._v("订单编号：")]),s("span",{staticClass:"order-num"},[t._v(t._s(e.order_no))]),s("span",[t._v("成交时间："+t._s(e.created_at))])]),t._v(" "),s("el-row",{staticClass:"goods-item"},[s("el-col",{staticClass:"goods",attrs:{span:15}},t._l(e.detail,function(e,a){return s("el-row",{key:a,staticClass:"goods-item"},[s("el-col",{staticClass:"text-center",attrs:{span:4}},[s("el-image",{attrs:{src:e.goods_info.pic_url,fit:"cover"}})],1),t._v(" "),s("el-col",{attrs:{span:12}},[s("p",{staticClass:"name"},[s("router-link",{attrs:{to:"/goods/details?id="+e.goods_id}},[t._v(t._s(e.goods_info.name)+"\n                    ")])],1),t._v(" "),s("span",{staticClass:"sub"},[t._v(t._s(e.goods_info.attr_text))])]),t._v(" "),s("el-col",{staticClass:"text-center",attrs:{span:3}},[t._v("\n                  "+t._s(e.num)+"\n                ")]),t._v(" "),s("el-col",{staticClass:"text-center",attrs:{span:5}},[s("span",{staticClass:"price"},[t._v(t._s(e.unit_price))])])],1)})),t._v(" "),s("el-col",{staticClass:"text-center",attrs:{span:3}},[s("span",{staticClass:"price"},[t._v(t._s(e.total_price))])]),t._v(" "),s("el-col",{staticClass:"text-center",attrs:{span:2}},[s("el-link",[s("router-link",{attrs:{to:"./detail?id="+e.id}},[t._v("查看详情")])],1)],1),t._v(" "),s("el-col",{staticClass:"text-center",attrs:{span:2}},[s("div",[t._v(t._s(e.status_text))])]),t._v(" "),s("el-col",{staticClass:"text-center",attrs:{span:2}},[0===e.is_pay?s("el-link",[s("router-link",{attrs:{to:"/goods/orderPay?id="+e.id}},[t._v("立即支付")])],1):t._e(),t._v(" "),1===e.is_send&&0===e.is_confirm?s("el-link",{on:{click:function(s){t.bindFinishOrder(e.id)}}},[t._v("确认收货\n              ")]):t._e(),t._v(" "),0===e.is_pay?s("el-link",{on:{click:function(s){t.bindCancelOrder(e.id)}}},[t._v("取消订单")]):t._e()],1)],1)],1)})),t._v(" "),s("el-pagination",{staticStyle:{float:"right"},attrs:{"current-page":t.searchParams.page,"page-size":t.searchParams.limit,total:t.orderCount.total_count,"pager-count":5,background:"",layout:"slot, prev, pager, next"},on:{"current-change":t.handleCurrentChange}},[t._t("default",[s("span",{staticClass:"el-pagination__total"},[t._v("共 "+t._s(t.orderCount.total_count)+" 条")])])],2)],1)],1)],1)},[],!1,null,"bd590500",null);l.options.__file="index.vue";e.default=l.exports}}]);