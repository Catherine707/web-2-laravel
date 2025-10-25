<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>{{ $title ?? config('app.name') }}</title>

<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

{{-- Vite (CSS + JS de la app) --}}
@vite(['resources/css/app.css', 'resources/js/app.js'])

{{-- Flux (CSS) --}}
@fluxStyles

{{-- Tema de Flux (opcional, para modo oscuro/tema) --}}
@fluxAppearance
