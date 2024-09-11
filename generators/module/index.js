'use strict';
const _ = require('lodash');
const Generator = require('yeoman-generator');

module.exports = class extends Generator {

  constructor(args, opts) {
    super(args, opts);

    this.option('props', {
      type: Object,
      required: false,
      desc: 'Extension properties'
    });
  }

  initializing() {
    // Definir las propiedades de la extensión
    this.props = this.options.props;
  }

  prompting() {
    const prompts = [
      {
        type: 'list',
        name: 'moduleClient',
        message: 'What is the client of your module?',
        choices: [
          {
            name: 'Site',
            value: 'site'
          },
          {
            name: 'Administrator',
            value: 'admin'
          }
        ],
      },
      {
        type: 'input',
        name: 'namespace_vendor',
        message: `What do you call the first segment of a namespace, like Acme in Acme/Module/${this.props.namespaceExtName}?`,
        default: 'Acme'
      }
    ];

    return this.prompt(prompts).then(props => {
      props.namespace_vendor = _.startCase(props.namespace_vendor);
      // Añadir las propiedades de la extensión
      this.props = Object.assign(this.props, props);
    });
  }

  default() {
    // define the destination path
    this._destPath();

    // Add License
    this.composeWith(require.resolve('generator-license/app'), {
      name: this.props.extAuthor,
      email: this.props.extAuthorEmail,
      website: this.props.extAuthorURL,
      license: this.props.license,
      output: `${this.props.destPath}/LICENSE`
    });

  }

  writing() {
    // Definir la ruta del directorio de destino
    const destinationPath = this.destinationPath(this.props.destPath);

    // Copiar archivos de plantilla al directorio de destino
    this._copyModuleFiles(destinationPath);
  }

  // Private methods

  _copyModuleFiles(destinationPath) {
    let module_name = `mod_${this.props.extName}`;
    let from_to = [
      {
        from: 'services/**',
        to: 'services/'
      },
      {
        from: 'src/Dispatcher/**',
        to: 'src/Dispatcher/'
      },
      {
        from: 'tmpl/**',
        to: 'tmpl/'
      },
      {
          from: 'language/en-GB/extension.ini',
          to: `language/en-GB/${module_name}.ini`
      },
      {
          from: 'language/en-GB/extension.sys.ini',
          to: `language/en-GB/${module_name}.sys.ini`
      },
      {
          from: 'language/es-ES/extension.ini',
          to: `language/es-ES/${module_name}.ini`
      },
      {
          from: 'language/es-ES/extension.sys.ini',
          to: `language/es-ES/${module_name}.sys.ini`
      },
      {
        from: 'src/Helper/ModuleHelper.php',
        to: `src/Helper/${this.props.namespaceExtName}Helper.php`
      },
      {
        from: 'mod_module.xml',
        to: `${this.props.extName}.xml`
      }
    ];

    from_to.forEach((item) => {
      this._copyFiles(
        this.templatePath(item.from),
        this.destinationPath(`${destinationPath}/${item.to}`)
      );
    });
  }

  _copyFiles(templatePath, destinationPath) {
    // Copiar archivos de plantilla al directorio de destino
    this.fs.copyTpl(
      templatePath,
      destinationPath,
      this._patterns()
    );
  }

  /**
   * Define the destination path
   */
  _destPath() {
    this.props.destPath = `joomla_extensions/${this.props.extType}s/${this.props.moduleClient}/${this.props.extName}`;
  }

  /**
   * The patterns to replace in the templates
   *
   * @returns {Object} The patterns to replace in the templates
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
    }
  }
};