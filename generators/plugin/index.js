"use strict";
const Generator = require("yeoman-generator");
const chalk = require("chalk");
const fs = require("fs");

/**
 * Get the plugin groups
 * @returns {Array<string>} The plugin groups
 */
function getPluginGroups() {
  // read files from templates/src/Extension
  const files = fs.readdirSync(__dirname + "/templates/src/Extension");
  // map the files to get the plugin groups and remove extension from the file name
  return files.map(file => file.replace(".php", ""));
}

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

    this.pluginGroups = getPluginGroups();
  }

  prompting() {
    const prompts = [
      {
        type: "list",
        name: "pluginGroup",
        message: "Select the plugin group",
        choices: this.pluginGroups
      },
      {
        type: "input",
        name: "namespace_vendor",
        message: `Your vendor, like Acme in ${chalk.yellow(
          "Acme"
        )}\\Plugin\\PluginGroup\\${this.props.namespaceExtName}?`,
        default: "Acme"
      }
    ];

    return this.prompt(prompts).then(props => {
      // Add the extension properties
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
    // Define the destination path
    const destinationPath = this.destinationRoot(this.props.destPath);
    this._mapLanguageFiles(
      "extension",
      `plg_${this.props.pluginGroup.toLowerCase()}_${this.props.extName}`
    );
    // Copy the template files
    this._copyPluginFiles(destinationPath);
  }

  _copyPluginFiles(destinationPath) {
    let plugin_name = `plg_${this.props.pluginGroup.toLowerCase()}_${
      this.props.extName
    }`;
    let from_to = [
      {
        from: "services/**",
        to: "services/"
      },
      {
        from: `src/Extension/${this.props.pluginGroup}.php`,
        to: `src/Extension/${this.props.namespaceExtName}.php`
      },
      {
        from: "plugin.xml",
        to: `${this.props.extName}.xml`
      }
    ];

    // merge the language files
    from_to = from_to.concat(this._mapLanguageFiles("extension", plugin_name));

    // copy the files
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
    this.fs.copyTpl(templatePath, destinationPath, this._patterns());
  }

  _destPath() {
    // Define the destination path
    this.props.destPath = `joomla_extensions/plugins/${this.props.pluginGroup.toLowerCase()}/${
      this.props.extName
    }`;
  }

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
      lPluginGroup: this.props.pluginGroup.toLowerCase(),
      uPluginGroup: this.props.pluginGroup.toUpperCase(),
      nsPluginGroup: this.props.pluginGroup
    };
  }
};
