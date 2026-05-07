export const tableSkeleton = (rows = 5) => {
    return Array.from({ length: rows })
        .map(
            () => `
            <tr class="animate-pulse">
                <td class="px-6 py-4 sticky left-0 bg-white z-10">
                    <div class="h-4 w-32 bg-gray-200 rounded"></div>
                </td>

                <td class="px-6 py-4 sticky left-0 bg-white z-10">
                    <div class="h-4 w-32 bg-gray-200 rounded"></div>
                </td>

                <td class="px-6 py-4">
                    <div class="h-4 w-24 bg-gray-200 rounded"></div>
                </td>

                <td class="px-6 py-4">
                    <div class="h-4 w-20 bg-gray-200 rounded"></div>
                </td>

                <td class="px-6 py-4">
                    <div class="h-4 w-20 bg-gray-200 rounded"></div>
                </td>

                <td class="px-6 py-4">
                    <div class="h-4 w-16 bg-gray-200 rounded"></div>
                </td>

                <td class="px-6 py-4">
                    <div class="h-4 w-24 bg-gray-200 rounded"></div>
                </td>

                <td class="px-6 py-4">
                    <div class="h-4 w-20 bg-gray-200 rounded"></div>
                </td>

                <td class="px-6 py-4">
                    <div class="h-4 w-24 bg-gray-200 rounded"></div>
                </td>
            </tr>
        `,
        )
        .join("");
};

export const emptyStateTable = () => {
    return `
            <tr>
                <td colspan="9" class="px-6 py-4 text-sm text-gray-500 text-center">
                    No order items found.
                </td>
            </tr>
        `;
};
