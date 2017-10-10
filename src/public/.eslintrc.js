module.exports = {
    "parser": "babel-eslint",
    "extends": "eslint:recommended",
    "env": {
        "browser": true,
        "node": true
    },
    "parserOptions": {
        "sourceType": "module",
        "allowImportExportEverywhere": false,
        "codeFrame": false,
        "ecmaFeatures": {
            "jsx": true
        }
    },
    "rules": {
        // enable additional rules
        "indent": ["warn", 4],
        "no-unused-vars": ["warn", { "vars": "all", "args": "after-used", "ignoreRestSiblings": false }],
        
        // override default options for rules from base configurations
        "comma-dangle": ["warn", "always"],
        "no-cond-assign": ["warn", "always"],

        // disable rules from base configurations
        "no-console": "off",
    }
}