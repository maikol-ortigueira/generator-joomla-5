"use strict";
const Generator = require("yeoman-generator");
const chalk = require("chalk");
const pluralize = require("pluralize");
const _ = require("lodash");

module.exports = class extends Generator {
  constructor(args, opts) {
    super(args, opts);

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
        type: "input",
        name: "namespace_vendor",
        message: `Your vendor, like Acme in ${chalk.yellow(
          "Acme"
        )}\\Component\\${this.options.nsExtName}?`,
        default: "Acme"
      },
      {
        type: "input",
        name: "itemName",
        message: "The singular item name, like banner in com_banners?",
        default: "foo"
      }
    ];

    return this.prompt(prompts).then(props => {
      // nsVendorName
      let nsVendorName = props.namespace_vendor.trim().toLowerCase();
      props.namespace_vendor = _.startCase(nsVendorName).replace(/ /g, "");
      this.props = props;

      const additionalPrompts = [
        {
          type: "input",
          name: "itemsName",
          message: "The plural item name, like banners in com_banners?",
          default: `${pluralize(this.props.itemName)}`
        }
      ];

      return this.prompt(additionalPrompts).then(additionalProps => {
        this.props = Object.assign(this.props, additionalProps);
      });
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
    this._copyComponentFiles(destinationPath);
  }

  _copyComponentFiles(destinationPath) {
    let component_name = `com_${this.options.extName}`;

    let from_to = [
      {
        from: "extension.xml",
        to: `${this.options.extName}.xml`
      },
      {
        from: "script.php",
        to: "script.php"
      }
    ];

    // concat the admin, site and media from_to arrays
    from_to = from_to
      .concat(this._admin_from_to(component_name))
      .concat(this._site_from_to(component_name))
      .concat(this._media_from_to(component_name));

    from_to.forEach(item => {
      this._copyFiles(
        this.templatePath(item.from),
        this.destinationPath(`${destinationPath}/${item.to}`)
      );
    });
  }

  _admin_from_to(component_name) {
    const nsExtName = this.options.nsExtName;
    const lItemName = this.props.itemName.toLowerCase();
    const nsItemName = _.startCase(lItemName).replace(/\s/g, "");
    const lItemsName = this.props.itemsName.toLowerCase();
    const nsItemsName = _.startCase(lItemsName).replace(/\s/g, "");
    let from_to = [
      {
        from: "admin/access.xml",
        to: "admin/access.xml"
      },
      {
        from: "admin/config.xml",
        to: "admin/config.xml"
      },
      {
        from: "admin/forms/item.xml",
        to: `admin/forms/${lItemName}.xml`
      },
      {
        from: "admin/forms/items.xml",
        to: `admin/forms/filter_${lItemsName}.xml`
      },
      {
        from: "admin/services/**",
        to: `admin/services/`
      },
      {
        from: "admin/sql/**",
        to: `admin/sql/`
      },
      {
        from: "admin/src/View/Item/**",
        to: `admin/src/View/${nsItemName}/`
      },
      {
        from: "admin/src/View/Items/**",
        to: `admin/src/View/${nsItemsName}/`
      },
      {
        from: "admin/tmpl/item/**",
        to: `admin/tmpl/${lItemName}/`
      },
      {
        from: "admin/tmpl/items/**",
        to: `admin/tmpl/${lItemsName}/`
      },
      {
        from: "admin/src/Extension/extension.php",
        to: `admin/src/Extension/${nsExtName}Component.php`
      },
      {
        from: "admin/src/Helper/extension.php",
        to: `admin/src/Helper/${nsExtName}Helper.php`
      }
    ];

    return from_to
      .concat(
        this._get_from_to("admin/src/", true, true, ["Display"], "Controller")
      )
      .concat(this._get_from_to("admin/src/", true, true, [], "Model"))
      .concat(this._get_from_to("admin/src/", true, false, [], "Table"))
      .concat(this._mapLanguageFiles("extension", component_name, "admin"));
  }

  _site_from_to(component_name) {
    const nsExtName = this.options.nsExtName;
    let from_to = [
      {
        from: "site/src/Service/Router.php",
        to: "site/src/Service/Router.php"
      },
      {
        from: "site/src/Helper/extension.php",
        to: `site/src/Helper/${nsExtName}Helper.php`
      },
      {
        from: "site/src/View/Item/HtmlView.php",
        to: `site/src/View/${_.startCase(
          this.props.itemName.toLowerCase()
        ).replace(/\s/g, "")}/HtmlView.php`
      },
      {
        from: "site/src/View/Items/HtmlView.php",
        to: `site/src/View/${_.startCase(
          this.props.itemsName.toLowerCase()
        ).replace(/\s/g, "")}/HtmlView.php`
      },
      {
        from: "site/tmpl/items/default.php",
        to: `site/tmpl/${this.props.itemsName.toLowerCase()}/default.php`
      },
      {
        from: "site/tmpl/item/edit.php",
        to: `site/tmpl/${this.props.itemName.toLowerCase()}/edit.php`
      }
    ];

    return from_to
      .concat(
        this._get_from_to("site/src/", true, true, ["Display"], "Controller")
      )
      .concat(this._get_from_to("site/src/", true, true, [], "Model"))
      .concat(this._mapLanguageFiles("extension", component_name, "site"));
  }

  _media_from_to() {
    return [
      {
        from: "media/css/extension.css",
        to: `media/css/${this.options.extName}.css`
      },
      {
        from: "media/js/extension.js",
        to: `media/js/${this.options.extName}.js`
      },
      {
        from: "media/joomla.asset.json",
        to: "media/joomla.asset.json"
      }
    ];
  }

  _get_from_to(
    prefix,
    item = true,
    items = true,
    more = [],
    src_folder = "Controller"
  ) {
    let from_to = [];

    if (item) {
      from_to = from_to.concat({
        from: `${prefix}${src_folder}/Item${src_folder}.php`,
        to: `${prefix}${src_folder}/${_.startCase(
          this.props.itemName.toLowerCase()
        ).replace(/\s/g, "")}${src_folder}.php`
      });
    }

    if (items) {
      from_to = from_to.concat({
        from: `${prefix}${src_folder}/Items${src_folder}.php`,
        to: `${prefix}${src_folder}/${_.startCase(
          this.props.itemsName.toLowerCase()
        ).replace(/\s/g, "")}${src_folder}.php`
      });
    }

    if (more.length > 0) {
      more.forEach(item => {
        from_to = from_to.concat({
          from: `${prefix}${src_folder}/${item}${src_folder}.php`,
          to: `${prefix}${src_folder}/${item}${src_folder}.php`
        });
      });
    }

    return from_to;
  }

  /**
   * Map the language files
   * @param {string} initialName - The initial name of the language files
   * @param {string} finalName - The final name of the language files
   * @param {string} client - The client
   * @param {Array<string>} languages - The languages to map
   * @returns {Array<object>} The mapped language files
   */
  _mapLanguageFiles(initialName, finalName, client, languages = []) {
    // if language array is empty, set default languages
    if (languages.length === 0) {
      languages = ["en-GB", "es-ES"];
    }

    languages = languages.map(language => {
      // returns an array with the language files
      if (client === "site") {
        return [
          {
            from: `site/language/lang/${initialName}.ini`,
            to: `site/language/${language}/${finalName}.ini`
          }
        ];
      } else {
        return [
          {
            from: `admin/language/lang/${initialName}.ini`,
            to: `admin/language/${language}/${finalName}.ini`
          },
          {
            from: `admin/language/lang/${initialName}.sys.ini`,
            to: `admin/language/${language}/${finalName}.sys.ini`
          }
        ];
      }
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
    this.props.destPath = `joomla_extensions/components/${this.options.extName}`;
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
      lItemName: this.props.itemName,
      uItemName: this.props.itemName.toUpperCase(),
      nsItemName: _.startCase(this.props.itemName.toLowerCase()).replace(
        /\s/g,
        ""
      ),
      lItemsName: this.props.itemsName,
      uItemsName: this.props.itemsName.toUpperCase(),
      nsItemsName: _.startCase(this.props.itemsName.toLowerCase()).replace(
        /\s/g,
        ""
      )
    };
  }
};
