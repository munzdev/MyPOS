# Codename MyPOS

## v1.0.0-alpha2 - planned##

Features:

- Create an Web installer
- Create a Desktop installer witch install XAMPP package + MyPOS on Desktop

Database rework:

- remove table orders_detail_sizes
- make tables depend on eventid
- make menu_group and menu_type depend on eventid
- billing tables needs rework to possible match austrias laws for "registrierkasse", also keep other country laws possible to handle

Code refatoring:

- refactor PHP API classes. Use CleanCode and SOLID.
- refactor API names/uris. Planning a Service-oriented-design to match a better RESTful Service principe (PUT, GET, DELETE, PATCH)
- possible use slim and
- refactor JS Code with models, views and collections to match the RESTful API (PUT, GET, DELETE, PATCH)
- possible use marionette.js in order to handle subviews?

Testing:

- Start application testing
- create basic Unit tests for PHP code and JS Code
- bugfixing

## v1.0.0-alpha - In Progress##

- Finishing current planned interfaces, Database models and features
- no testing yet, no installer