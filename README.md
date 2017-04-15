# Magento 2 Module Directory Reader - Framework Agnostic Support
The _Magento 2 Module Directory Reader_ repository enables custom Magento 2 module directory location. 
The goal is to provide support for custom Magento 2 modules who respect framework agnostic model.

# Example
Suppose you have package which contains 2 directories Magento and Business logic. 
All Magento related framework dependencies and implementations are going to be in the Magento directory. 
All business logic which implements functionality is located outside of Magento directory. 
It can be _Pronko/CustomPackage/Api_ directory with all public APIs of the package and _Pronko/CustomPackage/Common_ directory with API implementations.

Package structure as follows:

```php
Pronko/CustomPackage
 - Magento
   - Controller
      - Checkout
         - Result.php
   - etc
   - Model
   - Test
 - Api
   - RepositoryInterface.php
 - Common
   - Repository.php
 - composer.json
 - registration.php
 ```
 
In the example we want to have an Action Controller which is accessible via _www.domain.com/pronko/checkout/result_ URL. 
With the structure provided in the example we have _Pronko/CustomPackage/Magento_ directory which is our Magento 2 module.

Directory path to Controller directory is _Pronko/CustomPackage/Magento/Controller_. 
In order for Magento framework to identify this custom controller path the _Magento\Framework\Module\Dir\Reader::getActionFiles()_ method collects all controller actions from all modules including our _Pronko/CustomPackage_ module.

# Problem Statement

Currently _Magento\Framework\Module\Dir\Reader::getActionFiles()_ method prepares Controller class names and its actions using expected by Magento 2 module naming _Vendor_ModuleName_.

In our case Pronko_CustomPackage module name declared in the etc/module.xml file will be different from Action Controller Directory _Pronko_CustomPackage_Magento_.

Action Controller in the _Pronko/CustomPackage/Magento/Controller/Checkout/Result_ will be ignored since _getActionFiles()_ method prepares _Pronko/CustomPackage/Controller/Checkout/Result_ Action Controller class name.

# Solution

Enable support for 3-level nesting Magento 2 package with custom directory where all Magento framework related functionality is located.

# Installation
Include repository as a dependency for your custom Magento 2 module or as part of Magento project:
```
$ composer require mcspronko/magento2-module-reader
```

Enable module in the system:
```
$ bin/magento module:enable Pronko_Magento2ModuleReader
$ bin/magento setup:upgrade
```

## Configuration
In order to start enable custom Vendor\PackageName\Magento directory the `Pronko\Magento2ModuleReader\Data\NamespaceMapping` class needs to be configured in etc/di.xml configuration file.
Here is an example of di.xml file:
```xml
<type name="Pronko\Magento2ModuleReader\Data\NamespaceMapping">
    <arguments>
        <argument name="mappings" xsi:type="array">
            <item name="Vendor_PackageName" xsi:type="string">Vendor_PackageName_Magento</item>
        </argument>
    </arguments>
</type>
```

Also, _frontend/routes.xml_ file in your module should be configured using new Magento directory path.
 ```xml
 <router id="standard">
     <route id="vendor" frontName="vendor">
         <module name="Vendor_PackageName_Magento" />
     </route>
 </router>
 ```
 
The `registration.php` file should be located in the `Vendor_PackageName` directory and point to Magento directory, module name however should still be `Vendor_PackageName`.
```php
\Magento\Framework\Component\ComponentRegistrar::register(
    \Magento\Framework\Component\ComponentRegistrar::MODULE,
    'Vendor_PackageName',
    __DIR__ . '/Magento'
);
```
Assuming that composer.json has same declaration for autoload as usual Magento 2 module and located in the root of a package.
```
  "autoload": {
    "files": [
      "registration.php"
    ],
    "psr-4": {
      "Vendor\\PackageName\\": ""
    }
  }
```

Once configuration is provided, create Action Controller in the _Vendor\PackageName\Magento\Controller_ directory. 
The _\Magento\Framework\App\FrontController_ class will match route and pass execution to an Action Controller. 

## Additional Information
This module is backward compatible with future Magento 2.x releases. 
There is an ObjectManager used to create instance of _Pronko\Magento2ModuleReader\Data\NamespaceMapping_ class. 
The goal of using ObjectManager class is to avoid ___construct()_ method overrides and give flexibility for developers to declare custom Namespaces via their own _di.xml_ files.
If you see better approach, I am open for discussion.

## Tests
Unit Tests are available at the Test directory.

# Contributions
Feel free to use this repository in your projects, create pull requests and most important is to provide feedback on further improvements.