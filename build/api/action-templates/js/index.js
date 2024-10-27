(()=>{"use strict";var e={d:(t,s)=>{for(var c in s)e.o(s,c)&&!e.o(t,c)&&Object.defineProperty(t,c,{enumerable:!0,get:s[c]})},o:(e,t)=>Object.prototype.hasOwnProperty.call(e,t),r:e=>{"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})}},t={};e.r(t),e.d(t,{INITIAL_STATE:()=>r,QUADLAYERS_AICP_STORE_NAME:()=>f,STORE_NAME:()=>n,actions:()=>c,fetchRestApiActions:()=>_,reducer:()=>i,resolvers:()=>o,selectors:()=>s,useApiActionTemplates:()=>h});var s={};e.r(s),e.d(s,{getActions:()=>d});var c={};e.r(c),e.d(c,{createAction:()=>T,deleteAction:()=>S,editAction:()=>g,setActions:()=>y});var o={};e.r(o),e.d(o,{getActions:()=>E});var i={};e.r(i),e.d(i,{default:()=>m});const a=window.wp.data,n="aicp/actions/store",r={actions:[]},d=e=>e.actions,p=window.wp.i18n,l=window.wp.notices,A=window.aicp.helpers,{QUADLAYERS_AICP_API_ACTION_TEMPLATES_REST_ROUTES:u}=aicpApiActionTemplates,_=({method:e,data:t}={})=>(0,A.apiFetch)({path:u.actions,method:e,data:t});function h(){const{createAction:e,editAction:t,deleteAction:s}=(0,a.useDispatch)(n),{actions:c,isResolvingActions:o,hasResolvedActions:i}=(0,a.useSelect)((e=>{const{isResolving:t,hasFinishedResolution:s,getActions:c}=e(n);let o=e("core/editor").getEditedPostAttribute("type");if(!o){const e=document.getElementById("post_type");e&&(o=e.value)}return{actions:[...([...A.SYSTEM_ACTIONS_TEMPLATES,...c()||[]].filter((e=>e.action_post_type.includes("all")||e.action_post_type.includes(o)||!o))||[]).map((e=>"action_origin"in e?e:{...e,action_origin:"user"}))],isResolvingActions:t("getActions"),hasResolvedActions:s("getActions")}}),[]);return{actions:c,isResolvingActions:o,hasResolvedActions:i,hasActions:!(!i||!c?.length),createAction:e,editAction:t,deleteAction:s}}const y=e=>({type:"SET_ACTIONS",payload:e}),T=e=>async({registry:t,dispatch:s,select:c})=>{const o=c.getActions(),i=await _({method:"POST",data:e});return i?.code||i?.message?(t.dispatch(l.store).createSuccessNotice((0,p.sprintf)("%s: %s",i.code,i.message),{type:"snackbar"}),!1):(o.push(i),s.setActions([...o]),t.dispatch(l.store).createSuccessNotice((0,p.__)("The action has been created successfully.","ai-copilot"),{type:"snackbar"}),i.action_id)},S=e=>async({registry:t,dispatch:s,select:c})=>{const o=c.getActions(),i=await _({method:"DELETE",data:{action_id:e}});if(i?.code||i?.message)return t.dispatch(l.store).createSuccessNotice((0,p.sprintf)("%s: %s",i.code,i.message),{type:"snackbar"}),!1;const a=o.filter((t=>parseInt(t.action_id)!==parseInt(e)));return s.setActions([...a]),t.dispatch(l.store).createSuccessNotice((0,p.sprintf)((0,p.__)("The action %s has been deleted.","ai-copilot"),e),{type:"snackbar"}),!0},g=e=>async({registry:t,dispatch:s,select:c})=>{const o=c.getActions(),i=await _({method:"PATCH",data:e});return i?.code||i?.message?(t.dispatch(l.store).createSuccessNotice((0,p.sprintf)("%s: %s",i.code,i.message),{type:"snackbar"}),!1):(s.setActions([...o.map((t=>t.action_id==e.action_id?e:t))]),t.dispatch(l.store).createSuccessNotice((0,p.__)("The action has been updated successfully.","ai-copilot"),{type:"snackbar"}),!0)},E=async()=>{try{const e=await _({method:"GET"});return y(e)}catch(e){console.error(e)}};function m(e=r,t){return"SET_ACTIONS"===t.type?{...e,actions:t.payload}:e}const w=(0,a.createReduxStore)(n,{reducer:m,actions:c,selectors:s,resolvers:o});(0,a.register)((0,A.isVersionLessThan)(A.WP_VERSION,A.FIRST_WP_VERSION_WITH_THUNK_SUPPORT)?(0,A.applyThunkMiddleware)(w):w);const f=n;(window.aicp=window.aicp||{})["api-action-templates"]=t})();