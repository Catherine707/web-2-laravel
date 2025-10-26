<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- CSRF para peticiones POST/AJAX --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Foro de programación</title>

    @isset($attributes) @endisset
    @livewireStyles

    {{-- Vite (css + js) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-gray-900 h-full">
    <div class="min-h-full flex flex-col">
        {{-- Navbar --}}
        <div class="px-4">
            <x-forum.navbar />
        </div>

        {{-- HERO --}}
        <div class="relative flex-1 flex items-center justify-center">
            {{-- Fondo decorativo SIEMPRE detrás --}}
            <div class="absolute inset-x-0 -top-40 sm:-top-80 transform-gpu blur-3xl pointer-events-none -z-10">
                <div
                    class="relative left-[calc(50%-11rem)] aspect-[1155/678] w-[36rem] -translate-x-1/2 rotate-[30deg]
                           bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-30
                           sm:left-[calc(50%-30rem)] sm:w-[72rem]"
                    style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%);">
                </div>
            </div>

            {{-- Contenido del HERO por delante --}}
            <div class="max-w-2xl relative z-10 pointer-events-auto">
                <div class="hidden sm:mb-8 sm:flex sm:justify-center">
                    <div class="rounded-full px-4 py-2 text-sm text-gray-600 border border-gray-300 bg-white/70 backdrop-blur-sm">
                        Resuelve tus preguntas de programación.
                        <a href="{{ route('home') }}" class="font-semibold text-indigo-600 hover:underline">
                            Acerca de &rarr;
                        </a>
                    </div>
                </div>

                <div class="text-center">
                    <h1 class="text-5xl font-semibold text-gray-900 sm:text-7xl">
                        Bienvenido a tu foro favorito
                    </h1>
                    <p class="my-8 text-lg font-medium text-gray-500 sm:text-xl">
                        Es un espacio para compartir, aprender y crecer en el mundo de la programación.
                        Únete a nuestra comunidad, participa en discusiones y aprende de otros profesionales.
                    </p>

                    <div class="flex items-center justify-center gap-6">
                        @auth
                            <a href="{{ route('questions.create') }}"
                               class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-500 transition">
                                Preguntar
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                               class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-500 transition">
                                Preguntar
                            </a>
                            <a href="{{ route('register') }}" class="text-sm font-semibold text-gray-900 hover:underline">
                                Crear cuenta &rarr;
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="mx-auto max-w-4xl px-4 mb-8">
        {{ $slot }}
    </div>



    {{-- Scripts de Livewire (v3) --}}
    @livewireScripts
</body>
</html>
