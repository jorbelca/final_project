const { testUser } = require("./data");

describe.only("User Login", () => {
    beforeEach(() => {
        cy.visit("/");
    });
    it("can log in a new user", () => {
        cy.contains("Log in").click();

        // Verifica que la página de login esté cargada
        cy.url().should("include", "/login");

        cy.get('input[type="email"], input[name="email"], input#email')
            .first()
            .type(testUser.email);
        cy.get('input[type="password"], input[name="password"], input#password')
            .first()
            .type(testUser.password);

        cy.contains("Iniciar Sesión").then(($btn) => {
            if ($btn.length > 0) {
                // Si el botón con el texto "Register" existe, se hace clic
                cy.wrap($btn).click();
            } else {
                // Si el botón con el texto "Register" no existe, buscar el botón tipo submit
                cy.get('button[type="submit"]').click(); // Selección alternativa
            }
        });

        // Verifica que el nombre de usuario se haya mostrado, indicando que el login fue exitoso
        cy.wait(5000).contains(testUser.name).should("be.visible").click();
    });
});
