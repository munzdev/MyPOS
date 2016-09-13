# Codename MyPOS

## v1.0 - planned##

- Bugfixing and optimizations
- Project naming

## v1.0-beta - planned##

Testing

- advanced unit tests and UI tests
- multible real usage testing
- Bugfixing and optimizations


## v1.0-alpha2 - planned##

Features:

- Create an Web installer
- Create a Desktop installer witch install XAMPP package + MyPOS on Desktop
- Multilanguage support
- Advaned User Permissions system
- Pages Paging system

Database rework:

- remove table orders_detail_sizes
- make tables depend on eventid
- make menu_group and menu_type depend on eventid
- billing tables needs rework to possible match austrias laws for "registrierkasse", also keep other country laws possible to handle

Code refatoring:

- New PHP Codebase version 7.1
- Use ECMAScript 6 for JavaScript
- refactor PHP API and JS classes. Use CleanCode and SOLID.
- refactor API names/uris. Planning a Service-oriented-design to match a better RESTful Service principe (PUT, GET, DELETE, PATCH)
- possible use slim and
- refactor JS Code with models, views and collections to match the RESTful API (PUT, GET, DELETE, PATCH)
- possible use marionette.js in order to handle subviews?
- optimize performance of PHP, SQL and JS code. Reduce RAM usage and DOM manipulation (better smartphone performance)

Testing:

- Start application testing
- create basic Unit tests for PHP code and JS Code
- Bugfixing and optimizations

## v1.0-alpha - in progress##

- PHP Codebase version 5.6
- Finishing current planned interfaces, Database models and features
- no testing yet, no installer