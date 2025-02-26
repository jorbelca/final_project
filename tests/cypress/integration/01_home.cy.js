describe("User Flow", () => {
    beforeEach(() => {
        // Antes de cada prueba, asegúrate de que el sitio esté limpio
        cy.visit("/");
    });

    it("shows the homepage", () => {
        cy.contains("budgets").should("be.visible");
    });
});
