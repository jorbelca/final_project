const { testUser, newBudget, newCost } = require("./data");
import "cypress-file-upload";

describe.only("Budgets", () => {
    it("can create a budget", () => {
        // LOGIN
        cy.visit("/login");
        cy.contains("Email").type(testUser.email);
        cy.contains("Password").type(testUser.password);
        cy.contains("Enter").click();
        cy.url().should("include", "/budgets");

        // CREATE BUDGET
        //client
        cy.contains("Create a Budget").click();
        cy.url().should("include", "/budgets/create");
        cy.get("#client").select(newBudget.client);

        //costs
        cy.get("#costs option").then(($options) => {
            const optionText = [...$options]
                .map((opt) => opt.textContent.trim())
                .find((text) => text.includes("Test Cost Edited")); // Busca la opción correcta

            if (optionText) {
                cy.get("#costs").select(optionText); // Selecciona la opción encontrada
                cy.contains("button", "Add cost").should("be.visible").click();

                cy.get('input[type="number"][placeholder="quantity"]')
                    .clear()
                    .type("2");
                cy.get("#costs").select(optionText);
                cy.contains("button", "Add cost").should("be.visible").click();

                cy.get('input[type="number"][placeholder="quantity"]')
                    .clear()
                    .type("3");
                cy.get("#costs").select(optionText);
                cy.contains("button", "Add cost").should("be.visible").click();
            } else {
                throw new Error("Opción 'Test Cost Edited' no encontrada");
            }
        });

        //taxes
        cy.contains("Tax").siblings("input").clear().type(newBudget.taxes);
        //discount
        cy.contains("Discount")
            .siblings("input")
            .clear()
            .type(newBudget.discount);
        //total
        cy.contains("Total").contains(newBudget.total);

        cy.contains("button", "Create").click();
        //notification
        cy.contains("Budget saved").should("exist");

        // EDIT BUDGET
        cy.url().should("include", "/budgets");
        //Seleccionar el ultimo budget creado
        cy.contains("tr", 1).within(() => {
            cy.get(".icon-edit").click();
        });
        //cambia el descuento
        cy.contains("Discount").siblings("input").clear().type("11");

        //Elimina 3er costo
        cy.contains("tr", 3).within(() => {
            cy.get(".text-red-600").click();
        });

        //el total deberia ser
        //cy.get("div.fle.flex-row.self-end").contains("323.07");
        //cy.contains("button", "Edit").click();

        //notification
        cy.contains("Budget updated").should("exist");

        //IMPRIMIR BUDGET
        cy.url().should("include", "/budgets");
        //Seleccionar el ultimo budget creado
        // Intercepta la solicitud para generar el PDF
        cy.intercept("GET", "budget/1/generate").as("generatePdf");

        // Hacer clic en el botón que genera el PDF
        cy.contains("tr", 1).within(() => {
            cy.get("button[title='Generate PDF']").click();
        });

        // Espera a que la solicitud se complete
        cy.wait("@generatePdf").then((interception) => {
            // Verifica si la respuesta tiene el formato esperado (por ejemplo, un código de estado 200)
            expect(interception.response.statusCode).to.eq(200);
            // Puedes añadir más verificaciones dependiendo de lo que el servidor responda
        });

        // DELETE BUDGET
        cy.url().should("include", "/budgets");
        //Seleccionar el ultimo budget creado
        cy.contains("tr", 1).within(() => {
            cy.get(".icon-delete").click();
        });
        //notification
        cy.contains("Budget deleted").should("exist");
    });
});
