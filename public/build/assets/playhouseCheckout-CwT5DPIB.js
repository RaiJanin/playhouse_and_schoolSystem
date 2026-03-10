import{a as O,A as L,s as m,g as I}from"./requestApi-CHIgbzXx.js";document.addEventListener("DOMContentLoaded",()=>{const E=document.getElementById("checkout-search-form"),c=document.getElementById("search-results"),b=document.getElementById("orders-list"),w=document.getElementById("no-results"),u=document.getElementById("loading"),f=document.getElementById("error-message"),y=document.getElementById("success-modal"),l=document.getElementById("modal-container");l.querySelector(".modal-footer").classList.add("hidden");let p=null,g=null,h=null;E.addEventListener("submit",function(a){if(a.preventDefault(),c.classList.add("hidden"),w.classList.add("hidden"),f.classList.add("hidden"),b.innerHTML="",p=document.getElementById("search-phone").value.trim(),g=document.getElementById("search-guardian").value.trim(),h=document.getElementById("search-order").value.trim(),!p&&!g&&!h){x("Please enter either a phone number, order number or guardian/parent name.");return}$(p,g,h)}),window.handleCheckout=async function(a){if(confirm("Are you sure you want to check out this order?")){u.classList.remove("hidden"),c.classList.add("hidden");try{const e=await O(L.checkOutURL,null,"PATCH",a);if(m("log","Child checked out: ",e),u.classList.add("hidden"),e.checked_out){const t=e.orderItem,n=Number(t.durationsubtotal)+Number(t.socksprice),r=Number(t.lne_xtra_chrg||0),s=n+r;let d="";const o=Math.max(0,(new Date(t.updated_at)-new Date(t.created_at))/6e4-t.durationhours*60),i=Math.ceil(o/window.masterfile.minutesPerCharge);d+=`
                    <div class="mb-5 rounded-xl border border-gray-200 bg-white shadow-sm p-4">
                        <div class="flex items-center justify-between mb-2">
                            <p class="font-semibold text-gray-800">
                                ${t.child?.firstname||"N/A"} ${t.child?.lastname||""}
                            </p>
                            <span class="text-xs px-2 py-1 bg-teal-100 text-teal-700 rounded-lg">
                                ${t.durationhours} hr(s)
                            </span>
                        </div>
                        <div class="grid grid-cols-2 gap-y-1 text-sm text-gray-600">
                            <span>Play Duration</span>
                            <span class="text-right font-medium text-gray-800">
                                ₱${Number(t.durationsubtotal).toFixed(2)}
                            </span>
                            <span>Socks</span>
                            <span class="text-right font-medium text-gray-800">
                                ₱${Number(t.socksprice).toFixed(2)}
                            </span>
                            <span class="font-semibold text-gray-700">Subtotal</span>
                            <span class="text-right font-semibold text-gray-900">
                                ₱${n.toFixed(2)}
                            </span>
                        </div>
                        ${r>0?`
                            <div class="mt-3 rounded-lg bg-red-50 border border-red-200 p-3 text-sm">
                                <p class="font-semibold text-red-600 mb-1">⚠ Extra Charges</p>
                                <div class="grid grid-cols-2 gap-y-1 text-red-600">
                                    <span>Overtime</span>
                                    <span class="text-right">${Math.max(0,Math.round(o))} min</span>
                                    <span>Charge Units</span>
                                    <span class="text-right">${i}</span>
                                    <span>Rate</span>
                                    <span class="text-right">
                                        ₱${window.masterfile.chargeOfMinutes} / ${window.masterfile.minutesPerCharge} min
                                    </span>
                                    <span class="font-semibold">Extra Total</span>
                                    <span class="text-right font-semibold">
                                        ₱${r.toFixed(2)}
                                    </span>
                                </div>
                            </div>
                        `:""}
                        <div class="mt-3 flex justify-between border-t pt-2">
                            <span class="font-semibold text-gray-800">Total</span>
                            <span class="font-bold text-lg text-teal-600">
                                ₱${s.toFixed(2)}
                            </span>
                        </div>
                    </div>
                    `;const v=`
                    <div class="space-y-4">
                        <div class="rounded-xl bg-gray-50 border border-gray-200 p-4">
                            <p class="text-sm text-gray-500">Order Number</p>
                            <p class="font-bold text-lg text-gray-800">${t.order.ord_code_ph}</p>

                            <p class="text-sm text-gray-500 mt-2">Parent</p>
                            <p class="font-semibold text-gray-700">${t.order.parent}</p>
                        </div>
                        ${d}
                        <div class="rounded-xl bg-teal-50 border border-teal-200 p-4 space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Holding Order Total</span>
                                <span class="font-semibold text-gray-800">
                                    ₱${Number(e.holdingTotal).toFixed(2)}
                                </span>
                            </div>
                            <div class="flex justify-between text-lg border-t pt-2">
                                <span class="font-bold text-gray-800">New Order Total</span>
                                <span class="font-bold text-teal-600">
                                    ₱${Number(t.order.total_amnt).toFixed(2)}
                                </span>
                            </div>
                        </div>
                    </div>
                    `;document.getElementById("checkout-details").innerHTML=v,y.classList.remove("hidden")}else x(e.message||"Checkout failed. Please try again."),c.classList.remove("hidden")}catch(e){u.classList.add("hidden"),x("Error during checkout: "+e.message),console.error(e),m("error","Error during checkout: ",e.message),c.classList.remove("hidden")}}},l.querySelector(".close-modal").addEventListener("click",()=>{l.classList.add("hidden")}),y.querySelector(".close-success-modal").addEventListener("click",a=>{a.preventDefault(),y.classList.add("hidden"),b.innerHTML="",$(p,g,h)});async function $(a="",e="",t=""){u.classList.remove("hidden");const n=await I("GET",`${L.getOrdersURL}?ph_num=${a}&grdian_name=${e}&ord_code=${t}`);if(m("log","Orders: ",n),u.classList.add("hidden"),n.not_found){x(n.message);return}n.orders&&n.orders.length>=1?C(n.orders):w.classList.remove("hidden")}function k(a){const e=JSON.parse(a.dataset.items),t=a.dataset.orderNum,n=document.getElementById("items-subcat-container");n.innerHTML="",m("log","Order Items: ",e),l.classList.remove("hidden"),l.querySelector(".modal-title").textContent=t,n.innerHTML=e.map(r=>{const s=M(r);return`
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-300 mb-3">
                    <p class="font-medium text-gray-800">
                        ${r.child.firstname} ${r.child.lastname}
                    </p>
                    <div class="mt-2 space-y-1 text-sm text-gray-600">
                        <div class="flex justify-between">
                            <span>Play duration</span>
                            <span>${r.durationhours} hr(s) — ₱${Number(r?.durationsubtotal).toFixed(2)}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span>Socks</span>
                            <span>₱${Number(r?.socksprice).toFixed(2)||""}</span>
                        </div>
                        
                    </div>
                    <div class="mt-2 pt-2 border-t border-gray-200 flex justify-between font-medium text-gray-800">
                        <span>Subtotal</span>
                        <span>₱${s.subtotal.toFixed(2)}</span>
                    </div>

                    
                    <div class="mt-2 text-red-500">
                        <div class="flex justify-between">
                            <span>Extra Charge (Preview)</span>
                            <span>₱${s.extraCharge.toFixed(2)}</span>
                        </div>
                        <div class="text-sm text-gray-600 mt-1">
                            <p>Overtime: ${s.extraMinutes} minute(s) (actual ${s.actualMinutes} - paid ${s.paidMinutes})</p>
                            <p>Rate: ₱${window.masterfile.chargeOfMinutes} per ${window.masterfile.minutesPerCharge} minutes</p>
                            <p>Number of ${window.masterfile.minutesPerCharge}-minute blocks: ${s.chargeUnits}</p>
                            <p>Extra Charge = ${s.chargeUnits} × ₱${window.masterfile.chargeOfMinutes} = ₱${s.extraCharge.toFixed(2)}</p>
                        </div>
                    </div>
                    

                    <div class="mt-1 pt-1 border-t border-gray-200 flex justify-between font-semibold">
                        <span>Total (Preview)</span>
                        <span>₱${s.totalWithExtra.toFixed(2)}</span>
                    </div>
                    <button 
                        data-check-out-id="${r.id}"
                        class="check-out-btn mt-3 w-full bg-gradient-to-t from-[var(--color-primary)] to-[var(--color-primary-light)] text-white font-bold py-2 px-4 rounded-lg hover:bg-[var(--color-primary-light)] transition-colors"
                    >
                        Check out
                    </button>
                </div>
            `}).join(""),n.querySelectorAll(".check-out-btn").forEach(r=>{r.addEventListener("click",s=>{const d=s.currentTarget.dataset.checkOutId;m("log","Check out ID: ",d),l.classList.add("hidden"),handleCheckout(d)})})}window.viewOrder=k;function C(a){c.classList.remove("hidden"),a.forEach(e=>{const t=document.createElement("div");t.className="bg-gray-50 border border-gray-50 rounded-lg shadow-md p-4";const n=e.order_items||[],r=n.length;let s="";n.forEach(d=>{const o=d.durationhours==5?"Unlimited":d.durationhours+" hours",i=d.socksqty>0?`, ${d.socksqty} pair(s) of socks`:"",v=`${d.child.firstname} ${d.child.lastname}`;s+=`<li class="text-gray-600"><span class="text-gray-800 font-medium">${v}</span> ${o}${i}</li>`}),t.innerHTML=`
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h4 class="font-bold text-gray-800">Order #${e.ord_code_ph}</h4>
                        <p class="text-sm text-gray-600">Parent: ${e.parent}</p>
                        <p class="text-sm text-gray-600">Total: ₱${e.total_amnt}</p>
                    </div>
                    <span class="bg-green-100 text-green-800 text-sm font-medium px-3 py-1 rounded-full">
                        ${r} child(ren)
                    </span>
                    
                </div>
                <ul class="mb-4 ml-4">
                    ${s}
                </ul>
                <button 
                    data-items='${JSON.stringify(e.order_items)}'
                    data-orderNum='${e.ord_code_ph}'
                    onclick="viewOrder(this)"
                    class="w-full bg-gradient-to-t from-[var(--color-primary)] to-[var(--color-primary-light)] text-white font-bold py-2 px-4 rounded-lg hover:bg-[var(--color-primary-light)]] transition-colors"
                >
                    View Order
                </button>
            `,b.appendChild(t)})}function M(a){const e=Number(a?.durationsubtotal)+Number(a?.socksprice),t=new Date(a.created_at),n=new Date,r=a.durationhours*60,s=Math.ceil((n-t)/6e4);let d=0,o=0,i=0;return s>r&&(d=s-r,o=Math.ceil(d/window.masterfile.minutesPerCharge),i=o*window.masterfile.chargeOfMinutes),{subtotal:e,actualMinutes:s,paidMinutes:r,extraMinutes:d,chargeUnits:o,extraCharge:i,totalWithExtra:e+i}}function x(a){f.textContent=a,f.classList.remove("hidden")}});
