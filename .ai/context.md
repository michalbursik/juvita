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
- Laravel responder/transporter review (keep or move to native?)
- Final migration of production data to the event store 

Architecture:
- app/Domain: Core logic, Events, Aggregates, Projectors.
- app/Services: Orchestration, Business flows.
- app/DTOs: Typed data contracts.
- app/Http: Thin Controllers.
- app/Livewire: UI components and logic (Migrated from Nuxt).
- resources/views/livewire: Frontend templates (Tailwind CSS).
- database/migrations: Re-indexed to UUIDs and Decimals.

Implemented Improvements:
- Migrated frontend from Nuxt.js to Livewire + Tailwind CSS for better DX and deployment.
- Replaced separate API calls with direct Service/DTO usage in Livewire components.
- Standardized UI colors for movement types (Receipt: Emerald, Issue: Rose, Transmission: Amber, Check: Blue).
- Optimized "Overviews" and stock level tracking (pivot-based) to avoid expensive re-computations.
- Implemented responsive Tailwind-based Numeric Pad with a shared `HasNumericPad` trait.
- Added database indexes to `movements` and `price_levels` tables to optimize frequent filtering and stock lookups.
- Restored API login support for tests while maintaining session-based auth for the web UI.
- Fixed decimal precision for all quantity/price columns (Decimal 10,1).
- Improved Product Image upload UI by making the entire dashed area clickable and improving hover states.
- Corrected storage link for product images to ensure they are served from the correct application port (8080).
- Refactored all Livewire views to leverage reusable Blade components (`x-button`, `x-input`, `x-card`, `x-table`, etc.) for better maintainability and style consistency.
- Extracted and migrated legacy product images and static assets from the defunct Nuxt.js directory to `public/images/`.

Improvements: (What we want to improve, refactor, optimize, etc. - FUTURE)
- 

[//]: # (TODO Project guidelines)
[//]: # (If you need more context, check "~/.ai/guidlines/*.md")
