const { defineConfig } = require('cypress')

module.exports = defineConfig({
    e2e: {
        defaultCommandTimeout: 60000,
    },
})
