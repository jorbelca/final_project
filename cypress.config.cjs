const { defineConfig } = require("cypress");

module.exports = defineConfig({
    chromeWebSecurity: false,
    defaultCommandTimeout: 5000,
    watchForFileChanges: false,
    failOnStatusCode: false,
    videosFolder: "tests/cypress/videos",
    screenshotsFolder: "tests/cypress/screenshots",
    fixturesFolder: "tests/cypress/fixture",
    e2e: {
        baseUrl: "http://localhost:8000",
        specPattern: "tests/cypress/integration/**/*.cy.{js,jsx,ts,tsx}",
        supportFile: false,
    },
});
