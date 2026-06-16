## Nachschlagewerke
- Migrations: https://laravel.com/docs/13.x/migrations#main-content


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

Quiz 1
├─ Question 1 
│   └─ Answer 1
│   └─ Answer 2
│   └─ Answer 3
│
├─ Question 2 
│   └─ Answer 1
│   └─ Answer 2
│   └─ Answer 3
│
├─ Question 3
│   └─ Answer 1
│   └─ Answer 2
│   └─ Answer 3
│ 
└─ QuizSession 
    └─ SessionAnswer