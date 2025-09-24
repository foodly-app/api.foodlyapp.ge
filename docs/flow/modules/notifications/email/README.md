# Email Notifications — Architecture and Operations

This document describes the Email notification subsystem for the Foodly API app. It covers design, key files, flow, failure modes observed during development, testing guidance (including the dev QA UI), and recommended next steps.

## Overview

The application uses an event-driven approach to send email notifications about reservation lifecycle events (creation, status changes, payments, reminders). Events are dispatched from application code and handled by queueable listeners that build and send audience-specific Mailables.

Goals:
- Deliver audience-specific emails (client, restaurant manager, administrator).
- Keep email templates self-contained and independent from web front-end tooling (Vite) so queued workers can render them.
- Make testing easy via a dev QA UI (`/email-test`) that can send mails synchronously or queue them.

## Key Concepts

- Event: `App\Events\ReservationStatusChanged` — fired when a reservation's status changes.
- Listener: `App\Listeners\SendReservationCreatedNotification` — implements `ShouldQueue` and queues audience-specific Mailables.
- Mailables: `App\Mail\ReservationStatusMail` (base) and audience subclasses in `App\Mail\Client`, `App\Mail\Restaurant`, `App\Mail\Administrator`.
- Views: Blade templates under `resources/views/emails/{client,restaurant,administrator}/` and shared `resources/views/emails/layout.blade.php`.
- Cast: `App\Casts\ReservationStatusCast` — normalizes legacy DB strings to `App\Enums\ReservationStatus` values.

## File map (important files)

- app/Events/ReservationStatusChanged.php — event payload (reservation, oldStatus, newStatus)
- app/Listeners/SendReservationCreatedNotification.php — queueable listener that resolves recipients and queues Mailables
- app/Mail/ReservationStatusMail.php — base Mailable with view resolution and subject building logic
- app/Mail/Client/ReservationStatusMail.php — client-specific subclass (calls parent constructor with recipientType 'client')
- app/Mail/Restaurant/ReservationStatusMail.php — restaurant-specific
- app/Mail/Administrator/ReservationStatusMail.php — admin-specific
- resources/views/emails/layout.blade.php — shared email layout (minimal, inline CSS)
- resources/views/emails/partials/header.blade.php and footer.blade.php — small includes used by layout
- resources/views/emails/{client,restaurant,administrator}/* — per-audience, per-status templates (e.g., ConfirmedMail.blade.php)
- app/Casts/ReservationStatusCast.php — custom Eloquent cast to tolerate legacy values
- app/Http/Controllers/EmailTestController.php — dev QA controller for sending test emails (sync or queued)
- routes/web.php — route for the dev QA UI (`/email-test`) — ensure this is gated in non-local environments

## Runtime Flow

1. Reservation status changes → App dispatches `ReservationStatusChanged` event.
2. `SendReservationCreatedNotification` (queued listener) handles the event.
3. Listener resolves recipients (client email, restaurant/manager email via `reservable` relationships, configured admin email).
4. For each recipient, listener instantiates the appropriate Mailable class and calls `Mail::to($email)->queue($mail)` (or `send()` for synchronous QA).
5. The Mailable's `build()` resolves the best-matching Blade view and subject and returns the built object.
6. The queue worker pulls queued Mailables and renders the view (this happens within the worker process) and sends the email via configured mail transport.

## Common failure modes and mitigations

- Vite manifest missing (ViewException): email templates or included partials attempted to call `@vite()` (the Vite helper) which reads `public/build/manifest.json`. If assets are not built in local dev or the queued worker runs in a different environment, the worker throws and the job fails.
  - Mitigation: Keep email views free from `@vite()` and other front-end helpers. Use a minimal `emails/layout.blade.php` that does not include `partials/head.blade.php` or include a defensive check around `vite()` calls (see `resources/views/partials/head.blade.php` which was updated to avoid calling `vite()` when running in console or when manifest.json doesn't exist).

- Malformed Blade templates: duplicated raw HTML followed by `@extends` can cause Blade compile errors. Keep views canonical: either a plain partial (returned directly by Mail::view) or an `@extends('emails.layout')` + `@section('content')` structure, not both.

- Enum ValueError: legacy DB values like `"Pending"` (capitalized) can throw when Laravel attempts to cast to a backed enum. The `ReservationStatusCast` exists to normalize values; however, additional defensive catch may be required to avoid ValueError when Laravel's default enum casting kicks in.

- Missing recipient emails: Ensure `resolveManagerEmail` logic checks multiple fields (`manager_email`, `email`, `contact_email`) and falls back to restaurant-related contacts when present.

## Testing & QA

- Dev QA UI: `GET /email-test` shows a form to send one or all status emails to the configured QA address. Use the "Send synchronously" checkbox to send immediately without the queue (useful for debugging view rendering and mail transport).
- Synchronous test: Use the QA UI with sync to immediately surface exceptions in the web request/laravel.log.
- Queue test: Run `php artisan queue:work` and send queued emails (uncheck sync). Monitor worker output and `php artisan queue:failed` for failures.

## Troubleshooting checklist

1. If job fails with `Vite manifest not found`: confirm email templates don't include `@vite()` or ensure `public/build/manifest.json` exists (run `npm run build` or `npm run dev` as appropriate) or rely on the defensive check added to the head partial.
2. If job fails on `ValueError` enum casting: inspect DB values for capitalization or legacy values; consider hardening `ReservationStatusCast::get()` to catch ValueError and return `null` or a mapped value.
3. If job fails with a missing view: ensure the Mailable's view resolution fallback is correct and that per-audience views exist for the expected status key.
4. If some recipients are missing: check `SendReservationCreatedNotification::resolveManagerEmail()` logic and `config('reservation.admin_email')` fallback.

## Operational notes

- Protect the dev QA route in non-local environments.
- Keep email templates simple and self-contained — no Livewire, no components that depend on app layout or front-end tooling.
- Consider enabling a monitoring alert on `php artisan queue:failed` or via your host/queue service.

## Next steps (to resume tomorrow)

- Harden `ReservationStatusMail::build()` with a try/catch that falls back to a simple text view if rendering fails.
- Harden `ReservationStatusCast::get()` to catch enum ValueError and map legacy values where possible.
- Fix any other malformed templates discovered and run a full verification with the queue worker.
- Add unit/integration tests for the email building logic (happy path + missing template + legacy status string case).

---

If you'd like, tomorrow I can implement the Mailable fallback and the cast hardening, then re-run the worker and report back with the results and final cleanup. 