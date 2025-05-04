const { newCost, testUser } = require("./data");

// Utilizar cy.session() para mantener la sesión entre tests
// const login = (email, password) => {
//     cy.session([email, password], () => {
//         cy.visit("/login");
//         cy.contains("Email").type(email);
//         cy.contains("Password").type(password);
//         cy.contains("Entrar").click().wait(3000);
//         cy.url().should("include", "/budgets");
//     });
// };

describe.only("Costs", () => {
    it("can create a cost", () => {
        //LOGIN
        cy.visit("/login");
        cy.contains("Email").type(testUser.email);
        cy.contains("Contraseña").type(testUser.password);
        cy.contains("Entrar").click();
        cy.url().should("include", "/budgets");

        //CREAR COSTO
        cy.contains("Costes").click();
        cy.url().should("include", "/costs");

        cy.contains("Crear un Cost").click();
        cy.url().should("include", "/costs/create");

        cy.get('label:contains("Descripcion")')
            .siblings("input")
            .type(newCost.description);
        cy.get('label:contains("Coste")').siblings("input").type(newCost.cost);
        cy.get('label:contains("Unidad")').siblings("input").type(newCost.unit);
        cy.get('label:contains("Periodicidad")')
            .siblings("select")
            .select(newCost.periodicity);

        cy.get("button").contains("Crear").click();

        cy.contains(newCost.description).should("exist");

        //EDITAR COSTO
        // Visitar la página de costos directamente sin necesidad de login previo
        cy.visit("/costs");
        cy.contains("tr", newCost.description).within(() => {
            cy.get(".icon-edit").click();
        });
        cy.url().should("include", "/edit");

        cy.get('label:contains("Descripcion")')
            .siblings("input")
            .type(" Editado");

        cy.get("button").contains("Editar").click();

        cy.contains(newCost.description + " Editado").should("exist");
    });
});
