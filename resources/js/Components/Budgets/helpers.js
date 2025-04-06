export const showDescription = (item) => {
    if (item.description.length > 20) {
        return `
            <span class="text-normal text-text relative group cursor-pointer">
                ${item.description.slice(0, 15)}...
                <span
                    class="absolute left-0 top-full mt-1 w-max bg-gray-700 text-white text-xs rounded px-2 py-1 opacity-0 group-hover:opacity-100 transition-opacity z-50"
                >
                    ${item.description}
                </span>
            </span>
        `;
    }
    return `<span class="text-sm text-gray-500">${item.description}</span>`;
};

export const adaptarTexto = (texto) => {
    if (window.innerWidth < 500) {
        if (texto.slice(0, 1) === "◀") {
            return texto.split(" ").slice(0, 2).join(" ");
        }
        if (texto.split(" ").slice(0, 1).join(" ") === "Crear") {
            return texto.split(" ").slice(0, 1).join(" ");
        }
        if (texto.split(" ").slice(0, 1).join(" ") === "Subir") {
            return texto.split(" ").slice(0, 1).join(" ");
        }
        return texto.split(" ").slice(0, 1) + "r";
    } else {
        return texto;
    }
};
