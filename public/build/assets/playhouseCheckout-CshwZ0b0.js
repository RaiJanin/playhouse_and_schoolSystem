import{a as M,A as k,s as x,g as O,d as I}from"./requestApi-xv_ic5BW.js";function _(p){const c=Number(p?.durationsubtotal)+Number(p?.socksprice),h=new Date(p.created_at),b=new Date,i=p.durationhours*60;let l=Math.ceil((b-h)/6e4);const g=300;l>g&&(l=g);let o=0,u=0,m=0;return l>i&&p.durationhours!==5&&(o=l-i,u=Math.ceil(o/window.masterfile.minutesPerCharge),m=u*window.masterfile.chargeOfMinutes),{subtotal:c,actualMinutes:l,paidMinutes:i,extraMinutes:o,chargeUnits:u,extraCharge:m,totalWithExtra:c+m}}document.addEventListener("DOMContentLoaded",()=>{const p=document.getElementById("checkout-search-form"),c=document.getElementById("search-results"),h=document.getElementById("orders-list"),b=document.getElementById("no-results"),i=document.getElementById("loading"),l=document.getElementById("error-message"),g=document.getElementById("success-modal"),o=document.getElementById("modal-container");o.querySelector(".modal-footer").classList.add("hidden");let u=null,m=null,f=null;p.addEventListener("submit",function(n){if(n.preventDefault(),c.classList.add("hidden"),b.classList.add("hidden"),l.classList.add("hidden"),h.innerHTML="",u=document.getElementById("search-phone").value.trim(),m=document.getElementById("search-guardian").value.trim(),f=document.getElementById("search-order").value.trim(),!u&&!m&&!f){y("Please enter either a phone number, order number or guardian/parent name.");return}L(u,m,f)}),App.utilites.handleCheckout=async function(n){if(confirm("Are you sure you want to check out this order?")){i.classList.remove("hidden"),c.classList.add("hidden");try{const t=await M(k.checkOutURL,null,"PATCH",n);if(x("log","Child checked out: ",t),i.classList.add("hidden"),t.checked_out){const e=t.orderItem,a=Number(e.durationsubtotal)+Number(e.socksprice),s=Number(e.lne_xtra_chrg||0),r=a+s;let d="";const v=Math.max(0,(new Date(e.updated_at)-new Date(e.created_at))/6e4-e.durationhours*60),$=Math.ceil(v/window.masterfile.minutesPerCharge);d+=`
                    <div class="mb-5 rounded-xl border border-gray-200 bg-white shadow-sm p-4">
                        <div class="flex items-center justify-between mb-2">
                            <p class="font-semibold text-gray-800">
                                ${e.child?.firstname||"N/A"} ${e.child?.lastname||""}
                            </p>
                            <span class="text-xs px-2 py-1 bg-teal-100 text-teal-700 rounded-lg">
                                ${e.durationhours} hr(s)
                            </span>
                        </div>
                        <div class="grid grid-cols-2 gap-y-1 text-sm text-gray-600">
                            <span>Play Duration</span>
                            <span class="text-right font-medium text-gray-800">
                                ₱${Number(e.durationsubtotal).toFixed(2)}
                            </span>
                            <span>Socks</span>
                            <span class="text-right font-medium text-gray-800">
                                ₱${Number(e.socksprice).toFixed(2)}
                            </span>
                            <span class="font-semibold text-gray-700">Subtotal</span>
                            <span class="text-right font-semibold text-gray-900">
                                ₱${a.toFixed(2)}
                            </span>
                        </div>
                        ${s>0?`
                            <div class="mt-3 rounded-lg bg-red-50 border border-red-200 p-3 text-sm">
                                <p class="font-semibold text-red-600 mb-1">⚠ Extra Charges</p>
                                <div class="grid grid-cols-2 gap-y-1 text-red-600">
                                    <span>Overtime</span>
                                    <span class="text-right">${Math.max(0,Math.round(v))} min</span>
                                    <span>Charge Units</span>
                                    <span class="text-right">${$}</span>
                                    <span>Rate</span>
                                    <span class="text-right">
                                        ₱${window.masterfile.chargeOfMinutes} / ${window.masterfile.minutesPerCharge} min
                                    </span>
                                    <span class="font-semibold">Extra Total</span>
                                    <span class="text-right font-semibold">
                                        ₱${s.toFixed(2)}
                                    </span>
                                </div>
                            </div>
                        `:""}
                        <div class="mt-3 flex justify-between border-t pt-2">
                            <span class="font-semibold text-gray-800">Total</span>
                            <span class="font-bold text-lg text-teal-600">
                                ₱${r.toFixed(2)}
                            </span>
                        </div>
                    </div>
                    `;const w=`
                    <div class="space-y-4">
                        <div class="rounded-xl bg-gray-50 border border-gray-200 p-4">
                            <p class="text-sm text-gray-500">Order Number</p>
                            <p class="font-bold text-lg text-gray-800">${e.order.ord_code_ph}</p>

                            <p class="text-sm text-gray-500 mt-2">Parent</p>
                            <p class="font-semibold text-gray-700">${e.order.parent}</p>
                        </div>
                        ${d}
                        <div class="rounded-xl bg-teal-50 border border-teal-200 p-4 space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Holding Order Total</span>
                                <span class="font-semibold text-gray-800">
                                    ₱${Number(t.holdingTotal).toFixed(2)}
                                </span>
                            </div>
                            <div class="flex justify-between text-lg border-t pt-2">
                                <span class="font-bold text-gray-800">New Order Total</span>
                                <span class="font-bold text-teal-600">
                                    ₱${Number(e.order.total_amnt).toFixed(2)}
                                </span>
                            </div>
                        </div>
                    </div>
                    `;document.getElementById("checkout-details").innerHTML=w,g.classList.remove("hidden")}else y(t.message||"Checkout failed. Please try again."),c.classList.remove("hidden")}catch(t){i.classList.add("hidden"),y("Error during checkout: "+t.message),console.error(t),x("error","Error during checkout: ",t.message),c.classList.remove("hidden")}}},o.querySelector(".close-modal").addEventListener("click",()=>{o.classList.add("hidden")}),g.querySelector(".close-success-modal").addEventListener("click",n=>{n.preventDefault(),g.classList.add("hidden"),h.innerHTML="",L(u,m,f)});async function L(n="",t="",e=""){i.classList.remove("hidden");const a=await O("GET",`${k.getOrdersURL}?ph_num=${n}&grdian_name=${t}&ord_code=${e}`);if(x("log","Orders: ",a),i.classList.add("hidden"),a.not_found){y(a.message);return}a.orders&&a.orders.length>=1?C(a.orders):b.classList.remove("hidden")}function E(n){const t=JSON.parse(n.dataset.items),e=n.dataset.orderNum,a=document.getElementById("items-subcat-container");a.innerHTML="",x("log","Order Items: ",t),o.classList.remove("hidden"),o.querySelector(".modal-title").textContent=e,a.innerHTML=t.map(s=>{const r=_(s);return`
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-300 mb-3">
                    <p class="font-medium text-gray-800">
                        ${s.child.firstname} ${s.child.lastname}
                    </p>
                    <p class="text-sm text-gray-800">
                        <span class="font-medium">Guardian: </span>${s.guardian?s.guardian:s.child.updatedby}
                    </p>
                    <div class="mt-2 space-y-1 text-sm text-gray-600">
                        <div class="flex justify-between">
                            <span>Play duration</span>
                            <span>${s.durationhours===5?"Unlimited":s.durationhours} hr(s) — ₱${Number(s?.durationsubtotal).toFixed(2)}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Socks</span>
                            <span>₱${Number(s?.socksprice).toFixed(2)||""}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Check-in Time</span>
                            <span>${I("timeOnly12",s?.created_at)}</span>
                        </div>
                    </div>
                    <div class="mt-2 pt-2 border-t border-gray-200 flex justify-between font-medium text-gray-800">
                        <span>Subtotal</span>
                        <span>₱${r.subtotal.toFixed(2)}</span>
                    </div>
                    <div class="mt-2 text-red-500">
                        <div class="flex justify-between">
                            <span>Extra Charge (Preview)</span>
                            <span>₱${r.extraCharge.toFixed(2)}</span>
                        </div>
                        <div class="text-sm text-gray-600 mt-1">
                            <p>Overtime: ${r.extraMinutes} minute(s) (actual ${r.actualMinutes} - paid ${r.paidMinutes})</p>
                            <p>Rate: ₱${window.masterfile.chargeOfMinutes} per ${window.masterfile.minutesPerCharge} minutes</p>
                            <p>Number of ${window.masterfile.minutesPerCharge}-minute blocks: ${r.chargeUnits}</p>
                            <p>Extra Charge = ${r.chargeUnits} × ₱${window.masterfile.chargeOfMinutes} = ₱${r.extraCharge.toFixed(2)}</p>
                        </div>
                    </div>
                    <div class="mt-1 pt-1 border-t border-gray-200 flex justify-between font-semibold">
                        <span>Total (Preview)</span>
                        <span>₱${r.totalWithExtra.toFixed(2)}</span>
                    </div>
                    <button 
                        data-check-out-id="${s.id}"
                        class="check-out-btn mt-3 w-full bg-gradient-to-t from-[var(--color-primary)] to-[var(--color-primary-light)] text-white font-bold py-2 px-4 rounded-lg hover:bg-[var(--color-primary-light)] transition-colors"
                    >
                        Check out
                    </button>
                </div>
            `}).join(""),a.querySelectorAll(".check-out-btn").forEach(s=>{s.addEventListener("click",r=>{const d=r.currentTarget.dataset.checkOutId;x("log","Check out ID: ",d),o.classList.add("hidden"),App.utilites.handleCheckout(d)})})}window.viewOrder=E;function C(n){c.classList.remove("hidden"),n.forEach(t=>{const e=document.createElement("div");e.className="bg-gray-50 border border-gray-50 rounded-lg shadow-md p-4";const a=t.order_items||[],s=a.length;let r="";a.forEach(d=>{const v=d.durationhours==5?"Unlimited":d.durationhours+" hours",$=d.socksqty>0?`, ${d.socksqty} pair(s) of socks`:"",w=`${d.child.firstname} ${d.child.lastname}`;r+=`<li class="text-gray-600"><span class="text-gray-800 font-medium">${w}</span> ${v}${$}</li>`}),e.innerHTML=`
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h4 class="font-bold text-gray-800">Order #${t.ord_code_ph}</h4>
                        <p class="text-sm text-gray-600">Parent: ${t.parent}</p>
                        <p class="text-sm text-gray-600">Total: ₱${t.total_amnt}</p>
                    </div>
                    <span class="bg-green-100 text-green-800 text-sm font-medium px-3 py-1 rounded-full">
                        ${s} child(ren)
                    </span>
                    
                </div>
                <ul class="mb-4 ml-4">
                    ${r}
                </ul>
                <button 
                    data-items='${JSON.stringify(t.order_items)}'
                    data-orderNum='${t.ord_code_ph}'
                    onclick="viewOrder(this)"
                    class="w-full bg-gradient-to-t from-[var(--color-primary)] to-[var(--color-primary-light)] text-white font-bold py-2 px-4 rounded-lg hover:bg-[var(--color-primary-light)]] transition-colors"
                >
                    View Order
                </button>
            `,h.appendChild(e)})}function y(n){l.textContent=n,l.classList.remove("hidden")}});
