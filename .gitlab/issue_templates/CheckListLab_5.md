---
name: Checkliste Praktikum 5
about: Template zur Bewertung des Praktikums

---

# Bewertung
1. Allgemeines
  - [ ] Konsequente Verwendung der PHP-Seitenklassen für alle Seiten? 
  - [ ] Alle generierten Seiten werden vom W3C Validator als fehlerfrei erkannt und "HTML Lint" zeigt keine Meldungen mit Schwierigkeitsgrad > 2? 
  - [ ] Die Web-Applikation ist gesichert gegen Cross-Site-Scripting (XSS) und gegen SQL-Injection? 
 - [ ] Erfasste Daten werden (bis auf Angriffsversuche) unverändert in die Datenbank geschrieben? 
 - [ ] Es gibt entweder eine Übersichtsseite oder eine ausblendbare Navigationsleiste mit Links zu den 4 Hauptseiten? 
2.    Funktion
  - [ ] Eine direkt in die Datenbank eingetragene Pizza erscheint nach einem Reload auf der Speisekarte 
  - [ ] Der Warenkorb erlaubt das Löschen von einzelnen Pizzen und von allen Pizzen und rechnet korrekt? 
  - [ ] Die Bestellung kann erst abgeschickt werden, wenn sich mindestens eine Pizza im Warenkorb befindet und das Adressfeld nicht leer ist? Vorher ist der "Bestellen"-Knopf deaktiviert? 
  - [ ] Wenn man 2 Bestellungen in verschiedenen Browsern abschickt, sieht der jeweilige Browser (Kunde) nur die eigene Bestellung? 
  - [ ] Die Kundenseite wird nach der Bestellung geöffnet und aktualisiert sich ohne Neuladen (mit AJAX)? 
  - [ ] Statusänderung auf der Bäcker- [ ] oder Fahrerseite werden kurz darauf auf der Kundenseite angezeigt? 
  - [ ] Die Fahrer- [ ] und Bäckerseite laden sich alle 5 Sekunden neu? 
  - [ ] Die Bäckerseite zeigt alle bestellten Pizzen? 
  - [ ] Fertig gebackene Pizzen verschwinden erst von der Bäckerseite, wenn der Fahrer die Bestellung ausliefert? 
  - [ ] Bestellungen verschwinden nach der Auslieferung von der Fahrerseite? 
  - [ ] HTML-Tags im Adressfeld einer Bestellung (XSS) werden auf der Fahrerseits nicht ausgeführt? 
3.    Layout
   - [ ] Ansprechendes Design? 
   - [ ] Auf der Bestellseite verwenden Sie Media Queries um ansprechende Layouts für mindestens 2 Bildschirmformate umzusetzen? 
  - [ ] Der Warenkorb rutscht je nach Bildschirmbreite und Ausrichtung neben oder unter die Speisekarte? 
  - [ ] Keine Verwendung von absoluten Maßeinheiten (außerhalb von Media Queries)? 