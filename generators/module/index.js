"use strict";
const _ = require("lodash");
const Generator = require("yeoman-generator");

module.exports = class extends Generator {
  constructor(args, options) {
    super(args, options);

    this.option("extName", {
      type: String,
      required: true,
      desc: "Extension name"
    });

    this.option("description", {
      type: String,
      required: false,
      desc: "Extension description"
    });

    this.option("author", {
      type: String,
      required: false,
      desc: "Extension author"
    });

    this.option("authorEmail", {
      type: String,
      required: false,
      desc: "Extension author email"
    });

    this.option("authorURL", {
      type: String,
      required: false,
      desc: "Extension author URL"
    });

    this.option("license", {
      type: String,
      required: false,
      desc: "Extension license"
    });

    this.option("nsExtName", {
      type: String,
      required: false,
      desc: "Extension namespace name"
    });

    this.option("currentDate", {
      type: String,
      required: false,
      desc: "Current date"
    });

    this.option("currentYear", {
      type: String,
      required: false,
      desc: "Current year"
    });
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
        message: `What do you call the first segment of a namespace, like Acme in Acme/Module/${this.options.nsExtName}?`,
        default: "Acme"
      }
    ];

    return this.prompt(prompts).then(props => {
      // nsVendorName
      let nsVendorName = props.namespace_vendor.trim().toLowerCase();
      props.namespace_vendor = _.startCase(nsVendorName).replace(/ /g, "");
      // Assign the extension properties
      this.props = props;
    });
  }

  default() {
    // define the destination path
    this._destPath();

    // Add License
    this.composeWith(require.resolve("generator-license/app"), {
      name: this.options.author,
      email: this.options.authorEmail,
      website: this.options.authorURL,
      license: this.options.license,
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
    let module_name = `mod_${this.options.extName}`;
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
        to: `src/Helper/${this.options.nsExtName}Helper.php`
      },
      {
        from: "mod_module.xml",
        to: `${this.options.extName}.xml`
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
    this.props.destPath = `joomla_extensions/modules/${this.props.moduleClient}/${this.options.extName}`;
  }

  /**
   * The patterns to replace in the templates
   * @returns {object} The patterns to replace in the templates
   */
  _patterns() {
    return {
      lExtName: this.options.extName,
      uExtName: this.options.extName.toUpperCase(),
      nsExtName: this.options.nsExtName,
      authorName: this.options.author,
      authorEmail: this.options.authorEmail,
      authorURL: this.options.authorURL,
      description: this.options.description,
      vendorName: this.props.namespace_vendor,
      creationDate: this.options.currentDate,
      year: this.options.currentYear,
      license: this.options.license,
      lModuleClient: this.props.moduleClient,
      uModuleClient: this.props.moduleClient.toUpperCase(),
      nsModuleClient: _.startCase(this.props.moduleClient)
    };
  }
};
