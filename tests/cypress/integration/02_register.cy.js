const { testUser } = require("./data");

describe.only("User Registration", () => {
    beforeEach(() => {
        cy.visit("/");
    });

    it.only("can register a new user", () => {
        cy.contains("Registro").click();

        // Verifica que la página de registro está cargada
        cy.url().should("include", "/register");

        // Completa el formulario de registro
          cy.get('input[type="text"], input[name="name"], input#name')
            .first().type(testUser.name);
        cy.get('input[type="email"], input[name="email"], input#email')
            .first()
            .type(testUser.email);
        cy.get('input[type="password"], input[name="password"], input#password')
            .first()
            .type(testUser.password);
          cy.get(' input#password_confirmation')
            .first().type(testUser.confirmPassword);

        cy.get('button[type="submit"]')
            .contains("Crear Cuenta")
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
        cy.contains("Cerrar Sesión").click();

        // Verifica que el usuario esté desconectado
        cy.contains("Registro").should("be.visible");
    });
});
