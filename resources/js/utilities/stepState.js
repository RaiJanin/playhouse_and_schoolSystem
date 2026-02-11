let currentStepName = '';

export function getCurrentStepNameS(stepName) {
    currentStepName = stepName;
}

export function readStep() {
    return currentStepName;
}