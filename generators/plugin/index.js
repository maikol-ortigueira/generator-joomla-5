'use strict';

const Generator = require('yeoman-generator');
const chalk = require('chalk');
const fs = require('fs');

function getPluginGroups() {
    // read files from templates/src/Extension
    const files = fs.readdirSync(__dirname + '/templates/src/Extension');
    // map the files to get the plugin groups and remove extension from the file name
    return files.map(file => file.replace('.php', ''));
}

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
        // Define the extension properties
        this.props = this.options.props;

        this.pluginGroups = getPluginGroups();
    }

    prompting() {
        const prompts = [
            {
                type: 'list',
                name: 'pluginGroup',
                message: 'Select the plugin group',
                choices: this.pluginGroups,
            },
            {
                type: 'input',
                name: 'namespace_vendor',
                message: `Your vendor, like Acme in ${chalk.yellow("Acme")}\\Plugin\\PluginGroup\\${this.props.namespaceExtName}?`,
                default: 'Acme'
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
        this.composeWith(require.resolve('generator-license/app'), {
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

        // this.log(this.props);
        // this.log(this._patterns());
        // Copy the template files
        this._copyPluginFiles(destinationPath);
    }

    _copyPluginFiles(destinationPath) {
        let plugin_name = `plg_${this.props.pluginGroup.toLowerCase()}_${this.props.extName}`;
        let from_to = [
            {
                from: 'services/**',
                to: 'services/'
            },
            {
                from: 'language/en-GB/extension.ini',
                to: `language/en-GB/${plugin_name}.ini`
            },
            {
                from: 'language/en-GB/extension.sys.ini',
                to: `language/en-GB/${plugin_name}.sys.ini`
            },
            {
                from: 'language/es-ES/extension.ini',
                to: `language/es-ES/${plugin_name}.ini`
            },
            {
                from: 'language/es-ES/extension.sys.ini',
                to: `language/es-ES/${plugin_name}.sys.ini`
            },
            {
                from: `src/Extension/${this.props.pluginGroup}.php`,
                to: `src/Extension/${this.props.namespaceExtName}.php`
            },
            {
                from: 'plugin.xml',
                to: `${this.props.extName}.xml`
            }
        ]

        from_to.forEach(item => {
            this._copyFiles(
                this.templatePath(item.from),
                this.destinationPath(`${destinationPath}/${item.to}`)
            );
        });
    }

    _copyFiles(templatePath, destinationPath) {
        this.fs.copyTpl(
            templatePath,
            destinationPath,
            this._patterns()
        );
    }

    _destPath() {
        // Define the destination path
        this.props.destPath = `joomla_extensions/plugins/${this.props.pluginGroup.toLowerCase()}/${this.props.extName}`;
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