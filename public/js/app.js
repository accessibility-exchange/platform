(self.webpackChunk_accessibility_in_action_platform=self.webpackChunk_accessibility_in_action_platform||[]).push([[773],{0:(t,o,r)=>{"use strict";var e=r(306);var i=r(490);function n(t,o){return function(t){if(Array.isArray(t))return t}(t)||function(t,o){if("undefined"==typeof Symbol||!(Symbol.iterator in Object(t)))return;var r=[],e=!0,i=!1,n=void 0;try{for(var a,s=t[Symbol.iterator]();!(e=(a=s.next()).done)&&(r.push(a.value),!o||r.length!==o);e=!0);}catch(t){i=!0,n=t}finally{try{e||null==s.return||s.return()}finally{if(i)throw n}}return r}(t,o)||function(t,o){if(!t)return;if("string"==typeof t)return a(t,o);var r=Object.prototype.toString.call(t).slice(8,-1);"Object"===r&&t.constructor&&(r=t.constructor.name);if("Map"===r||"Set"===r)return Array.from(t);if("Arguments"===r||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(r))return a(t,o)}(t,o)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function a(t,o){(null==o||o>t.length)&&(o=t.length);for(var r=0,e=new Array(o);r<o;r++)e[r]=t[r];return e}r(689),e.Z.data("confirmPassword",(function(){var t=arguments.length>0&&void 0!==arguments[0]&&arguments[0],o=arguments.length>1&&void 0!==arguments[1]&&arguments[1];return{routes:{confirmedPasswordStatus:t,confirmPassword:o},locale:!1,confirmedPassword:!1,targetForm:!1,showingModal:!1,validationError:!1,init:function(){var t=this;axios.get(this.routes.confirmedPasswordStatus).then((function(o){o.data.confirmed&&(t.confirmedPassword=!0)}))},submitForm:function(t){!0===this.confirmedPassword?t.target.submit():(this.targetForm=t.target,this.showModal())},showModal:function(){var t=this;this.showingModal=!0;var o=window.scrollY;document.body.style.position="fixed",document.body.style.top="-".concat(o,"px"),this.$nextTick((function(){var o=document.querySelectorAll("a, button, input, select, textarea, [contenteditable]");Array.prototype.forEach.call(o,(function(t){t.closest(".modal")||t.setAttribute("tabindex","-1")})),t.$refs.password.focus()}))},hideModal:function(){this.showingModal=!1;var t=document.body.style.top;document.body.style.position="",document.body.style.top="",window.scrollTo(0,-1*parseInt(t||"0"));var o=document.querySelectorAll("a, button, input, select, textarea, [contenteditable]");Array.prototype.forEach.call(o,(function(t){t.closest(".modal")||t.removeAttribute("tabindex","-1")}))},confirmPassword:function(){var t=this;axios.post(this.routes.confirmPassword,{password:this.$refs.password.value}).then((function(){t.hideModal(),t.confirmPassword=!0,t.validationError=!1,t.targetForm.submit()})).catch((function(){t.$refs.password.focus(),t.validationError=!0}))},cancel:function(){this.hideModal()}}})),e.Z.data("dateInput",(function(){return{dateTime:!1,date:"",year:"",month:"",day:"",error:!1,componentsToDate:function(){if(this.year&&this.month&&this.day){var t=i.ou.fromObject({month:this.month,day:this.day,year:this.year});t.isValid?(this.date=t.toSQLDate(),this.dateTime=t,this.error=!1):(this.dateTime=!1,this.date="",this.error=!0)}else this.dateTime=!1,this.date="",this.error=!0},dateToComponents:function(t){if(t){var o,r,e,a=n(t.split("-"),3);o=a[0],r=a[1],e=a[2];var s=i.ou.fromObject({month:r,day:e,year:o});s.isValid?(this.date=t,this.dateTime=s,this.year=o,this.month=r,this.day=e,this.error=!1):(this.date="",this.dateTime=!1,this.error=!0)}}}})),e.Z.start()},689:(t,o,r)=>{window.axios=r(669),window.axios.defaults.headers.common["X-Requested-With"]="XMLHttpRequest"},678:()=>{}},0,[[0,929,898],[678,929,898]]]);