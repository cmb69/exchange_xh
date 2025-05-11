# Exchange_XH

Exchange_XH facilitates exporting and importing the contents of a
CMSimple_XH website to and from XML, respectively.  This may be helpful for
the migration to or from other CMSs, and also for migrating between
CMSimple_XH versions.

- [Requirements](#requirements)
- [Download](#download)
- [Installation](#installation)
- [Settings](#settings)
- [Usage](#usage)
- [Limitations](#limitations)
- [Troubleshooting](#troubleshooting)
- [License](#license)
- [Credits](#credits)

## Requirements

Exchange_XH is a plugin for [CMSimple_XH](https://cmsimple-xh.org/).
It requires CMSimple_XH ≥ 1.7.0 and PHP ≥ 7.1.0 with the DOM and SimpleXML extensions.

## Download

The [lastest release](https://github.com/cmb69/exchange_xh/releases/latest)
is available for download on Github.

## Installation

The installation is done as with many other CMSimple_XH plugins.

1. Backup the data on your server.
1. Unzip the distribution on your computer.
1. Upload the whole folder `exchange/` to your server into the `plugins/` folder
   of CMSimple_XH.
1. Set write permissions for the subfolders `css/` and `languages/`.
1. Browse to `Plugins` → `Exchange` in the back-end of the site,
   to check if all requirements are fulfilled.

## Settings

The configuration of the plugin is done as with many other CMSimple_XH plugins in
the back-end of the site. Browse to `Plugins` → `Exchange`.

<!-- You can change the default settings of Exchange_XH under `Config`. Hints
for the options will be displayed when hovering over the help icon with your
mouse. -->

Localization is done under `Language`. You can translate the character
strings to your own language if there is no appropriate language file available,
or customize them according to your needs.

The look of Exchange_XH can be customized under `Stylesheet`.

## Usage

You can export and import the contents of the current language in the
main administration of the plugin (`Plugins` → `Import/Export`).
After successful export you find the file `content.xml` in the
`content/` folder of the current language, right besides the
`content.htm` from which it was exported. To import foreign
contents, you have to place `content.xml`, which must have an
appropriate format, right besides the `content.htm` that you want
to be *overwritten* (so make sure you make a backup before) by
the import.

For multilingual websites, each language content has to be imported and
exported separately.

To migrate between CMSimple_XH versions, at first the plugin has to be
installed in the old version, and all language contents have to be exported.
Then the plugin has to be installed in the new version, the language
contents have to be copied to their appropriate places in the new version,
and all language contents have to be imported.

## Limitations

Additional markup of page headings will silently be stripped during export.

## Troubleshooting

Report bugs and ask for support either on
[Github](https://github.com/cmb69/exchange_xh/issues)
or in the [CMSimple_XH Forum](https://cmsimpleforum.com/).

## License

Exchange_XH is free software: you can redistribute it and/or modify it
under the terms of the GNU General Public License as published
by the Free Software Foundation, either version 3 of the License,
or (at your option) any later version.

Exchange_XH is distributed in the hope that it will be useful,
but without any warranty; without even the implied warranty of merchantibility
or fitness for a particular purpose.
See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Exchange_XH. If not, see https://www.gnu.org/licenses/.

Copyright © Christoph M. Becker

French translation © 2017 Patrick Varlet

## Credits

The plugin logo is designed by Dellustrations.
Many thanks for publishing this icon as freeware.

Many thanks to the community at the
[CMSimple_XH Forum](https://www.cmsimpleforum.com/)
for tips, suggestions and testing.

And last but not least many thanks to [Peter Harteg](https://www.harteg.dk/),
the “father” of CMSimple, and all developers of [CMSimple_XH](https://www.cmsimple-xh.org/)
without whom this amazing CMS would not exist.
