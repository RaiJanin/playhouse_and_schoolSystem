const pagination = document.getElementById("pagination");
const paginationMeta = document.getElementById("pagination-meta");

/**
 * Create pagination button
 * @param {Object} options
 * @returns {HTMLButtonElement}
 */
function createButton({
    id,
    ariaLabel,
    icon,
    disabled = false,
    onClick,
    className = "",
}) {
    const button = document.createElement("button");

    button.id = id;
    button.setAttribute("aria-label", ariaLabel);
    button.disabled = disabled;

    button.className = `
        flex items-center justify-center
        w-10 h-10 rounded-full
        bg-white shadow-md text-gray-600
        disabled:cursor-not-allowed
        disabled:text-gray-400
        hover:bg-indigo-100 transition
        ${className}
    `;

    button.innerHTML = icon;

    if (typeof onClick === "function") {
        button.addEventListener("click", onClick);
    }

    return button;
}

/**
 * Create pagination info button
 * @param {Object} meta
 * @returns {HTMLButtonElement}
 */
function createCurrentPage(meta) {
    const button = document.createElement("button");

    button.id = "currentPage";
    button.disabled = true;

    button.className = `
        flex items-center justify-center
        p-0 w-10 max-w-15 h-10 rounded-full
        bg-white shadow-md text-gray-600
        disabled:text-gray-500
    `;

    button.textContent = `${meta.current_page}/${meta.last_page}`;

    return button;
}

/**
 * Handle pagination rendering
 * @param {Object} meta
 * @param {(page:number)=>void} callback
 * @param {boolean} enable
 */
export function renderPagination(meta, callback, enable = true) {
    pagination.innerHTML = "";
    paginationMeta.innerHTML = "";

    if (!enable || !meta || typeof callback !== "function") {
        return;
    }

    const prevIcon = `
        <svg class="w-5 h-5" fill="none" stroke="currentColor"
            stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round" viewBox="0 0 24 24">
            <polyline points="15 18 9 12 15 6"></polyline>
        </svg>
    `;

    const nextIcon = `
        <svg class="w-5 h-5" fill="none" stroke="currentColor"
            stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round" viewBox="0 0 24 24">
            <polyline points="9 18 15 12 9 6"></polyline>
        </svg>
    `;

    const firstIcon = `
        <svg xmlns="http://www.w3.org/2000/svg"
            class="w-5 h-5"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="2">
            <path stroke-linecap="round"
                stroke-linejoin="round"
                d="M11 19l-7-7 7-7M20 19l-7-7 7-7" />
        </svg>
    `;

    const lastIcon = `
        <svg xmlns="http://www.w3.org/2000/svg"
            class="w-5 h-5"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="2">
            <path stroke-linecap="round"
                stroke-linejoin="round"
                d="M13 5l7 7-7 7M4 5l7 7-7 7" />
        </svg>
    `;

    const prevBtn = createButton({
        id: "prevBtn",
        ariaLabel: "Previous page",
        icon: prevIcon,
        disabled: !meta.prev_page_url,
        onClick: () => callback(meta.current_page - 1),
    });

    const firstBtn = createButton({
        id: "firstPageBtn",
        ariaLabel: "First page",
        icon: firstIcon,
        disabled: meta.current_page === 1,
        onClick: () => callback(1),
    });

    const currentPage = createCurrentPage(meta);

    const lastBtn = createButton({
        id: "lastPageBtn",
        ariaLabel: "Last page",
        icon: lastIcon,
        disabled: meta.current_page === meta.last_page,
        onClick: () => callback(meta.last_page),
    });

    const nextBtn = createButton({
        id: "nextBtn",
        ariaLabel: "Next page",
        icon: nextIcon,
        disabled: !meta.next_page_url,
        onClick: () => callback(meta.current_page + 1),
    });

    pagination.append(prevBtn, firstBtn, currentPage, lastBtn, nextBtn);

    paginationMeta.innerHTML = `
        <p class="block font-sans text-sm font-small leading-relaxed text-gray-700 antialiased break-words ml-2">
            Showing ${meta.from} to ${meta.to} of ${meta.total} results
        </p>
    `;
}
