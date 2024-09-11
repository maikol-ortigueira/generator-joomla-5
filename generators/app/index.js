"use strict";
const Generator = require("yeoman-generator");
const chalk = require("chalk");
const yosay = require("yosay");
const _ = require("lodash");

const licenses = [
  { name: "Apache 2.0", value: "Apache-2.0" },
  { name: "MIT", value: "MIT" },
  { name: "Mozilla Public License 2.0", value: "MPL-2.0" },
  { name: "BSD 2-Clause (FreeBSD) License", value: "BSD-2-Clause-FreeBSD" },
  { name: "BSD 3-Clause (NewBSD) License", value: "BSD-3-Clause" },
  { name: "Internet Systems Consortium (ISC) License", value: "ISC" },
  { name: "GNU AGPL 3.0", value: "AGPL-3.0" },
  { name: "GNU GPL 3.0", value: "GPL-3.0" },
  { name: "GNU LGPL 3.0", value: "LGPL-3.0" },
  { name: "Unlicense", value: "Unlicense" },
  { name: "No License (Copyrighted)", value: "UNLICENSED" }
];

module.exports = class extends Generator {
  prompting() {
    // Have Yeoman greet the user.
    this.log(yosay(`Welcome to ${chalk.red("generator-joomla5")} generator!`));

    const prompts = [
      {
        type: "list",
        name: "extType",
        message: "What kind of extension would you like to create?",
        choices: [
          {
            name: "Component",
            value: "component"
          },
          {
            name: "Module",
            value: "module"
          },
          {
            name: "Plugin",
            value: "plugin"
          }
        ],
        default: "module"
      },
      {
        type: "input",
        name: "extName",
        message: "What is the name of your extension?",
        default: "mi extension",
        validate: function(input) {
          if (/^([a-zA-Z0-9_ -])+$/.test(input)) return true;
          return "Extension name may only include letters, numbers, underscores and hashes.";
        }
      },
      {
        type: "input",
        name: "extDescription",
        message: "What is the description of your extension?"
      },
      {
        type: "input",
        name: "extAuthor",
        message: "What is the author of your extension?",
        default: "John Doe"
      },
      {
        type: "input",
        name: "extAuthorEmail",
        message: "What is the author email of your extension?",
        default: "johndoe@example.com"
      },
      {
        type: "input",
        name: "extAuthorURL",
        message: "What is the author URL of your extension?",
        default: "http://www.example.com"
      },
      {
        type: "list",
        name: "license",
        message: "Which license do you want to use?",
        choices: licenses
      }
    ];

    return this.prompt(prompts).then(props => {
      let extName = props.extName.trim().toLowerCase();
      props.extName = _.snakeCase(extName);
      props.namespaceExtName = _.startCase(props.extName).replace(/ /g, "");

      // To access props later use this.props.someAnswer;
      this.props = props;
    });
  }

  default() {
    // date properties
    this._dateProps();

    // Compose with the selected extension type
    this.composeWith(require.resolve(`../${this.props.extType}`), {
      props: this.props
    });
  }

  _dateProps() {
    // current date
    const currentDate = new Date();
    const year = currentDate.getFullYear();
    const month = String(currentDate.getMonth() + 1).padStart(2, "0"); // Añadir 1 porque getMonth() devuelve 0-11
    this.props.currentDate = `${year}-${month}`;
    this.props.currentYear = year;
  }
};
