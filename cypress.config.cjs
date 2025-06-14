const { defineConfig } = require("cypress");
require("dotenv").config();

module.exports = defineConfig({
    chromeWebSecurity: false,
    defaultCommandTimeout: 15000,
    watchForFileChanges: false,
    failOnStatusCode: false,
    videosFolder: "tests/cypress/videos",
    screenshotsFolder: "tests/cypress/screenshots",
    fixturesFolder: "tests/cypress/fixture",

    e2e: {
        baseUrl:
            process.env.CI &&
            process.env.CI !== "false" &&
            process.env.CI !== "0"
                ? "http://localhost:8000"
                : "http://localhost",
        specPattern: "tests/cypress/integration/**/*.cy.{js,jsx,ts,tsx}",
        supportFile: false,
    },
});
