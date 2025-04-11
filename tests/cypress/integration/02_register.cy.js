const { testUser } = require("./data");

describe.only("User Registration", () => {
    beforeEach(() => {
        cy.visit("/");
    });

    it.only("can register a new user", () => {
        cy.contains("Register").click();

        // Verifica que la página de registro está cargada
        cy.url().should("include", "/register");

        // Completa el formulario de registro
        cy.contains("Name").type(testUser.name);
        cy.contains("Email").type(testUser.email);
        cy.contains("Password").type(testUser.password);
        cy.contains("Confirm Password").type(testUser.confirmPassword);

        cy.get('button[type="submit"]')
            .contains("Register")
            .then(($btn) => {
                if ($btn.length > 0) {
                    // Si el botón con el texto "Register" existe, se hace clic
                    cy.wrap($btn).click();
                } else {
                    // Si el botón con el texto "Register" no existe, buscar el botón tipo submit
                    cy.get('button[type="submit"]').click(); // Selección alternativa
                }
            });

        // Verifica que el nombre de usuario se haya mostrado, indicando que el registro fue exitoso
        cy.contains(testUser.name).should("be.visible").click();

        // Hacer logout
        cy.contains("Log Out").click();

        // Verifica que el usuario esté desconectado
        cy.contains("Register").should("be.visible");
    });
});
