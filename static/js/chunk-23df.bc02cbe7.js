(window.webpackJsonp=window.webpackJsonp||[]).push([["chunk-23df"],{KLDP:function(t,s,e){},dQBG:function(t,s,e){"use strict";e.r(s);var a=e("gDS+"),r=e.n(a),o=e("14Xm"),i=e.n(o),n=e("D3Ub"),c=e.n(n),l={name:"GoodsDetails",components:{},data:function(){return{loading:null,id:"",mchId:"0",store:{},goods:{},comment:{},commentType:0,commentParams:{currentPage:1,limit:10},attrIdx:0,currentSmallShowImg:0,attrSelected:{attrIdx0:0,attrIdx1:0,attrIdx2:0,attrIdx3:0,attrIdx4:0,attrIdx:0,attrId:"",count:1},activeName:"first"}},computed:{},watch:{commentType:function(){this.loadGoodsComments()}},mounted:function(){this.loading=this.$loading({lock:!0,text:"Loading..."}),this.id=this.$route.query.id||"",this.mchId=this.$route.query.mch_id||"",this.loadGoodsDetails(),this.loadGoodsComments()},methods:{loadGoodsDetails:function(){var t=this;c()(i.a.mark(function s(){var e,a;return i.a.wrap(function(s){for(;;)switch(s.prev=s.next){case 0:return s.next=2,t.$service.webMallLoadGoodsDetails({id:t.id,mch_id:t.mchId});case 2:if(0===(e=s.sent).code){for(t.store=e.data.store,t.goods=e.data.goods,a=0;a<t.goods.attr_groups.length;a++)t.attrSelected["attrIdx"+a]=0;t.attrSelected.attrId=t.goods.attr[0].id}else t.$message.error(e.msg);t.loading.close();case 5:case"end":return s.stop()}},s,t)}))()},loadGoodsComments:function(){var t=this;c()(i.a.mark(function s(){var e;return i.a.wrap(function(s){for(;;)switch(s.prev=s.next){case 0:return s.next=2,t.$service.webMallLoadGoodsComments({goods_id:t.id,status:t.commentType,page:t.commentParams.currentPage,limit:t.commentParams.limit});case 2:0===(e=s.sent).code?t.comment=e.data:t.$message.error(e.msg);case 4:case"end":return s.stop()}},s,t)}))()},addToGoodsCart:function(){var t=this;this.$service.getToken()?c()(i.a.mark(function s(){var e;return i.a.wrap(function(s){for(;;)switch(s.prev=s.next){case 0:return s.next=2,t.$service.webMallAddToCart({goods_id:t.id,attr:t.attrSelected.attrId,num:t.attrSelected.count});case 2:0===(e=s.sent).code?t.$message.success("成功加入购物车~"):t.$message.error(e.msg);case 4:case"end":return s.stop()}},s,t)}))():this.$router.push("/login")},toPreviewOrder:function(){if(this.$service.getToken()){var t={list:[],address_id:0},s=[];s.push({id:this.id,num:this.attrSelected.count,goods_attr_id:this.attrSelected.attrId,cart_id:0}),s.length>0&&t.list.push({mch_id:this.store.mch_id,use_integral:0,user_coupon_id:0,goods_list:s}),this.$router.push({path:"/goods/orderInfo",query:{form_data:r()(t)}})}else this.$router.push("/login")},bindAttrClick:function(t,s){this.attrSelected["attrIdx"+t]=s;for(var e=this.goods.attr_groups,a=this.goods.attr,r=a.length,o=e.length,i=null,n=null,c=0;c<r;c++){for(var l=0;l<o&&a[c].attr_list[l].attr_id===e[l].attr_list[this.attrSelected["attrIdx"+l]].attr_id;l++)l===o-1&&(i=c,n=a[c].id);if(null!==i)break}console.log(i,n),this.attrSelected.attrIdx=i,this.attrSelected.attrId=n},bindScroll:function(t){"right"===t&&(this.currentSmallShowImg<this.goods.pic_url.length-1&&this.currentSmallShowImg++,this.currentSmallShowImg>4&&(this.$refs.smallShowList.scrollLeft=62*(this.currentSmallShowImg-4)+"")),"left"===t&&(0!==this.currentSmallShowImg&&this.currentSmallShowImg--,this.currentSmallShowImg<this.goods.pic_url.length-4&&(this.$refs.smallShowList.scrollLeft=62*this.currentSmallShowImg+""))},handleCurrentChange:function(t){this.commentParams.currentPage=t}}},d=(e("vIcB"),e("KHd+")),m=Object(d.a)(l,function(){var t=this,s=t.$createElement,e=t._self._c||s;return e("div",{staticClass:"app-content"},[e("div",{staticClass:"app-container"},[e("el-breadcrumb",{staticClass:"breadcrumb",attrs:{"separator-class":"el-icon-arrow-right"}},[e("el-breadcrumb-item",{attrs:{to:{path:"/"}}},[t._v("首页")]),t._v(" "),e("el-breadcrumb-item",{attrs:{to:{path:"/goods/list"}}},[t._v("商品分类")]),t._v(" "),e("el-breadcrumb-item",[t._v(t._s(t.goods.name))])],1),t._v(" "),e("div",{staticClass:"goods-intro"},[e("div",{staticClass:"preview"},[e("el-image",{attrs:{src:t.goods.pic_url[t.currentSmallShowImg].pic_url,fit:"cover"}}),t._v(" "),e("div",{staticClass:"small-show"},[e("i",{staticClass:"el-icon-arrow-left",on:{click:function(s){t.bindScroll("left")}}}),t._v(" "),e("div",{ref:"smallShowList",staticClass:"list"},t._l(t.goods.pic_url,function(s,a){return e("el-image",{key:a,class:{active:a===t.currentSmallShowImg},attrs:{src:s.pic_url,fit:"cover"},on:{mouseenter:function(s){t.currentSmallShowImg=a}}})})),t._v(" "),e("i",{staticClass:"el-icon-arrow-right",on:{click:function(s){t.bindScroll("right")}}})])],1),t._v(" "),e("div",{staticClass:"info-box"},[e("div",{staticClass:"sku-name"},[t._v(t._s(t.goods.name))]),t._v(" "),e("div",{staticClass:"price-box"},[e("div",{staticClass:"label"},[t._v("价格")]),t._v(" "),e("span",{staticClass:"price"},[t._v(t._s(t.goods.attr[t.attrSelected.attrIdx].price)+"\n            "),e("span",{staticClass:"old-price"},[t._v("￥"+t._s(t.goods.original_price))])])]),t._v(" "),e("div",{staticClass:"meta"},[t.goods.express?e("div",{staticClass:"metatit"},[t._v("运费：￥"+t._s(t.goods.express))]):e("div",{staticClass:"metatit"},[t._v("运费：包邮")]),t._v(" "),e("div",{staticClass:"metatit"},[t._v("销量："+t._s(t.goods.sales))]),t._v(" "),e("div",{staticClass:"metatit"},[t._v("评价：284")])]),t._v(" "),t._l(t.goods.attr_groups,function(s,a){return e("div",{key:a,staticClass:"info-item info-item-tag-box"},[e("div",{staticClass:"info-item-label"},[t._v(t._s(s.attr_group_name)+"：")]),t._v(" "),e("div",{staticClass:"info-item-content"},t._l(s.attr_list,function(s,r){return e("span",{key:r,staticClass:"button",class:{hl:r===t.attrSelected["attrIdx"+a]},on:{click:function(s){t.bindAttrClick(a,r)}}},[e("span",[t._v(t._s(s.attr_name||" "))])])}))])}),t._v(" "),e("div",{staticClass:"count"},[e("span",[t._v("数量：")]),t._v(" "),e("el-input-number",{attrs:{size:"small"},model:{value:t.attrSelected.count,callback:function(s){t.$set(t.attrSelected,"count",s)},expression:"attrSelected['count']"}})],1),t._v(" "),e("div",{staticClass:"btns"},[e("el-button",{attrs:{type:"primary",plain:""},on:{click:t.toPreviewOrder}},[t._v("立即购买")]),t._v(" "),e("el-button",{attrs:{type:"primary"},on:{click:t.addToGoodsCart}},[t._v("加入购物车")])],1)],2),t._v(" "),e("div",{staticClass:"shop-info"},[e("div",{staticClass:"shop-title"},[t._v("卖家："+t._s(t.store.store_name))]),t._v(" "),e("ul",{staticClass:"info"},[e("li",[e("span",[t._v("商家名称：")]),t._v(" "),e("span",[t._v(t._s(t.store.store_name))])]),t._v(" "),e("li",[e("span",[t._v("地址：")]),t._v(" "),e("span",[t._v(t._s(t.store.address))])]),t._v(" "),e("li",[e("span",[t._v("客服：")]),t._v(" "),e("span",[e("a",{attrs:{href:t.store.web_service_url}},[t._v("咨询")])])]),t._v(" "),e("li",[e("span",[t._v("简介：")]),t._v(" "),e("span",[t._v(t._s(t.store.desc))])])]),t._v(" "),e("el-button",{staticClass:"btn",attrs:{disabled:0===t.store.mch_id,size:"small"},on:{click:function(s){t.$router.push("/shop/index?id="+t.store.mch_id)}}},[t._v("\n          进入店铺\n        ")]),t._v(" "),e("div",{staticClass:"qrcode"},[e("div",[t._v("扫一扫，进入手机店铺")]),t._v(" "),e("el-image",{attrs:{src:t.store.store_qr_code,fit:"cover"}})],1)],1)]),t._v(" "),e("div",{staticClass:"goods-detail"},[e("div",{staticClass:"left"},[e("div",{staticClass:"item"},[e("router-link",{attrs:{to:0===t.store.mch_id?"":"/shop/index?id="+t.store.mch_id}},[e("el-image",{attrs:{src:t.store.logo,fit:"cover"}}),t._v(" "),e("div",[t._v(t._s(t.store.store_name))])],1)],1),t._v(" "),e("div",{staticClass:"item"},[e("span",[t._v("商品数量")]),t._v(" "),e("div",[t._v(t._s(t.store.goods_count))])])]),t._v(" "),e("div",{staticClass:"right"},[e("el-tabs",{model:{value:t.activeName,callback:function(s){t.activeName=s},expression:"activeName"}},[e("el-tab-pane",{attrs:{label:"商品详情",name:"first"}},[e("div",{domProps:{innerHTML:t._s(t.goods.detail)}},[t._v(t._s(t.goods.detail))])]),t._v(" "),e("el-tab-pane",{attrs:{label:"评论（"+t.comment.comment_count[0].count+"）",name:"second"}},[e("el-row",[e("el-radio-group",{model:{value:t.commentType,callback:function(s){t.commentType=s},expression:"commentType"}},t._l(t.comment.comment_count,function(s){return e("el-radio",{key:s.index,attrs:{label:s.index}},[t._v("\n                  "+t._s(s.name)+"（"+t._s(s.count)+"）\n                ")])}))],1),t._v(" "),t._l(t.comment.comments,function(s,a){return e("el-row",{key:a,staticClass:"comment-item"},[e("el-row",{staticClass:"head"},[e("el-col",{attrs:{span:2}},[e("el-image",{staticClass:"avatar",attrs:{src:s.avatar,fit:"cover"}})],1),t._v(" "),e("el-col",{staticClass:"name",attrs:{span:4}},[t._v(t._s(s.nickname))]),t._v(" "),e("el-col",{staticClass:"time",attrs:{span:4}},[t._v(t._s(s.time))]),t._v(" "),e("el-col",{staticClass:"type",attrs:{span:6}},[t._v(t._s(s.status))])],1),t._v(" "),e("p",[t._v(t._s(s.content))]),t._v(" "),e("el-row",t._l(s.pic_url,function(t){return e("el-col",{key:t,attrs:{span:4}},[e("el-image",{staticClass:"img",attrs:{src:t,"preview-src-list":s.pic_url,fit:"cover"}})],1)}))],1)}),t._v(" "),e("el-pagination",{staticStyle:{float:"right"},attrs:{"current-page":t.commentParams.currentPage,"page-size":t.commentParams.limit,total:t.comment.comment_count[0].count,"pager-count":5,background:"",layout:"slot, prev, pager, next"},on:{"current-change":t.handleCurrentChange}},[t._t("default",[e("span",{staticClass:"el-pagination__total"},[t._v("共 "+t._s(t.comment.comment_count[0].count)+" 条")])])],2)],2)],1)],1)])],1)])},[],!1,null,"425117f5",null);m.options.__file="index.vue";s.default=m.exports},"gDS+":function(t,s,e){t.exports={default:e("oh+g"),__esModule:!0}},"oh+g":function(t,s,e){var a=e("WEpk"),r=a.JSON||(a.JSON={stringify:JSON.stringify});t.exports=function(t){return r.stringify.apply(r,arguments)}},vIcB:function(t,s,e){"use strict";var a=e("KLDP");e.n(a).a}}]);