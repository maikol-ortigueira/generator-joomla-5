"use strict";
const path = require("path");
const assert = require("yeoman-assert");
const helpers = require("yeoman-test");

jest.mock("generator-license/app", () => {
  const helpers = require("yeoman-test");
  return helpers.createDummyGenerator();
});

describe("generator-joomla-5:module", () => {
  describe("Site generator", () => {
    beforeAll(() => {
      return helpers
        .run(path.join(__dirname, "../generators/module"))
        .withOptions({
          extName: "my_extension",
          description: "My module description",
          author: "Maikol Fustes",
          authorEmail: "maikol.ortigueira@gmail.com",
          authorURL: "https://maikol.eu",
          license: "MIT",
          nsExtName: "MyExtension",
          currentDate: "2020-01-01",
          currentYear: "2020"
        })
        .withPrompts({ moduleClient: "site", namespace_vendor: "ortIGa" });
    });

    it("creates expected files", () => {
      const expectedFiles = [
        "joomla_extensions/modules/site/my_extension/services/provider.php",
        "joomla_extensions/modules/site/my_extension/language/en-GB/mod_my_extension.ini",
        "joomla_extensions/modules/site/my_extension/language/en-GB/mod_my_extension.sys.ini",
        "joomla_extensions/modules/site/my_extension/language/es-ES/mod_my_extension.ini",
        "joomla_extensions/modules/site/my_extension/language/es-ES/mod_my_extension.sys.ini",
        "joomla_extensions/modules/site/my_extension/src/Dispatcher/Dispatcher.php",
        "joomla_extensions/modules/site/my_extension/src/Helper/MyExtensionHelper.php",
        "joomla_extensions/modules/site/my_extension/tmpl/default.php",
        "joomla_extensions/modules/site/my_extension/my_extension.xml"
      ];

      assert.file(expectedFiles);
    });

    it("creates expected manifest file", () => {
      assert.fileContent(
        "joomla_extensions/modules/site/my_extension/my_extension.xml",
        /<name>mod_my_extension<\/name>/
      );
      assert.fileContent(
        "joomla_extensions/modules/site/my_extension/my_extension.xml",
        /<author>Maikol Fustes<\/author>/
      );
      assert.fileContent(
        "joomla_extensions/modules/site/my_extension/my_extension.xml",
        /<description>MOD_MY_EXTENSION_XML_DESCRIPTION<\/description>/
      );
      assert.fileContent(
        "joomla_extensions/modules/site/my_extension/my_extension.xml",
        /<authorEmail>maikol.ortigueira@gmail.com<\/authorEmail>/
      );
      assert.fileContent(
        "joomla_extensions/modules/site/my_extension/my_extension.xml",
        /<authorUrl>https:\/\/maikol.eu<\/authorUrl>/
      );
      assert.fileContent(
        "joomla_extensions/modules/site/my_extension/my_extension.xml",
        /<creationDate>2020-01-01<\/creationDate>/
      );
      assert.fileContent(
        "joomla_extensions/modules/site/my_extension/my_extension.xml",
        /<namespace path="src">Ortiga\\Module\\MyExtension<\/namespace>/
      );
      assert.fileContent(
        "joomla_extensions/modules/site/my_extension/my_extension.xml",
        /<license>MIT; see LICENSE.txt<\/license>/
      );
      assert.fileContent(
        "joomla_extensions/modules/site/my_extension/my_extension.xml",
        /<version>1.0.0<\/version>/
      );
      assert.fileContent(
        "joomla_extensions/modules/site/my_extension/my_extension.xml",
        /<folder module="mod_my_extension">services<\/folder>/
      );
      assert.fileContent(
        "joomla_extensions/modules/site/my_extension/my_extension.xml",
        /<language tag="en-GB">language\/en-GB\/mod_my_extension.ini<\/language>/
      );
      assert.fileContent(
        "joomla_extensions/modules/site/my_extension/my_extension.xml",
        /<language tag="en-GB">language\/en-GB\/mod_my_extension.sys.ini<\/language>/
      );
      assert.fileContent(
        "joomla_extensions/modules/site/my_extension/my_extension.xml",
        /<language tag="es-ES">language\/es-ES\/mod_my_extension.ini<\/language>/
      );
      assert.fileContent(
        "joomla_extensions/modules/site/my_extension/my_extension.xml",
        /<language tag="es-ES">language\/es-ES\/mod_my_extension.sys.ini<\/language>/
      );
    });

    it("creates expected provider file", () => {
      // commented header
      assert.fileContent(
        "joomla_extensions/modules/site/my_extension/services/provider.php",
        "@subpackage  mod_my_extension"
      );
      assert.fileContent(
        "joomla_extensions/modules/site/my_extension/services/provider.php",
        "@author      Maikol Fustes <maikol.ortigueira@gmail.com>"
      );
      assert.fileContent(
        "joomla_extensions/modules/site/my_extension/services/provider.php",
        "@copyright   Copyright (c) 2020 Maikol Fustes"
      );
      assert.fileContent(
        "joomla_extensions/modules/site/my_extension/services/provider.php",
        "@license     MIT; see LICENSE.txt"
      );
      assert.fileContent(
        "joomla_extensions/modules/site/my_extension/services/provider.php",
        /\\\\Ortiga\\\\Module\\\\MyExtension\\\\Site\\\\Helper/
      );
    });

    it("creates expected Dispatcher file", () => {
      // commented header
      assert.fileContent(
        "joomla_extensions/modules/site/my_extension/src/Dispatcher/Dispatcher.php",
        "@subpackage  mod_my_extension"
      );
      assert.fileContent(
        "joomla_extensions/modules/site/my_extension/src/Dispatcher/Dispatcher.php",
        "@author      Maikol Fustes <maikol.ortigueira@gmail.com>"
      );
      assert.fileContent(
        "joomla_extensions/modules/site/my_extension/src/Dispatcher/Dispatcher.php",
        "@copyright   Copyright (c) 2020 Maikol Fustes"
      );
      assert.fileContent(
        "joomla_extensions/modules/site/my_extension/src/Dispatcher/Dispatcher.php",
        "@license     MIT; see LICENSE.txt"
      );
      assert.fileContent(
        "joomla_extensions/modules/site/my_extension/src/Dispatcher/Dispatcher.php",
        /namespace Ortiga\\Module\\MyExtension\\Site\\Dispatcher/
      );
      assert.fileContent(
        "joomla_extensions/modules/site/my_extension/src/Dispatcher/Dispatcher.php",
        "$data['list'] = $this->getHelperFactory()->getHelper('MyExtensionHelper')->getFoos($data['params'], $data['app']);"
      );
    });

    it("creates expected Helper file", () => {
      // commented header
      assert.fileContent(
        "joomla_extensions/modules/site/my_extension/src/Helper/MyExtensionHelper.php",
        "@subpackage  mod_my_extension"
      );
      assert.fileContent(
        "joomla_extensions/modules/site/my_extension/src/Helper/MyExtensionHelper.php",
        "@author      Maikol Fustes <maikol.ortigueira@gmail.com>"
      );
      assert.fileContent(
        "joomla_extensions/modules/site/my_extension/src/Helper/MyExtensionHelper.php",
        "@copyright   Copyright (c) 2020 Maikol Fustes"
      );
      assert.fileContent(
        "joomla_extensions/modules/site/my_extension/src/Helper/MyExtensionHelper.php",
        "@license     MIT; see LICENSE.txt"
      );
      assert.fileContent(
        "joomla_extensions/modules/site/my_extension/src/Helper/MyExtensionHelper.php",
        /namespace Ortiga\\Module\\MyExtension\\Site\\Helper/
      );
      assert.fileContent(
        "joomla_extensions/modules/site/my_extension/src/Helper/MyExtensionHelper.php",
        "class MyExtensionHelper"
      );
      assert.fileContent(
        "joomla_extensions/modules/site/my_extension/src/Helper/MyExtensionHelper.php",
        "public function getFoos(Registry $params, SiteApplication $app)"
      );
      assert.fileContent(
        "joomla_extensions/modules/site/my_extension/src/Helper/MyExtensionHelper.php",
        "return (new self())->getFoos($params, $app);"
      );
    });
  });

  describe("Admin generator", () => {
    beforeAll(() => {
      return helpers
        .run(path.join(__dirname, "../generators/module"))
        .withOptions({
          extName: "my_extension",
          description: "My module description",
          author: "Maikol Fustes",
          authorEmail: "maikol.ortigueira@gmail.com",
          authorURL: "https://maikol.eu",
          license: "MIT",
          nsExtName: "MyExtension",
          currentDate: "2020-01-01",
          currentYear: "2020"
        })
        .withPrompts({ moduleClient: "admin", namespace_vendor: "ortIGa" });
    });
  });
});
