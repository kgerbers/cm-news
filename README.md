# Opdracht

De opdracht is de volgende:
Bouw een nieuws applicatie waarmee het laatste nieuws wordt gepresenteerd,
gebruikmakend van de RSS-feeds van Nu.nl en/of Tweakers.net, waarbij de gebruiker zelf
van ‘kanaal’ kan wisselen.

De opdracht is expres eenvoudig geformuleerd. Dit om ervoor te zorgen dat de uitvoering
in een redelijk tijdsbestek kan worden gedaan (4 tot 6 uren maximaal) en de developer ook
creativiteit kan inbrengen.

De oplossing dient werkend te worden aangeleverd (hosted), tevens een ZIP-archief met
de volledige code en een beperkte separate beschrijving van de componenten en
gemaakte keuzes

# Toespitsing backend
Van backend-developers wordt verwacht dat ze minimaal het volgende in de oplossing
betrekken:
* Object-oriented PHP5 of PHP7
* Een PHP MVC Framework, bij voorkeur Symfony. Een framework waar reeds
ervaring mee is kan ook worden gebruikt
* Een cache mechanisme gebruikmakend van een database (MySQL)

Wil de developer meer toevoegen aan de oplossing, denk dan aan het volgende:
* Gebruikers autorisatie (login) met behoud van voorkeuren
* Gebruikers authenticatie middels een Oauth provider (Google of Facebook)
* Filteren op categorieen binnen een kanaal
* Zie ook de uitdagingen bij Front-End

# Toespitsing frontend
Van frontend-developers wordt verwacht dat ze minimaal het volgende in de oplossing
betrekken:
* Valide HTML5 markup
* CSS2/3, eventueel gebruikmakend van een Less/SCSS compiler
* jQuery (clientside) voor het uitlezen van de feed
* Bootstrap als basistemplate (responsive) met gebruikmaking van de UI-
componenten

Wil de developer meer toevoegen aan de oplossing, denk dan aan het volgende:
* Custom CSS / templating van bootstrap. Meer design elementen toevoegen
* Optimalisatie voor page-speed
* Optimalisatie voor SEO d.m.v. microformats etc.
* Filteren op categorieen binnen een kanaal
* Zie ook de uitdagingen bij backend

# Beoordelingscriteria
De volgende aandachtspunten worden gebruikt om de aangeleverde code te beoordelen
(voor zover relevant en toepasbaar). Mocht je een begrip niet kennen of weten toe te
passen, op Wikipedia zijn zeer uitgebreide artikelen over software development en code
reviewing te vinden waarin onderstaande zeker terug te vinden is.


## Aspecten

### Maintainability,Adaptability
- Duplicated Code
- Use of Short Methods
- Variable Scoping
- Cohesion of Logic in Classes
- Coupling: Long Parameter Lists
- Coupling: Control Coupling
- Coupling: Global Data Coupling
- Coupling: Solution Sprawl Across Classes
- Coupling: Inter-Layer Dependencies
- Conditional Complexity, Level of Nesting, Use of Flags, use of switch statements
- Encapsulation, Information Hiding, Inappropriate Intimacy between Classes
- Magic Numbers and Literals
- Speculative Generality
- Versioning Approach
- Use of Interfaces
- Simplicity of Solution
- General readability and intuitive naming of fields, properties,variables, methods, etc.
- General adherence to Coding Standards
- Use of Framework
- Proper use of OOP
- Proper use of MVC

### Robustness 
- Defensive Programming
- Proper use of Exception Handling
- Exceptions are logged to facilitate debugging
- Parameters are strongly typed
- Assess Potential for Data Loss Due to “Shortening Conversions”
- Security aspects
- Use of cookies and sessions

### Performance 
- Style of Communication
- Loop considerations
- String Handling
- Resource Cleanup
- Appropriate Use of Caching
- Consider opportunities for Asynchronous or Queued Operations

### Documentation 
- Class documentation
- Method documentation
- Inline documentation


### Notes
RSS feeds:
``` 
  * https://www.nu.nl/rss/tech
  * http://feeds.feedburner.com/tweakers/mixed
```