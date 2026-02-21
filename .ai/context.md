Currently, the project is in an old state.

We are changing it to:
- Latest Laravel + PHP version (Laravel 12 & PHP 8.4) [DONE]
- UUID primary keys for all major tables [DONE]
- PEST tests for feature coverage [DONE]
- Static TestDataSeeder with fixed UUID constants [DONE]
- Laraval Cloud
- Livewire
- Event sourcing (spatie lib?)
- Laravel responser/transporter (do we need it with Livewire?)
- Hook up a makefile for typical events (generating types, some linting, static analysis, deploy scripts, running tests, ...)
- Database migrations refactoring (Refactored to UUIDs, float issues noted) [IN PROGRESS]
- Authentication (how does this work with Livewire?)
- Do we want to work with DTOs? (Create, Update, Read DTOs)
- Laravel Sail? - PostreSQL (local setup?)
- Migration of an old database to a new one

We need to transfer safely, so we are focusing on making tests for current features.

[//]: # (TODO Project guidelines)
[//]: # (If you need more context, check "~/.ai/guidlines/*.md")
