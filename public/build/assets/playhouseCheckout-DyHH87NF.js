import{a as O,A as L,s as p,g as I,d as _}from"./requestApi-BQivj6Nk.js";document.addEventListener("DOMContentLoaded",()=>{const k=document.getElementById("checkout-search-form"),u=document.getElementById("search-results"),f=document.getElementById("orders-list"),$=document.getElementById("no-results"),m=document.getElementById("loading"),y=document.getElementById("error-message"),v=document.getElementById("success-modal"),c=document.getElementById("modal-container");c.querySelector(".modal-footer").classList.add("hidden");let g=null,h=null,x=null;k.addEventListener("submit",function(r){if(r.preventDefault(),u.classList.add("hidden"),$.classList.add("hidden"),y.classList.add("hidden"),f.innerHTML="",g=document.getElementById("search-phone").value.trim(),h=document.getElementById("search-guardian").value.trim(),x=document.getElementById("search-order").value.trim(),!g&&!h&&!x){b("Please enter either a phone number, order number or guardian/parent name.");return}w(g,h,x)}),App.utilites.handleCheckout=async function(r){if(confirm("Are you sure you want to check out this order?")){m.classList.remove("hidden"),u.classList.add("hidden");try{const e=await O(L.checkOutURL,null,"PATCH",r);if(p("log","Child checked out: ",e),m.classList.add("hidden"),e.checked_out){const t=e.orderItem,n=Number(t.durationsubtotal)+Number(t.socksprice),s=Number(t.lne_xtra_chrg||0),a=n+s;let d="";const o=Math.max(0,(new Date(t.updated_at)-new Date(t.created_at))/6e4-t.durationhours*60),i=Math.ceil(o/window.masterfile.minutesPerCharge);d+=`
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
                        ${s>0?`
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
                                        ₱${s.toFixed(2)}
                                    </span>
                                </div>
                            </div>
                        `:""}
                        <div class="mt-3 flex justify-between border-t pt-2">
                            <span class="font-semibold text-gray-800">Total</span>
                            <span class="font-bold text-lg text-teal-600">
                                ₱${a.toFixed(2)}
                            </span>
                        </div>
                    </div>
                    `;const l=`
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
                    `;document.getElementById("checkout-details").innerHTML=l,v.classList.remove("hidden")}else b(e.message||"Checkout failed. Please try again."),u.classList.remove("hidden")}catch(e){m.classList.add("hidden"),b("Error during checkout: "+e.message),console.error(e),p("error","Error during checkout: ",e.message),u.classList.remove("hidden")}}},c.querySelector(".close-modal").addEventListener("click",()=>{c.classList.add("hidden")}),v.querySelector(".close-success-modal").addEventListener("click",r=>{r.preventDefault(),v.classList.add("hidden"),f.innerHTML="",w(g,h,x)});async function w(r="",e="",t=""){m.classList.remove("hidden");const n=await I("GET",`${L.getOrdersURL}?ph_num=${r}&grdian_name=${e}&ord_code=${t}`);if(p("log","Orders: ",n),m.classList.add("hidden"),n.not_found){b(n.message);return}n.orders&&n.orders.length>=1?C(n.orders):$.classList.remove("hidden")}function E(r){const e=JSON.parse(r.dataset.items),t=r.dataset.orderNum,n=document.getElementById("items-subcat-container");n.innerHTML="",p("log","Order Items: ",e),c.classList.remove("hidden"),c.querySelector(".modal-title").textContent=t,n.innerHTML=e.map(s=>{const a=M(s);return`
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
                            <span>${_("timeOnly12",s?.created_at)}</span>
                        </div>
                    </div>
                    <div class="mt-2 pt-2 border-t border-gray-200 flex justify-between font-medium text-gray-800">
                        <span>Subtotal</span>
                        <span>₱${a.subtotal.toFixed(2)}</span>
                    </div>
                    <div class="mt-2 text-red-500">
                        <div class="flex justify-between">
                            <span>Extra Charge (Preview)</span>
                            <span>₱${a.extraCharge.toFixed(2)}</span>
                        </div>
                        <div class="text-sm text-gray-600 mt-1">
                            <p>Overtime: ${a.extraMinutes} minute(s) (actual ${a.actualMinutes} - paid ${a.paidMinutes})</p>
                            <p>Rate: ₱${window.masterfile.chargeOfMinutes} per ${window.masterfile.minutesPerCharge} minutes</p>
                            <p>Number of ${window.masterfile.minutesPerCharge}-minute blocks: ${a.chargeUnits}</p>
                            <p>Extra Charge = ${a.chargeUnits} × ₱${window.masterfile.chargeOfMinutes} = ₱${a.extraCharge.toFixed(2)}</p>
                        </div>
                    </div>
                    <div class="mt-1 pt-1 border-t border-gray-200 flex justify-between font-semibold">
                        <span>Total (Preview)</span>
                        <span>₱${a.totalWithExtra.toFixed(2)}</span>
                    </div>
                    <button 
                        data-check-out-id="${s.id}"
                        class="check-out-btn mt-3 w-full bg-gradient-to-t from-[var(--color-primary)] to-[var(--color-primary-light)] text-white font-bold py-2 px-4 rounded-lg hover:bg-[var(--color-primary-light)] transition-colors"
                    >
                        Check out
                    </button>
                </div>
            `}).join(""),n.querySelectorAll(".check-out-btn").forEach(s=>{s.addEventListener("click",a=>{const d=a.currentTarget.dataset.checkOutId;p("log","Check out ID: ",d),c.classList.add("hidden"),App.utilites.handleCheckout(d)})})}window.viewOrder=E;function C(r){u.classList.remove("hidden"),r.forEach(e=>{const t=document.createElement("div");t.className="bg-gray-50 border border-gray-50 rounded-lg shadow-md p-4";const n=e.order_items||[],s=n.length;let a="";n.forEach(d=>{const o=d.durationhours==5?"Unlimited":d.durationhours+" hours",i=d.socksqty>0?`, ${d.socksqty} pair(s) of socks`:"",l=`${d.child.firstname} ${d.child.lastname}`;a+=`<li class="text-gray-600"><span class="text-gray-800 font-medium">${l}</span> ${o}${i}</li>`}),t.innerHTML=`
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h4 class="font-bold text-gray-800">Order #${e.ord_code_ph}</h4>
                        <p class="text-sm text-gray-600">Parent: ${e.parent}</p>
                        <p class="text-sm text-gray-600">Total: ₱${e.total_amnt}</p>
                    </div>
                    <span class="bg-green-100 text-green-800 text-sm font-medium px-3 py-1 rounded-full">
                        ${s} child(ren)
                    </span>
                    
                </div>
                <ul class="mb-4 ml-4">
                    ${a}
                </ul>
                <button 
                    data-items='${JSON.stringify(e.order_items)}'
                    data-orderNum='${e.ord_code_ph}'
                    onclick="viewOrder(this)"
                    class="w-full bg-gradient-to-t from-[var(--color-primary)] to-[var(--color-primary-light)] text-white font-bold py-2 px-4 rounded-lg hover:bg-[var(--color-primary-light)]] transition-colors"
                >
                    View Order
                </button>
            `,f.appendChild(t)})}function M(r){const e=Number(r?.durationsubtotal)+Number(r?.socksprice),t=new Date(r.created_at),n=new Date,s=r.durationhours*60;let a=Math.ceil((n-t)/6e4);const d=300;a>d&&(a=d);let o=0,i=0,l=0;return a>s&&r.durationhours!==5&&(o=a-s,i=Math.ceil(o/window.masterfile.minutesPerCharge),l=i*window.masterfile.chargeOfMinutes),{subtotal:e,actualMinutes:a,paidMinutes:s,extraMinutes:o,chargeUnits:i,extraCharge:l,totalWithExtra:e+l}}function b(r){y.textContent=r,y.classList.remove("hidden")}});
