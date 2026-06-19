# Lokales Setup

## Voraussetzungen
- PHP 8.2
- Composer
- SqLite

## Setup
- cd quiz-engine
- composer install
- .env-Datei erstellen & konfigurieren
- php artisan key:generate
- 'database.sqlite' in Ordner 'database' anlegen
- In der .env Datei folgende Datenbankkonfiguration verwenden:
    - DB_CONNECTION=sqlite
    - DB_DATABASE=database/database.sqlite
- DB-Migrationen ausführen: php artisan migrate
- Anwendung starten: php artisan serve

## API testen
Zum Testen ist eine Bruno-Collection (Ähnlich wie Postman) angelegt -> Dort sind beispiele, wie die JSONs aufgebaut sind

- Quiz erstellen (POST /api/quizzes)
- Quiz abrufen (GET /api/quizzes/{id})
- Quiz-Session starten (POST /api/quizzes/{id}/sessions)
- Antworten einreichen (POST /api/sessions/{id}/submitAnswers)
- Quiz beenden (POST /api/sessions/{id}/submitAnswers)
- Ergebnis abrufen (GET /api/sessions/{id}/finish)

## Aufteilung der Komponenten
Folgende Komponenten finden Anwendung in dieser Applikation:
- Quiz
- Question
- Answer
- SessionAnswer
- QuizSession

### Kardinalitäten:
1 Quiz          hat n Question(s)
1 Question      hat n Answer(s)     (Answer -> Antwortmöglichkeiten)
1 Quiz          hat n QuizSession(s)
1 QuizSession   hat n SessionAnswer(s)
1 SessionAnswer hat 1 Question & 1 Answer (Implementation von Single-Choice)

```text
Quiz 1
├─ Question 1 (id 1)
│   └─ Answer 1 (id 1)
│   └─ Answer 2 (id 2)
│   └─ Answer 3 (id 3)
│
├─ Question 2 (id 2)
│   └─ Answer 1 (id 4)
│   └─ Answer 2 (id 5)
│   └─ Answer 3 (id 6)
│
├─ Question 3 (id 33)
│   └─ Answer 1 (id 7)
│   └─ Answer 2 (id 8)
│   └─ Answer 3 (id 9)
│ 
└─ QuizSession 
    └─ SessionAnswer
```

## Designentscheidungen
Konkret lässt sich sagen, dass alle bereits genannten Komponenten nochmals in zwei Modelle aufteilen lässt: Inhalt & Durchlauf
- Quiz, Question und Answer beschreiben den Inhalt des Quiz              -> Eingabe durch Quiz-Ersteller
- QuizSession und SessionAnswer repräsentieren einen konkreten Durchlauf -> Eingabe durch Quiz-Teilnehmer

Dadurch kann dasselbe Quiz beliebig oft durchgeführt werden, ohne die ursprünglichen Quizdaten zu verändern.

### Antworten pro Session
Antworten werden gesammelt über einen *einzelnen* API-Aufruf eingereicht.

Vorteile:
- Einfachere API
- Weniger Requests
- Einfachere Auswertung

### Auswertung
Die erreichten Punkte werden nicht dauerhaft gespeichert, sondern bei der Ergebnisabfrage berechnet.

Vorteile:
- Keine redundanten Daten (Ergebnis lässt sich aus gespeicherten Daten berechnen)
- Einfaches Datenmodell

Nachteil:
- Berechnung muss immer wieder neu gemacht werden

### Erweiterbarkeit für Multiple Choice
Aktuell ist nur eine Implementation von Single-Choice vorhanden, diese wurde aber mit Hinblick auf weitere Antworttypen erstellt.
'Question' besitzt ein feld 'type', das aktuell den Wert 'single_choice' enthält. Dadurch können zukünftig auch weitere Fragetypen ergänzt werden, ohne das grundlegende Datenmodell zu verändern.
Die Funktion 'results' in 'QuizSessionController' wurde bereits so geschrieben, dass der Score unabhängig von Antworttyp berechnet wird.

## Offene Punkte
- Aktuell ist es möglich, Antworten für Sessions zu submitten, welche nicht zusammengehören
- Eingaben werden lediglich auf Typen etc. validiert und meist nicht nach Zusammengehörigkeit (Z.b Antworten zu falscher Session)

## Nachschlagewerke
- Migrations: https://laravel.com/docs/13.x/migrations#main-content