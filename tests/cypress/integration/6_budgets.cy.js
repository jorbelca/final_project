const { testUser, newBudget } = require("./data");
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
        cy.contains("Select a cost").select(newBudget.cost);
        cy.contains("ADD COST").click();
        cy.contains("Select a cost").select(newBudget.cost);
        cy.contains("ADD COST").siblings("input").type("2");
        cy.contains("ADD COST").click();
        cy.contains("Select a cost").select(newBudget.cost);
        cy.contains("ADD COST").siblings("input").type("3");
        cy.contains("ADD COST").click();
        //taxes
        cy.contains("Tax").siblings("input").type(newBudget.taxes);
        //discount
        cy.contains("Discount").siblings("input").type(newBudget.discount);
        //total
        cy.contains("Total").should("have.text", newBudget.total);

        cy.contains("Create").click();
        //notification
        cy.contains("Budget saved").should("exist");

        // EDIT BUDGET
        cy.url().should("include", "/budgets");
        //Seleccionar el ultimo budget creado
        cy.contains("tr", 1).within(() => {
            cy.get(".icon-edit").click();
        });
        //Elimina 3er costo
        cy.contains("tr", 3).within(() => {
            cy.get(".text-red-600").click();
        });
        //cambia el descuento
        cy.contains("Discount").siblings("input").type("11");
        //el total deberia ser
        cy.contains("Total").should("have.text", 161, 53);
        cy.contains("EDIT").click();

        //notification
        cy.contains("Budget updated").should("exist");

        //IMPRIMIR BUDGET
        cy.url().should("include", "/budgets");
        //Seleccionar el ultimo budget creado
        cy.contains("tr", 1).within(() => {
            cy.get("button[title='Generate PDF']").click();
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
