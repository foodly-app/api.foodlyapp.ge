<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>{{ $title ?? config('app.name') }}</title>

<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

@php
// Avoid calling @vite when running in a non-HTTP / queued worker context where
// the Vite manifest may be missing. Use a defensive check so background jobs
// that render Blade (like queued Mailables) won't throw when manifest.json is
// absent. In production, you should build assets so this is present.
try {
	$manifestPath = public_path('build/manifest.json');
	if (app()->runningInConsole() === false && file_exists($manifestPath)) {
		echo vite(['resources/css/app.css', 'resources/js/app.js']);
	}
} catch (Throwable $e) {
	// swallow - we don't want asset loading failures to break the request
}

@fluxAppearance
