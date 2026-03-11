/**
 * Computes extra charge details for a single order item.
 *
 * @param {Object} item - The order item containing duration, prices, and timestamps.
 * @returns {Object} - Detailed breakdown including subtotal, extra minutes, charge units, and total with extra charge.
 */
export function computeExtraChargeDetails(item) {
    const subtotal = Number(item?.durationsubtotal) + Number(item?.socksprice);

    // times
    const checkIn = new Date(item.created_at);
    const now = new Date(); // can use current time or simulate checkout
    const paidMinutes = item.durationhours * 60;

    // actual minutes stayed
    let actualMinutes = Math.ceil((now - checkIn) / 60000);

    const maxMinutes = 5 * 60;
    if(actualMinutes > maxMinutes) {
        actualMinutes = maxMinutes;
    }

    let extraMinutes = 0;
    let chargeUnits = 0;
    let extraCharge = 0;

    if ((actualMinutes > paidMinutes) && (item.durationhours !== 5)) {
        extraMinutes = actualMinutes - paidMinutes;
        chargeUnits = Math.ceil(extraMinutes / window.masterfile.minutesPerCharge);
        extraCharge = chargeUnits * window.masterfile.chargeOfMinutes;
    }

    return {
        subtotal,
        actualMinutes,
        paidMinutes,
        extraMinutes,
        chargeUnits,
        extraCharge,
        totalWithExtra: subtotal + extraCharge
    };
}