"use strict";
const Generator = require("yeoman-generator");

module.exports = class extends Generator {
  constructor(args, opts) {
    super(args, opts);

    this.option("props", {
      type: Object,
      required: true,
      desc: "The properties of the component"
    });
  }

  initializing() {
    this.props = this.options.props;
  }

  prompting() {
    const prompts = [
      {
        type: "input",
        name: "componentName",
        message: "What is the name of your component?",
        default: this.props.componentName
      }
    ];

    return this.prompt(prompts).then(props => {
      this.props = Object.assign(this.props, props);
    });
  }

  writing() {
    this.log(this.props);
  }
};
