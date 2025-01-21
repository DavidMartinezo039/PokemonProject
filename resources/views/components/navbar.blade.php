<!-- Aquí va tu contenido específico -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav">
    <div class="container">
        <a class="navbar-brand" href="{{ route('welcome') }}"><img src="{{ asset('View/assets/img/logo.png') }}" alt="..." style="height: 70px; transition: transform 0.3s ease-in-out;"
                                                      onmouseover="this.style.transform='scale(1.2)'"
                                                      onmouseout="this.style.transform='scale(1)'"/></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            Menu
            <i class="fas fa-bars ms-1"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav text-uppercase ms-auto py-4 py-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('cards') ? 'active' : '' }}" href="{{ route('cards.index') }}">
                        Cards
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('sets') ? 'active' : '' }}" href="{{ route('sets.index') }}">
                        Sets
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('user_sets') ? 'active' : '' }}" href="{{ route('user_sets.index') }}">
                        My Sets
                    </a>
                    <!-- Aquí está la lógica para mostrar el Login/Sign Up o el avatar -->
                    <!-- Aquí está la lógica para mostrar el Login/Sign Up o el avatar -->
                    @if(Auth::check())
                        <!-- Si el usuario está autenticado, muestra su avatar -->
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('profile.edit') }}">
                        <img src="{{ Auth::user()->avatar_url }}" alt="Avatar" style="width: 40px; height: 40px; border-radius: 50%;">
                    </a>
                </li>
                @else
                    <!-- Si el usuario no está autenticado, muestra los enlaces de Login y Sign Up -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}" id="login-link">
                            Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">
                            Sign Up
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
