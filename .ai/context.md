Currently, the project is in a modernized, event-sourced state.

We have implemented:
- Latest Laravel + PHP version (Laravel 12 & PHP 8.4) [DONE]
- UUID primary keys for all major tables [DONE]
- PEST tests for full feature coverage [DONE]
- Static TestDataSeeder with fixed UUID constants [DONE]
- Event Sourcing via Spatie (Aggregates, Events, Projections) [DONE]
- Service Layer architecture with DTOs [DONE]
- Database migration to Decimal(10,1) for precise stock/price tracking [DONE]

Next steps:
- Laravel Cloud deployment
- Livewire integration (replace Nuxt)
- Laravel responder/transporter review (keep or move to native?)
- Hook up a makefile for typical tasks
- Authentication (Inertia/Livewire session-based vs Sanctum)
- Laravel Sail / Docker setup for local dev
- Final migration of production data to the event store

Architecture:
- app/Domain: Core logic, Events, Aggregates, Projectors.
- app/Services: Orchestration, Business flows.
- app/DTOs: Typed data contracts.
- app/Http: Thin Controllers.
- database/migrations: Re-indexed to UUIDs and Decimals.

[//]: # (TODO Project guidelines)
[//]: # (If you need more context, check "~/.ai/guidlines/*.md")
