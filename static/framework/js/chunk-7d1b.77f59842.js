(window.webpackJsonp=window.webpackJsonp||[]).push([["chunk-7d1b"],{"6hXz":function(t,e,a){"use strict";var i=a("c6aT");a.n(i).a},c6aT:function(t,e,a){},dkuo:function(t,e,a){a("pDBO"),t.exports=a("12G+").Number.isInteger},e9eg:function(t,e,a){t.exports={default:a("dkuo"),__esModule:!0}},i7Ny:function(t,e,a){var i=a("83yQ"),r=Math.floor;t.exports=function(t){return!i(t)&&isFinite(t)&&r(t)===t}},jgnt:function(t,e,a){"use strict";a.r(e);var i=a("e9eg"),r=a.n(i),s={name:"PlatformManage",data:function(){var t=this;return{ImgList:[],uploadImg:"",chooseImg:"",radio1:"不限",radio2:"不限",activeName2:"first",centerDialogVisible:!1,pageSize:0,current_page:0,total:0,text:"添加",search_form:{},is_unlimited:!1,form:{validity_time:new Date,img:"",name:""},loading:!1,showDialog:!1,redirect:void 0,imgLoading:!1,rules:{name:[{required:!0,message:"请输入平台名称",trigger:"blur"}],validity_time:[{validator:function(e,a,i){t.is_unlimited?i():""===a&&i(new Error("请选择有效日期")),i()},type:"date",trigger:"change"}],img:[{required:!0,message:"请选择上传图片",trigger:"blur"}]}}},created:function(){"edit"===this.$route.query.type&&(this.text="编辑",this.id=this.$route.query.id,this.getData()),this.currentChange(1)},destroyed:function(){},methods:{chooseTheImg:function(t){this.form[this.chooseImg]=t,this.centerDialogVisible=!1},chooseYear:function(t){this.currentChange(1)},chooseMonth:function(t){this.currentChange(1)},openUpload:function(t){this.chooseImg=t,this.uploadImg="",this.centerDialogVisible=!0},sureImg:function(){this.form[this.chooseImg]=this.uploadImg,this.centerDialogVisible=!1},getData:function(){var t=this;$http.post("/admin/application/getApp",{id:this.id},"加载中").then(function(e){1===e.result?(t.form=e.data,e.data.validity_time?t.form.validity_time=1e3*e.data.validity_time:(t.is_unlimited=!0,t.form.validity_time=new Date)):e.msg&&""!=e.msg&&t.$message.error(e.msg)}).catch(function(t){console.error(t)})},currentChange:function(t){var e=this;$http.get("/admin/all/list",{page:t,year:this.radio1,month:this.radio2},"加载中").then(function(t){1===t.result?(e.total=t.data.total,e.ImgList=t.data.data,e.current_page=t.data.current_page,e.pageSize=t.data.per_page):(e.list=t.data,t.msg&&""!=t.msg&&e.$message.error(t.msg))}).catch(function(t){console.error(t)})},clearImg:function(t){this.form[t]=""},deleteImg:function(t){var e=this;$http.get("/admin/all/delImg",{id:t}," ").then(function(t){1===t.result?(t.msg&&""!=t.msg&&e.$message.success("系统删除成功"),e.currentChange(1)):t.msg&&""!=t.msg&&e.$message.error(t.msg)}).catch(function(t){console.error(t)})},submitForm:function(t){var e=this;this.$refs[t].validate(function(a){if(!a)return!1;e.is_unlimited?e.form.validity_time=0:r()(e.form.validity_time)?e.form.validity_time=e.form.validity_time/1e3:e.form.validity_time=parseInt(e.form.validity_time.getTime()/1e3);var i="";"edit"===e.$route.query.type?(i="/admin/application/update/"+e.id,e.form.id=e.id):i="/admin/application/add",$http.post(i,e.form,"提交中").then(function(a){1===a.result?(a.msg&&""!=a.msg&&e.$message.success("提交成功！"),e.$refs[t].resetFields(),e.$router.push({path:"/manage/index"})):a.msg&&""!=a.msg&&e.$message.error(a.msg),e.loading=!1}).catch(function(t){e.loading=!1,console.error(t)})})},uploadSuccess:function(t,e){1===t.result?t.data.success?(this.uploadImg=t.data.success,response.msg&&""!=response.msg&&this.$message.success("上传成功！")):this.$message.error(t.data.fail):response.msg&&""!=response.msg&&this.$message.error(response.msg),this.imgLoading=!1},beforeUpload:function(t){this.imgLoading=!0;var e=t.size/1024/1024<4;return e||(this.$message.error("上传图片大小不能超过 4MB!"),this.imgLoading=!1),e}}},o=(a("6hXz"),a("ZrdR")),l=Object(o.a)(s,function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"right"},[a("el-breadcrumb",{attrs:{"separator-class":"el-icon-arrow-right"}},[a("el-breadcrumb-item",[a("router-link",{attrs:{to:"/manage/index"}},[t._v("平台管理")])],1),t._v(" "),a("el-breadcrumb-item",[t._v(t._s(t.text)+"平台")])],1),t._v(" "),a("el-form",{ref:"form",staticStyle:{"margin-top":"30px"},attrs:{model:t.form,rules:t.rules,"label-width":"15%"}},[a("el-form-item",{attrs:{label:"平台名称",prop:"name"}},[a("el-input",{staticStyle:{width:"70%"},attrs:{placeholder:"请输入平台名称"},model:{value:t.form.name,callback:function(e){t.$set(t.form,"name",e)},expression:"form.name"}})],1),t._v(" "),a("el-form-item",{attrs:{label:"平台LOGO",prop:"img"}},[a("el-input",{staticStyle:{width:"70%"},attrs:{disabled:"",placeholder:"请上传平台LOGO"},model:{value:t.form.img,callback:function(e){t.$set(t.form,"img",e)},expression:"form.img"}}),t._v(" "),a("el-button",{attrs:{size:"small",type:"primary"},on:{click:function(e){t.openUpload("img")}}},[t._v("点击上传\n      ")]),t._v(" "),a("div",{staticClass:"avatar-uploader-box"},[t.form.img?a("img",{staticClass:"avatar",attrs:{src:t.form.img}}):t._e(),t._v(" "),a("i",{directives:[{name:"show",rawName:"v-show",value:t.form.img,expression:"form.img"}],staticClass:"el-icon-circle-close",attrs:{title:"点击清除图片"},on:{click:function(e){t.clearImg("img")}}})])],1),t._v(" "),a("el-form-item",{attrs:{label:"有效期",prop:"validity_time"}},[a("el-date-picker",{staticStyle:{width:"50%"},attrs:{disabled:t.is_unlimited,type:"date",placeholder:"请选择有效期"},model:{value:t.form.validity_time,callback:function(e){t.$set(t.form,"validity_time",e)},expression:"form.validity_time"}}),t._v(" "),a("el-checkbox",{staticStyle:{width:"5%"},model:{value:t.is_unlimited,callback:function(e){t.is_unlimited=e},expression:"is_unlimited"}},[t._v("无限制\n      ")])],1),t._v(" "),a("el-form-item",[a("el-button",{attrs:{type:"success"},on:{click:function(e){t.submitForm("form")}}},[t._v("\n        "+t._s(t.text)+"平台\n      ")])],1)],1),t._v(" "),a("el-dialog",{attrs:{visible:t.centerDialogVisible,width:"65%",center:""},on:{"update:visible":function(e){t.centerDialogVisible=e}}},[a("el-tabs",{attrs:{type:"card"},model:{value:t.activeName2,callback:function(e){t.activeName2=e},expression:"activeName2"}},[a("el-tab-pane",{attrs:{label:"上传图片",name:"first"}},[a("div",{directives:[{name:"loading",rawName:"v-loading",value:t.imgLoading,expression:"imgLoading"}],staticClass:"submit_Img",staticStyle:{"text-align":"center"}},[a("el-upload",{staticClass:"avatar-uploader",attrs:{action:"/admin/all/upload",accept:"image/*","show-file-list":!1,"on-success":t.uploadSuccess,"before-upload":t.beforeUpload}},[t.uploadImg?a("div",{staticClass:"avatar_box"},[a("img",{staticClass:"avatar",attrs:{src:t.uploadImg}})]):a("i",{staticClass:"el-icon-plus avatar-uploader-icon"})])],1)]),t._v(" "),a("el-tab-pane",{attrs:{label:"提取网络图片",name:"second"}},[a("el-input",{staticStyle:{width:"90%"},attrs:{placeholder:"请输入网络图片地址"},model:{value:t.uploadImg,callback:function(e){t.uploadImg=e},expression:"uploadImg"}})],1),t._v(" "),a("el-tab-pane",{attrs:{label:"浏览图片",name:"third"}},[a("div",[a("el-radio-group",{attrs:{size:"medium"},on:{change:t.chooseYear},model:{value:t.radio1,callback:function(e){t.radio1=e},expression:"radio1"}},[a("el-radio-button",{attrs:{label:"不限"}}),t._v(" "),a("el-radio-button",{attrs:{label:"2019"}},[t._v("2019年")]),t._v(" "),a("el-radio-button",{attrs:{label:"2018"}},[t._v("2018年")]),t._v(" "),a("el-radio-button",{attrs:{label:"2017"}},[t._v("2017年")]),t._v(" "),a("el-radio-button",{attrs:{label:"2016"}},[t._v("2016年")])],1)],1),t._v(" "),a("div",{staticStyle:{"margin-top":"10px"}},[a("el-radio-group",{attrs:{size:"small"},on:{change:t.chooseMonth},model:{value:t.radio2,callback:function(e){t.radio2=e},expression:"radio2"}},[a("el-radio-button",{attrs:{label:"不限"}}),t._v(" "),a("el-radio-button",{attrs:{label:"1"}},[t._v("1月")]),t._v(" "),a("el-radio-button",{attrs:{label:"2"}},[t._v("2月")]),t._v(" "),a("el-radio-button",{attrs:{label:"3"}},[t._v("3月")]),t._v(" "),a("el-radio-button",{attrs:{label:"4"}},[t._v("4月")]),t._v(" "),a("el-radio-button",{attrs:{label:"5"}},[t._v("5月")]),t._v(" "),a("el-radio-button",{attrs:{label:"6"}},[t._v("6月")]),t._v(" "),a("el-radio-button",{attrs:{label:"7"}},[t._v("7月")]),t._v(" "),a("el-radio-button",{attrs:{label:"8"}},[t._v("8月")]),t._v(" "),a("el-radio-button",{attrs:{label:"9"}},[t._v("9月")]),t._v(" "),a("el-radio-button",{attrs:{label:"10"}},[t._v("10月")]),t._v(" "),a("el-radio-button",{attrs:{label:"11"}},[t._v("11月")]),t._v(" "),a("el-radio-button",{attrs:{label:"12"}},[t._v("12月")])],1)],1),t._v(" "),a("div",{staticClass:"imgList",attrs:{id:"upload-img"}},t._l(t.ImgList,function(e,i){return a("div",{key:i,staticClass:"avatar-uploader-box"},[a("img",{staticClass:"avatar",attrs:{src:e.url},on:{click:function(a){t.chooseTheImg(e.url)}}}),t._v(" "),a("i",{staticClass:"el-icon-circle-close",attrs:{title:"点击清除图片"},on:{click:function(a){t.deleteImg(e.id)}}})])})),t._v(" "),a("el-pagination",{staticStyle:{"margin-top":"10px","text-align":"right"},attrs:{background:"","page-size":t.pageSize,"current-page":t.current_page,total:t.total,layout:"prev, pager, next"},on:{"current-change":t.currentChange,"update:currentPage":function(e){t.current_page=e}}})],1)],1),t._v(" "),a("span",{staticClass:"dialog-footer",attrs:{slot:"footer"},slot:"footer"},[a("el-button",{on:{click:function(e){t.centerDialogVisible=!1}}},[t._v("取 消")]),t._v(" "),a("el-button",{attrs:{type:"primary"},on:{click:t.sureImg}},[t._v("确 定 ")])],1)],1)],1)},[],!1,null,null,null);l.options.__file="add_platform.vue";e.default=l.exports},pDBO:function(t,e,a){var i=a("zikX");i(i.S,"Number",{isInteger:a("i7Ny")})}}]);