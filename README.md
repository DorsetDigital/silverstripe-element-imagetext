# silverstripe-element-imagetext
Adds an elemental content block with an image

[![License](https://img.shields.io/badge/License-BSD%203--Clause-blue.svg)](LICENSE.md)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/DorsetDigital/silverstripe-element-imagetext/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/DorsetDigital/silverstripe-element-imagetext/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/DorsetDigital/silverstripe-element-imagetext/badges/build.png?b=master)](https://scrutinizer-ci.com/g/DorsetDigital/silverstripe-element-imagetext/build-status/master)

# Requirements
* Silverstripe 4.x
* Silverstripe Elemental

# Installation
* Install the code with `composer require dorsetdigital/silverstripe-element-imagetext`
* Run a `dev/build?flush` to update your project

# Usage
This module adds a simple way to add a content block with an HTML image
By default, the module doesn't add much in the way of styling, it expects you to do this in your own CSS file

The CSS class assigned to the various image widths can be configured using a YML config file:

eg, for Bootstrap 4, you may want to use:

```
DorsetDigital\Elements\ImageTextElement:
  sizes:
    'col-md-6': '1/2 page width'
    'col-md-4': '1/3 page width'
    'col-md-3': '1/4 page width'
    'col-md-2': '1/6 page width'
```
