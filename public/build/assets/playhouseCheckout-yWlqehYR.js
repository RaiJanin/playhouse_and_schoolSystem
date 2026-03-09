import{g as E,A as y,s as m,a as L}from"./requestApi-CHIgbzXx.js";window.masterfile={chargeOfMinutes:20,minutesPerCharge:2};document.addEventListener("DOMContentLoaded",()=>{const v=document.getElementById("checkout-search-form"),l=document.getElementById("search-results"),f=document.getElementById("orders-list"),b=document.getElementById("no-results"),u=document.getElementById("loading"),g=document.getElementById("error-message"),x=document.getElementById("success-modal"),c=document.getElementById("modal-container");c.querySelector(".modal-footer").classList.add("hidden"),v.addEventListener("submit",async function(a){a.preventDefault(),l.classList.add("hidden"),b.classList.add("hidden"),g.classList.add("hidden"),f.innerHTML="";const t=document.getElementById("search-phone").value.trim(),r=document.getElementById("search-guardian").value.trim(),n=document.getElementById("search-order").value.trim();if(!t&&!r&&!n){h("Please enter either a phone number, order number or guardian/parent name.");return}u.classList.remove("hidden");const e=await E("GET",`${y.getOrdersURL}?ph_num=${t}&grdian_name=${r}&ord_code=${n}`);if(m("log","Orders: ",e),u.classList.add("hidden"),e.not_found){h(e.message);return}e.orders&&e.orders.length>=1?w(e.orders):b.classList.remove("hidden")}),window.handleCheckout=async function(a){if(confirm("Are you sure you want to check out this order?")){u.classList.remove("hidden"),l.classList.add("hidden");try{const t=await L(y.checkOutURL,null,"PATCH",a);if(m("log","Child checked out: ",t),u.classList.add("hidden"),t.checked_out){const r=t.orderItem,n=Number(r.durationsubtotal)+Number(r.socksprice),e=Number(r.lne_xtra_chrg||0),s=n+e;let o="";const d=Math.max(0,(new Date(r.updated_at)-new Date(r.created_at))/6e4-r.durationhours*60),i=Math.ceil(d/window.masterfile.minutesPerCharge);o+=`
                    <div class="mb-4 border-b border-gray-200 pb-2">
                        <p><strong>Child:</strong> ${r.child?.firstname||"N/A"} ${r.child?.lastname||""}</p>
                        <p><strong>Play Duration:</strong> ${r.durationhours} hr(s) — ₱${Number(r.durationsubtotal).toFixed(2)}</p>
                        <p><strong>Socks:</strong> ₱${Number(r.socksprice).toFixed(2)}</p>
                        <p><strong>Subtotal:</strong> ₱${n.toFixed(2)}</p>
                        ${e>0?`
                            <div class="text-red-500">
                                <p><strong>Extra Charges:</strong> ₱${e.toFixed(2)}</p>
                                <p>Overtime: ${Math.max(0,Math.round(d))} minute(s)</p>
                                <p>Charge units: ${i} × ₱${window.masterfile.chargeOfMinutes} per ${window.masterfile.minutesPerCharge} min</p>
                            </div>
                        `:""}
                        <p><strong>Total:</strong> ₱${s.toFixed(2)}</p>
                    </div>
                `;const p=`
                    <p><strong>Order #:</strong> ${a}</p>
                    <p><strong>Guardian:</strong> ${r.order.guardian}</p>
                    ${o}
                    <p><strong>Order Total:</strong> ₱${Number(r.order.total_amnt).toFixed(2)}</p>
                `;document.getElementById("checkout-details").innerHTML=p,x.classList.remove("hidden")}else h(t.message||"Checkout failed. Please try again."),l.classList.remove("hidden")}catch(t){u.classList.add("hidden"),h("Error during checkout: "+t.message),console.error(t),m("error","Error during checkout: ",t.message),l.classList.remove("hidden")}}},c.querySelector(".close-modal").addEventListener("click",()=>{c.classList.add("hidden")});function $(a){const t=JSON.parse(a.dataset.items),r=a.dataset.orderNum,n=document.getElementById("items-subcat-container");n.innerHTML="",m("log","Order Items: ",t),c.classList.remove("hidden"),c.querySelector(".modal-title").textContent=r,n.innerHTML=t.map(e=>{const s=k(e);return`
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-300 mb-3">
                    <p class="font-medium text-gray-800">
                        ${e.child.firstname} ${e.child.lastname}
                    </p>
                    <div class="mt-2 space-y-1 text-sm text-gray-600">
                        <div class="flex justify-between">
                            <span>Play duration</span>
                            <span>${e.durationhours} hr(s) — ₱${Number(e?.durationsubtotal).toFixed(2)}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span>Socks</span>
                            <span>₱${Number(e?.socksprice).toFixed(2)||""}</span>
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
                            <p>Number of 2-minute blocks: ${s.chargeUnits}</p>
                            <p>Extra Charge = ${s.chargeUnits} × ₱${window.masterfile.chargeOfMinutes} = ₱${s.extraCharge.toFixed(2)}</p>
                        </div>
                    </div>
                    

                    <div class="mt-1 pt-1 border-t border-gray-200 flex justify-between font-semibold">
                        <span>Total (Preview)</span>
                        <span>₱${s.totalWithExtra.toFixed(2)}</span>
                    </div>
                    <button 
                        data-check-out-id="${e.id}"
                        class="check-out-btn mt-3 w-full bg-gradient-to-t from-[var(--color-primary)] to-[var(--color-primary-light)] text-white font-bold py-2 px-4 rounded-lg hover:bg-[var(--color-primary-light)] transition-colors"
                    >
                        Check out
                    </button>
                </div>
            `}).join(""),n.querySelectorAll(".check-out-btn").forEach(e=>{e.addEventListener("click",s=>{const o=s.currentTarget.dataset.checkOutId;m("log","Check out ID: ",o),c.classList.add("hidden"),handleCheckout(o)})})}window.viewOrder=$;function w(a){l.classList.remove("hidden"),a.forEach(t=>{const r=document.createElement("div");r.className="bg-gray-50 border border-gray-50 rounded-lg shadow-md p-4";const n=t.order_items||[],e=n.length;let s="";n.forEach(o=>{const d=o.durationhours==5?"Unlimited":o.durationhours+" hours",i=o.socksqty>0?`, ${o.socksqty} pair(s) of socks`:"",p=`${o.child.firstname} ${o.child.lastname}`;s+=`<li class="text-gray-600"><span class="text-gray-800 font-medium">${p}</span> ${d}${i}</li>`}),r.innerHTML=`
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h4 class="font-bold text-gray-800">Order #${t.ord_code_ph}</h4>
                        <p class="text-sm text-gray-600">Guardian: ${t.guardian}</p>
                        <p class="text-sm text-gray-600">Total: ₱${t.total_amnt}</p>
                    </div>
                    <span class="bg-green-100 text-green-800 text-sm font-medium px-3 py-1 rounded-full">
                        ${e} child(ren)
                    </span>
                    
                </div>
                <ul class="mb-4 ml-4">
                    ${s}
                </ul>
                <button 
                    data-items='${JSON.stringify(t.order_items)}'
                    data-orderNum='${t.ord_code_ph}'
                    onclick="viewOrder(this)"
                    class="w-full bg-gradient-to-t from-[var(--color-primary)] to-[var(--color-primary-light)] text-white font-bold py-2 px-4 rounded-lg hover:bg-[var(--color-primary-light)]] transition-colors"
                >
                    View Order
                </button>
            `,f.appendChild(r)})}function k(a){const t=Number(a?.durationsubtotal)+Number(a?.socksprice),r=new Date(a.created_at),n=new Date,e=a.durationhours*60,s=Math.ceil((n-r)/6e4);let o=0,d=0,i=0;return s>e&&(o=s-e,d=Math.ceil(o/window.masterfile.minutesPerCharge),i=d*window.masterfile.chargeOfMinutes),{subtotal:t,actualMinutes:s,paidMinutes:e,extraMinutes:o,chargeUnits:d,extraCharge:i,totalWithExtra:t+i}}function h(a){g.textContent=a,g.classList.remove("hidden")}});
