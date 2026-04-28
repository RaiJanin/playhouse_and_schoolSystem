<script>
(() => {
    const maxChars = 255;
    const templates = @json($templates);

    let input;
    let countEl;
    let templateSelect;
    let titleInput;
    let hiddenSlug;
    let sendMode;
    let scheduleFields;

    // RECIPIENT SYSTEM
    let recipientMode;
    let searchInput;
    let searchResults;
    let selectedBox;
    let hiddenRecipients;

    let selectedRecipients = new Map();
    let searchCancel = null;
    let debounceTimer = null;

    /* -----------------------
        CORE UI FUNCTIONS
    ------------------------*/

    const updateCharCount = () => {
        if (!input || !countEl) return;

        const length = input.value.length;
        countEl.textContent = length;
        countEl.classList.toggle('text-red-600', length >= maxChars);
    };

    const updateTextareaState = () => {
        if (!input) return;

        const hasValue = input.value.trim() !== '';
        input.classList.toggle('text-gray-900', hasValue);
        input.classList.toggle('text-gray-400', !hasValue);
    };

    const handleInput = () => {
        updateCharCount();
        updateTextareaState();
    };

    /* -----------------------
        TEMPLATE SYSTEM
    ------------------------*/

    const applyTemplate = (index) => {
        if (!templates[index]) return;

        const t = templates[index];

        if (titleInput) titleInput.value = t.name;
        if (input) input.value = t.message;
        if (hiddenSlug) hiddenSlug.value = t.slug;

        handleInput();
    };

    const handleTemplateChange = (e) => {
        applyTemplate(e.target.value);
    };

    /* -----------------------
        SCHEDULE SYSTEM
    ------------------------*/

    const dropdownSchedules = () => {
        if (!sendMode || !scheduleFields) return;

        const fields = [
            document.getElementById('schedule-date'),
            document.getElementById('schedule-time'),
        ];

        let requireFields = false;

        if (sendMode.value === 'scheduled') {
            scheduleFields.classList.remove('hidden');
            requireFields = true;
        } else {
            scheduleFields.classList.add('hidden');
        }

        fields.forEach(field => {
            if (field) field.required = requireFields;
        });
    };

    /* -----------------------
        RECIPIENT SEARCH (AXIOS)
    ------------------------*/

    const renderSelected = () => {
        if (!selectedBox || !hiddenRecipients) return;

        selectedBox.innerHTML = '';
        hiddenRecipients.innerHTML = '';

        selectedRecipients.forEach(c => {
            const chip = document.createElement('div');
            chip.className =
                "bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs flex items-center gap-2";

            chip.innerHTML = `
                ${c.name}
                <button type="button" data-id="${c.id}">x</button>
            `;

            chip.querySelector('button').addEventListener('click', () => {
                selectedRecipients.delete(c.id);
                renderSelected();
            });

            selectedBox.appendChild(chip);

            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'recipient_ids[]';
            hidden.value = c.id;

            hiddenRecipients.appendChild(hidden);
        });
    };

    const searchContacts = async (query) => {
        if (searchCancel) searchCancel.abort();
        searchCancel = new AbortController();

        try {
            const res = await window.axios.get('/api/get-contact', {
                params: { q: query },
                signal: searchCancel.signal
            });

            const data = res.data;

            searchResults.innerHTML = data.map(c => `
                <div class="flex justify-between items-center p-2 hover:bg-gray-100 rounded">
                    <span>${c.name} - ${c.mobile}</span>
                    <button type="button"
                        data-id="${c.id}"
                        data-name="${c.name}">
                        Add
                    </button>
                </div>
            `).join('');

            searchResults.querySelectorAll('button').forEach(btn => {
                btn.addEventListener('click', () => {
                    selectedRecipients.set(btn.dataset.id, {
                        id: btn.dataset.id,
                        name: btn.dataset.name
                    });

                    renderSelected();
                });
            });

        } catch (err) {
            if (err.name !== 'CanceledError') {
                console.error(err);
            }
        }
    };

    const handleSearchInput = (e) => {
        clearTimeout(debounceTimer);

        const value = e.target.value;

        if (value.length < 2) {
            searchResults.innerHTML = '';
            return;
        }

        debounceTimer = setTimeout(() => {
            searchContacts(value);
        }, 300);
    };

    const toggleRecipientMode = () => {
        if (!recipientMode) return;

        const isSearch = recipientMode.value === 'search';

        if (searchInput) searchInput.classList.toggle('hidden', !isSearch);
    };

    /* -----------------------
        MOUNT DOM
    ------------------------*/

    const onMount = () => {
        input = document.getElementById('messageInput');
        countEl = document.getElementById('charCount');
        templateSelect = document.getElementById('templateSelect');
        titleInput = document.querySelector('input[name="title"]');
        hiddenSlug = document.getElementById('hidden-slug');

        sendMode = document.getElementById('send-mode');
        scheduleFields = document.getElementById('scheduleFields');

        // recipients
        recipientMode = document.getElementById('recipientMode');
        searchInput = document.getElementById('contactSearch');
        searchResults = document.getElementById('searchResults');
        selectedBox = document.getElementById('selectedList');
        hiddenRecipients = document.getElementById('hiddenRecipients');
    };

    const init = () => {
        onMount();

        if (input) input.addEventListener('input', handleInput);
        if (templateSelect) templateSelect.addEventListener('change', handleTemplateChange);
        if (sendMode) sendMode.addEventListener('change', dropdownSchedules);

        // recipients
        if (recipientMode) {
            recipientMode.addEventListener('change', toggleRecipientMode);
        }

        if (searchInput) {
            searchInput.addEventListener('input', handleSearchInput);
        }

        // init states
        handleInput();
        dropdownSchedules();
        toggleRecipientMode();
    };

    const destroy = () => {
        if (input) input.removeEventListener('input', handleInput);
        if (templateSelect) templateSelect.removeEventListener('change', handleTemplateChange);
        if (sendMode) sendMode.removeEventListener('change', dropdownSchedules);

        if (recipientMode) recipientMode.removeEventListener('change', toggleRecipientMode);
        if (searchInput) searchInput.removeEventListener('input', handleSearchInput);
    };

    document.addEventListener('DOMContentLoaded', init);
    window.addEventListener('beforeunload', destroy);
})();
</script>