import { API_ROUTES } from "../config/api.js";
import { 
    getOrDelete,
    submitData
} from "../services/requestApi.js";

document.addEventListener('DOMContentLoaded', () => {

    let orders;

    async function getOrder(phonNum = null, guardianOrParent = null) {
        const routeUrl = API_ROUTES.getOrdersURL;
        orders = await getOrDelete('GET', `${routeUrl}?ph_num=${phonNum}&grdian_name=${guardianOrParent}`);
    }

    async function checkOutNa(orderNumber) {
        const response = await submitData(API_ROUTES.checkOutURL, null, 'PATCH', orderNumber);
    }
});