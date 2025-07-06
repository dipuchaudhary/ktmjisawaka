 <div class="nav-bar">
            <div class="container">
                <nav class="navbar navbar-expand-md bg-dark navbar-dark">
                    <a href="#" class="navbar-brand">मेनु</a>
                    <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                        <div class="navbar-nav mr-auto">
                            <a href="{{ url('/') }}" class="nav-item nav-link {{ request()->is('/') ? 'active' : '' }}">गृहपृष्ठ</a>
                            <div class="nav-item" aria-expanded="false">
                                <a href="#" class="nav-link dropdown-toggle {{ request()->is('mudda-darta') || request()->is('banking-mudda') ? 'active' : '' }}" data-bs-toggle="dropdown"> दर्ता </a>
                                <div class="dropdown-menu">
                                    <a href="{{ route('mudda_darta.index') }}" class="{{ request()->is('mudda-darta') ? 'active' : '' }} dropdown-item">मुल मुद्दा दर्ता</a>
                                    <li><hr class="dropdown-divider"></li>
                                    <a href="{{ route('banking_mudda.index') }}" class="{{ request()->is('banking-mudda') ? 'active' : '' }} dropdown-item">बैकिङ्ग मुद्दा दर्ता</a>
                                </div>
                            </div>
                            <div class="nav-item" aria-expanded="false">
                                <a href="#" class="nav-link dropdown-toggle {{ request()->is('patra-challani') || request()->is('aviyog-challani') ? 'active' : '' }}" data-bs-toggle="dropdown"> चलानी </a>
                                <div class="dropdown-menu">
                                    <a href="{{ route('patra_challani.index') }}" class="dropdown-item {{ request()->is('patra-challani') ? 'active' : '' }}">पत्र चलानी</a>
                                    <li><hr class="dropdown-divider"></li>
                                    <a href="{{ route('aviyog_challani.index') }}" class="{{ request()->is('aviyog-challani') ? 'active' : '' }} dropdown-item">अभियोग चलानी</a>
                                </div>
                            </div>
                            <a href="{{ route('punarabedan.index') }}" class="nav-item nav-link {{ request()->is('punarabedan') ? 'active' : '' }}">पुनरावेदन</a>
                            <a href="#" class="nav-item nav-link {{ request()->is('contact') ? 'active' : '' }}">सम्पर्क ठेगाना</a>
                        </div>
                        <div class="navbar-nav ml-auto">
                        @guest
                            @if (Route::has('login'))
                                <div class="nav-item">
                                    <a href="{{ route('login') }}" class="nav-link btn btn-sm px-3">
                                        <i class="fas fa-sign-in-alt me-1"></i> {{ __('Login') }}
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                     {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('dashboard') }}">
                                        <i class="fas fa-tachometer-alt me-2"></i> {{ __('Dashboard') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i> {{ __('Logout') }}
                                        </a>
                                    </li>
                                    <li>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @endguest
                    </div>
                    </div>
                </nav>
            </div>
        </div>
