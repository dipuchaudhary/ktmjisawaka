 <div class="nav-bar">
            <div class="container">
                <nav class="navbar navbar-expand-md bg-dark navbar-dark">
                    <a href="#" class="navbar-brand">मेनु</a>
                    <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                        <div class="navbar-nav mr-auto">
                            <a href="{{ url('/') }}" class="nav-item nav-link active">गृहपृष्ठ</a>
                            <div class="nav-item" aria-expanded="false">
                                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"> दर्ता </a>
                                <div class="dropdown-menu">
                                    <a href="{{ route('mudda_darta.index') }}" class="dropdown-item">मुल मुद्दा दर्ता</a>
                                    <li><hr class="dropdown-divider"></li>
                                    <a href="{{ route('banking_mudda.index') }}" class="dropdown-item">बैकिङ्ग मुद्दा दर्ता</a>
                                </div>
                            </div>
                            <div class="nav-item" aria-expanded="false">
                                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"> चलानी </a>
                                <div class="dropdown-menu">
                                    <a href="{{ route('patra_challani.index') }}" class="dropdown-item">पत्र चलानी</a>
                                    <li><hr class="dropdown-divider"></li>
                                    <a href="" class="dropdown-item">अभियोग चलानी</a>
                                </div>
                            </div>
                            <a href="#" class="nav-item nav-link">पुनरावेदन</a>
                            <a href="contact.html" class="nav-item nav-link">सम्पर्क ठेगाना</a>
                        </div>
                        <div class="social ml-auto">
                            <a href=""><i class="fab fa-twitter"></i></a>
                            <a href=""><i class="fab fa-facebook-f"></i></a>
                            <a href=""><i class="fab fa-linkedin-in"></i></a>
                            <a href=""><i class="fab fa-instagram"></i></a>
                            <a href=""><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
