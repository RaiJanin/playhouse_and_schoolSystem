/**
 * @namespace App
 */
window.App = window.App || {}

/**
 * Static states
 * @type {{
 * storePhone: *|null,
 * correctCode: *|null
 * }}
 */
App.staticState = {
    storePhone: 0,
    correctCode: false,
}

/**
 * Validation methods
 * @type {{
 * validatePhone: (phoneInput:) => boolean
 * }}
 */
App.validations = App.validations || {}

/**
 * Changes or sets input fields behavior
 * @type {{
 * phoneReadOnly: (boolean) => void
 * }}
 */
App.inputFieldControl = App.inputFieldControl || {}

/**
 * Form control modules/functions
 * @type {{
 * showSteps: (step:, direction:'next'|'prev', override:) => void,
 * removeFirstChild: (index: number) => void
 * }}
 */
App.formControl = App.formControl || {}

/**
 * Utility modules/functions
 * @type {{
 * generateOtp: (phoneNumber: number, email: string, resend: boolean) => void,
 * handleCheckout: (orderNumber: ) => Promise<void>
 * }}
 */
App.utilites = App.utilites || {}

/**
 * Dynamic runtime states and helpers
 * @type {{
 * countSelectedSocks: () => number,
 * getCurrentStepName: () => string,
 * getCurrentStep: () => number,
 * updateNextBtnState: () => void,
 * }}
 */
App.dynamicState = App.dynamicState || {}

/**
 * Component modules/functions
 * @type {{
 * populateSummary: () => void,
 * showAlert: (message: string, type:'success'|'caution'|'error', duration: number) => void,
 * dismissAlert: (id: string) => void,
 * criticalAlert: (err: string) => void,
 * promptDisabledFeature: () => void,
 * }}
 */
App.component = App.component || {}