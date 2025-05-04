const { testUser, newClient } = require("./data");
import "cypress-file-upload";

describe.only("Clients", () => {
    it("can create a client", () => {
        // LOGIN
        cy.visit("/login");
        cy.contains("Email").type(testUser.email);
        cy.contains("Contraseña").type(testUser.password);
        cy.contains("Entrar").click();
        cy.url().should("include", "/budgets");

        // CREATE CLIENTE
        cy.contains("Clientes").click();
        cy.url().should("include", "/clients");

        cy.contains("Crear un Cliente").click();
        cy.url().should("include", "/clients/create");

        cy.get('label:contains("Nombre")').siblings("input").type(newClient.name);
        cy.get('label:contains("Email")')
            .siblings("input")
            .type(newClient.email);
        cy.get('label:contains("Empresa")')
            .siblings("input")
            .type(newClient.company);

        cy.get("button").contains("Crear").click().wait(10000);
        cy.url().should("include", "/clients");
        //cy.contains("Client created succesfully").should("exist");
        cy.contains(newClient.name).should("exist");

        // EDIT CLIENT
        // A USER CAN UPDATE HIS CLIENT LOGO
        cy.visit("/clients");
        cy.contains("tr", newClient.name).within(() => {
            cy.get(".icon-edit").click();
        });
        cy.url().should("include", "/edit");

        const imagePath = "/images/logo.png";
        cy.get('input[type="file"]').attachFile(imagePath);

        cy.get("button").contains("Edit").click();
        //cy.contains("Client updated succesfully").should("exist");

        //Check the image uploaded
        cy.get("table")
            .find('th:contains("Logo")')
            .closest("table")
            .find("tbody img")
            .should("have.attr", "src")
            .and("include", "res.cloudinary.com")
            .and("include", "client_logos");

        // A USER CAN UPDATE HIS CLIENT NAME
        cy.contains("tr", newClient.name).within(() => {
            cy.get(".icon-edit").click();
        });
        cy.url().should("include", "/edit");
        cy.get('label:contains("Nombre")').siblings("input").type(" Editado");

        cy.get("button").contains("Editar").click();

        cy.contains(newClient.name + " Editado").should("exist");
        cy.contains("Cliente actualizado correctamente").should("exist");
    });
    // after(() => {
    //     // DELETE CLIENT
    //     cy.visit("/clients");
    //     cy.contains("tr", newClient.name + " Editado").within(() => {
    //         cy.get(".icon-delete").click();
    //     });

    //     cy.on("window:confirm", () => true);

    //     cy.contains(newClient.name + " Editado").should("not.exist");
    //     cy.contains("Client deleted").should("exist");
    // });
});
