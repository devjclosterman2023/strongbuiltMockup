(()=>{var e={4184:(e,t)=>{var o;!function(){"use strict";var n={}.hasOwnProperty;function a(){for(var e=[],t=0;t<arguments.length;t++){var o=arguments[t];if(o){var i=typeof o;if("string"===i||"number"===i)e.push(o);else if(Array.isArray(o)){if(o.length){var l=a.apply(null,o);l&&e.push(l)}}else if("object"===i)if(o.toString===Object.prototype.toString)for(var r in o)n.call(o,r)&&o[r]&&e.push(r);else e.push(o.toString())}}return e.join(" ")}e.exports?(a.default=a,e.exports=a):void 0===(o=function(){return a}.apply(t,[]))||(e.exports=o)}()},2819:e=>{"use strict";e.exports=window.lodash},9543:e=>{"use strict";e.exports=window.kadence.components},6222:e=>{"use strict";e.exports=window.kadence.helpers},9995:e=>{"use strict";e.exports=window.kadence.icons},2175:e=>{"use strict";e.exports=window.wp.blockEditor},4981:e=>{"use strict";e.exports=window.wp.blocks},5609:e=>{"use strict";e.exports=window.wp.components},9818:e=>{"use strict";e.exports=window.wp.data},9307:e=>{"use strict";e.exports=window.wp.element},5736:e=>{"use strict";e.exports=window.wp.i18n},444:e=>{"use strict";e.exports=window.wp.primitives}},t={};function o(n){var a=t[n];if(void 0!==a)return a.exports;var i=t[n]={exports:{}};return e[n](i,i.exports,o),i.exports}o.n=e=>{var t=e&&e.__esModule?()=>e.default:()=>e;return o.d(t,{a:t}),t},o.d=(e,t)=>{for(var n in t)o.o(t,n)&&!o.o(e,n)&&Object.defineProperty(e,n,{enumerable:!0,get:t[n]})},o.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),o.r=e=>{"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})};var n={};(()=>{"use strict";o.r(n);const e=JSON.parse('{"$schema":"https://schemas.wp.org/trunk/block.json","apiVersion":2,"title":"Single Button","name":"kadence/singlebtn","parent":["kadence/advancedbtn"],"category":"kadence-blocks","attributes":{"uniqueID":{"type":"string","default":""},"text":{"type":"string","default":"","__experimentalRole":"content"},"link":{"type":"string","default":"","__experimentalRole":"content"},"target":{"type":"string","default":"_self","__experimentalRole":"content"},"noFollow":{"type":"boolean","default":false,"__experimentalRole":"content"},"sponsored":{"type":"boolean","default":false,"__experimentalRole":"content"},"download":{"type":"boolean","default":false,"__experimentalRole":"content"},"style":{"type":"string","default":"basic"},"sizePreset":{"type":"string","default":"standard"},"gap":{"type":"array","default":["","",""]},"width":{"type":"array","default":["","",""]},"widthUnit":{"type":"string","default":"px"},"widthType":{"type":"string","default":"auto"},"padding":{"type":"array","default":["","","",""]},"tabletPadding":{"type":"array","default":["","","",""]},"mobilePadding":{"type":"array","default":["","","",""]},"paddingUnit":{"type":"string","default":"px"},"margin":{"type":"array","default":["","","",""]},"tabletMargin":{"type":"array","default":["","","",""]},"mobileMargin":{"type":"array","default":["","","",""]},"marginUnit":{"type":"string","default":"px"},"color":{"type":"string","default":""},"background":{"type":"string","default":""},"gradient":{"type":"string","default":""},"backgroundType":{"type":"string","default":"normal"},"colorHover":{"type":"string","default":""},"backgroundHover":{"type":"string","default":""},"backgroundHoverType":{"type":"string","default":"normal"},"gradientHover":{"type":"string","default":""},"borderStyle":{"type":"array","default":[{"top":["","",""],"right":["","",""],"bottom":["","",""],"left":["","",""],"unit":"px"}]},"tabletBorderStyle":{"type":"array","default":[{"top":["","",""],"right":["","",""],"bottom":["","",""],"left":["","",""],"unit":"px"}]},"mobileBorderStyle":{"type":"array","default":[{"top":["","",""],"right":["","",""],"bottom":["","",""],"left":["","",""],"unit":"px"}]},"borderHoverStyle":{"type":"array","default":[{"top":["","",""],"right":["","",""],"bottom":["","",""],"left":["","",""],"unit":"px"}]},"tabletBorderHoverStyle":{"type":"array","default":[{"top":["","",""],"right":["","",""],"bottom":["","",""],"left":["","",""],"unit":"px"}]},"mobileBorderHoverStyle":{"type":"array","default":[{"top":["","",""],"right":["","",""],"bottom":["","",""],"left":["","",""],"unit":"px"}]},"borderRadius":{"type":"array","default":["","","",""]},"tabletBorderRadius":{"type":"array","default":["","","",""]},"mobileBorderRadius":{"type":"array","default":["","","",""]},"borderRadiusUnit":{"type":"string","default":"px"},"borderHoverRadius":{"type":"array","default":["","","",""]},"tabletBorderHoverRadius":{"type":"array","default":["","","",""]},"mobileBorderHoverRadius":{"type":"array","default":["","","",""]},"borderHoverRadiusUnit":{"type":"string","default":"px"},"icon":{"type":"string","default":""},"iconColor":{"type":"string","default":""},"iconColorHover":{"type":"string","default":""},"iconSide":{"type":"string","default":"right"},"iconHover":{"type":"boolean","default":false},"iconPadding":{"type":"array","default":["","","",""]},"iconPaddingUnit":{"type":"string","default":"px"},"tabletIconPadding":{"type":"array","default":["","","",""]},"mobileIconPadding":{"type":"array","default":["","","",""]},"iconSize":{"type":"array","default":["","",""]},"iconSizeUnit":{"type":"string","default":"px"},"onlyIcon":{"type":"array","default":[false,"",""]},"label":{"type":"string","default":""},"inheritStyles":{"type":"string","default":"fill"},"typography":{"type":"array","default":[{"size":["","",""],"sizetype":"px","lineHeight":["","",""],"linetype":"","letterSpacing":["","",""],"letterType":"px","textTransform":"","family":"","google":"","style":"","weight":"","variant":"","subset":"","loadGoogle":true}]},"displayShadow":{"type":"boolean","default":false},"displayHoverShadow":{"type":"boolean","default":false},"shadow":{"type":"array","default":[{"color":"#000000","opacity":0.2,"spread":0,"blur":2,"hOffset":1,"vOffset":1,"inset":false}]},"shadowHover":{"type":"array","default":[{"color":"#000000","opacity":0.4,"spread":0,"blur":3,"hOffset":2,"vOffset":2,"inset":false}]},"inQueryBlock":{"type":"boolean","default":false},"anchor":{"type":"string"},"hideLink":{"type":"boolean","default":false},"noCustomDefaults":{"type":"boolean","default":false}},"supports":{"anchor":true,"ktanimate":true,"ktanimateadd":true,"ktanimatepreview":true,"ktdynamic":true,"html":false,"reusable":false},"usesContext":["postId","queryId"]}');var t=o(9995),a=o(9307),i=o(6222),l=o(9543),r=o(4184),d=o.n(r),s=(o(2819),o(5736)),c=o(9818),u=o(444);const p=(0,a.createElement)(u.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,a.createElement)(u.Path,{d:"M15.6 7.2H14v1.5h1.6c2 0 3.7 1.7 3.7 3.7s-1.7 3.7-3.7 3.7H14v1.5h1.6c2.8 0 5.2-2.3 5.2-5.2 0-2.9-2.3-5.2-5.2-5.2zM4.7 12.4c0-2 1.7-3.7 3.7-3.7H10V7.2H8.4c-2.9 0-5.2 2.3-5.2 5.2 0 2.9 2.3 5.2 5.2 5.2H10v-1.5H8.4c-2 0-3.7-1.7-3.7-3.7zm4.6.9h5.3v-1.5H9.3v1.5z"})),g=window.wp.keycodes;var v=o(2175),b=o(5609);const m=window.wp.hooks;(0,o(4981).registerBlockType)("kadence/singlebtn",{...e,title:(0,s.__)("Single Button","kadence-blocks"),description:(0,s.__)("Single button within a button block","kadence-blocks"),keywords:[(0,s.__)("Button","kadence-blocks"),(0,s.__)("btn","kadence-blocks"),"KB"],icon:{src:t.advancedBtnIcon},edit:function(t){let{attributes:o,setAttributes:n,className:r,isSelected:u,context:y,clientId:f,name:h}=t;const{uniqueID:k,text:S,link:C,target:w,sponsored:_,download:x,noFollow:O,sizePreset:B,padding:T,tabletPadding:E,mobilePadding:P,paddingUnit:R,color:H,background:z,backgroundType:I,gradient:A,colorHover:M,backgroundHover:F,backgroundHoverType:U,gradientHover:L,borderStyle:V,tabletBorderStyle:D,mobileBorderStyle:N,borderHoverStyle:$,tabletBorderHoverStyle:K,mobileBorderHoverStyle:q,typography:G,borderRadius:j,tabletBorderRadius:W,mobileBorderRadius:Q,borderRadiusUnit:J,borderHoverRadius:X,tabletBorderHoverRadius:Y,mobileBorderHoverRadius:Z,borderHoverRadiusUnit:ee,icon:te,iconSide:oe,iconHover:ne,width:ae,widthUnit:ie,widthType:le,displayShadow:re,shadow:de,displayHoverShadow:se,shadowHover:ce,inheritStyles:ue,iconSize:pe,iconPadding:ge,tabletIconPadding:ve,mobileIconPadding:be,iconPaddingUnit:me,onlyIcon:ye,iconColor:fe,iconColorHover:he,label:ke,marginUnit:Se,margin:Ce,iconSizeUnit:we,tabletMargin:_e,mobileMargin:xe,kadenceAOSOptions:Oe,kadenceAnimation:Be,hideLink:Te,inQueryBlock:Ee}=o,{updateBlockAttributes:Pe}=(0,c.useDispatch)(v.store),{btnsBlock:Re,rootID:He}=(0,c.useSelect)((e=>{const{getBlockRootClientId:t,getBlocksByClientId:o}=e(v.store),n=t(f),a=o(n);return{btnsBlock:void 0!==a?a:"",rootID:void 0!==n?n:""}}),[f]),ze=(e,t)=>{Pe(He,{[e]:t})},{addUniqueID:Ie}=(0,c.useDispatch)("kadenceblocks/data"),{isUniqueID:Ae,isUniqueBlock:Me,previewDevice:Fe}=(0,c.useSelect)((e=>({isUniqueID:t=>e("kadenceblocks/data").isUniqueID(t),isUniqueBlock:(t,o)=>e("kadenceblocks/data").isUniqueBlock(t,o),previewDevice:e("kadenceblocks/data").getPreviewDeviceType()})),[f]),Ue=(0,i.mouseOverVisualizer)(),Le=(0,i.mouseOverVisualizer)();(0,a.useEffect)((()=>{(0,i.setBlockDefaults)("kadence/singlebtn",o);let e=(0,i.getUniqueId)(k,f,Ae,Me);e!==k?(o.uniqueID=e,n({uniqueID:e}),Ie(e,f)):Ie(k,f),n({inQueryBlock:(0,i.getInQueryBlock)(y,Ee)})}),[]);const[Ve,De]=(0,a.useState)("general"),[Ne,$e]=(0,a.useState)(!1);(0,a.useEffect)((()=>{u||$e(!1)}),[u]);const Ke=e=>{const t=G.map(((t,o)=>(0===o&&(t={...t,...e}),t)));n({typography:t})},qe=e=>{const t=de.map(((t,o)=>(0===o&&(t={...t,...e}),t)));n({shadow:t})},Ge=e=>{const t=ce.map(((t,o)=>(0===o&&(t={...t,...e}),t)));n({shadowHover:t})},je=[{value:"small",label:(0,s.__)("SM","kadence-blocks")},{value:"standard",label:(0,s.__)("MD","kadence-blocks")},{value:"large",label:(0,s.__)("LG","kadence-blocks")},{value:"xlarge",label:(0,s.__)("XL","kadence-blocks")}],We=[{value:"auto",label:(0,s.__)("Auto","kadence-blocks")},{value:"fixed",label:(0,s.__)("Fixed","kadence-blocks")},{value:"full",label:(0,s.__)("Full","kadence-blocks")}],Qe=[{value:"fill",label:(0,s.__)("Fill","kadence-blocks")},{value:"outline",label:(0,s.__)("Outline","kadence-blocks")},{value:"inherit",label:(0,s.__)("Theme","kadence-blocks")}],Je=(0,i.getPreviewSize)(Fe,void 0!==Ce?.[0]?Ce[0]:"",void 0!==_e?.[0]?_e[0]:"",void 0!==xe?.[0]?xe[0]:""),Xe=(0,i.getPreviewSize)(Fe,void 0!==Ce?.[1]?Ce[1]:"",void 0!==_e?.[1]?_e[1]:"",void 0!==xe?.[1]?xe[1]:""),Ye=(0,i.getPreviewSize)(Fe,void 0!==Ce?.[2]?Ce[2]:"",void 0!==_e?.[2]?_e[2]:"",void 0!==xe?.[2]?xe[2]:""),Ze=(0,i.getPreviewSize)(Fe,void 0!==Ce?.[3]?Ce[3]:"",void 0!==_e?.[3]?_e[3]:"",void 0!==xe?.[3]?xe[3]:""),et=Se||"px",tt=(0,i.getPreviewSize)(Fe,void 0!==T?.[0]?T[0]:"",void 0!==E?.[0]?E[0]:"",void 0!==P?.[0]?P[0]:""),ot=(0,i.getPreviewSize)(Fe,void 0!==T?.[1]?T[1]:"",void 0!==E?.[1]?E[1]:"",void 0!==P?.[1]?P[1]:""),nt=(0,i.getPreviewSize)(Fe,void 0!==T?.[2]?T[2]:"",void 0!==E?.[2]?E[2]:"",void 0!==P?.[2]?P[2]:""),at=(0,i.getPreviewSize)(Fe,void 0!==T?.[3]?T[3]:"",void 0!==E?.[3]?E[3]:"",void 0!==P?.[3]?P[3]:""),it=(0,i.getPreviewSize)(Fe,void 0!==j?j[0]:"",void 0!==W?W[0]:"",void 0!==Q?Q[0]:""),lt=(0,i.getPreviewSize)(Fe,void 0!==j?j[1]:"",void 0!==W?W[1]:"",void 0!==Q?Q[1]:""),rt=(0,i.getPreviewSize)(Fe,void 0!==j?j[2]:"",void 0!==W?W[2]:"",void 0!==Q?Q[2]:""),dt=(0,i.getPreviewSize)(Fe,void 0!==j?j[3]:"",void 0!==W?W[3]:"",void 0!==Q?Q[3]:""),st=(0,i.getPreviewSize)(Fe,void 0!==pe?.[0]?pe[0]:"",void 0!==pe?.[1]?pe[1]:"",void 0!==pe?.[2]?pe[2]:""),ct=(0,i.getPreviewSize)(Fe,void 0!==ge?.[0]?ge[0]:"",void 0!==ve?.[0]?ve[0]:"",void 0!==be?.[0]?be[0]:""),ut=(0,i.getPreviewSize)(Fe,void 0!==ge?.[1]?ge[1]:"",void 0!==ve?.[1]?ve[1]:"",void 0!==be?.[1]?be[1]:""),pt=(0,i.getPreviewSize)(Fe,void 0!==ge?.[2]?ge[2]:"",void 0!==ve?.[2]?ve[2]:"",void 0!==be?.[2]?be[2]:""),gt=(0,i.getPreviewSize)(Fe,void 0!==ge?.[3]?ge[3]:"",void 0!==ve?.[3]?ve[3]:"",void 0!==be?.[3]?be[3]:""),vt=(0,i.getPreviewSize)(Fe,void 0!==ae?.[0]?ae[0]:"",void 0!==ae?.[1]?ae[1]:void 0,void 0!==ae?.[2]?ae[2]:void 0),bt=(0,i.getBorderStyle)(Fe,"top",V,D,N),mt=(0,i.getBorderStyle)(Fe,"right",V,D,N),yt=(0,i.getBorderStyle)(Fe,"bottom",V,D,N),ft=(0,i.getBorderStyle)(Fe,"left",V,D,N),ht=(0,i.getBorderColor)(Fe,"top",V,D,N),kt=(0,i.getBorderColor)(Fe,"right",V,D,N),St=(0,i.getBorderColor)(Fe,"bottom",V,D,N),Ct=(0,i.getBorderColor)(Fe,"left",V,D,N),wt=[V,D,N],_t=(0,i.getBorderStyle)(Fe,"top",$,K,q,wt),xt=(0,i.getBorderStyle)(Fe,"right",$,K,q,wt),Ot=(0,i.getBorderStyle)(Fe,"bottom",$,K,q,wt),Bt=(0,i.getBorderStyle)(Fe,"left",$,K,q,wt),Tt=(0,i.getBorderColor)(Fe,"top",$,K,q,wt),Et=(0,i.getBorderColor)(Fe,"right",$,K,q,wt),Pt=(0,i.getBorderColor)(Fe,"bottom",$,K,q,wt),Rt=(0,i.getBorderColor)(Fe,"left",$,K,q,wt),Ht=(0,i.getPreviewSize)(Fe,void 0!==X?X[0]:"",void 0!==Y?Y[0]:"",void 0!==Z?Z[0]:""),zt=(0,i.getPreviewSize)(Fe,void 0!==X?X[1]:"",void 0!==Y?Y[1]:"",void 0!==Z?Z[1]:""),It=(0,i.getPreviewSize)(Fe,void 0!==X?X[2]:"",void 0!==Y?Y[2]:"",void 0!==Z?Z[2]:""),At=(0,i.getPreviewSize)(Fe,void 0!==X?X[3]:"",void 0!==Y?Y[3]:"",void 0!==Z?Z[3]:""),Mt=(0,i.getPreviewSize)(Fe,void 0!==Re?.[0]?.attributes?.hAlign?Re?.[0]?.attributes?.hAlign:"",void 0!==Re?.[0]?.attributes?.thAlign?Re?.[0]?.attributes?.thAlign:"",void 0!==Re?.[0]?.attributes?.mhAlign?Re?.[0]?.attributes?.mhAlign:""),Ft=(0,i.getPreviewSize)(Fe,void 0!==Re?.[0]?.attributes?.vAlign?Re?.[0]?.attributes?.vAlign:"",void 0!==Re?.[0]?.attributes?.tvAlign?Re?.[0]?.attributes?.tvAlign:"",void 0!==Re?.[0]?.attributes?.mvAlign?Re?.[0]?.attributes?.mvAlign:""),Ut=(0,i.getPreviewSize)(Fe,void 0!==ye?.[0]?ye[0]:"",void 0!==ye?.[1]?ye[1]:void 0,void 0!==ye?.[2]?ye[2]:void 0);let Lt;Lt=void 0!==I&&"gradient"===I?A:"transparent"===z||void 0===z?void 0:(0,i.KadenceColorOutput)(z);const Vt=["hideLink","link","target","download","text","sponsor"],Dt=d()({"kt-button":!0,[`kt-button-${k}`]:!0,[`kb-btn-global-${ue}`]:ue,"wp-block-button__link":ue&&"inherit"===ue,"kb-btn-has-icon":te,["kt-btn-svg-show-"+(ne?"hover":"always")]:te,"kb-btn-only-icon":Ut,[`kt-btn-size-${B||"standard"}`]:!0}),Nt=d()({className:r,[`kb-single-btn-${k}`]:!0,[`kt-btn-width-type-${le||"auto"}`]:!0}),$t=(0,v.useBlockProps)({className:Nt,style:{width:void 0!==le&&"fixed"===le&&"%"===(void 0!==ie?ie:"px")&&""!==vt?vt+(void 0!==ie?ie:"px"):void 0}});let Kt="0",qt="",Gt="";const jt="gradient"===U?L:(0,i.KadenceColorOutput)(F);void 0!==se&&se&&void 0!==ce?.[0]&&void 0!==ce?.[0].inset&&!1===ce?.[0].inset&&(qt=(void 0!==ce?.[0].inset&&ce[0].inset?"inset ":"")+(void 0!==ce?.[0].hOffset?ce[0].hOffset:0)+"px "+(void 0!==ce?.[0].vOffset?ce[0].vOffset:0)+"px "+(void 0!==ce?.[0].blur?ce[0].blur:14)+"px "+(void 0!==ce?.[0].spread?ce[0].spread:0)+"px "+(0,i.KadenceColorOutput)(void 0!==ce?.[0].color?ce[0].color:"#000000",void 0!==ce?.[0].opacity?ce[0].opacity:1),Gt="none",Kt="0"),void 0!==se&&se&&void 0!==ce?.[0]&&void 0!==ce?.[0].inset&&!0===ce?.[0].inset&&(Gt=(void 0!==ce?.[0].inset&&ce[0].inset?"inset ":"")+(void 0!==ce?.[0].hOffset?ce[0].hOffset:0)+"px "+(void 0!==ce?.[0].vOffset?ce[0].vOffset:0)+"px "+(void 0!==ce?.[0].blur?ce[0].blur:14)+"px "+(void 0!==ce?.[0].spread?ce[0].spread:0)+"px "+(0,i.KadenceColorOutput)(void 0!==ce?.[0].color?ce[0].color:"#000000",void 0!==ce?.[0].opacity?ce[0].opacity:1),Kt=void 0!==j?j:"3",qt="none");const Wt=(0,i.typographyStyle)(G,`.editor-styles-wrapper .wp-block-kadence-advancedbtn .kb-single-btn-${k} .kt-button-${k}`,Fe),Qt=(0,a.createElement)("style",null,""!==Wt?Wt:"",`.kb-single-btn-${k} .kt-button-${k}.kb-btn-global-outline {`,!bt&&ht?"border-top-color:"+ht+";":"",!mt&&kt?"border-right-color:"+kt+";":"",!ft&&Ct?"border-left-color:"+Ct+";":"",!yt&&St?"border-bottom-color:"+St+";":"","}",`.kb-single-btn-${k} .kt-button-${k}.kb-btn-global-outline:hover {`,!_t&&Tt?"border-top-color:"+Tt+";":"",!xt&&Et?"border-right-color:"+Et+";":"",!Bt&&Rt?"border-left-color:"+Rt+";":"",!Ot&&Pt?"border-bottom-color:"+Pt+";":"","}",`.kb-single-btn-${k} .kt-button-${k}:hover {`,M?"color:"+(0,i.KadenceColorOutput)(M)+"!important;":"",qt?"box-shadow:"+qt+"!important;":"",_t?"border-top:"+_t+"!important;":"",xt?"border-right:"+xt+"!important;":"",Bt?"border-left:"+Bt+"!important;":"",Ot?"border-bottom:"+Ot+"!important;":"",Ht?"border-top-left-radius:"+Ht+(ee||"px")+"!important;":"",zt?"border-top-right-radius:"+zt+(ee||"px")+"!important;":"",At?"border-bottom-left-radius:"+At+(ee||"px")+"!important;":"",It?"border-bottom-right-radius:"+It+(ee||"px")+"!important;":"","}",he?`.kb-single-btn-${k} .kt-button-${k}:hover .kt-btn-svg-icon { color:${(0,i.KadenceColorOutput)(he)} !important;}`:"",`.kb-single-btn-${k} .kt-button-${k}::before {`,jt?"background:"+jt+";":"",Gt?"box-shadow:"+Gt+";":"",Kt?"border-radius:"+Kt+"px;":"","}");return(0,a.createElement)("div",$t,Qt,(0,a.createElement)(v.BlockControls,null,(0,a.createElement)(b.ToolbarGroup,null,(0,a.createElement)(v.JustifyContentControl,{value:Mt,onChange:e=>{"Mobile"===Fe?ze("mhAlign",e||""):"Tablet"===Fe?ze("thAlign",e||""):ze("hAlign",e||"center")}}),(0,a.createElement)(v.BlockVerticalAlignmentControl,{value:Ft,onChange:e=>{"Mobile"===Fe?ze("mvAlign",e||""):"Tablet"===Fe?ze("tvAlign",e||""):ze("vAlign",e||"center")}})),!Te&&(0,a.createElement)(b.ToolbarGroup,null,(0,a.createElement)(b.ToolbarButton,{name:"link",icon:p,title:(0,s.__)("Link","kadence-blocks"),shortcut:g.displayShortcut.primary("k"),onClick:function(e){e.preventDefault(),$e(!0)}})),(0,a.createElement)(l.CopyPasteAttributes,{attributes:o,excludedAttrs:Vt,defaultAttributes:e.attributes,blockSlug:e.name,onPaste:e=>n(e)})),!Te&&u&&Ne&&(0,a.createElement)(l.URLInputInline,{url:C,onChangeUrl:e=>{n({link:e})},additionalControls:!0,changeTargetType:!0,opensInNewTab:void 0!==w?w:"",onChangeTarget:e=>{n({target:e})},linkNoFollow:void 0!==O&&O,onChangeFollow:e=>{n({noFollow:e})},linkSponsored:void 0!==_&&_,onChangeSponsored:e=>{n({sponsored:e})},linkDownload:void 0!==x&&x,onChangeDownload:e=>{n({download:e})},dynamicAttribute:"link",allowClear:!0,isSelected:u,attributes:o,setAttributes:n,name:h,clientId:f,context:y}),(0,i.showSettings)("allSettings","kadence/advancedbtn")&&(0,a.createElement)(a.Fragment,null,(0,a.createElement)(v.InspectorControls,null,(0,a.createElement)(l.InspectorControlTabs,{panelName:"singlebtn",setActiveTab:e=>De(e),activeTab:Ve}),"general"===Ve&&(0,a.createElement)(a.Fragment,null,(0,a.createElement)(l.KadencePanelBody,{title:(0,s.__)("Button Settings","kadence-blocks"),initialOpen:!0,panelName:"kb-adv-single-btn"},!Te&&(0,a.createElement)(l.URLInputControl,{label:(0,s.__)("Button Link","kadence-blocks"),url:C,onChangeUrl:e=>{n({link:e})},additionalControls:!0,changeTargetType:!0,opensInNewTab:void 0!==w?w:"",onChangeTarget:e=>{n({target:e})},linkNoFollow:void 0!==O&&O,onChangeFollow:e=>{n({noFollow:e})},linkSponsored:void 0!==_&&_,onChangeSponsored:e=>{n({sponsored:e})},linkDownload:void 0!==x&&x,onChangeDownload:e=>{n({download:e})},dynamicAttribute:"link",allowClear:!0,isSelected:u,attributes:o,setAttributes:n,name:h,clientId:f,context:y}),(0,a.createElement)(l.KadenceRadioButtons,{value:ue,options:Qe,hideLabel:!1,label:(0,s.__)("Button Inherit Styles","kadence-blocks"),onChange:e=>{n({inheritStyles:e})}}),(0,i.showSettings)("sizeSettings","kadence/advancedbtn")&&(0,a.createElement)(a.Fragment,null,(0,a.createElement)(l.KadenceRadioButtons,{value:B,options:je,hideLabel:!1,label:(0,s.__)("Button Size","kadence-blocks"),onChange:e=>{n({sizePreset:e})}}),(0,a.createElement)(l.KadenceRadioButtons,{value:le,options:We,hideLabel:!1,label:(0,s.__)("Button Width","kadence-blocks"),onChange:e=>{n({widthType:e})}}),"fixed"===le&&(0,a.createElement)("div",{className:"kt-inner-sub-section"},(0,a.createElement)(l.ResponsiveRangeControls,{label:(0,s.__)("Fixed Width","kadence-blocks"),value:void 0!==ae?.[0]?ae[0]:void 0,onChange:e=>{n({width:[e,void 0!==ae?.[1]?ae[1]:"",void 0!==ae?.[2]?ae[2]:""]})},tabletValue:void 0!==ae?.[1]?ae[1]:void 0,onChangeTablet:e=>{n({width:[void 0!==ae?.[0]?ae[0]:"",e,void 0!==ae?.[2]?ae[2]:""]})},mobileValue:void 0!==ae?.[2]?ae[2]:void 0,onChangeMobile:e=>{n({width:[void 0!==ae?.[0]?ae[0]:"",void 0!==ae?.[1]?ae[1]:"",e]})},min:0,max:"px"!==(ie||"px")?100:600,step:1,unit:ie||"px",onUnit:e=>{n({widthUnit:e})},units:["px","%"]}))))),"style"===Ve&&(0,a.createElement)(a.Fragment,null,(0,i.showSettings)("colorSettings","kadence/advancedbtn")&&(0,a.createElement)(l.KadencePanelBody,{title:(0,s.__)("Button Styles","kadence-blocks"),initialOpen:!0,panelName:"kb-adv-single-btn-styles"},(0,a.createElement)(l.HoverToggleControl,{hover:(0,a.createElement)(a.Fragment,null,(0,a.createElement)(l.PopColorControl,{label:(0,s.__)("Color Hover","kadence-blocks"),value:M||"",default:"",onChange:e=>n({colorHover:e})}),(0,a.createElement)(l.BackgroundTypeControl,{label:(0,s.__)("Hover Type","kadence-blocks"),type:U||"normal",onChange:e=>n({backgroundHoverType:e}),allowedTypes:["normal","gradient"]}),"gradient"===U&&(0,a.createElement)(l.GradientControl,{value:L,onChange:e=>n({gradientHover:e}),gradients:[]}),"normal"===U&&(0,a.createElement)(l.PopColorControl,{label:(0,s.__)("Background Color","kadence-blocks"),value:F||"",default:"",onChange:e=>n({backgroundHover:e})}),(0,a.createElement)(l.ResponsiveBorderControl,{label:(0,s.__)("Border","kadence-blocks"),value:$,tabletValue:K,mobileValue:q,onChange:e=>n({borderHoverStyle:e}),onChangeTablet:e=>n({tabletBorderHoverStyle:e}),onChangeMobile:e=>n({mobileBorderHoverStyle:e})}),(0,a.createElement)(l.ResponsiveMeasurementControls,{label:(0,s.__)("Border Radius","kadence-blocks"),value:X,tabletValue:Y,mobileValue:Z,onChange:e=>n({borderHoverRadius:e}),onChangeTablet:e=>n({tabletBorderHoverRadius:e}),onChangeMobile:e=>n({mobileBorderHoverRadius:e}),unit:ee,units:["px","em","rem","%"],onUnit:e=>n({borderHoverRadiusUnit:e}),max:"em"===ee||"rem"===ee?24:500,step:"em"===ee||"rem"===ee?.1:1,min:0,isBorderRadius:!0,allowEmpty:!0}),(0,a.createElement)(l.BoxShadowControl,{label:(0,s.__)("Box Shadow","kadence-blocks"),enable:void 0!==se&&se,color:void 0!==ce&&void 0!==ce[0]&&void 0!==ce[0].color?ce[0].color:"#000000",colorDefault:"#000000",onArrayChange:(e,t)=>{Ge({color:e,opacity:t})},opacity:void 0!==ce&&void 0!==ce[0]&&void 0!==ce[0].opacity?ce[0].opacity:.2,hOffset:void 0!==ce&&void 0!==ce[0]&&void 0!==ce[0].hOffset?ce[0].hOffset:0,vOffset:void 0!==ce&&void 0!==ce[0]&&void 0!==ce[0].vOffset?ce[0].vOffset:0,blur:void 0!==ce&&void 0!==ce[0]&&void 0!==ce[0].blur?ce[0].blur:14,spread:void 0!==ce&&void 0!==ce[0]&&void 0!==ce[0].spread?ce[0].spread:0,inset:void 0!==ce&&void 0!==ce[0]&&void 0!==ce[0].inset&&ce[0].inset,onEnableChange:e=>{n({displayHoverShadow:e})},onColorChange:e=>{Ge({color:e})},onOpacityChange:e=>{Ge({opacity:e})},onHOffsetChange:e=>{Ge({hOffset:e})},onVOffsetChange:e=>{Ge({vOffset:e})},onBlurChange:e=>{Ge({blur:e})},onSpreadChange:e=>{Ge({spread:e})},onInsetChange:e=>{Ge({inset:e})}})),normal:(0,a.createElement)(a.Fragment,null,(0,a.createElement)(l.PopColorControl,{label:(0,s.__)("Color","kadence-blocks"),value:H||"",default:"",onChange:e=>n({color:e})}),(0,a.createElement)(l.BackgroundTypeControl,{label:(0,s.__)("Type","kadence-blocks"),type:I||"normal",onChange:e=>n({backgroundType:e}),allowedTypes:["normal","gradient"]}),"gradient"===I&&(0,a.createElement)(l.GradientControl,{value:A,onChange:e=>n({gradient:e}),gradients:[]}),"normal"===I&&(0,a.createElement)(l.PopColorControl,{label:(0,s.__)("Background Color","kadence-blocks"),value:z||"",default:"",onChange:e=>n({background:e})}),(0,a.createElement)(l.ResponsiveBorderControl,{label:(0,s.__)("Border","kadence-blocks"),value:V,tabletValue:D,mobileValue:N,onChange:e=>n({borderStyle:e}),onChangeTablet:e=>n({tabletBorderStyle:e}),onChangeMobile:e=>n({mobileBorderStyle:e})}),(0,a.createElement)(l.ResponsiveMeasurementControls,{label:(0,s.__)("Border Radius","kadence-blocks"),value:j,tabletValue:W,mobileValue:Q,onChange:e=>n({borderRadius:e}),onChangeTablet:e=>n({tabletBorderRadius:e}),onChangeMobile:e=>n({mobileBorderRadius:e}),unit:J,units:["px","em","rem","%"],onUnit:e=>n({borderRadiusUnit:e}),max:"em"===J||"rem"===J?24:500,step:"em"===J||"rem"===J?.1:1,min:0,isBorderRadius:!0,allowEmpty:!0}),(0,a.createElement)(l.BoxShadowControl,{label:(0,s.__)("Box Shadow","kadence-blocks"),enable:void 0!==re&&re,color:void 0!==de&&void 0!==de[0]&&void 0!==de[0].color?de[0].color:"#000000",colorDefault:"#000000",onArrayChange:(e,t)=>{qe({color:e,opacity:t})},opacity:void 0!==de&&void 0!==de[0]&&void 0!==de[0].opacity?de[0].opacity:.2,hOffset:void 0!==de&&void 0!==de[0]&&void 0!==de[0].hOffset?de[0].hOffset:0,vOffset:void 0!==de&&void 0!==de[0]&&void 0!==de[0].vOffset?de[0].vOffset:0,blur:void 0!==de&&void 0!==de[0]&&void 0!==de[0].blur?de[0].blur:14,spread:void 0!==de&&void 0!==de[0]&&void 0!==de[0].spread?de[0].spread:0,inset:void 0!==de&&void 0!==de[0]&&void 0!==de[0].inset&&de[0].inset,onEnableChange:e=>{n({displayShadow:e})},onColorChange:e=>{qe({color:e})},onOpacityChange:e=>{qe({opacity:e})},onHOffsetChange:e=>{qe({hOffset:e})},onVOffsetChange:e=>{qe({vOffset:e})},onBlurChange:e=>{qe({blur:e})},onSpreadChange:e=>{qe({spread:e})},onInsetChange:e=>{qe({inset:e})}}))})),(0,i.showSettings)("iconSettings","kadence/advancedbtn")&&(0,a.createElement)(l.KadencePanelBody,{title:(0,s.__)("Icon Settings","kadence-blocks"),initialOpen:!1,panelName:"kb-adv-single-btn-icons"},(0,a.createElement)("div",{className:"kt-select-icon-container"},(0,a.createElement)(l.KadenceIconPicker,{value:te,onChange:e=>{n({icon:e})},allowClear:!0})),(0,a.createElement)(l.SmallResponsiveControl,{label:(0,s.__)("Icon and Text Display","kadence-blocks"),desktopChildren:(0,a.createElement)(b.SelectControl,{value:void 0!==ye?.[0]&&ye[0]?"true":"false",options:[{value:"false",label:(0,s.__)("Show Icon and Text","kadence-blocks")},{value:"true",label:(0,s.__)("Show Only Icon","kadence-blocks")}],onChange:e=>{n({onlyIcon:["true"===e,void 0!==ye?.[1]?ye[1]:"",void 0!==ye?.[2]?ye[2]:""]})}}),tabletChildren:(0,a.createElement)(b.SelectControl,{value:void 0!==ye?.[1]&&ye[1]?"true":void 0!==ye?.[1]&&!1===ye[1]?"false":"",options:[{value:"",label:(0,s.__)("Inherit","kadence-blocks")},{value:"false",label:(0,s.__)("Show Icon and Text","kadence-blocks")},{value:"true",label:(0,s.__)("Show Only Icon","kadence-blocks")}],onChange:e=>{let t=e;"true"===e?t=!0:"false"===e&&(t=!1),n({onlyIcon:[void 0!==ye?.[0]?ye[0]:"",t,void 0!==ye?.[2]?ye[2]:""]})}}),mobileChildren:(0,a.createElement)(b.SelectControl,{value:void 0!==ye?.[2]&&ye[2]?"true":void 0!==ye?.[2]&&!1===ye[2]?"false":"",options:[{value:"",label:(0,s.__)("Inherit","kadence-blocks")},{value:"false",label:(0,s.__)("Show Icon and Text","kadence-blocks")},{value:"true",label:(0,s.__)("Show Only Icon","kadence-blocks")}],onChange:e=>{let t=e;"true"===e?t=!0:"false"===e&&(t=!1),n({onlyIcon:[void 0!==ye?.[0]?ye[0]:"",void 0!==ye?.[1]?ye[1]:"",t]})}})}),(0,a.createElement)(b.SelectControl,{label:(0,s.__)("Icon Location","kadence-blocks"),value:oe,options:[{value:"right",label:(0,s.__)("Right")},{value:"left",label:(0,s.__)("Left")}],onChange:e=>{n({iconSide:e})}}),(0,a.createElement)(l.ResponsiveRangeControls,{label:(0,s.__)("Icon Size","kadence-blocks"),value:void 0!==pe?.[0]?pe[0]:"",onChange:e=>{n({iconSize:[e,void 0!==pe[1]?pe[1]:"",void 0!==pe?.[2]&&pe[2]?pe[2]:""]})},tabletValue:void 0!==pe?.[1]?pe[1]:"",onChangeTablet:e=>{n({iconSize:[void 0!==pe?.[0]?pe[0]:"",e,void 0!==pe?.[2]?pe[2]:""]})},mobileValue:void 0!==pe?.[2]?pe[2]:"",onChangeMobile:e=>{n({iconSize:[void 0!==pe?.[0]?pe[0]:"",void 0!==pe?.[1]?pe[1]:"",e]})},min:0,max:"px"!==(we||"px")?12:200,step:"px"!==(we||"px")?.1:1,unit:we||"px",onUnit:e=>{n({iconSizeUnit:e})},units:["px","em","rem"]}),(0,a.createElement)(l.PopColorControl,{label:(0,s.__)("Icon Color","kadence-blocks"),value:fe||"",default:"",onChange:e=>{n({iconColor:e})},swatchLabel2:(0,s.__)("Hover Color","kadence-blocks"),value2:he||"",default2:"",onChange2:e=>{n({iconColorHover:e})}}),(0,a.createElement)(l.ResponsiveMeasureRangeControl,{label:(0,s.__)("Icon Padding","kadence-blocks"),value:void 0!==ge?ge:["","","",""],tabletValue:void 0!==ve?ve:["","","",""],mobileValue:void 0!==be?be:["","","",""],onChange:e=>n({iconPadding:e}),onChangeTablet:e=>n({tabletIconPadding:e}),onChangeMobile:e=>n({mobileIconPadding:e}),min:"em"===me||"rem"===me?-2:-200,max:"em"===me||"rem"===me?12:200,step:"em"===me||"rem"===me?.1:1,unit:me,units:["px","em","rem"],onUnit:e=>n({iconPaddingUnit:e})})),(0,i.showSettings)("fontSettings","kadence/advancedbtn")&&(0,a.createElement)(l.KadencePanelBody,{title:(0,s.__)("Typography Settings","kadence-blocks"),initialOpen:!1,panelName:"kb-adv-btn-font-family"},(0,a.createElement)(l.TypographyControls,{fontGroup:"typography",fontSize:G[0].size,onFontSize:e=>Ke({size:e}),fontSizeType:G[0].sizeType,onFontSizeType:e=>Ke({sizeType:e}),lineHeight:G[0].lineHeight,onLineHeight:e=>Ke({lineHeight:e}),lineHeightType:G[0].lineType,onLineHeightType:e=>Ke({lineType:e}),reLetterSpacing:G[0].letterSpacing,onLetterSpacing:e=>Ke({letterSpacing:e}),letterSpacingType:G[0].letterType,onLetterSpacingType:e=>Ke({letterType:e}),textTransform:G[0].textTransform,onTextTransform:e=>Ke({textTransform:e}),fontFamily:G[0].family,onFontFamily:e=>Ke({family:e}),onFontChange:e=>{Ke({family:e.value,google:e.google})},onFontArrayChange:e=>Ke(e),googleFont:G[0].google,onGoogleFont:e=>Ke({google:e}),loadGoogleFont:G[0].loadGoogle,onLoadGoogleFont:e=>Ke({loadGoogle:e}),fontVariant:G[0].variant,onFontVariant:e=>Ke({variant:e}),fontWeight:G[0].weight,onFontWeight:e=>Ke({weight:e}),fontStyle:G[0].style,onFontStyle:e=>Ke({style:e}),fontSubset:G[0].subset,onFontSubset:e=>Ke({subset:e})}))),"advanced"===Ve&&(0,a.createElement)(a.Fragment,null,(0,i.showSettings)("marginSettings","kadence/advancedbtn")&&(0,a.createElement)(a.Fragment,null,(0,a.createElement)(l.KadencePanelBody,{panelName:"kb-single-button-margin-settings"},(0,a.createElement)(l.ResponsiveMeasureRangeControl,{label:(0,s.__)("Padding","kadence-blocks"),value:T,onChange:e=>n({padding:e}),tabletValue:E,onChangeTablet:e=>n({tabletPadding:e}),mobileValue:P,onChangeMobile:e=>n({mobilePadding:e}),min:"em"===R||"rem"===R?-25:-400,max:"em"===R||"rem"===R?25:400,step:"em"===R||"rem"===R?.1:1,unit:R,units:["px","em","rem"],onUnit:e=>n({paddingUnit:e}),onMouseOver:Le.onMouseOver,onMouseOut:Le.onMouseOut}),(0,a.createElement)(l.ResponsiveMeasureRangeControl,{label:(0,s.__)("Margin","kadence-blocks"),value:Ce,onChange:e=>n({margin:e}),tabletValue:_e,onChangeTablet:e=>n({tabletMargin:e}),mobileValue:xe,onChangeMobile:e=>n({mobileMargin:e}),min:"em"===Se||"rem"===Se?-25:-400,max:"em"===Se||"rem"===Se?25:400,step:"em"===Se||"rem"===Se?.1:1,unit:Se,units:["px","em","rem"],onUnit:e=>n({marginUnit:e}),onMouseOver:Ue.onMouseOver,onMouseOut:Ue.onMouseOut}),(0,a.createElement)(b.TextControl,{label:(0,s.__)("Add Aria Label","kadence-blocks"),value:ke||"",onChange:e=>n({label:e}),className:"kb-textbox-style"})),(0,a.createElement)("div",{className:"kt-sidebar-settings-spacer"})),(0,a.createElement)(l.KadenceBlockDefaults,{attributes:o,defaultAttributes:e.attributes,blockSlug:e.name,excludedAttrs:Vt})))),(0,a.createElement)("div",{id:`animate-id${k}`,className:"btn-inner-wrap aos-animate kt-animation-wrap","data-aos":Be||void 0,"data-aos-duration":Oe&&Oe[0]&&Oe[0].duration?Oe[0].duration:void 0,"data-aos-easing":Oe&&Oe[0]&&Oe[0].easing?Oe[0].easing:void 0},(0,a.createElement)("span",{className:Dt,style:{paddingTop:""!==tt?(0,i.getSpacingOptionOutput)(tt,R):void 0,paddingRight:""!==ot?(0,i.getSpacingOptionOutput)(ot,R):void 0,paddingBottom:""!==nt?(0,i.getSpacingOptionOutput)(nt,R):void 0,paddingLeft:""!==at?(0,i.getSpacingOptionOutput)(at,R):void 0,marginTop:""!==Je?(0,i.getSpacingOptionOutput)(Je,et):void 0,marginRight:""!==Xe?(0,i.getSpacingOptionOutput)(Xe,et):void 0,marginBottom:""!==Ye?(0,i.getSpacingOptionOutput)(Ye,et):void 0,marginLeft:""!==Ze?(0,i.getSpacingOptionOutput)(Ze,et):void 0,borderTop:bt||void 0,borderRight:mt||void 0,borderBottom:yt||void 0,borderLeft:ft||void 0,borderTopLeftRadius:""!==it?it+(J||"px"):void 0,borderTopRightRadius:""!==lt?lt+(J||"px"):void 0,borderBottomRightRadius:""!==rt?rt+(J||"px"):void 0,borderBottomLeftRadius:""!==dt?dt+(J||"px"):void 0,boxShadow:void 0!==re&&re&&void 0!==de&&void 0!==de[0]&&void 0!==de[0].color?(void 0!==de[0].inset&&de[0].inset?"inset ":"")+(void 0!==de[0].hOffset?de[0].hOffset:0)+"px "+(void 0!==de[0].vOffset?de[0].vOffset:0)+"px "+(void 0!==de[0].blur?de[0].blur:14)+"px "+(void 0!==de[0].spread?de[0].spread:0)+"px "+(0,i.KadenceColorOutput)(void 0!==de[0].color?de[0].color:"#000000",void 0!==de[0].opacity?de[0].opacity:1):void 0,background:void 0!==Lt?Lt:void 0,color:void 0!==H?(0,i.KadenceColorOutput)(H):void 0,width:void 0!==le&&"fixed"===le&&"px"===(void 0!==ie?ie:"px")&&""!==vt?vt+(void 0!==ie?ie:"px"):void 0}},te&&"left"===oe&&(0,a.createElement)(l.IconRender,{className:`kt-btn-svg-icon kt-btn-svg-icon-${te} kt-btn-side-${oe}`,name:te,size:"1em",style:{fontSize:st?(0,i.getFontSizeOptionOutput)(st,void 0!==we?we:"px"):void 0,color:""!==fe?(0,i.KadenceColorOutput)(fe):void 0,paddingTop:ct?(0,i.getSpacingOptionOutput)(ct,me):void 0,paddingRight:ut?(0,i.getSpacingOptionOutput)(ut,me):void 0,paddingBottom:pt?(0,i.getSpacingOptionOutput)(pt,me):void 0,paddingLeft:gt?(0,i.getSpacingOptionOutput)(gt,me):void 0}}),(0,a.createElement)(v.RichText,{tagName:"div",placeholder:(0,s.__)("Button...","kadence-blocks"),value:S,onChange:e=>n({text:e}),allowedFormats:(0,m.applyFilters)("kadence.whitelist_richtext_formats",["kadence/insert-dynamic","core/bold","core/italic","core/strikethrough","toolset/inline-field"],"kadence/advancedbtn"),className:"kt-button-text",keepPlaceholderOnFocus:!0}),te&&"left"!==oe&&(0,a.createElement)(l.IconRender,{className:`kt-btn-svg-icon kt-btn-svg-icon-${te} kt-btn-side-${oe}`,name:te,size:"1em",style:{fontSize:st?(0,i.getFontSizeOptionOutput)(st,void 0!==we?we:"px"):void 0,color:""!==fe?(0,i.KadenceColorOutput)(fe):void 0,paddingTop:ct?(0,i.getSpacingOptionOutput)(ct,me):void 0,paddingRight:ut?(0,i.getSpacingOptionOutput)(ut,me):void 0,paddingBottom:pt?(0,i.getSpacingOptionOutput)(pt,me):void 0,paddingLeft:gt?(0,i.getSpacingOptionOutput)(gt,me):void 0}}),(0,a.createElement)(l.SpacingVisualizer,{type:"inside",forceShow:Le.isMouseOver,spacing:[(0,i.getSpacingOptionOutput)(tt,R),(0,i.getSpacingOptionOutput)(ot,R),(0,i.getSpacingOptionOutput)(nt,R),(0,i.getSpacingOptionOutput)(at,R)]})),(0,a.createElement)(l.SpacingVisualizer,{type:"inside",forceShow:Ue.isMouseOver,spacing:[(0,i.getSpacingOptionOutput)(Je,et),(0,i.getSpacingOptionOutput)(Xe,et),(0,i.getSpacingOptionOutput)(Ye,et),(0,i.getSpacingOptionOutput)(Ze,et)]}),G?.[0]?.google&&(0,a.createElement)(l.KadenceWebfontLoader,{typography:G,clientId:f,id:"typography"})))},save:()=>null,example:{attributes:{text:(0,s.__)("Click Me!","kadence-blocks")}}})})(),(this.kadence=this.kadence||{})["blocks-singlebtn"]=n})();