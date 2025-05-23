describe("User Flow", () => {
    beforeEach(() => {
        // Antes de cada prueba, asegúrate de que el sitio esté limpio
        cy.visit("/");
    });

    it("shows the homepage", () => {
        cy.contains("Gestión de presupuestos profesionales").should("be.visible");
    });
});
