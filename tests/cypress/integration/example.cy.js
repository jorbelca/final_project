const testUser = {
    name: "User Test",
    email: "user_test@test.com",
    password: "password",
    confirmPassword: "password",
};

describe("User Authentication Flow", () => {
    beforeEach(() => {
        // Antes de cada prueba, asegúrate de que el sitio esté limpio
        cy.visit("/");
    });

    it("shows the homepage", () => {
        cy.contains("budgets").should("be.visible");
    });

    it("can register a new user", () => {
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
        cy.wait(5000).contains(testUser.name).should("be.visible").click();

        // Hacer logout
        cy.contains("Log Out").click();

        // Verifica que el usuario esté desconectado
        cy.contains("Register").should("be.visible");
    });

    it("can log in an existing user", () => {
        cy.contains("Log in").click();

        // Verifica que la página de login esté cargada
        cy.url().should("include", "/login");

        // Completa el formulario de login
        cy.contains("Email").type(testUser.email);
        cy.contains("Password").type(testUser.password);

        cy.contains("Enter").then(($btn) => {
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

        // Eliminar el usuario
        cy.contains("Profile").should("be.visible").click();
        cy.wait(1500)
            .scrollTo("bottom")
            .get("button.bg-red-600")
            .should("be.visible")
            .click();
        cy.contains(
            "Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account."
        )
            .should("be.visible")
            .get("input#password_delete_user")
            .type(testUser.password)
            .then(() => {
                cy.get("button#btn_delete_user_final").click();
            });
        // Verifica que el usuario esté desconectado
        cy.contains("Log in").should("be.visible");
    });
});
