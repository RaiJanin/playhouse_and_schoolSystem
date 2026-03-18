import{a as M,A as E,s as b,g as O,d as I}from"./alertBlade-DgmP6ge0.js";function _(l){const u=Number(l?.durationsubtotal)+Number(l?.socksprice),h=new Date(l.created_at),x=new Date,d=l.durationhours*60;let c=Math.ceil((x-h)/6e4);const g=300;c>g&&(c=g);let i=0,m=0,p=0;return c>d&&l.durationhours!==5&&(i=c-d,m=Math.ceil(i/window.masterfile.minutesPerCharge),p=m*window.masterfile.chargeOfMinutes),{subtotal:u,actualMinutes:c,paidMinutes:d,extraMinutes:i,chargeUnits:m,extraCharge:p,totalWithExtra:u+p}}document.addEventListener("DOMContentLoaded",()=>{const l=document.getElementById("checkout-search-form"),u=document.getElementById("search-results"),h=document.getElementById("orders-list"),x=document.getElementById("no-results"),d=document.getElementById("loading"),c=document.getElementById("error-message"),g=document.getElementById("success-modal"),i=document.getElementById("modal-container");i.querySelector(".modal-footer").classList.add("hidden");let m=null,p=null,y=null;l.addEventListener("submit",function(n){if(n.preventDefault(),u.classList.add("hidden"),x.classList.add("hidden"),c.classList.add("hidden"),h.innerHTML="",m=document.getElementById("search-phone").value.trim(),p=document.getElementById("search-guardian").value.trim(),y=document.getElementById("search-order").value.trim(),!m&&!p&&!y){L("Please enter either a phone number, order number or guardian/parent name.");return}w(m,p,y)}),App.utilites.handleCheckout=async function(n){if(confirm("Are you sure you want to check out this order?")){d.classList.remove("hidden"),u.classList.add("hidden");try{const s=await M(E.checkOutURL,null,"PATCH",n);if(b("log","Child checked out: ",s),d.classList.add("hidden"),s.checked_out){const t=s.orderItem,r=Number(t.durationsubtotal)+Number(t.socksprice),e=Number(t.lne_xtra_chrg||0),a=r+e;let o="";const f=Math.max(0,(new Date(t.updated_at)-new Date(t.created_at))/6e4-t.durationhours*60),v=Math.ceil(f/window.masterfile.minutesPerCharge);o+=`
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
                                ₱${r.toFixed(2)}
                            </span>
                        </div>
                        ${e>0?`
                            <div class="mt-3 rounded-lg bg-red-50 border border-red-200 p-3 text-sm">
                                <p class="font-semibold text-red-600 mb-1">⚠ Extra Charges</p>
                                <div class="grid grid-cols-2 gap-y-1 text-red-600">
                                    <span>Overtime</span>
                                    <span class="text-right">${Math.max(0,Math.round(f))} min</span>
                                    <span>Charge Units</span>
                                    <span class="text-right">${v}</span>
                                    <span>Rate</span>
                                    <span class="text-right">
                                        ₱${window.masterfile.chargeOfMinutes} / ${window.masterfile.minutesPerCharge} min
                                    </span>
                                    <span class="font-semibold">Extra Total</span>
                                    <span class="text-right font-semibold">
                                        ₱${e.toFixed(2)}
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
                    `;const $=`
                    <div class="space-y-4">
                        <div class="rounded-xl bg-gray-50 border border-gray-200 p-4">
                            <p class="text-sm text-gray-500">Order Number</p>
                            <p class="font-bold text-lg text-gray-800">${t.order.ord_code_ph}</p>

                            <p class="text-sm text-gray-500 mt-2">Parent</p>
                            <p class="font-semibold text-gray-700">${t.order.parent}</p>
                        </div>
                        ${o}
                        <div class="rounded-xl bg-teal-50 border border-teal-200 p-4 space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Holding Order Total</span>
                                <span class="font-semibold text-gray-800">
                                    ₱${Number(s.holdingTotal).toFixed(2)}
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
                    `;document.getElementById("checkout-details").innerHTML=$,g.classList.remove("hidden")}else App.component.showAlert("Checkout failed. Please try again.","error"),u.classList.remove("hidden")}catch(s){d.classList.add("hidden"),App.component.showAlert("Error during checkout. Server Error","error"),console.error(s),b("error","Error during checkout"),u.classList.remove("hidden"),App.component.criticalAlert(`Error: ${s.status}
Message: ${s.data?.message||s.statusText||"Unknown error"}`)}}},i.querySelector(".close-modal").addEventListener("click",()=>{i.classList.add("hidden")}),g.querySelector(".close-success-modal").addEventListener("click",n=>{n.preventDefault(),g.classList.add("hidden"),h.innerHTML="",w(m,p,y)});async function w(n="",s="",t=""){d.classList.remove("hidden");const r=l.querySelector("button");r.disabled=!0,r.classList.remove("bg-gradient-to-t","from-[var(--color-primary)]","to-[var(--color-primary-light)]"),r.classList.add("bg-[var(--color-primary-light)]");try{const e=await O("GET",`${E.getOrdersURL}?ph_num=${n}&grdian_name=${s}&ord_code=${t}`);if(b("log","Orders: ",e),d.classList.add("hidden"),e.not_found){L(e.message);return}e.orders&&e.orders.length>=1?C(e.orders):x.classList.remove("hidden")}catch(e){App.component.criticalAlert(`Error: ${e.status}
Message: ${e.data?.message||e.statusText||"Unknown error"}`)}finally{d.classList.add("hidden"),r.classList.remove("bg-[var(--color-primary-light)]"),r.classList.add("bg-gradient-to-t","from-[var(--color-primary)]","to-[var(--color-primary-light)]"),l.querySelector("button").disabled=!1}}function k(n){const s=JSON.parse(n.dataset.items),t=n.dataset.orderNum,r=document.getElementById("items-subcat-container");r.innerHTML="",b("log","Order Items: ",s),i.classList.remove("hidden"),i.querySelector(".modal-title").textContent=t,r.innerHTML=s.map(e=>{const a=_(e);return`
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-300 mb-3">
                    <p class="font-medium text-gray-800">
                        ${e.child.firstname} ${e.child.lastname}
                    </p>
                    <p class="text-sm text-gray-800">
                        <span class="font-medium">Guardian: </span>${e.guardian?e.guardian:e.child.updatedby}
                    </p>
                    <div class="mt-2 space-y-1 text-sm text-gray-600">
                        <div class="flex justify-between">
                            <span>Play duration</span>
                            <span>${e.durationhours===5?"Unlimited":e.durationhours} hr(s) — ₱${Number(e?.durationsubtotal).toFixed(2)}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Socks</span>
                            <span>₱${Number(e?.socksprice).toFixed(2)||""}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Check-in Time</span>
                            <span>${I("timeOnly12",e?.created_at)}</span>
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
                        data-check-out-id="${e.id}"
                        class="check-out-btn mt-3 w-full bg-gradient-to-t from-[var(--color-primary)] to-[var(--color-primary-light)] text-white font-bold py-2 px-4 rounded-lg hover:bg-[var(--color-primary-light)] transition-colors"
                    >
                        Check out
                    </button>
                </div>
            `}).join(""),r.querySelectorAll(".check-out-btn").forEach(e=>{e.addEventListener("click",a=>{const o=a.currentTarget.dataset.checkOutId;b("log","Check out ID: ",o),i.classList.add("hidden"),e.disabled=!0,App.utilites.handleCheckout(o),e.disabled=!1})})}window.viewOrder=k;function C(n){u.classList.remove("hidden"),n.forEach(s=>{const t=document.createElement("div");t.className="bg-gray-50 border border-gray-50 rounded-lg shadow-md p-4";const r=s.order_items||[],e=r.length;let a="";r.forEach(o=>{const f=o.durationhours==5?"Unlimited":o.durationhours+" hours",v=o.socksqty>0?`, ${o.socksqty} pair(s) of socks`:"",$=`${o.child.firstname} ${o.child.lastname}`;a+=`<li class="text-gray-600"><span class="text-gray-800 font-medium">${$}</span> ${f}${v}</li>`}),t.innerHTML=`
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h4 class="font-bold text-gray-800">Order #${s.ord_code_ph}</h4>
                        <p class="text-sm text-gray-600">Parent: ${s.parent}</p>
                        <p class="text-sm text-gray-600">Total: ₱${s.total_amnt}</p>
                    </div>
                    <span class="bg-green-100 text-green-800 text-sm font-medium px-3 py-1 rounded-full">
                        ${e} child(ren)
                    </span>
                    
                </div>
                <ul class="mb-4 ml-4">
                    ${a}
                </ul>
                <button 
                    data-items='${JSON.stringify(s.order_items)}'
                    data-orderNum='${s.ord_code_ph}'
                    onclick="viewOrder(this)"
                    class="w-full bg-gradient-to-t from-[var(--color-primary)] to-[var(--color-primary-light)] text-white font-bold py-2 px-4 rounded-lg hover:bg-[var(--color-primary-light)]] transition-colors"
                >
                    View Order
                </button>
            `,h.appendChild(t)})}function L(n){c.textContent=n,c.classList.remove("hidden")}});
