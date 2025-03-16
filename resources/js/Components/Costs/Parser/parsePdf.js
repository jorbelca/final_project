import * as pdfjsLib from "pdfjs-dist";
import workerSrc from "pdfjs-dist/build/pdf.worker.min.mjs?url";

pdfjsLib.GlobalWorkerOptions.workerSrc = workerSrc;

export default async function parsePdfText(data, tableData) {
    try {
        const pdf = await pdfjsLib.getDocument({ data }).promise;

        // Extraer texto de todas las páginas
        let extractedText = "";
        for (let i = 1; i <= pdf.numPages; i++) {
            const page = await pdf.getPage(i);
            const textContent = await page.getTextContent();

            // Procesamos los items para mantener su posición
            const items = textContent.items.map((item) => ({
                text: item.str,
                x: item.transform[4], // Posición X
                y: item.transform[5], // Posición Y
                fontHeight: item.height,
            }));

            // Ordenamos los items por posición Y (de arriba hacia abajo)
            items.sort((a, b) => b.y - a.y);

            // Agrupamos por líneas basadas en la posición Y
            const lines = [];
            let currentLine = [];
            let currentY = items.length > 0 ? items[0].y : 0;

            items.forEach((item) => {
                // Si la diferencia de Y es mayor que cierto umbral, es una nueva línea
                if (Math.abs(item.y - currentY) > item.fontHeight * 0.5) {
                    if (currentLine.length > 0) {
                        // Ordenamos los items en la línea por posición X (de izquierda a derecha)
                        currentLine.sort((a, b) => a.x - b.x);
                        lines.push(currentLine.map((i) => i.text).join(" "));
                        currentLine = [];
                    }
                    currentY = item.y;
                }
                currentLine.push(item);
            });

            // Añadimos la última línea
            if (currentLine.length > 0) {
                currentLine.sort((a, b) => a.x - b.x);
                lines.push(currentLine.map((i) => i.text).join(" "));
            }

            extractedText += lines.join("\n") + "\n";
        }

        // Separar en líneas y limpiar
        const lines = extractedText
            .split("\n")
            .map((line) => line.trim())
            .filter((line) => line.length > 0);

        // Buscar línea de encabezados (que contienen al menos "DESCRIPTION" y "COST")
        const headerIndex = lines.findIndex(
            (line) =>
                line.toUpperCase().includes("DESCRIPTION") &&
                line.toUpperCase().includes("COST")
        );

        if (headerIndex === -1) {
            console.error("Encabezados no encontrados");
            return [];
        }

        // Extraer datos después de los encabezados
        const dataRows = lines.slice(headerIndex + 1);

        let parsedData = [];

        dataRows.forEach((line) => {
            // Intentar extraer datos basados en las posiciones aproximadas
            let description = "";
            let cost = "";
            let unit = "";
            let periodicity = "";

            // Dividir la línea en palabras
            const words = line.split(/\s+/);

            // Buscar el costo (primer valor numérico)
            const costIndex = words.findIndex(
                (word) => !isNaN(parseFloat(word))
            );

            if (costIndex !== -1) {
                // Todo lo anterior al costo es la descripción
                description = words.slice(0, costIndex).join(" ");
                cost = words[costIndex];
                // Intentar extraer unidad y periodicidad
                if (costIndex + 1 < words.length) {
                    unit = words[costIndex + 1];
                } else {
                    unit = "unit";
                }

                if (costIndex + 2 < words.length) {
                    periodicity = words[costIndex + 2];
                } else {
                    periodicity = unit; // Si no hay periodicidad, usar el mismo valor que unit
                }

                // Solo añadir si tenemos descripción y costo
                if (description && cost) {
                    parsedData.push({
                        description,
                        cost: parseFloat(cost), // Convertir a número
                        unit: unit.toLowerCase(),
                        periodicity: periodicity.toLowerCase(),
                    });
                }
            }
        });

        tableData.value = parsedData;

        return tableData;
    } catch (error) {
        console.error("Error al procesar el PDF:", error);
        alert("No se pudo procesar el archivo PDF.");
        return tableData;
    }
}
