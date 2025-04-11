const { newCost, testUser } = require("./data");

// Utilizar cy.session() para mantener la sesión entre tests
// const login = (email, password) => {
//     cy.session([email, password], () => {
//         cy.visit("/login");
//         cy.contains("Email").type(email);
//         cy.contains("Password").type(password);
//         cy.contains("Enter").click().wait(3000);
//         cy.url().should("include", "/budgets");
//     });
// };

describe.only("Costs", () => {
    it("can create a cost", () => {
        //LOGIN
        cy.visit("/login");
        cy.contains("Email").type(testUser.email);
        cy.contains("Password").type(testUser.password);
        cy.contains("Enter").click()
        cy.url().should("include", "/budgets");

        //CREAR COSTO
        cy.contains("Costs").click();
        cy.url().should("include", "/costs");

        cy.contains("Create a Cost").click();
        cy.url().should("include", "/costs/create");

        cy.get('label:contains("Description")')
            .siblings("input")
            .type(newCost.description);
        cy.get('label:contains("Cost")').siblings("input").type(newCost.cost);
        cy.get('label:contains("Unit")').siblings("input").type(newCost.unit);
        cy.get('label:contains("Periodicity")')
            .siblings("select")
            .select(newCost.periodicity);

        cy.get("button").contains("Create").click();

        cy.contains(newCost.description).should("exist");

        //EDITAR COSTO
        // Visitar la página de costos directamente sin necesidad de login previo
        cy.visit("/costs");
        cy.contains("tr", newCost.description).within(() => {
            cy.get(".icon-edit").click();
        });
        cy.url().should("include", "/edit");

        cy.get('label:contains("Description")')
            .siblings("input")
            .type(" Edited");

        cy.get("button").contains("Edit").click();

        cy.contains(newCost.description + " Edited").should("exist");
    });
    // after(() => {
    //     //ELIMINAR COSTO
    //     cy.visit("/costs");
    //     cy.contains("tr", newCost.description + " Edited").within(() => {
    //         cy.get(".icon-delete").click();
    //     });

    //     cy.on("window:confirm", () => true);

    //     cy.contains(newCost.description + " Edited").should("not.exist");
    // });
});
