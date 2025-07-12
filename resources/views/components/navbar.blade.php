<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom shadow-sm py-3">
    <div class="container-fluid">

        <!-- Logo e link alla homepage -->
        <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ route('homepage') }}">
            <img src="https://s1.qwant.com/thumbr/474x473/8/6/0b30ce48a5ec31f35befd74b51dbdbcb8da4a2a9e2b4ade13bc64d7e92bdfb/th.jpg?u=https%3A%2F%2Ftse.mm.bing.net%2Fth%3Fid%3DOIP.-CK8bsx5awEEJ0_FAA4jzQHaHZ%26pid%3DApi&q=0&b=1&p=0&a=0" 
                 alt="Logo" style="width: 40px; height: auto; margin-right: 10px;">
            Presto.it
        </a>

        <!-- Bottone per il menu responsive (su mobile) -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Contenuto principale della navbar -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto align-items-center gap-3">

                <!-- Link alla homepage -->
                <li class="nav-item">                 
                    <a class="nav-link fw-semibold" href="{{ route('homepage') }}">{{ __('ui.home') }}</a>
                </li>

                <!-- Elementi visibili solo all'utente autenticato -->
                @auth
                    <!-- Link per creare un nuovo articolo -->
                    <li class="nav-item">
                        <a class="nav-link fw-semibold" href="{{ route('create.article') }}"> {{ __('ui.create_article') }} </a>
                    </li>

                    <!-- Link per il logout (attiva il form nascosto) -->
                    <li class="nav-item">
                        <a class="nav-link fw-semibold" href="#" onclick="event.preventDefault(); document.querySelector('#form-logout').submit();">
                            Logout
                        </a>

                        <!-- Form nascosto per eseguire il logout (necessario per usare il metodo POST) -->
                        <form id="form-logout" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>

                    <!-- Se l’utente ha il ruolo di revisore, mostra il link alla zona revisore -->
                    @if (Auth::user()->is_revisor)
                        <li class="nav-item">
                            <a href="{{ route('revisor.index') }}" class="nav-link btn btn-outline-success btn-sm position-relative-w-sm-25">
                                {{ __('ui.revisor_zone') }}
                            </a>
                        </li>
                    @endif

                    <!-- Dropdown menu con tutte le categorie -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ __('ui.categories') }}
                        </a>
                        <ul class="dropdown-menu">
                            @foreach ($categories as $category)
                                <li>
                                    <a class="dropdown-item text-capitalize" href="{{ route('byCategory', ['category' => $category->id]) }}">
                                        {{ __("ui." . strtolower($category->name)) }}
                                    </a>
                                </li>
                                <!-- Divisore tra categorie, esclusa l’ultima -->
                                @if (!$loop->last)
                                    <hr class="dropdown-divider">
                                @endif
                            @endforeach
                        </ul>
                    </li>
                @endauth

                <!-- Elementi visibili solo agli utenti non autenticati -->
                @guest
                    <!-- Link per la registrazione -->
                    <li class="nav-item">
                        <a class="nav-link fw-semibold" href="{{ route('register') }}">
                            {{ __('ui.register') }}
                        </a>
                    </li>

                    <!-- Link per il login -->
                    <li class="nav-item">
                        <a class="nav-link fw-semibold" href="{{ route('login') }}">
                            {{ __('ui.login') }}
                        </a>
                    </li>

                    <!-- Link per visualizzare tutti gli articoli pubblicati -->
                    <li class="nav-item">
                        <a class="nav-link fw-semibold" href="{{ route('article.index') }}">
                            {{ __('ui.all_articles') }}
                        </a>
                    </li>

                    <!-- Dropdown menu con tutte le categorie -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ __('ui.categories') }}
                        </a>
                        <ul class="dropdown-menu">
                            @foreach ($categories as $category)
                                <li>
                                    <a class="dropdown-item text-capitalize" href="{{ route('byCategory', ['category' => $category->id]) }}">
                                        {{ __("ui." . strtolower($category->name)) }}
                                    </a>
                                </li>
                                <!-- Divisore tra categorie, esclusa l’ultima -->
                                @if (!$loop->last)
                                    <hr class="dropdown-divider">
                                @endif
                            @endforeach
                        </ul>
                    </li>
                @endguest

                <!-- Barra di ricerca con Bootstrap - USER STORY 10 -->
                <form class="d-flex justify-content-center my-3" role="search" method="GET" action="{{ route('article.search') }}">
                    <div class="input-group input-group-sm" style="max-width: 450px; width: 100%;">
                        <input 
                            type="search" 
                            name="query" 
                            class="form-control rounded-start" 
                            placeholder="Find all..." 
                            aria-label="search">
                        <button 
                            type="submit" 
                            class="btn btn-warning rounded-end" 
                            id="basic-addon2">
                            Find all
                        </button>
                    </div>
                </form>

                <!-- Selettore lingua tramite componenti Blade -->
                <li class="nav-item d-flex align-items-center gap-1 ms-3">
                    <!-- Ogni componente invia un form POST per cambiare lingua -->
                    <x-_locale lang="it" />
                    <x-_locale lang="en" />
                    <x-_locale lang="es" />
                </li>
            </ul>
        </div>
    </div>
</nav>

{{--  
 Questo codice crea un menu a discesa (dropdown) per la navbar con l'elenco delle categorie,
 e aggiunge un divisore hr tra gli elementi tranne che dopo l ultimo, grazie alla direttiva @if (!$loop->last)
 di Blade. Stiamo usando !$loop.->last. per inserire il divisore del dropdown
 in ogni iterazione del ciclo tranne l'ultima 
--}}

{{-- 
    CAMBIO LINGUE     ----- USER STORY 4 --------
    Come vediamo, stiamo richiamando il componente _locale con l’attributo lang, che nel componente diventerà la variabile $lang che
    notavamo in precedenza: il valore di lang sarà, nel nostro caso, rispettivamente, it, en e es, ovvero italiano e inglese, le lingue richieste, e
    spagnolo, la lingua a scelta.
    Questa stringa servirà a specificare quale bandiera far visualizzare.
    Passiamo dunque alla gestione del cambio lingua vero e proprio.
--}}
