export const showDescription = (item) => {
    if (item.description.length > 20) {
        return `
            <span class="text-sm text-gray-500 relative group cursor-pointer">
                ${item.description.slice(0, 15)}...
                <span
                    class="absolute left-0 top-full mt-1 w-max bg-gray-700 text-white text-xs rounded px-2 py-1 opacity-0 group-hover:opacity-100 transition-opacity"
                >
                    ${item.description}
                </span>
            </span>
        `;
    }
    return `<span class="text-sm text-gray-500">${item.description}</span>`;
};



