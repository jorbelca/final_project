const { testUser } = require("./data");

describe.only("User Delete", () => {
    beforeEach(() => {
        cy.visit("/login");
        cy.contains("Email").type(testUser.email);
        cy.contains("Password").type(testUser.password);
        cy.contains("Enter").click().wait(1000);
    });
    it("can delete a user", () => {
        cy.contains(testUser.name).should("be.visible").click();

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
