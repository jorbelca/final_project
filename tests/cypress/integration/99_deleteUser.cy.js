const { testUser } = require("./data");

describe.only("User Delete", () => {
    beforeEach(() => {
        cy.visit("/login");
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
    });
    it("can delete a user", () => {
        cy.contains(testUser.name).should("be.visible").click();

        // Eliminar el usuario
        cy.contains("Perfil").should("be.visible").click();
        cy.scrollTo("bottom")
            .get("button.bg-red-600")
            .should("be.visible")
            .click();
        cy.contains(
            "¿Estás seguro de que deseas eliminar tu cuenta? Una vez que tu cuenta sea eliminada, todos sus recursos y datos serán eliminados de forma permanente. Por favor, introduce tu contraseña para confirmar que deseas eliminar tu cuenta de forma permanente."
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
