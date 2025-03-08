const parseRowData = (row) => {
    const columnsWithPreload = columns.filter(
        (column) => column.preload && Object.keys(column.preload).length > 1
    );

    if (!columnsWithPreload.length) return row;

    const newRow = Object.assign({}, row);
    columnsWithPreload.forEach((column) => {
        const rowKey = newRow[column.preload.column];
        const preloadData = preload?.[column.preload.table];
        //preloadData, array de datos precargados de una tabla diferente
        if (!preloadData) return;

        if (typeof preloadData === "object") {
            const preloadKey = preloadData.find(
                (preloadRow) => preloadRow[column.preload.key] === rowKey
            );

            if (preloadKey) {
                newRow[column.preload.column] = {
                    value: preloadKey[column.preload.key],
                    label: `${preloadKey[column.preload.key]} - ${
                        preloadKey[column.preload.column]
                    }`,
                };
            }
        } else {
            const preloadKey = preloadData.find(
                (preloadRow) => preloadRow[column.preload.column] === rowKey
            );

            if (preloadKey) {
                newRow[column.preload.column] = {
                    value: preloadKey[column.preload.column],
                    label: `${preloadKey[column.preload.column]} - ${
                        preloadKey[column.preload.key]
                    }`,
                };
            }
        }
    });

    return newRow;
};

const columns = [
    {
        key: "relation2",
        preload: {
            column: "relation2",
            key: "idpk",
            table: "test_mantenimiento_4",
        },
    },
];

const preload = {
    test_mantenimiento_4: [
        { idpk: 1, name: "Motor" },
        { idpk: 2, name: "Freno" },
        { idpk: 3, name: "Suspensión" },
    ],
};

const row = { id: 101, relation2: 2 }; // Queremos que relation2 se convierta en un objeto

const parsedRow = parseRowData(row);

console.log(parsedRow);
