(window.webpackJsonp=window.webpackJsonp||[]).push([["chunk-daef"],{"A/dI":function(t,e,n){},GmPy:function(t,e,n){},JTK5:function(t,e,n){"use strict";var s=n("A/dI");n.n(s).a},JfXW:function(t,e,n){},c11S:function(t,e,n){"use strict";var s=n("JfXW");n.n(s).a},ntYl:function(t,e,n){"use strict";n.r(e);var s=n("omC7"),i=n.n(s);var a=n("ETGp"),o={name:"SocialSignin",methods:{wechatHandleClick:function(t){alert("ok")},tencentHandleClick:function(t){alert("ok")}}},r=(n("JTK5"),n("ZrdR")),l=Object(r.a)(o,function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"social-signup-container"},[n("div",{staticClass:"sign-btn",on:{click:function(e){t.wechatHandleClick("wechat")}}},[n("span",{staticClass:"wx-svg-container"},[n("svg-icon",{staticClass:"icon",attrs:{"icon-class":"wechat"}})],1),t._v(" 微信\n  ")]),t._v(" "),n("div",{staticClass:"sign-btn",on:{click:function(e){t.tencentHandleClick("tencent")}}},[n("span",{staticClass:"qq-svg-container"},[n("svg-icon",{staticClass:"icon",attrs:{"icon-class":"qq"}})],1),t._v(" QQ\n  ")])])},[],!1,null,"1267af6f",null);l.options.__file="socialsignin.vue";var c=l.exports,d=n("RYct"),g={name:"Login",components:{LangSelect:a.a,SocialSign:c},data:function(){return{login_info:{},site:{},remember_pwd:!1,loginForm:{username:"",password:""},loginRules:{username:[{required:!0,trigger:"blur",message:"请输入账号"}],password:[{required:!0,trigger:"blur",message:"请输入密码"}]},passwordType:"password",loading:!1,showDialog:!1,redirect:void 0}},watch:{$route:{handler:function(t){this.redirect=t.query&&t.query.redirect},immediate:!0}},created:function(){this.fun.setTitle("登录"),this.getLoginSite()},mounted:function(){this.getlocalStorage()},destroyed:function(){},methods:{getLoginSite:function(){var t=this;$http.get("/admin/login/site",{}).then(function(e){1===e.result?(t.site=e.data,t.fun.setIcon(t.site.title_icon)):d.MessageBox.alert(e.msg)}).catch(function(t){console.log(t)})},getIndex:function(){var t=this;this.remember_pwd?this.loginForm.remember=1:delete this.loginForm.remember,$http.post("/admin/login",this.loginForm).then(function(e){if(1===e.result){if(t.loading=!1,!t.fun.isTextEmpty(e.data)&&-5===e.data.status)return void d.Message.error(e.msg);if(!t.fun.isTextEmpty(e.data)&&e.data.url)return void(window.location.href=e.data.url);t.$store.dispatch("GenerateRoutes",0),t.remember_pwd?(t.loginForm.remember=1,t.setlocalStorage(t.loginForm.username)):t.setlocalStorage(""),t.$router.push(t.fun.getUrl("Manage"))}else d.MessageBox.alert(e.msg),t.loading=!1}).catch(function(){t.loading=!1})},showPwd:function(){"password"===this.passwordType?this.passwordType="":this.passwordType="password"},handleLogin:function(){var t=this;this.$refs.loginForm.validate(function(e){if(!e)return console.log("error submit!!"),!1;t.loading=!0,t.getIndex()})},setlocalStorage:function(t){localStorage.setItem("siteName",i()(t))},getlocalStorage:function(){this.loginForm.username=JSON.parse(localStorage.getItem("siteName"))}}},u=(n("c11S"),n("pVwv"),Object(r.a)(g,function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"login-container"},[n("div",{staticClass:"login-container-con"},[n("div",{staticClass:"head"},[n("el-col",{staticStyle:{width:"36px",height:"36px",overflow:"hidden","margin-right":"10px"},attrs:{xs:8,sm:8,md:4,lg:1}},[n("img",{staticStyle:{width:"100%"},attrs:{src:t.site.site_logo,alt:""}})]),t._v(" "),n("el-col",{attrs:{span:15}},[n("div",{staticStyle:{color:"#666","font-size":"24px","font-weight":"900","line-height":"36px"}},[t._v("\n          "+t._s(t.site.name)+"\n        ")])])],1),t._v(" "),n("el-row",{staticClass:"content_info"},[n("el-col",{attrs:{span:15}},[n("div",{staticClass:"login_img"},[n("img",{attrs:{src:t.site.advertisement,alt:""}})])]),t._v(" "),n("el-col",{attrs:{span:1}}),t._v(" "),n("el-col",{staticClass:"login_input",attrs:{span:8}},[n("el-form",{ref:"loginForm",staticClass:"login-form",attrs:{model:t.loginForm,rules:t.loginRules,"auto-complete":"on","label-position":"left"}},[n("div",{staticClass:"title-container"},[n("h3",{staticClass:"title"},[t._v("\n              "+t._s(t.$t("login.title"))+"\n            ")])]),t._v(" "),n("el-form-item",{attrs:{prop:"name"}},[n("span",{staticClass:"svg-container"},[n("svg-icon",{attrs:{"icon-class":"user"}})],1),t._v(" "),n("el-input",{attrs:{placeholder:t.$t("login.username"),name:"name",type:"text","auto-complete":"on"},model:{value:t.loginForm.username,callback:function(e){t.$set(t.loginForm,"username",e)},expression:"loginForm.username"}})],1),t._v(" "),n("el-form-item",{attrs:{prop:"password"}},[n("span",{staticClass:"svg-container"},[n("svg-icon",{attrs:{"icon-class":"password"}})],1),t._v(" "),n("el-input",{attrs:{type:t.passwordType,placeholder:t.$t("login.password"),name:"password","auto-complete":"on"},nativeOn:{keyup:function(e){return"button"in e||!t._k(e.keyCode,"enter",13,e.key,"Enter")?t.handleLogin(e):null}},model:{value:t.loginForm.password,callback:function(e){t.$set(t.loginForm,"password",e)},expression:"loginForm.password"}}),t._v(" "),n("span",{staticClass:"show-pwd",on:{click:t.showPwd}},[n("svg-icon",{attrs:{"icon-class":"password"===t.passwordType?"eye":"eye-open"}})],1)],1),t._v(" "),n("el-checkbox",{staticStyle:{color:"#999"},attrs:{"true-label":1,"false-label":0,label:1},model:{value:t.remember_pwd,callback:function(e){t.remember_pwd=e},expression:"remember_pwd"}},[t._v("记住用户名\n          ")]),t._v(" "),n("router-link",{attrs:{to:"/forget"}},[n("span",{staticClass:"forget"},[t._v("忘记密码？")])]),t._v(" "),n("el-button",{staticStyle:{width:"100%",margin:"30px 0",padding:"12px 20px","font-size":"18px"},attrs:{loading:t.loading,type:"primary"},nativeOn:{click:function(e){return e.preventDefault(),t.handleLogin(e)}}},[t._v("\n            登录\n          ")]),t._v(" "),n("div",{staticStyle:{position:"relative"}})],1)],1)],1),t._v(" "),n("el-row",{staticStyle:{"margin-top":"40px"}},[n("el-col",{staticStyle:{color:"#999",padding:"30px 0"},attrs:{align:"center"}},[n("p",{domProps:{innerHTML:t._s(t.site.information)}})])],1)],1)])},[],!1,null,"01a6a46d",null));u.options.__file="index.vue";e.default=u.exports},pVwv:function(t,e,n){"use strict";var s=n("GmPy");n.n(s).a}}]);