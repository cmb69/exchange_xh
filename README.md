# Exchange_XH

Exchange_XH facilitates exporting and importing the contents of a
CMSimple_XH website to and from XML, respectively.  This may be helpful for
the migration to or from other content management systems.
The plugin also supports importing the contents of CMSimple_XH 1.6.x
into CMSimple_XH ≥ 1.7.0.

- [Requirements](#requirements)
- [Download](#download)
- [Installation](#installation)
- [Settings](#settings)
- [Usage](#usage)
  - [Import old CMSimple_XH Contents](#import-old-cmsimple_xh-contents)
- [Limitations](#limitations)
- [Troubleshooting](#troubleshooting)
- [License](#license)
- [Credits](#credits)

## Requirements

Exchange_XH is a plugin for [CMSimple_XH](https://cmsimple-xh.org/).
It requires CMSimple_XH ≥ 1.7.0 and PHP ≥ 7.1.0 with the DOM extension.
Exchange_XH also requires [Plib_XH](https://github.com/cmb69/plib_xh) ≥ 1.8;
if that is not already installed (see *Settings*→*Info*),
get the [lastest release](https://github.com/cmb69/plib_xh/releases/latest),
and install it.

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
contents, you have to place `content.xml` right besides the `content.htm` that you want
to be *overwritten* (so make sure you make a backup before) by
the import.

<details>
<summary>content.xml schema definition</summary>

````xml
<?xml version="1.0" encoding="UTF-8"?>
<grammar xmlns="http://relaxng.org/ns/structure/1.0">
  <start>
    <ref name="Contents"/>
  </start>

  <define name="Contents">
    <element name="contents">
      <attribute name="version"/>
      <zeroOrMore>
        <ref name="Page"/>
      </zeroOrMore>
    </element>
  </define>

  <define name="Page">
    <element name="page">
      <attribute name="title"/>
      <element name="data">
        <zeroOrMore>
          <attribute>
            <anyName/>
            <text/>
          </attribute>
        </zeroOrMore>
      </element>
      <element name="content">
        <text/>
      </element>
      <zeroOrMore>
        <ref name="Page"/>
      </zeroOrMore>
    </element>
  </define>
</grammar>
````
</details>

For multilingual websites, each language content has to be imported and
exported separately.

Note that import/export from or to other content management systems will not
work out of the box.  The XML format is just a helpful step, but you certainly
need to transform from or to the actual export/import formats of the other
content management systems.  How this can be done exactly, depends obviously
on the other content managements import/export facilities, and the details
are out of scope for this documentation.  If you need help with this,
see the [Troubleshooting section](#troubleshooting).

### Import old CMSimple_XH Contents

To import the contents of CMSimple_XH 1.6.x, you need to rename the old
`content.htm` to `content.1.6.htm`, and put it in the `content/` folder of
the new CMSimple_XH installation.  Then go to `Plugins` → `Import/Export`
and press the `Import` button in the `Import CMSimple_XH 1.6 contents`
section.  For multilingual websites, this needs to be done for each
language.

## Limitations

When importing old CMSimple_XH 1.6.x contents, only the most basic adjustments
are made, but the HTML headings are not changed in any way.  You may want to
do this manually after importing, so that your pages have a proper HTML heading outline.

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
