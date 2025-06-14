const { testUser, newClient } = require("./data");
import "cypress-file-upload";

describe.only("Clients", () => {
    it("can create a client", () => {
        // LOGIN
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





    // // Test 5: File Upload Vulnerabilities
    // describe("File Upload Security Tests", () => {
    //     it("should prevent malicious file uploads", () => {
    //         cy.visit('/upload', { failOnStatusCode: false });

    //         const maliciousFiles = [
    //             'test.php',
    //             'shell.jsp',
    //             'backdoor.aspx',
    //             'script.js',
    //             'payload.svg'
    //         ];

    //         maliciousFiles.forEach((filename) => {
    //             cy.get('input[type="file"]').then($input => {
    //                 if ($input.length > 0) {
    //                     const blob = new Blob(['<?php system($_GET["cmd"]); ?>'], { type: 'text/plain' });
    //                     const file = new File([blob], filename, { type: 'text/plain' });

    //                     const dataTransfer = new DataTransfer();
    //                     dataTransfer.items.add(file);
    //                     $input[0].files = dataTransfer.files;

    //                     cy.wrap($input).trigger('change');
    //                     cy.get('button[type="submit"]').click();

    //                     // Should reject malicious files
    //                     cy.get('body').should('contain', 'error').or('contain', 'rejected');
    //                 }
    //             });
    //         });
    //     });
    // });
