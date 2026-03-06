import{g as L,A as b,s as m,a as E}from"./requestApi-CFAcmW9O.js";window.masterfile={chargeOfMinutes:20,minutesPerCharge:2};document.addEventListener("DOMContentLoaded",()=>{const x=document.getElementById("checkout-search-form"),l=document.getElementById("search-results"),p=document.getElementById("orders-list"),f=document.getElementById("no-results"),u=document.getElementById("loading"),g=document.getElementById("error-message"),v=document.getElementById("success-modal"),c=document.getElementById("modal-container");c.querySelector(".modal-footer").classList.add("hidden"),x.addEventListener("submit",async function(n){n.preventDefault(),l.classList.add("hidden"),f.classList.add("hidden"),g.classList.add("hidden"),p.innerHTML="";const e=document.getElementById("search-phone").value.trim(),t=document.getElementById("search-guardian").value.trim();if(!e&&!t){h("Please enter either a phone number or a guardian/parent name.");return}u.classList.remove("hidden");const a=await L("GET",`${b.getOrdersURL}?ph_num=${e}&grdian_name=${t}`,[]);if(m("log","Orders: ",a),u.classList.add("hidden"),a.not_found){h(a.message);return}a.orders&&a.orders.length>=1?w(a.orders):f.classList.remove("hidden")}),window.handleCheckout=async function(n){if(confirm("Are you sure you want to check out this order?")){u.classList.remove("hidden"),l.classList.add("hidden");try{const e=await E(b.checkOutURL,null,"PATCH",n);if(m("log","Child checked out: ",e),u.classList.add("hidden"),e.checked_out){const t=e.orderItem,a=Number(t.durationsubtotal)+Number(t.socksprice),r=Number(t.lne_xtra_chrg||0),s=a+r;let o="";const d=Math.max(0,(new Date(t.updated_at)-new Date(t.created_at))/6e4-t.durationhours*60),i=Math.ceil(d/window.masterfile.minutesPerCharge);o+=`
                    <div class="mb-4 border-b border-gray-200 pb-2">
                        <p><strong>Child:</strong> ${t.child?.firstname||"N/A"} ${t.child?.lastname||""}</p>
                        <p><strong>Play Duration:</strong> ${t.durationhours} hr(s) — ₱${Number(t.durationsubtotal).toFixed(2)}</p>
                        <p><strong>Socks:</strong> ₱${Number(t.socksprice).toFixed(2)}</p>
                        <p><strong>Subtotal:</strong> ₱${a.toFixed(2)}</p>
                        ${r>0?`
                            <div class="text-red-500">
                                <p><strong>Extra Charges:</strong> ₱${r.toFixed(2)}</p>
                                <p>Overtime: ${Math.max(0,Math.round(d))} minute(s)</p>
                                <p>Charge units: ${i} × ₱${window.masterfile.chargeOfMinutes} per ${window.masterfile.minutesPerCharge} min</p>
                            </div>
                        `:""}
                        <p><strong>Total:</strong> ₱${s.toFixed(2)}</p>
                    </div>
                `;const k=`
                    <p><strong>Order #:</strong> ${n}</p>
                    <p><strong>Guardian:</strong> ${t.order.guardian}</p>
                    ${o}
                    <p><strong>Order Total:</strong> ₱${Number(t.order.total_amnt).toFixed(2)}</p>
                `;document.getElementById("checkout-details").innerHTML=k,v.classList.remove("hidden")}else h(e.message||"Checkout failed. Please try again."),l.classList.remove("hidden")}catch(e){u.classList.add("hidden"),h("Error during checkout: "+e.message),console.error(e),m("error","Error during checkout: ",e.message),l.classList.remove("hidden")}}},c.querySelector(".close-modal").addEventListener("click",()=>{c.classList.add("hidden")});function y(n){const e=JSON.parse(n.dataset.items),t=n.dataset.orderNum,a=document.getElementById("items-subcat-container");a.innerHTML="",m("log","Order Items: ",e),c.classList.remove("hidden"),c.querySelector(".modal-title").textContent=t,a.innerHTML=e.map(r=>{const s=$(r);return`
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
                            <p>Number of 2-minute blocks: ${s.chargeUnits}</p>
                            <p>Extra Charge = ${s.chargeUnits} × ₱${window.masterfile.chargeOfMinutes} = ₱${s.extraCharge.toFixed(2)}</p>
                        </div>
                    </div>
                    

                    <div class="mt-1 pt-1 border-t border-gray-200 flex justify-between font-semibold">
                        <span>Total (Preview)</span>
                        <span>₱${s.totalWithExtra.toFixed(2)}</span>
                    </div>
                    <button 
                        data-check-out-id="${r.id}"
                        class="check-out-btn mt-3 w-full bg-[#0d9984] text-white font-bold py-2 px-4 rounded-lg hover:bg-[#0a7a6a] transition-colors"
                    >
                        Check out
                    </button>
                </div>
            `}).join(""),a.querySelectorAll(".check-out-btn").forEach(r=>{r.addEventListener("click",s=>{const o=s.currentTarget.dataset.checkOutId;m("log","Check out ID: ",o),c.classList.add("hidden"),handleCheckout(o)})})}window.viewOrder=y;function w(n){l.classList.remove("hidden"),n.forEach(e=>{const t=document.createElement("div");t.className="bg-gray-50 border border-gray-200 rounded-lg p-4";const a=e.order_items||[],r=a.length;let s="";a.forEach(o=>{const d=o.durationhours==5?"Unlimited":o.durationhours+" hours",i=o.socksqty>0?`, ${o.socksqty} pair(s) of socks`:"";s+=`<li class="text-gray-600">${d}${i}</li>`}),t.innerHTML=`
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h4 class="font-bold text-gray-800">Order #${e.ord_code_ph}</h4>
                        <p class="text-sm text-gray-600">Guardian: ${e.guardian}</p>
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
                    class="w-full bg-[#0d9984] text-white font-bold py-2 px-4 rounded-lg hover:bg-[#0a7a6a] transition-colors"
                >
                    View Order
                </button>
            `,p.appendChild(t)})}function $(n){const e=Number(n?.durationsubtotal)+Number(n?.socksprice),t=new Date(n.created_at),a=new Date,r=n.durationhours*60,s=Math.ceil((a-t)/6e4);let o=0,d=0,i=0;return s>r&&(o=s-r,d=Math.ceil(o/window.masterfile.minutesPerCharge),i=d*window.masterfile.chargeOfMinutes),{subtotal:e,actualMinutes:s,paidMinutes:r,extraMinutes:o,chargeUnits:d,extraCharge:i,totalWithExtra:e+i}}function h(n){g.textContent=n,g.classList.remove("hidden")}});
