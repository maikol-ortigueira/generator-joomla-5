"use strict";
const _ = require("lodash");
const Generator = require("yeoman-generator");

module.exports = class extends Generator {
  constructor(args, opts) {
    super(args, opts);

    this.option("props", {
      type: Object,
      required: false,
      desc: "Extension properties"
    });
  }

  initializing() {
    // Define the extension properties
    this.props = this.options.props;
  }

  prompting() {
    const prompts = [
      {
        type: "list",
        name: "moduleClient",
        message: "What is the client of your module?",
        choices: [
          {
            name: "Site",
            value: "site"
          },
          {
            name: "Administrator",
            value: "admin"
          }
        ]
      },
      {
        type: "input",
        name: "namespace_vendor",
        message: `What do you call the first segment of a namespace, like Acme in Acme/Module/${this.props.namespaceExtName}?`,
        default: "Acme"
      }
    ];

    return this.prompt(prompts).then(props => {
      props.namespace_vendor = _.startCase(props.namespace_vendor);
      // Assign the extension properties
      this.props = Object.assign(this.props, props);
    });
  }

  default() {
    // define the destination path
    this._destPath();

    // Add License
    this.composeWith(require.resolve("generator-license/app"), {
      name: this.props.extAuthor,
      email: this.props.extAuthorEmail,
      website: this.props.extAuthorURL,
      license: this.props.license,
      output: `${this.props.destPath}/LICENSE`
    });
  }

  writing() {
    // define the destination path
    const destinationPath = this.destinationPath(this.props.destPath);

    // copy module files to the destination path
    this._copyModuleFiles(destinationPath);
  }

  // Private methods

  _copyModuleFiles(destinationPath) {
    let module_name = `mod_${this.props.extName}`;
    let from_to = [
      {
        from: "services/**",
        to: "services/"
      },
      {
        from: "src/Dispatcher/**",
        to: "src/Dispatcher/"
      },
      {
        from: "tmpl/**",
        to: "tmpl/"
      },
      {
        from: "src/Helper/ModuleHelper.php",
        to: `src/Helper/${this.props.namespaceExtName}Helper.php`
      },
      {
        from: "mod_module.xml",
        to: `${this.props.extName}.xml`
      }
    ];

    // merge the language files
    from_to = from_to.concat(this._mapLanguageFiles("extension", module_name));

    from_to.forEach(item => {
      this._copyFiles(
        this.templatePath(item.from),
        this.destinationPath(`${destinationPath}/${item.to}`)
      );
    });
  }

  /**
   * Map the language files
   * @param {string} initialName - The initial name of the language files
   * @param {string} finalName - The final name of the language files
   * @param {Array<string>} languages - The languages to map
   * @returns {Array<object>} The mapped language files
   */
  _mapLanguageFiles(initialName, finalName, languages = []) {
    // if language array is empty, set default languages
    if (languages.length === 0) {
      languages = ["en-GB", "es-ES"];
    }

    languages = languages.map(language => {
      // returns an array with the language files
      return [
        {
          from: `language/${language}/${initialName}.ini`,
          to: `language/${language}/${finalName}.ini`
        },
        {
          from: `language/${language}/${initialName}.sys.ini`,
          to: `language/${language}/${finalName}.sys.ini`
        }
      ];
    });

    // flatten the array
    return [].concat.apply([], languages);
  }

  _copyFiles(templatePath, destinationPath) {
    // copy the files
    this.fs.copyTpl(templatePath, destinationPath, this._patterns());
  }

  /**
   * Define the destination path
   */
  _destPath() {
    this.props.destPath = `joomla_extensions/${this.props.extType}s/${this.props.moduleClient}/${this.props.extName}`;
  }

  /**
   * The patterns to replace in the templates
   * @returns {object} The patterns to replace in the templates
   */
  _patterns() {
    return {
      lExtName: this.props.extName,
      uExtName: this.props.extName.toUpperCase(),
      nsExtName: this.props.namespaceExtName,
      authorName: this.props.extAuthor,
      authorEmail: this.props.extAuthorEmail,
      authorURL: this.props.extAuthorURL,
      description: this.props.extDescription,
      vendorName: this.props.namespace_vendor,
      creationDate: this.props.currentDate,
      year: this.props.currentYear,
      license: this.props.license,
      lModuleClient: this.props.moduleClient,
      uModuleClient: this.props.moduleClient.toUpperCase(),
      nsModuleClient: _.startCase(this.props.moduleClient)
    };
  }
};
