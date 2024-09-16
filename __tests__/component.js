"use strict";
const path = require("path");
const assert = require("yeoman-assert");
const helpers = require("yeoman-test");

jest.mock("generator-license/app", () => {
  const helpers = require("yeoman-test");
  return helpers.createDummyGenerator();
});

describe("generator-joomla-5:component", () => {
  beforeAll(() => {
    return helpers
      .run(path.join(__dirname, "../generators/component"))
      .withOptions({
        extName: "my_extension",
        description: "My component description",
        author: "Maikol Fustes",
        authorEmail: "maikol.ortigueira@gmail.com",
        authorURL: "https://maikol.eu",
        license: "MIT",
        nsExtName: "MyExtension",
        currentDate: "2020-01-01",
        currentYear: "2020"
      })
      .withPrompts({
        itemName: "item",
        itemsName: "items",
        namespace_vendor: "ortIGa"
      });
  });
  describe("Manifest & script Files", () => {
    it("creates expected files", () => {
      const expectedFiles = [
        "joomla_extensions/components/my_extension/my_extension.xml",
        "joomla_extensions/components/my_extension/script.php"
      ];

      assert.file(expectedFiles);
    });

    it("has expected content", () => {
      assert.fileContent(
        "joomla_extensions/components/my_extension/my_extension.xml",
        /<name>com_my_extension<\/name>/
      );
      assert.fileContent(
        "joomla_extensions/components/my_extension/my_extension.xml",
        /<author>Maikol Fustes<\/author>/
      );
      assert.fileContent(
        "joomla_extensions/components/my_extension/my_extension.xml",
        /<description>COM_MY_EXTENSION_XML_DESCRIPTION<\/description>/
      );
      assert.fileContent(
        "joomla_extensions/components/my_extension/my_extension.xml",
        /<authorEmail>maikol.ortigueira@gmail.com<\/authorEmail>/
      );
      assert.fileContent(
        "joomla_extensions/components/my_extension/my_extension.xml",
        /<authorUrl>https:\/\/maikol.eu<\/authorUrl>/
      );
      assert.fileContent(
        "joomla_extensions/components/my_extension/my_extension.xml",
        /<creationDate>2020-01-01<\/creationDate>/
      );
      assert.fileContent(
        "joomla_extensions/components/my_extension/my_extension.xml",
        /<namespace path="src">Ortiga\\Component\\MyExtension<\/namespace>/
      );
      assert.fileContent(
        "joomla_extensions/components/my_extension/my_extension.xml",
        /<license>MIT; see LICENSE.txt<\/license>/
      );
      assert.fileContent(
        "joomla_extensions/components/my_extension/my_extension.xml",
        /<version>1.0.0<\/version>/
      );
      assert.fileContent(
        "joomla_extensions/components/my_extension/my_extension.xml",
        /<scriptfile>script.php<\/scriptfile>/
      );
      assert.fileContent(
        "joomla_extensions/components/my_extension/my_extension.xml",
        /<file driver="mysql" charset="utf8">sql\/install.mysql.utf8.sql<\/file>/
      );
      assert.fileContent(
        "joomla_extensions/components/my_extension/my_extension.xml",
        /<schemapath type="mysql">sql\/updates\/mysql<\/schemapath>/
      );
      assert.fileContent(
        "joomla_extensions/components/my_extension/my_extension.xml",
        /<file driver="mysql" charset="utf8">sql\/uninstall.mysql.utf8.sql<\/file>/
      );
      assert.fileContent(
        "joomla_extensions/components/my_extension/my_extension.xml",
        /<files folder="site">/
      );
      assert.fileContent(
        "joomla_extensions/components/my_extension/my_extension.xml",
        /<files folder="admin">/
      );
      assert.fileContent(
        "joomla_extensions/components/my_extension/my_extension.xml",
        /<media destination="com_my_extension" folder="media">/
      );
      assert.fileContent(
        "joomla_extensions/components/my_extension/my_extension.xml",
        /<languages folder="site\/language">/
      );
      assert.fileContent(
        "joomla_extensions/components/my_extension/my_extension.xml",
        /<languages folder="admin">/
      );
      assert.fileContent(
        "joomla_extensions/components/my_extension/my_extension.xml",
        /<language tag="en-GB">language\/en-GB\/com_my_extension.ini<\/language>/
      );
      assert.fileContent(
        "joomla_extensions/components/my_extension/my_extension.xml",
        /<language tag="en-GB">language\/en-GB\/com_my_extension.sys.ini<\/language>/
      );
      assert.fileContent(
        "joomla_extensions/components/my_extension/my_extension.xml",
        /<language tag="es-ES">language\/es-ES\/com_my_extension.ini<\/language>/
      );
      assert.fileContent(
        "joomla_extensions/components/my_extension/my_extension.xml",
        /<language tag="es-ES">language\/es-ES\/com_my_extension.sys.ini<\/language>/
      );
    });
  });

  describe("Admin files", () => {
    it("creates expected files", () => {
      const prefix = "joomla_extensions/components/my_extension/admin/";
      const expectedFiles = [
        `${prefix}access.xml`,
        `${prefix}config.xml`,
        `${prefix}forms/item.xml`,
        `${prefix}forms/filter_items.xml`,
        `${prefix}language/en-GB/com_my_extension.ini`,
        `${prefix}language/en-GB/com_my_extension.sys.ini`,
        `${prefix}language/es-ES/com_my_extension.ini`,
        `${prefix}language/es-ES/com_my_extension.sys.ini`,
        `${prefix}services/provider.php`,
        `${prefix}sql/install.mysql.utf8.sql`,
        `${prefix}sql/uninstall.mysql.utf8.sql`,
        `${prefix}sql/updates/mysql/1.0.0.sql`,
        `${prefix}src/Controller/DisplayController.php`,
        `${prefix}src/Controller/ItemController.php`,
        `${prefix}src/Controller/ItemsController.php`,
        `${prefix}src/Extension/MyExtensionComponent.php`,
        `${prefix}src/Helper/MyExtensionHelper.php`,
        `${prefix}src/Model/ItemModel.php`,
        `${prefix}src/Model/ItemsModel.php`,
        `${prefix}src/Table/ItemTable.php`,
        `${prefix}src/View/Item/HtmlView.php`,
        `${prefix}src/View/Items/HtmlView.php`,
        `${prefix}tmpl/item/edit.php`,
        `${prefix}tmpl/items/default.php`
      ];

      assert.file(expectedFiles);
    });
  });

  describe("Site files", () => {
    it("creates expected files", () => {
      const prefix = "joomla_extensions/components/my_extension/site/";
      const expectedFiles = [
        // `${prefix}forms/item.xml`,
        // `${prefix}forms/filter_items.xml`,
        `${prefix}language/en-GB/com_my_extension.ini`,
        `${prefix}language/es-ES/com_my_extension.ini`,
        `${prefix}src/Controller/DisplayController.php`,
        `${prefix}src/Controller/ItemController.php`,
        `${prefix}src/Controller/ItemsController.php`,
        `${prefix}src/Helper/MyExtensionHelper.php`,
        `${prefix}src/Model/ItemModel.php`,
        `${prefix}src/Model/ItemsModel.php`,
        `${prefix}src/Service/Router.php`,
        `${prefix}src/View/Item/HtmlView.php`,
        `${prefix}src/View/Items/HtmlView.php`,
        `${prefix}tmpl/items/default.php`
      ];

      assert.file(expectedFiles);
    });
  });

  describe("Media files", () => {
    it("creates expected files", () => {
      const prefix = "joomla_extensions/components/my_extension/media/";
      const expectedFiles = [
        `${prefix}css/my_extension.css`,
        `${prefix}js/my_extension.js`,
        `${prefix}joomla.asset.json`
      ];

      assert.file(expectedFiles);
    });
  });
});
