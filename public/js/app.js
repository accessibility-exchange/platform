(self.webpackChunk_accessibility_exchange_platform=self.webpackChunk_accessibility_exchange_platform||[]).push([[773],{99:(t,e,n)=>{"use strict";n.r(e);var r=n(519);function o(t){return o="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},o(t)}function i(t,e){if(e&&("object"===o(e)||"function"==typeof e))return e;if(void 0!==e)throw new TypeError("Derived constructors may only return object or undefined");return function(t){if(void 0===t)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return t}(t)}function a(t){var e="function"==typeof Map?new Map:void 0;return a=function(t){if(null===t||(n=t,-1===Function.toString.call(n).indexOf("[native code]")))return t;var n;if("function"!=typeof t)throw new TypeError("Super expression must either be null or a function");if(void 0!==e){if(e.has(t))return e.get(t);e.set(t,r)}function r(){return s(t,arguments,l(this).constructor)}return r.prototype=Object.create(t.prototype,{constructor:{value:r,enumerable:!1,writable:!0,configurable:!0}}),u(r,t)},a(t)}function s(t,e,n){return s=c()?Reflect.construct:function(t,e,n){var r=[null];r.push.apply(r,e);var o=new(Function.bind.apply(t,r));return n&&u(o,n.prototype),o},s.apply(null,arguments)}function c(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Boolean.prototype.valueOf.call(Reflect.construct(Boolean,[],(function(){}))),!0}catch(t){return!1}}function u(t,e){return u=Object.setPrototypeOf||function(t,e){return t.__proto__=e,t},u(t,e)}function l(t){return l=Object.setPrototypeOf?Object.getPrototypeOf:function(t){return t.__proto__||Object.getPrototypeOf(t)},l(t)}var f=function(t){!function(t,e){if("function"!=typeof e&&null!==e)throw new TypeError("Super expression must either be null or a function");t.prototype=Object.create(e&&e.prototype,{constructor:{value:t,writable:!0,configurable:!0}}),e&&u(t,e)}(a,t);var e,n,o=(e=a,n=c(),function(){var t,r=l(e);if(n){var o=l(this).constructor;t=Reflect.construct(r,arguments,o)}else t=r.apply(this,arguments);return i(this,t)});function a(){var t;!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,a);var e=(t=o.call(this)).innerText,n=t.dataset.definition,i=document.createElement("span");i.setAttribute("class","defined-term");var s=document.createElement("button");s.setAttribute("type","button"),s.setAttribute("data-definition",n),s.innerHTML="".concat(e,' <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>');var c=document.createElement("span");c.setAttribute("role","status");var u=document.createElement("span");return u.setAttribute("class","definition"),u.innerText=n,t.innerHTML="",t.appendChild(i),i.appendChild(s),i.appendChild(c),s.addEventListener("click",(function(){c.innerHTML="",window.setTimeout((function(){c.appendChild(u),(0,r.fi)(s,u,{modifiers:[{name:"offset",options:{offset:[0,10]}}]})}),100)})),document.addEventListener("click",(function(t){s!==t.target.closest("button")&&(c.innerHTML="")})),s.addEventListener("keydown",(function(t){"Escape"===t.key&&(c.innerHTML="")})),s.addEventListener("blur",(function(){c.innerHTML=""})),t}return a}(a(HTMLElement));customElements.define("defined-term",f)},574:(t,e,n)=>{"use strict";var r=n(306),o=n(221),i=n.n(o);var a=n(490);function s(t,e){return function(t){if(Array.isArray(t))return t}(t)||function(t,e){var n=null==t?null:"undefined"!=typeof Symbol&&t[Symbol.iterator]||t["@@iterator"];if(null==n)return;var r,o,i=[],a=!0,s=!1;try{for(n=n.call(t);!(a=(r=n.next()).done)&&(i.push(r.value),!e||i.length!==e);a=!0);}catch(t){s=!0,o=t}finally{try{a||null==n.return||n.return()}finally{if(s)throw o}}return i}(t,e)||function(t,e){if(!t)return;if("string"==typeof t)return c(t,e);var n=Object.prototype.toString.call(t).slice(8,-1);"Object"===n&&t.constructor&&(n=t.constructor.name);if("Map"===n||"Set"===n)return Array.from(t);if("Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n))return c(t,e)}(t,e)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function c(t,e){(null==e||e>t.length)&&(e=t.length);for(var n=0,r=new Array(e);n<e;n++)r[n]=t[n];return r}n(689),n(99),window.Alpine=r.Z,r.Z.data("autocomplete",(function(){return{init:function(){i().enhanceSelectElement({defaultValue:"",displayMenu:"overlay",selectElement:this.$root,showAllValues:!0})}}})),r.Z.data("confirmPassword",(function(){var t=arguments.length>0&&void 0!==arguments[0]&&arguments[0],e=arguments.length>1&&void 0!==arguments[1]&&arguments[1];return{routes:{confirmedPasswordStatus:t,confirmPassword:e},locale:!1,confirmedPassword:!1,targetForm:!1,showingModal:!1,validationError:!1,init:function(){var t=this;axios.get(this.routes.confirmedPasswordStatus).then((function(e){e.data.confirmed&&(t.confirmedPassword=!0)}))},submitForm:function(t){!0===this.confirmedPassword?t.target.submit():(this.targetForm=t.target,this.showModal())},showModal:function(){var t=this;this.showingModal=!0;var e=window.scrollY;document.body.style.position="fixed",document.body.style.top="-".concat(e,"px"),this.$nextTick((function(){var e=document.querySelectorAll("a, button, input, select, textarea, [contenteditable]");Array.prototype.forEach.call(e,(function(t){t.closest(".modal")||t.setAttribute("tabindex","-1")})),t.$refs.password.focus()}))},hideModal:function(){this.showingModal=!1;var t=document.body.style.top;document.body.style.position="",document.body.style.top="",window.scrollTo(0,-1*parseInt(t||"0"));var e=document.querySelectorAll("a, button, input, select, textarea, [contenteditable]");Array.prototype.forEach.call(e,(function(t){t.closest(".modal")||t.removeAttribute("tabindex","-1")}))},confirmPassword:function(){var t=this;axios.post(this.routes.confirmPassword,{password:this.$refs.password.value}).then((function(){t.hideModal(),t.confirmPassword=!0,t.validationError=!1,t.targetForm.submit()})).catch((function(){t.$refs.password.focus(),t.validationError=!0}))},cancel:function(){this.hideModal()}}})),r.Z.data("dateInput",(function(){return{dateTime:!1,date:"",year:"",month:"",day:"",error:!1,componentsToDate:function(){if(this.year&&this.month&&this.day){var t=a.ou.fromObject({month:this.month,day:this.day,year:this.year});t.isValid?(this.date=t.toSQLDate(),this.dateTime=t,this.error=!1):(this.dateTime=!1,this.date="",this.error=!0)}else this.dateTime=!1,this.date=""},dateToComponents:function(t,e){if(t){var n,r,o,i=s(t.split("-"),3);n=i[0],r=i[1],o=i[2];var c=a.ou.fromObject({month:r,day:o,year:n});c.isValid?(this.date=t,this.dateTime=c,this.year=n,this.month=r,this.day=o,this.error=!1):(this.date="",this.dateTime=!1,this.error=!0)}else e&&(this.error=!0)}}})),r.Z.start()},689:(t,e,n)=>{window.axios=n(669),window.axios.defaults.headers.common["X-Requested-With"]="XMLHttpRequest"},662:()=>{}},t=>{var e=e=>t(t.s=e);t.O(0,[170,898],(()=>(e(574),e(662))));t.O()}]);