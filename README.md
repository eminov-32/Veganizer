# Veganizer 🌱

Veganizer ist eine Webanwendung, die klassische, nicht vegane Rezepte in passende vegane Varianten umwandelt.

Die Anwendung soll nicht nur einzelne Zutaten ersetzen, sondern auch Mengen, Zubereitungsschritte und die jeweilige Funktion einer Zutat im Rezept berücksichtigen.

## Projektziel

Nutzerinnen und Nutzer können ein Rezept mit Zutaten und Zubereitung eingeben. Veganizer erkennt nicht vegane Bestandteile und schlägt geeignete pflanzliche Alternativen vor.

Beispiel:

- Milch → Haferdrink
- Ei → je nach Rezept Leinsamen-Ei, Seidentofu oder Ei-Ersatz
- Butter → vegane Margarine
- Speck → Räuchertofu oder Pilze

Die umgewandelten Rezepte sollen anschließend gespeichert, bearbeitet und wieder aufgerufen werden können.

## Geplante Funktionen

- Registrierung und Anmeldung
- Rezepte manuell eingeben
- Nicht vegane Zutaten automatisch erkennen
- Kontextabhängige vegane Alternativen auswählen
- Mengen und Zubereitungsschritte anpassen
- Umgewandelte Rezepte speichern
- Gespeicherte Rezepte bearbeiten und löschen
- Allergien und persönliche Vorlieben berücksichtigen
- Responsive Darstellung für Desktop und Smartphone
- Spätere Nutzung als Progressive Web App und mobile App

## Aktueller Entwicklungsstand

Das Projekt befindet sich in einer frühen Entwicklungsphase.

Bereits vorhanden:

- Laravel-Grundgerüst
- API-Grundstruktur
- Datenmodelle für Zutaten und Alternativen
- erste regelbasierte Testumwandlungen
- SQLite-Konfiguration für die lokale Entwicklung
- Tailwind CSS und Vite

Noch nicht umgesetzt:

- Benutzeroberfläche
- Authentifizierung
- Rezeptverwaltung
- vollständige Datenbankstruktur
- kontextabhängige Umwandlungslogik
- KI-Anbindung
- echte Feature-Tests

## Geplante Funktionsweise

1. Rezept und Zutaten einlesen
2. Zutaten und Mengen erkennen
3. Nicht vegane Zutaten identifizieren
4. Funktion der Zutaten im Rezept bestimmen
5. Passende vegane Alternativen auswählen
6. Mengen und Zubereitung anpassen
7. Ergebnis auf Veganität und Allergene prüfen
8. Rezept anzeigen und speichern

## Technologie

- PHP 8.3
- Laravel 13
- SQLite für die lokale Entwicklung
- Tailwind CSS 4
- Vite
- PHPUnit

Laravel soll später außerdem eine JSON-API für eine mobile Veganizer-App bereitstellen.

## Lokale Installation

Voraussetzungen:

- PHP 8.3 oder neuer
- Composer
- Node.js und npm
- SQLite

Repository klonen:

```bash
git clone https://github.com/eminov-32/Veganizer.git
cd Veganizer
```

PHP-Abhängigkeiten installieren:

```bash
composer install
```

Umgebungsdatei erstellen:

```bash
cp .env.example .env
php artisan key:generate
```

SQLite-Datenbank erstellen und Migrationen ausführen:

```bash
touch database/database.sqlite
php artisan migrate
```

Frontend-Abhängigkeiten installieren:

```bash
npm install
npm run build
```

Entwicklungsumgebung starten:

```bash
composer run dev
```

Die Anwendung ist anschließend normalerweise unter folgender Adresse erreichbar:

```text
http://127.0.0.1:8000
```

## API-Test

Der derzeitige Test-Endpunkt ist:

```text
POST /api/veganize
```

Beispielanfrage:

```json
{
  "dish": "Döner"
}
```

Dieser Endpunkt verwendet aktuell nur fest eingetragene Beispieldaten und wird später durch die vollständige Veganizer-Logik ersetzt.

## Roadmap

- [ ] Projektkonfiguration bereinigen
- [ ] Authentifizierung einrichten
- [ ] Designs umsetzen
- [ ] Rezept-Datenmodell erstellen
- [ ] Zutatenbibliothek aufbauen
- [ ] Regelbasierte Umwandlung entwickeln
- [ ] KI-Unterstützung ergänzen
- [ ] Tests schreiben
- [ ] Anwendung als PWA vorbereiten
- [ ] Mobile App anbinden

## Hinweis

Veganizer befindet sich in Entwicklung. Generierte Rezeptvorschläge sollten besonders bei Allergien und Unverträglichkeiten vor der Verwendung überprüft werden.
