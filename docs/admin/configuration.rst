Konfiguration
=============

Konfigurationsdatei anlegen
---------------------------

Die Vorlage für die Konfigurationsdatei befindet sich in der Datei ``.env``. Von dieser Datei muss eine Kopie ``.env.local`` erzeugt werden.
Anschließend muss die Datei angepasst werden.

.. code-block:: shell

    $ cp .env .env.local

Konfigurationseinstellungen
---------------------------

APP_ENV
#######

Dieser Wert muss immer ``prod`` enthalten, sodass das System in der Produktionsumgebung ist.

.. warning:: Niemals ``dev`` in einer Produktivumgebung verwenden.

APP_SECRET
##########

Dieser Wert muss eine zufällige Zeichenfolge beinhalten. Diese kann beispielsweise mit ``openssl rand -base64 32`` erzeugt werden

APP_URL
#######

Dieser Wert beinhaltet die URL zur ICC-Instanz, bspw. https://icc.example.com/

APP_NAME
########

Name des ICCs, kann nach Belieben geändert werden.

APP_LOGO
########

Pfad zum großen Logo für den Fußbereich. Das Bild muss im ``public``-Ordner (oder einem Unterordner) abgelegt werden.

APP_SMALLLOGO
#############

Pfad zum kleinen Logo für den Kopfbereich. Das Bild muss im ``public``-Ordner (oder einem Unterordner) abgelegt werden.

SAML_ENTITY_ID
##############

ID des ICCs, welche für SAML-Anfragen (Authentifizierung) genutzt wird. Dieser Wert muss mit dem Wert im Identity Provider übereinstimmen.
Als Entity ID wird in der Regel die URL der Anwendung (bspw. ``https://icc.example.com/`` verwendet).

IDP_PROFILE_URL
###############

Link zu den Kontoeinstellungen im Identity Provider, bspw. ``https://sso.schulit.de/profile``.

IDP_LOGOUT_URL
##############

Link zur Abmeldung vom Identity Provider, bspw. ``https://sso.schulit.de/logout``.

KIVUTO_ACCOUNT
##############

Kontonummer des Kivuto Accounts.

KIVUTO_ENDPOINT
###############

Endpunkt des Kivuto-Anmeldevorgangs. Stand Dezember 2020 ist dies ``https://e5.onthehub.com/WebStore/Security/AuthenticateUser.ashx``

KIVUTO_SECRET_KEY
#################

Der geheime Schlüssel für die Anmeldung.

KIVUTO_FIRSTNAME
################

SAML-Attribut welches für den Vornamen genutzt werden soll, der an Kivuto übermittelt wird. Beispiel: ``http://schemas.xmlsoap.org/ws/2005/05/identity/claims/givenname``.
Möchte man ein anderes Attribut verwenden, muss hier der Name des Attributes geändert werden.

KIVUTO_LASTNAME
################

SAML-Attribut welches für den Nachnamen genutzt werden soll, der an Kivuto übermittelt wird. Beispiel: ``http://schemas.xmlsoap.org/ws/2005/05/identity/claims/surname``.
Möchte man ein anderes Attribut verwenden, muss hier der Name des Attributes geändert werden.

KIVUTO_EMAIL
################

SAML-Attribut welches für die E-Mail-Adresse genutzt werden soll, die an Kivuto übermittelt wird. Beispiel: ``http://schemas.xmlsoap.org/ws/2005/05/identity/claims/emailaddress``.
Möchte man ein anderes Attribut verwenden, muss hier der Name des Attributes geändert werden.

DATABASE_URL
############

Verbindungszeichenfolge für die Datenbankverbindung. Aktuell unterstützt das ICC ausschließlich MySQL/MariaDB-Datenbanken
ab Version MySQl 5.7. Die Zeichenfolge setzt sich dabei folgendermaßen zusammen:

.. code-block:: shell

    mysql://USERNAME:PASSWORD@HOST:3306/NAME

- ``USERNAME``: Benutzername der Datenbank
- ``PASSWORD``: zugehöriges Passwort des Datenbankbenutzers
- ``HOST``: Hostname des Datenbankservers
- ``NAME``: Name der Datenbank

Weitere Informationen (englisch) gibt `hier <https://www.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html#connecting-using-a-url>`_.
