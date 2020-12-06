Installation
============

Schritt 1: Anwendung installieren
---------------------------------

Möglichkeit 1: Installation mit Git
###################################

Zunächst mittels Git den Quelltext des Projektes auschecken:

.. code-block:: shell

    $ git clone https://github.com/schulit/windows-edu.git
    $ git checkout -b 1.0.0

Dabei muss ``1.0.0`` durch die gewünschte Version ersetzt werden.

Anschließend in das Verzeichnis ``windows-edu`` wechseln und alle Abhängigkeiten installieren:

.. code-block:: shell

    $ composer install --no-dev --optimize-autoloader --no-scripts

Die Direktive ``no-scripts`` ist wichtig, da es ansonsten zu Fehlermeldungen kommt.

.. warning:: Der folgende Teil funktioniert nur, wenn Node und yarn verfügbar sind. Falls die beiden Tools nicht verfügbar sind, müssen die Dateien manuell hochgeladen werden.

Nun müssen noch die Assets installiert werden:

.. code-block:: shell

    $ yarn encore production

Möglichkeit 2: Installation ohne Git
####################################

Den Quelltext der Anwendung von `GitHub <https://github.com/schulit/windows-edu/releases>`_ herunterladen und auf dem Webspace
entpacken. Anschließend kann mit der Konfiguration fortgefahren werden.

Schritt 2: Konfiguration
------------------------

Nachdem der Quelltext und seine Abhängigkeiten installiert sind, müssen alle benötigten Zertifikate und Konfigurationsdateien erstellt werden.

Schritt 2.1: Konfigurationsdatei erstellen
##########################################

Siehe `Konfiguration <configuration.html>`_.

Schritt 2.2: Zertifikate erstellen
##################################

Damit die Software mit dem Identity Provider sprechen kann, wird ein entsprechendes Zertifikat benötigt. Das Zertifikat kann über die Konsole
erstellt werden:

.. code-block:: shell

    $ php bin/console app:create-certificate --type saml

Anschließend werden einige Daten abgefragt. Diese können abgesehen vom ``commonName`` frei beantwortet werden.

- ``countryName``, ``stateOrProvinceName``, ``localityName`` geben den Standort der Schule an
- ``organizationName`` entspricht dem Namen der Schule
- ``organizationalUnitName`` entspricht der Fachabteilung der Schule, welche das ICC administriert, bspw. Schulname und IT-Suffix
- ``commonName`` Domainname des ICCs, bspw. ``icc.example.com``
- ``emailAddress`` entspricht der E-Mail-Adresse des Administrators

Schritt 2.3: Identity Provider bekannt machen
#############################################

Im Identity Provider unter Verwaltung > IdP Details öffnen. Den Inhalt der angezeigten XML-Datei in die Zwischenablage kopieren
und anschließend die Datei ``saml/idp.xml`` einfügen (die Datei muss erstellt werden).

Der Inhalt der XML-Datei sieht dann folgendermaßen aus (Einträge mit ... sind pro System individuell):

.. code-block:: xml

    <?xml version="1.0"?>
    <EntityDescriptor xmlns="urn:oasis:names:tc:SAML:2.0:metadata" entityID="...">
      <IDPSSODescriptor protocolSupportEnumeration="urn:oasis:names:tc:SAML:2.0:protocol">
        <KeyDescriptor use="encryption">
          <ds:KeyInfo xmlns:ds="http://www.w3.org/2000/09/xmldsig#">
            <ds:X509Data>
              <ds:X509Certificate>..</ds:X509Certificate>
            </ds:X509Data>
          </ds:KeyInfo>
        </KeyDescriptor>
        <KeyDescriptor use="signing">
          <ds:KeyInfo xmlns:ds="http://www.w3.org/2000/09/xmldsig#">
            <ds:X509Data>
              <ds:X509Certificate..</ds:X509Certificate>
            </ds:X509Data>
          </ds:KeyInfo>
        </KeyDescriptor>
        <SingleSignOnService Binding="urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST" Location="..."/>
        <SingleSignOnService Binding="urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect" Location="..."/>
      </IDPSSODescriptor>
    </EntityDescriptor>

Schritt 3: Installation abschließen
-----------------------------------

Nun folgende Kommandos ausführen, um die Installation abzuschließen:

.. code-block:: shell

    $ php bin/console cache:clear
    $ php bin/console doctrine:migrations:migrate --no-interaction

Schritt 4: ICC im Identity Provider registrieren
------------------------------------------------

Schritt 4.1: Dienst erstellen
#############################

Im Identity Provider muss die Software als neuer Dienst registriert werden.

- Entity ID: Ist die in der Konfigurationsdatei gewählte Entity ID.
- Name: Windows Edu Store
- Beschreibung: *
- Assertion Customer Service URL: ``https://store.example.com/saml/login_check`` (dabei muss ``store.example.com`` durch die korrekte URL zur Software ersetzt werden)
- URL: ``https://store.example.com`` (dabei muss ``store.example.com`` durch die korrekte URL zur Software ersetzt werden)
- Zertifikat: hier muss der Inhalt der Datei ``saml/sp.crt`` hineinkopiert werden

Schritt 4.2: Attribut für Rollen erstellen
##########################################

Im nächsten Schritt muss ein Attribut erstellt werden, welches die ICC Rolle eines Benutzers speichert und dem ICC übermittelt. Dazu
im Identity Provider unter Verwaltung > Attribute ein neues Attrribut anlegen.

- Name: ``store-roles``
- Anzeigename: Windows Edu Store Rollen
- Beschreibung: Rollen, die der Benutzer im Windows Edu Store annimmt
- Benutzer können dieses Attribut ändern: nicht aktiviert
- SAML Attribut-Name: ``urn:roles``
- Typ: Auswahlfeld
- Dienste: Windows Edu Store

Anschließend unten unter Optionen folgende Optionen konfigurieren:

- Mehrfach-Auswahl möglich: aktiviert
- Optionen: hier die einzelnen Benutzerrollen eintragen (siehe `Benutzerrollen <roles.html>`_). Der Schlüssel ist der
  Name der Rolle (Präfix ``ROLE_``) und der Wert ist der Anzeigename.

Schritt 5: ICC im Webspace einrichten
-------------------------------------

Die Software muss auf einer Subdomain (bspw. ``store.example.com``) betrieben werden. Das Betreiben in einem Unterordner
wird nicht unterstützt.

.. warning:: Der Root-Pfad der Subdomain muss auf das ``public/``-Verzeichnis zeigen. Anderenfalls funktioniert die Software nicht und es können wichtige Konfigurationsdaten abgerufen werden.