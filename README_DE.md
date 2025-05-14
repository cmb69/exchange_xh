# Exchange_XH

Exchange_XH ermöglicht den Export und Import der Inhalte einer CMSimple_XH
Website nach beziehungsweise von XML. Dies kann nützlich für die Migration
zu und von anderen CMSen sein, und ebenfalls für die Migration zwischen
CMSimple_XH Versionen.

- [Voraussetzungen](#voraussetzungen)
- [Download](#download)
- [Installation](#installation)
- [Einstellungen](#einstellungen)
- [Verwendung](#verwendung)
- [Einschränkungen](#einschränkungen)
- [Problembehebung](#problembehebung)
- [Lizenz](#lizenz)
- [Danksagung](#danksagung)

## Voraussetzungen

Exchange_XH ist ein Plugin für [CMSimple_XH](https://cmsimple-xh.org/de/).
Es benötigt CMSimple_XH ≥ 1.7.0 und PHP ≥ 7.1.0 mit der DOM Extension.
Exchange_XH benötigt weiterhin [Plib_XH](https://github.com/cmb69/plib_xh) ≥ 1.8;
ist dieses noch nicht installiert (siehe `Einstellungen` → `Info`),
laden Sie das [aktuelle Release](https://github.com/cmb69/plib_xh/releases/latest)
herunter, und installieren Sie es.

## Download

Das [aktuelle Release](https://github.com/cmb69/exchange_xh/releases/latest)
kann von Github herunter geladen werden.

## Installation

Die Installation erfolgt wie bei vielen anderen CMSimple_XH-Plugins auch.

1. Sichern Sie die Daten auf Ihrem Server.
1. Entpacken Sie die ZIP-Datei auf Ihrem Computer.
1. Laden Sie den gesamten Ordner `exchange/` auf Ihren Server in den `plugins/`
   Ordner von CMSimple_XH hoch.
1. Vergeben Sie Schreibrechte für die Unterordner `css/` und `languages/`.
1. Browsen Sie zu `Plugins` → `Exchange` im  Backend der Website,
   und prüfen Sie, ob alle Voraussetzungen für den Betrieb erfüllt sind.

## Einstellungen

Die Konfiguration des Plugins erfolgt wie bei vielen anderen CMSimple_XH Plugins
auch im Backend der Website. Browsen Sie zu `Plugins` → `Exchange`.

<!-- Sie können die Original-Einstellungen von Exchange_XH unter
    `Konfiguration` ändern. Beim Überfahren der Hilfe-Icons mit der Maus
    werden Hinweise zu den Einstellungen angezeigt. -->

Die Lokalisierung wird unter `Sprache` vorgenommen. Sie können die
Zeichenketten in Ihre eigene Sprache übersetzen, falls keine entsprechende
Sprachdatei zur Verfügung steht, oder sie entsprechend Ihren Anforderungen
anpassen.

Das Aussehen von Exchange_XH kann unter `Stylesheet` angepasst werden.

## Verwendung

Sie können den Inhalt der aktuellen Sprache in der Hauptadministration des
Plugins (`Plugins` → `Import/Export`) exportieren und importieren
Nach erfolgreichem Export befindet sich die Datei `content.xml` im `content/`
Ordner der aktuellen Sprache, gleich neben der `content.htm` Datei, aus der
exportiert wurde. Um fremde Inhalte zu importieren, müssen Sie eine Datei
`content.xml` gleich neben diejenige
`content.htm` Datei platzieren, die Sie durch den Import *überschreiben*
lassen möchten (also legen Sie zuvor ein Backup an).

<details>
<summary>content.xml Schema-Definition</summary>

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

Bei mehrsprachigen Websites muss der Inhalt jeder Sprache separat importiert
und exportiert werden.

Um zwischen CMSimple_XH Versionen zu migrieren, installieren Sie das Plugin
zunächst in der alten Version, und exportieren Sie die Inhalte aller
Sprachen. Dann muss das Plugin in der neuen Version installiert werden, die
zuvor exportierten Inhalte der Sprachen müssen an die passenden Stellen der
neuen Version kopiert werden, und alle Sprach-Inhalte müssen importiert
werden.

## Einschränkungen

Zusätzliches Markup der Seitenüberschriften wird beim Export stillschweigend
entfernt.

## Problembehebung

Melden Sie Programmfehler und stellen Sie Supportanfragen entweder auf
[Github](https://github.com/cmb69/exchange_xh/issues)
oder im [CMSimple\_XH Forum](https://cmsimpleforum.com/).

## Lizenz

Exchange_XH ist freie Software. Sie können es unter den Bedingungen
der GNU General Public License, wie von der Free Software Foundation
veröffentlicht, weitergeben und/oder modifizieren, entweder gemäß
Version 3 der Lizenz oder (nach Ihrer Option) jeder späteren Version.

Die Veröffentlichung von Exchange_XH erfolgt in der Hoffnung, daß es
Ihnen von Nutzen sein wird, aber *ohne irgendeine Garantie*, sogar ohne
die implizite Garantie der *Marktreife* oder der *Verwendbarkeit für einen
bestimmten Zweck*. Details finden Sie in der GNU General Public License.

Sie sollten ein Exemplar der GNU General Public License zusammen mit
Exchange_XH erhalten haben. Falls nicht, siehe <https://www.gnu.org/licenses/>.

Copyright © Christoph M. Becker

Französische Übersetzung © 2017 Patrick Varlet

## Danksagung

Das Pluginlogo wurde von Dellustrations gestaltet.
Vielen Dank für die Veröffentlichung dieses Icons als Freeware.

Vielen Dank an die Community im
[CMSimple_XH-Forum](https://www.cmsimpleforum.com/)
für Tipps, Vorschläge und das Testen.

Und zu guter Letzt vielen Dank an
[Peter Harteg](https://www.harteg.dk/), den „Vater“ von CMSimple,
und alle Entwickler von [CMSimple_XH](https://www.cmsimple-xh.org/de/),
ohne die dieses phantastische CMS nicht existieren würde.
