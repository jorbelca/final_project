const { testUser } = require("./data");

describe.only("User Delete", () => {
    beforeEach(() => {
        cy.visit("/login");
        cy.contains("Email").type(testUser.email);
        cy.contains("Contraseña").type(testUser.password);
        cy.contains("Entrar").click();
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
