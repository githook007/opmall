(window.webpackJsonp=window.webpackJsonp||[]).push([["chunk-458e"],{"Ae+K":function(s,t,a){},YVqe:function(s,t,a){"use strict";var e=a("yJBn");a.n(e).a},pB0e:function(s,t,a){"use strict";var e=a("Ae+K");a.n(e).a},qFIS:function(s,t,a){"use strict";a.r(t);var e=a("14Xm"),i=a.n(e),n=a("D3Ub"),o=a.n(n),c={name:"ShopIndex",components:{GoodsList:a("vvji").a},data:function(){return{searchParams:{page:1,sort:"",sort_type:"",cat_id:"",catName:"",limit:20,keyword:"",mch_id:""},shopInfo:{},goodsList:[],catList:[],total:0}},mounted:function(){this.searchParams.mch_id=this.$route.query.id||"",this.loadBusinessInfo(),this.loadBusinessGoodsList()},methods:{loadBusinessInfo:function(){var s=this;o()(i.a.mark(function t(){var a;return i.a.wrap(function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,s.$service.webMallLoadBusinessInfo({id:s.searchParams.mch_id});case 2:0===(a=t.sent).code?(s.shopInfo=a.data.detail.store,s.catList=a.data.cat_list):s.$message.error(a.msg);case 4:case"end":return t.stop()}},t,s)}))()},loadBusinessGoodsList:function(){var s=this;o()(i.a.mark(function t(){var a;return i.a.wrap(function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,s.$service.webMallLoadBusinessGoodsList(s.searchParams);case 2:0===(a=t.sent).code?(s.goodsList=a.data.list,s.total=a.data.pagination.totalCount):s.$message.error(a.msg);case 4:case"end":return t.stop()}},t,s)}))()},bindCateSelect:function(s){this.searchParams.cat_id=s.id,this.searchParams.catName=s.name,this.searchParams.page=1,this.loadBusinessGoodsList()}}},r=(a("pB0e"),a("KHd+")),l=Object(r.a)(c,function(){var s=this,t=s.$createElement,a=s._self._c||t;return a("div",{staticClass:"app-content"},[a("el-carousel",{staticClass:"banner-box",attrs:{height:"400px"}},s._l(s.shopInfo.pic_url,function(s,t){return a("el-carousel-item",{key:t},[a("el-image",{staticClass:"banner",attrs:{src:s.pic_url,fill:"cover"}})],1)})),s._v(" "),a("div",{staticClass:"app-container"},[a("div",{staticClass:"left"},[a("div",{staticClass:"shop-info"},[a("div",{staticClass:"shop-title"},[s._v(s._s(s.shopInfo.name))]),s._v(" "),a("ul",{staticClass:"info"},[a("li",[a("span",[s._v("商家名称：")]),s._v(" "),a("span",[s._v(s._s(s.shopInfo.name))])]),s._v(" "),a("li",[a("span",[s._v("电话：")]),s._v(" "),a("span",[s._v(s._s(s.shopInfo.mobile))])]),s._v(" "),a("li",[a("span",[s._v("地址：")]),s._v(" "),a("span",[s._v(s._s(s.shopInfo.address))])]),s._v(" "),a("li",[a("span",[s._v("客服：")]),s._v(" "),a("span",[s._v(s._s(s.shopInfo.web_service_url))])]),s._v(" "),a("li",[a("span",[s._v("简介：")]),s._v(" "),a("span",[s._v(s._s(s.shopInfo.description))])])]),s._v(" "),a("div",{staticClass:"qrcode"},[a("div",[s._v("扫一扫，进入手机店铺")]),s._v(" "),a("el-image",{attrs:{src:s.shopInfo.store_qr_code,fit:"cover"}})],1)]),s._v(" "),a("div",{staticClass:"cate-box"},[a("div",{staticClass:"title"},[s._v("店内分类")]),s._v(" "),a("el-collapse",{attrs:{accordion:""},model:{value:s.activeName,callback:function(t){s.activeName=t},expression:"activeName"}},s._l(s.catList,function(t,e){return a("el-collapse-item",{key:e,attrs:{title:t.name,name:"1"}},[a("ul",s._l(t.child,function(t,e){return a("li",{key:e,on:{click:function(a){s.bindCateSelect({id:t.id,name:t.name})}}},[a("div",{staticClass:"name",class:{active:t.id===s.searchParams.cat_id}},[s._v(s._s(t.name))]),s._v(" "),a("ul",s._l(t.child,function(t,e){return a("li",{key:e,staticClass:"name",class:{active:t.id===s.searchParams.cat_id},on:{click:function(a){a.stopPropagation(),s.bindCateSelect({id:t.id,name:t.name})}}},[s._v(s._s(t.name)+"\n                  ")])}))])}))])}))],1)]),s._v(" "),a("div",{staticClass:"right"},[a("el-breadcrumb",{staticClass:"breadcrumb",attrs:{"separator-class":"el-icon-arrow-right"}},[a("el-breadcrumb-item",{attrs:{to:{path:"/"}}},[s._v("全部商品")]),s._v(" "),a("el-breadcrumb-item",[s._v(s._s(s.searchParams.catName))])],1),s._v(" "),a("goods-list",{attrs:{"goods-list":s.goodsList}}),s._v(" "),a("div",{staticClass:"pagination"},[a("el-pagination",{attrs:{"current-page":s.searchParams.page,"page-size":s.searchParams.limit,total:s.total,"pager-count":5,background:"",layout:"slot, prev, pager, next"},on:{"current-change":s.handleCurrentChange}},[s._t("default",[a("span",{staticClass:"el-pagination__total"},[s._v("共 "+s._s(s.total)+" 件商品")])])],2)],1)],1)])],1)},[],!1,null,"72b513eb",null);l.options.__file="index.vue";t.default=l.exports},vvji:function(s,t,a){"use strict";var e={name:"GoodsListComponents",components:{},props:{goodsList:{type:Array,default:function(){return[]}}},data:function(){return{}},methods:{toDetails:function(s,t){window.open(this.$config.DOMAIN+"/index.html#/goods/details?id="+s+"&mch_id="+t)}}},i=(a("YVqe"),a("KHd+")),n=Object(i.a)(e,function(){var s=this,t=s.$createElement,a=s._self._c||t;return a("ul",{staticClass:"goods-list"},s._l(s.goodsList,function(t,e){return a("li",{key:e,staticClass:"goods-item",on:{click:function(a){s.toDetails(t.id,t.mch_id)}}},[a("div",{staticClass:"goods-img"},[a("el-image",{attrs:{src:t.cover_pic,fit:"fill",alt:""}})],1),s._v(" "),a("p",{staticClass:"goods-name"},[s._v(s._s(t.name))]),s._v(" "),a("div",{staticClass:"goods-info"},[a("div",{staticClass:"price"},[s._v("￥"+s._s(t.original_price))]),s._v(" "),a("div",{staticClass:"sale"},[s._v("销量："+s._s(t.sales))])]),s._v(" "),a("div",{staticClass:"goods-price"},[s._v(s._s(t.price))])])}))},[],!1,null,"3474e012",null);n.options.__file="goodsList.vue";t.a=n.exports},yJBn:function(s,t,a){}}]);