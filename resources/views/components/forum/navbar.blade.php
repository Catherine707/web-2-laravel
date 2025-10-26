<nav class="flex items-center justify-between h-16">
  <div>
    <a href="{{ route('home') }}">
      <x-forum.logo />
    </a>
  </div>

  <div class="flex gap-4">
    <a href="{{ route('home') }}" class="text-sm font-semibold">Foro</a>
    <a href="#" class="text-sm font-semibold">Blog</a>
  </div>

  <div class="flex items-center gap-3">
    @auth
      <span class="text-sm text-white-600">Hola, {{ auth()->user()->name }}</span>

      <a href="{{ route('questions.create') }}"
         class="relative z-50 px-3 py-1.5 rounded-sm border border-[#8b1e2d] bg-[#8b1e2d] text-white hover:bg-[#a12c3b] hover:border-[#a12c3b] transition">
        Preguntar
      </a>

      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="text-sm font-semibold">Salir</button>
      </form>
    @else
      <a href="{{ route('login') }}" class="text-sm font-semibold">Iniciar sesion</a>
      <a href="{{ route('register') }}" class="text-sm font-semibold">Registrarse</a>
    @endauth
  </div>
</nav>




