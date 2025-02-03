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
                <li class="nav-item text">
                    <a class="nav-link {{ request()->is('cards') ? 'active' : '' }}" href="{{ route('cards.index') }}">
                        Cards
                    </a>
                </li>
                <li class="nav-item text">
                    <a class="nav-link {{ request()->is('sets') ? 'active' : '' }}" href="{{ route('sets.index') }}">
                        Sets
                    </a>
                </li>
                <li class="nav-item text">
                    <a class="nav-link {{ request()->is('user-sets') ? 'active' : '' }}" href="{{ route('user-sets.index') }}">
                        My Sets
                    </a>
                @if(Auth::check())
                    <li class="nav-item">
                    <button class="user-avatar" id="user-avatar-btn" style="all: unset; display: inline-block; padding: 0; background: none; border: none; cursor: pointer; margin: 5px 0 0;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40">
                            <!-- Círculo blanco -->
                            <circle cx="20" cy="20" r="20" fill="white" />
                            <!-- Inicial del nombre del usuario (en este caso se usa "A" como ejemplo) -->
                            <text x="20" y="25" text-anchor="middle" font-size="18" fill="#0056b3" font-weight="bold">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</text>
                        </svg>
                    </button>

                    <div class="dropdown-menu hidden" id="user-dropdown-menu">
                            <div class="py-1">
                                <x-dropdown-link :href="route('profile.edit')">
                                    {{ __('Profile') }}
                                </x-dropdown-link>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf

                                    <x-dropdown-link :href="route('logout')"
                                                     onclick="event.preventDefault();
                                         this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </div>
                        </div>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link login-button {{ request()->is('login') ? 'active' : '' }}" href="{{ route('login') }}" id="login-link">
                            Login/Register
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
