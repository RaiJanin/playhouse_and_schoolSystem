import { showConsole } from "../config/debug.js";
import { disableBirthdayonSubmit } from "./formControl.js";
import { openEditModal } from "../components/reviewEdit.js";
import { addguardianCheckBx } from "../modules/playhouseParent.js";

/**
 * Adds event listeners for the summary/review form
 * @param {FormData} data - The form data object
 * @param {boolean} parentBirthdayIsFilled - Whether the parent's birthday is filled
 * @returns {void}
 */
export function addEventListenersForLastForm(data, parentBirthdayIsFilled) {
    const dscCodeInput = document.getElementById('discount-code-input');
    const applyDscBtn = document.getElementById('apply-discount-btn');
    let apply = false;

    applyDscBtn.addEventListener('click', () => {
        apply = !apply;

        if(apply) {
            dscCodeInput.setAttribute('readonly', true);
            applyDscBtn.textContent = 'Edit';
        } else {
            dscCodeInput.removeAttribute('readonly');
            applyDscBtn.textContent = 'Apply';
        }
    });

    document.getElementById('ff-page-btn').addEventListener('click', () => {
        const hiddenValueForFfFlag = document.getElementById('isFollowedFlag');

        if(hiddenValueForFfFlag.value === '0') {
            document.getElementById('isFollowedFlag').value = '1';
        }
        window.open(window.masterfile.extras.myFacebookPage, '_blank');
    });

    const fbUrlInput = document.getElementById('fb-pp-url-input');
    
    const normalizeFbUrl = (rawValue) => {
        const trimmedUrl = rawValue.trim();

        if (!trimmedUrl) {
            return '';
        }

        if (!/^https?:\/\//i.test(trimmedUrl)) {
            return `https://${trimmedUrl}`;
        }

        return trimmedUrl;
    };

    const isValidUrl = (urlValue) => {
        if (!urlValue) return false;

        try {
            const url = new URL(urlValue);

            if (!url.hostname.includes('facebook.com')) {
                return false;
            }

            return true;
        } catch (e) {
            return false;
        }
    };

    fbUrlInput.addEventListener('blur', () => {
        const cursorPos = fbUrlInput.selectionStart;
        const normalizedUrl = normalizeFbUrl(fbUrlInput.value);

        if (normalizedUrl !== fbUrlInput.value) {
            fbUrlInput.value = normalizedUrl;
            fbUrlInput.setSelectionRange(cursorPos + (normalizedUrl.length - fbUrlInput.value.length), cursorPos + (normalizedUrl.length - fbUrlInput.value.length));
        }

        if (isValidUrl(normalizedUrl)) {
            fbUrlInput.classList.remove('border-red-500');
            fbUrlInput.classList.add('border-green-500');
        } else {
            fbUrlInput.classList.remove('border-green-500');
            fbUrlInput.classList.add('border-red-500');
        }
    });

    if(data.get('parentBirthday')) parentBirthdayIsFilled = true;
    
    const editBtn = document.getElementById('edit-review-btn');
    if (editBtn) {
        editBtn.addEventListener('click', () => {
            const reviewData = {
                parent: {
                    first_name: data.get('parentName'),
                    last_name: data.get('parentLastName'),
                    email: data.get('parentEmail'),
                    birthday: data.get('parentBirthday')
                },
                phone: data.get('phone'),
                guardian: addguardianCheckBx.isChecked() ? {
                    first_name: data.get('guardianName'),
                    last_name: data.get('guardianLastName'),
                    phone: data.get('guardianPhone'),
                    birthday: data.get('guardianBirthday')
                } : null,
                children: []
            };
            
            document.querySelectorAll('.child-entry').forEach((child) => {
                const nameEl = child.querySelector('input[name*="[name]"]');
                const birthdayEl = child.querySelector('input[name*="[birthday]"]');
                const durationEl = child.querySelector('select[name*="[playDuration]"]');
                const addedSocksEl = child.querySelector('select[name*="[addSocks]"]');
                const socksIcon = child.querySelector('[id*="add-socks-child-icon"]');

                const hasSocks = (addedSocksEl && addedSocksEl.value === '1') || 
                                 (socksIcon && socksIcon.classList.contains('fa-check-square'));
                
                reviewData.children.push({
                    name: nameEl ? nameEl.value : '',
                    birthday: birthdayEl ? birthdayEl.value : '',
                    playtime_duration: durationEl ? durationEl.value : '',
                    add_socks: hasSocks
                });
            });
            
            openEditModal(reviewData);
        });
    }

    showConsole('log', 'Is Parent Birthdate filled? ', parentBirthdayIsFilled);

    if(parentBirthdayIsFilled) {
        disableBirthdayonSubmit(false);
    } else {
        disableBirthdayonSubmit();
    }
}
