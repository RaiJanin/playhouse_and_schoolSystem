/**
 * Generates and displays a QR code for the given order number
 * @param {string} orderNum - The order number to encode in the QR code
 * @returns {void}
 */
export function generateQR(orderNum) {
    const qrContainer = document.getElementById('qr-container');
    const orderNumText = document.getElementById('order-number-text');
    const qrImage = document.getElementById('qr-image');
    
    qrContainer.classList.remove('hidden');
    orderNumText.textContent = orderNum;
    qrImage.innerHTML = "";

    const orderInfoLink = document.getElementById('order-info-link');
    if (orderInfoLink) {
        orderInfoLink.href = `/order-info/${orderNum}`;
        orderInfoLink.classList.remove('hidden');
    }

    new QRCode(qrImage, {
        text: orderNum,
        width: 150,
        height: 150
    });
}