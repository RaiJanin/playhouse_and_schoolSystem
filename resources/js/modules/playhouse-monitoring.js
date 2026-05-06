import '../bootstrap.js'
import '../config/global.js'
import { showConsole } from '../config/debug.js'
import { dateToString } from '../utilities/dateString.js'

let searchIn
let searchState = ''
let debounceTimer = null
let meta = null
let dataRowsBody

const api = window.axios

const renderTableRows = (items) => {
    dataRowsBody.innerHTML = ''

    if(items.length === 0) {
        dataRowsBody.innerHTML = `
            <tr>
                <td colspan="9" class="px-6 py-4 text-sm text-gray-500 text-center">
                    No order items found.
                </td>
            </tr>
        `;
        return
    }

    dataRowsBody.innerHTML = items.map(item => tableRow(parseItem(item))).join('')
}

const parseItem = (item) => {
    const childName = item.child
        ? `${item.child.firstname} ${item.child.lastname}`
        : "N/A";

    const parentName = item.order.parentPl
        ? item.order.parentPl.d_name
        : (item.guardian ?? "N/A");

    const durationHours = !item.durationhours
        ? "N/A"
        : item.durationhours == 5
          ? "Unlimited"
          : `${item.durationhours}hr`;

    const checkedIn = item.checkedIn
        ? `${dateToString("shortDate", item.ckin)} ${dateToString("timeOnly24", item.ckin)}`
        : '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-200 text-gray-800">Not started</span>';

    let checkedOut;
    if (!item.ckin && !item.ckout) {
        checkedOut = `<span class="px-2 inline-flex text-xs font-semibold rounded-full bg-orange-200 text-gray-800">Not started</span>`;
    } else if (item.ckin && !item.ckout) {
        checkedOut = `<span class="px-2 inline-flex text-xs font-semibold rounded-full bg-green-200 text-gray-800">Active</span>`;
    } else {
        checkedOut = `${dateToString('shortDate', item.ckout)} ${dateToString('timeOnly24', item.ckout)}`
    }

    let remainingTime;
    if (item.remainmins === "done") {
        remainingTime = `<span class="px-2 inline-flex text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Checked out</span>`;
    } else if (item.remainmins === "unlimited") {
        remainingTime = `<span class="px-2 inline-flex text-xs font-semibold rounded-full bg-blue-200 text-gray-800">Unlimited</span>`;
    } else {
        remainingTime = item.remainmins;
    }

    return {
        childName,
        parentName,
        durationHours,
        checkedIn,
        checkedOut,
        remainingTime,
        orderCode: item.ord_code_ph,
        qrChild: item.qr_child,
        qrGuardian: item.qr_guardian,
    };
}

const tableRow = (item) => {

    return `
        <tr class="data-row cursor-pointer hover:bg-gray-100 transition">
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 sticky left-0 bg-white z-10">
                ${item.childName}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 sticky left-0 bg-white z-10">
                ${item.parentName}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ${item.orderCode}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ${item.qrChild}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ${item.qrGuardian}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ${item.durationHours}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ${item.checkedIn}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ${item.remainingTime}
            </td>
        </tr>
    `;
}

const loop = () => {
    setInterval(async () => {
        displayData()
    }, 5000)
}

const displayData = async () => {
    meta = await getData()
    renderTableRows(meta.data)
}

const getData = async(search = null) => {
    try {
        const response = await api.get('/api/get-inhouse', {
            params: {
                search: search
            }
        })

        if(!response.data.success) {
            App.component.showAlert(response.data.message, 'error')
            return
        }

        return response.data.meta

    } catch (err) {
        App.component.criticalAlert('Server Error')
        showConsole("error", err);
    }
}

const onMount = () => {
    searchIn = document.getElementById('search-it')
    dataRowsBody = document.getElementById("data-rows");
}

const attachEventListeners = () => {

}

const nextTick = () => {

}

const init = async () => {
    onMount()
    attachEventListeners()
    displayData();
    loop()
    nextTick()
}

const destroy = () => {

}

document.addEventListener('DOMContentLoaded', init);
window.addEventListener('beforeunload',  destroy)