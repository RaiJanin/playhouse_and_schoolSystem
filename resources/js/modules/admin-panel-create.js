const templates = window.adminPanelStates.templates
const maxChars = window.adminPanelStates.maxChars

let input;
let countEl;
let templateSelect;
let titleInput;
let hiddenSlug;
let sendMode;
let scheduleFields;
let recipientMode;
let searchInput;
let selectedBox;
let hiddenRecipients;
let searchResults;
let selectedRecipients = new Map();
let searchCancel = null;
let debounceTimer = null;

const updateCharCount = () => {
    if (!input || !countEl) return;

    const length = input.value.length;
    countEl.textContent = length;
    countEl.classList.toggle("text-red-600", length >= maxChars);
};

const updateTextareaState = () => {
    if (!input) return;

    const hasValue = input.value.trim() !== "";
    input.classList.toggle("text-gray-900", hasValue);
    input.classList.toggle("text-gray-400", !hasValue);
};

const applyTemplate = (index) => {
    if (!templates[index]) return;

    const selected = templates[index];

    if (titleInput) titleInput.value = selected.name;
    if (input) input.value = selected.message;
    if (hiddenSlug) hiddenSlug.value = selected.slug;

    handleInput();
};

const handleInput = () => {
    updateCharCount();
    updateTextareaState();
};

const handleTemplateChange = (e) => {
    applyTemplate(e.target.value);
};

const dropdownSchedules = () => {
    const fields = [
        document.getElementById("schedule-date"),
        document.getElementById("schedule-time"),
    ];
    let requireFields = false;

    if (sendMode.value === "scheduled") {
        scheduleFields.classList.remove("hidden");
        requireFields = true;
    } else {
        scheduleFields.classList.add("hidden");
    }

    fields.forEach((field) => {
        field.required = requireFields;
    });
};

const renderSelected = () => {
    if (!selectedBox || !hiddenRecipients) return;

    selectedBox.innerHTML = "";
    hiddenRecipients.innerHTML = "";

    selectedRecipients.forEach((c) => {
        const chip = document.createElement("div");
        chip.className =
            "bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs flex items-center gap-2";

        chip.innerHTML = `
                ${c.name} - ${c.mobile}
                <button type="button" data-id="${c.id}">x</button>
            `;

        chip.querySelector("button").addEventListener("click", () => {
            selectedRecipients.delete(c.id);
            renderSelected();
        });

        selectedBox.appendChild(chip);

        const hidden = document.createElement("input");
        hidden.type = "hidden";
        hidden.name = "recipient_ids[]";
        hidden.value = c.id;

        hiddenRecipients.appendChild(hidden);
    });
};

const searchContacts = async (query) => {
    if (searchCancel) searchCancel.abort();
    searchCancel = new AbortController();

    try {
        const res = await window.axios.get("/api/get-contact", {
            params: { search: query },
            signal: searchCancel.signal,
        });

        const data = res.data.contact;

        searchResults.innerHTML = data
            .map(
                (c) => `
                <div class="flex justify-between items-center gap-2 px-2 hover:bg-gray-100 rounded">
                    <span>${c.name} - ${c.contact_number}</span>
                    <button type="button" class="rounded px-2 py-1 bg-[var(--color-primary)] text-white shadow hover:opacity-75"
                        data-id="${c.id}"
                        data-name="${c.name}"
                        data-mobile="${c.contact_number}">
                        <!--<i class="fa-solid fa-plus"></i>-->
                        Add
                    </button>
                </div>
            `,
            )
            .join("");

        searchResults.querySelectorAll("button").forEach((btn) => {
            btn.addEventListener("click", () => {
                selectedRecipients.set(btn.dataset.id, {
                    id: btn.dataset.id,
                    name: btn.dataset.name,
                    mobile: btn.dataset.mobile
                });

                renderSelected();
            });
        });
    } catch (err) {
        if (err.name !== "CanceledError") {
            console.error(err);
        }
    }
};

const handleSearchInput = (e) => {
    clearTimeout(debounceTimer);

    const value = e.target.value;

    if (value.length < 2) {
        searchResults.innerHTML = "";
        return;
    }

    debounceTimer = setTimeout(() => {
        searchContacts(value);
    }, 300);
};

const toggleRecipientMode = () => {
    if (!recipientMode) return;

    const isSearch = recipientMode.value === "search";

    if (searchInput)
        document
            .getElementById("searchBox")
            .classList.toggle("hidden", !isSearch);
};

const onMount = () => {
    input = document.getElementById("messageInput");
    countEl = document.getElementById("charCount");
    templateSelect = document.getElementById("templateSelect");
    titleInput = document.querySelector('input[name="title"]');
    hiddenSlug = document.getElementById("hidden-slug");
    sendMode = document.getElementById("send-mode");
    scheduleFields = document.getElementById("scheduleFields");
    recipientMode = document.getElementById("recipientMode");
    searchInput = document.getElementById("contactSearch");
    searchResults = document.getElementById("searchResults");
    selectedBox = document.getElementById("selectedList");
    hiddenRecipients = document.getElementById("hiddenRecipients");
};

const nextTick = () => {
    handleInput();
    dropdownSchedules();
    toggleRecipientMode();
};

const init = () => {
    onMount();

    if (input) input.addEventListener("input", handleInput);
    if (templateSelect)
        templateSelect.addEventListener("change", handleTemplateChange);
    if (sendMode) sendMode.addEventListener("change", dropdownSchedules);
    if (recipientMode)
        recipientMode.addEventListener("change", toggleRecipientMode);
    if (searchInput) searchInput.addEventListener("input", handleSearchInput);

    nextTick();
};

const destroy = () => {
    if (input) input.removeEventListener("input", handleInput);
    if (templateSelect)
        templateSelect.removeEventListener("change", handleTemplateChange);
    if (sendMode) sendMode.removeEventListener("change", dropdownSchedules);
    if (recipientMode)
        recipientMode.removeEventListener("change", toggleRecipientMode);
    if (searchInput)
        searchInput.removeEventListener("input", handleSearchInput);
};

document.addEventListener("DOMContentLoaded", init);
window.addEventListener("beforeunload", destroy);