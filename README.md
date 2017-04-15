# Magento 2 Module Directory Reader
This repository provides support for the custom Magento 2 module path for Controller directory. The repository is a must have to use in case your goal is to write Magento 2 framework agnostic code.

# Example
Suppose you have package which contains 2 directories Magento and Business logic. All Magento related framework dependencies and implementations are going to be in the Magento directory. All business logic which implements functionality is located outside of Magento directory. It can be **Api** directory with all public APIs of the package and **Common** directory with API implementations.

Package structure as follows:

```
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
 
In the example we want to have an Action Controller which is accessible via www.domain.com/pronko/checkout/result URL. With the structure provided in the example we have Pronko/CustomPackage/Magento directory which is our Magento 2 module.

Directory path to Controller directory is Pronko/CustomPackage/Magento/Controller. In order for Magento framework to identify this custom controller path the Magento\Framework\Module\Dir\Reader::getActionFiles() method collects all controller actions from all modules including our Pronko/CustomPackage module.

# Problem Statement

Currently _Magento\Framework\Module\Dir\Reader::getActionFiles()_ method prepares Controller class names and its actions using expected by Magento 2 module naming Vendor_ModuleName.

In our case Pronko_CustomPackage module name declared in the etc/module.xml file will be different from Action Controller Directory Pronko_CustomPackage_Magento.

Action Controller in the Pronko/CustomPackage/Magento/Controller/Checkout/Result will be ignored since getActionFiles() method prepares Pronko/CustomPackage/Controller/Checkout/Result Action Controller name.

# Solution

This Magento 2 module provides fix for the problem where you have 3-level nesting package.

# Installation
Include repository as a dependency for your custom Magento 2 module or as part of Magento project:
```
$ composer require mcspronko/magento2-module-dir-reader
```
