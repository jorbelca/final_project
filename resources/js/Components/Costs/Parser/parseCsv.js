import Papa from "papaparse";

export default function parseCsv(file, tableData) {
    Papa.parse(file, {
        complete: (result) => {
            if (result.data.length <= 1) return;

            // Find all required columns by searching in all rows
            let headerRow = -1;
            let idxDesc = -1;
            let idxCost = -1;
            let idxUnit = -1;
            let idxPeriodicity = -1;

            // Search for column headers in the first few rows
            for (let i = 0; i < Math.min(5, result.data.length); i++) {
                const rowData = result.data[i].map(
                    (cell) => cell?.toString().trim().toUpperCase() || ""
                );

                idxDesc = rowData.findIndex((cell) => cell === "DESCRIPTION");
                idxCost = rowData.findIndex((cell) => cell === "COST");
                idxUnit = rowData.findIndex((cell) => cell === "UNIT");
                idxPeriodicity = rowData.findIndex(
                    (cell) => cell === "PERIODICITY"
                );

                if (idxDesc !== -1 && idxCost !== -1) {
                    headerRow = i;
                    break;
                }
            }

            // If we couldn't find the headers, try a more lenient approach
            if (headerRow === -1) {
                // Just take the first row with non-empty cells
                for (let i = 0; i < Math.min(10, result.data.length); i++) {
                    const nonEmptyCells = result.data[i].filter(
                        (cell) => cell?.toString().trim() !== ""
                    ).length;

                    if (nonEmptyCells >= 2) {
                        // Assume column order: description, cost, unit, periodicity
                        idxDesc = 1;
                        idxCost = 2;
                        idxUnit = 3;
                        idxPeriodicity = 4;
                        headerRow = i;
                        break;
                    }
                }
            }

            if (headerRow === -1) return;

            // Process data rows
            tableData.value = result.data
                .slice(headerRow + 1)
                .map((row) => {
                    const description = row[idxDesc]?.toString().trim() || "";
                    const cost = row[idxCost]?.toString().trim() || "";
                    const unit =
                        row[idxUnit]?.toString().toLowerCase().trim() || "unit"; // Use the value from CSV or default
                    const periodicity =
                        row[idxPeriodicity]?.toString().toLowerCase().trim() ||
                        "unit"; // Use the value from CSV or default

                    return {
                        description,
                        cost,
                        unit,
                        periodicity,
                    };
                })
                .filter((row) => row.description && row.cost);
        },
        skipEmptyLines: true,
        delimiter: ";", // Set delimiter to semicolon for your format
    });
    return tableData;
}
