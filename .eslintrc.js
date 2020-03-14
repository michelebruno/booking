module.exports = {
    "env": {
        "browser": true,
        "es6": true
    },
    "extends": [
        "eslint:recommended",
        "plugin:react/recommended"
    ],
    "globals": {
        "_": "readonly",
        "Atomics": "readonly",
        "axios": "readonly",
        "SharedArrayBuffer": "readonly",
        "process": "readonly",
        "uppercase": "readonly",
    },
    "parser": "babel-eslint",
    "parserOptions": {
        "ecmaFeatures": {
            "jsx": true
        },
        "ecmaVersion": 2018,
        "sourceType": "module"
    },
    "plugins": [
        "react",
        "react-hooks"
    ],
    "rules": {
        "react/display-name": "off",
        "react-hooks/rules-of-hooks": "error",
        "comma-dangle": ["error", "always-multiline"],
        "comma-style": ["error", "last"],
        "no-else-return": "error",
        "no-useless-return": "error",
        "no-empty": "error",
    }
};