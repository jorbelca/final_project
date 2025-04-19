const { testUser, newBudget, newCost } = require("./data");
import "cypress-file-upload";

describe.only("Budgets", () => {
    it("can create a budget", () => {
        // LOGIN
        cy.visit("/login");
        cy.contains("Email").type(testUser.email);
        cy.contains("Contraseña").type(testUser.password);
        cy.contains("Enter").click();
        cy.url().should("include", "/budgets");

        // CREATE BUDGET
        //client
        cy.contains("Crea un Presupuesto").click();
        cy.url().should("include", "/budgets/create");
        cy.get("#client").select(newBudget.client + " Editado");

        //costs
        cy.get("#costs option").then(($options) => {
            const optionText = [...$options]
                .map((opt) => opt.textContent.trim())
                .find((text) => text.includes(newBudget.cost + " Editado")); // Busca la opción correcta

            if (optionText) {
                cy.get("#costs").select(optionText); // Selecciona la opción encontrada
                cy.contains("button", "Añadir coste")
                    .should("be.visible")
                    .click();

                cy.get("td")
                    .find("div")
                    .find('input[type="number"][placeholder="quantity"]')
                    .first()
                    .clear()
                    .type("2");
                cy.get("#costs").select(optionText);
                cy.contains("button", "Añadir coste")
                    .should("be.visible")
                    .click();

                // Using find() to traverse down the DOM tree
                cy.get("td")
                    .find("div")
                    .find('input[type="number"][placeholder="quantity"]')
                    .first()
                    .clear()
                    .type("3");

                cy.get("#costs").select(optionText);
                cy.contains("button", "Añadir coste")
                    .should("be.visible")
                    .click();
            } else {
                throw new Error("Opción 'Test Cost Editado' no encontrada");
            }
        });

        //taxes
        cy.contains("Impuestos")
            .siblings("input")
            .clear()
            .type(newBudget.taxes);
        //discount
        cy.contains("Descuento")
            .siblings("input")
            .clear()
            .type(newBudget.discount);
        //total
        cy.get("b.text-text.text-lg.font-extrabold")
            .contains("Total")
            .contains(newBudget.total);

        cy.contains("button", "Crear").click();
        //notification
        cy.contains("Budget saved").should("exist");

        // EDIT BUDGET
        cy.url().should("include", "/budgets");
        //Seleccionar el ultimo budget creado
        cy.contains("tr", 1).within(() => {
            cy.get(".dropdown").get("button.btn.primary").click();
            // Find the button by its title attribute within the row and click it
            cy.get('button[title="Edit Budget"]').should("be.visible").click();
        });

        //cambia el descuento
        cy.contains("Descuento").siblings("input").clear().type("11");

        //Elimina 3er costo
        cy.contains("tr", 3).within(() => {
            cy.get(".text-red-600").click();
        });

        //el total editado deberia ser
        cy.get("b.text-text.text-lg.font-extrabold")
            .contains("Total")
            .contains("215,38");
        cy.contains("button", "Edit").click();

        //notification
        cy.contains("Budget updated").should("exist");

        // LIMPIAR
            // This test will only run if the previous test passed
            // (Cypress stops test execution after a failure)

            //ELIMINAR COSTO
            cy.visit("/costs");
            cy.contains("tr", newCost.description + " Editado").within(() => {
                cy.get(".icon-delete").click();
            });

            cy.on("window:confirm", () => true);

            cy.contains(newCost.description + " Editado").should("not.exist");

            // DELETE CLIENT
            cy.visit("/clients");
            cy.contains("tr", newBudget.client + " Editado").within(() => {
                cy.get(".icon-delete").click();
            });

            cy.on("window:confirm", () => true);

            cy.contains(newBudget.client + " Editado").should("not.exist");
            cy.contains("Client deleted").should("exist");

            // DELETE BUDGET
            cy.visit("/budgets");
            //Seleccionar el ultimo budget creado
            cy.contains("tr", 1).within(() => {
                cy.get(".dropdown").get("button.btn.primary").click();
                // Find the button by its title attribute within the row and click it
                cy.get('button[title="Delete Budget"]').should("be.visible").click();
            });
            cy.on("window:confirm", () => true);
            cy.contains("tr", 1).should("not.exist");
            //notification
            cy.contains("Budget deleted").should("exist");

    });
});
