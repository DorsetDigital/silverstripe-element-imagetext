# Silverstripe Element Image/Text

Adds an Elemental content block containing rich text and an accompanying image.

[![CI](https://github.com/DorsetDigital/silverstripe-element-imagetext/actions/workflows/ci.yml/badge.svg)](https://github.com/DorsetDigital/silverstripe-element-imagetext/actions/workflows/ci.yml)
[![License](https://img.shields.io/badge/License-BSD%203--Clause-blue.svg)](LICENSE)

## Requirements

- PHP ^8.3
- Silverstripe CMS ^6.0
- DNADesign Silverstripe Elemental ^6.0
- Silverstripe LinkField ^4.0

For Silverstripe CMS 4 projects, use the 1.x releases.

## Installation

Install with Composer:

```bash
composer require dorsetdigital/silverstripe-element-imagetext
```

Then run a dev/build.

## Usage

The module adds an **Image & Text** Elemental block with:

- rich-text content
- a single image
- configurable image position and relative width
- image alt text
- an optional LinkField link, including internal, external, email and telephone links

Images are rendered responsively as WebP derivatives, with intrinsic dimensions and native lazy loading.

The module intentionally provides no frontend CSS. The generated markup uses `content-element__*` classes so projects can apply their own layout and styling.

### Image widths

The values offered by the image-width dropdown can be changed through Silverstripe configuration. The configured key is output as a CSS class and the value is the CMS label:

```yml
DorsetDigital\Elements\ImageTextElement:
  sizes:
    'layout-half': '1/2 page width'
    'layout-third': '1/3 page width'
    'layout-quarter': '1/4 page width'
```

See [the SCSS example](docs/en/scss_example.md) for one possible implementation.

## Upgrading from 1.x

Version 2 targets Silverstripe CMS 6 and PHP 8.3 or newer and contains deliberate breaking data-model changes.

**Before upgrading, take a database backup.**

The `Image` relation has changed from `many_many` to `has_one`, reflecting the fact that the element supports a single image. The old SiteTree-only `ImageLink` relation has also been replaced by Silverstripe LinkField.

After updating the code, run a dev/build and then run the supplied `MigrateImageTextElementTask`. The task copies the first image from the legacy many-many relation into the new single-image relation and converts legacy SiteTree image links into LinkField `SiteTreeLink` records.

The migration is intentionally explicit rather than automatic. Review the migrated content before removing any legacy database tables or columns.
