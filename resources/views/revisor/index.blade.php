<x-layout>
    <div class="container my-5">

        {{-- HEADER --}}
        <div class="row">
            <div class="col-12">
                <div class="bg-dark text-white text-center py-3 rounded shadow">
                    <h3 class="display-5 m-0">{{ __('ui.revisor_dashboard') }}</h3>
                </div>
            </div>
        </div>

        {{-- Se c'è un messaggio di sessione, lo mostro all'utente --}}
        @if(session()->has('message'))
            <div class="row justify-content-center mt-4">
                <div class="col-md-6">
                    <div class="alert alert-success text-center shadow rounded">
                        {{ session('message') }}
                    </div>
                </div>
            </div>
        @endif

        {{-- Controllo se c'è un articolo da revisionare --}}
        @if($article_to_check)

            {{-- Qui inizio a mostrare le immagini associate all'articolo --}}
            <div class="container my-5">
                <div class="row justify-content-center gy-4">

                    {{-- Se l'articolo ha delle immagini caricate, le stampo --}}
                    @if ($article_to_check->images->count())
                        @foreach ($article_to_check->images as $key => $image)
                            @php
                                // Creo il percorso dell'immagine tagliata se esiste
                                $cropPath = dirname($image->path) . '/crop_300x300_' . basename($image->path);
                                // Controllo se il file esiste nel disco 'public', se sì uso il crop
                                $url = \Illuminate\Support\Facades\Storage::disk('public')->exists($cropPath)
                                       ? $image->getUrl(300, 300)
                                       : $image->getUrl();
                            @endphp

                            <div class="col-12 col-md-10 col-lg-8 mx-auto">
                                <div class="row g-3 align-items-center border rounded shadow p-3 bg-light">
                                    
                                    <div class="col-12 col-md-4 text-center">
                                        {{-- Mostro l'immagine vera e propria --}}
                                        <img
                                          src="{{ $url }}"
                                          class="img-fluid rounded shadow-sm border"
                                          style="width: 300px; height: 300px; object-fit: cover;"
                                          alt="Immagine {{ $key + 1 }} dell’articolo {{ $article_to_check->title }}">
                                    </div>

                                    {{-- USER STORY 7 PUNTO 13 --}}
                                    <div class="col-12 col-md-3 ps-md-3">
                                        <div class="card-body">
                                            <h5 class="mb-3">Labels</h5>
                                            @if ($image->labels)
                                               @foreach ($image->labels as $label)
                                                   <span class="badge bg-primary me-1 mb-1">#{{ $label }}</span>
                                               @endforeach
                                            @else
                                               <p class="fst-italic">No labels</p>
                                            @endif
                                        </div>
                                    {{-- USER STORY 7 PUNTO 13 FINE --}}
                                    </div>

                                    {{-- USER STORY 7 PUNTO 9 --}}
                                    <div class="col-12 col-md-5 p-3 bg-white rounded shadow-sm">
                                        <div class="card-body">
                                            <h5 class="card-title mb-3">Ratings</h5>

                                            <div class="row justify-content-center align-items-center mb-2">
                                                <div class="col-3 text-center fw-bold">{{ $image->adult }}</div>
                                                <div class="col-9">
                                                    <span class="badge bg-danger">adult</span>
                                                </div>
                                            </div>

                                            <div class="row justify-content-center align-items-center mb-2">
                                                <div class="col-3 text-center fw-bold">{{ $image->violence }}</div>
                                                <div class="col-9">
                                                    <span class="badge bg-warning text-dark">violence</span>
                                                </div>
                                            </div>

                                            <div class="row justify-content-center align-items-center mb-2">
                                                <div class="col-3 text-center fw-bold">{{ $image->spoof }}</div>
                                                <div class="col-9">
                                                    <span class="badge bg-info text-dark">spoof</span>
                                                </div>
                                            </div>

                                            <div class="row justify-content-center align-items-center mb-2">
                                                <div class="col-3 text-center fw-bold">{{ $image->racy }}</div>
                                                <div class="col-9">
                                                    <span class="badge bg-warning text-dark">racy</span>
                                                </div>
                                            </div>

                                            <div class="row justify-content-center align-items-center mb-0">
                                                <div class="col-3 text-center fw-bold">{{ $image->medical }}</div>
                                                <div class="col-9">
                                                    <span class="badge bg-secondary">medical</span>
                                                </div>
                                            </div>
                                        </div> 
                                    </div> 
                                    {{-- USER STORY 7 PUNTO 9 FINE --}}
                                </div>
                            </div>

                        @endforeach
                    @else
                        {{-- Se non ci sono immagini caricate, uso immagini casuali come segnaposto --}}
                        @for ($i = 0; $i < 6; $i++)
                            <div class="col-6 col-md-4 mb-4 text-center">
                                <img
                                  src="https://picsum.photos/300"
                                  class="img-fluid rounded shadow"
                                  style="width: 300px; height: 300px; object-fit: cover;"
                                  alt="immagine segnaposto">
                            </div>
                        @endfor
                    @endif

                </div>
            </div>

            {{-- Qui stampo i dettagli dell'articolo --}}
            <div class="bg-dark p-4 rounded shadow text-white">
                <h2 class="mb-3">{{ $article_to_check->title }}</h2>

                {{-- Controllo se l'articolo ha un utente associato --}}
                @if($article_to_check->user)
                    <h4 class="text-light mb-2">{{ __('ui.author') }}: {{ $article_to_check->user->name }}</h4>
                @else
                    {{-- Se per qualche motivo non c'è un autore, lo segnalo --}}
                    <h4 class="text-danger mb-2">{{ __('ui.author_not_available') }}</h4>
                @endif

                {{-- Mostro la categoria se presente --}}
                @if($article_to_check->category)
                    <h5 class="fst-italic text-info mb-3">{{ __('ui.category') }}: {{ $article_to_check->category->name }}</h5>
                @else
                    {{-- Se non ha categoria, lo evidenzio --}}
                    <h5 class="fst-italic text-danger mb-3">{{ __('ui.noCategory') }}</h5>
                @endif

                {{-- Mostro la data di creazione dell'articolo --}}
                <p class="text-secondary fst-italic mb-4">{{ __('ui.created_at') }}: {{ $article_to_check->created_at->format('d/m/Y H:i') }}</p>

                {{-- Descrizione dell’articolo --}}
                <p class="lh-lg">{{ $article_to_check->description }}</p>

                {{-- Se l'articolo è stato modificato dopo la creazione, mostro anche quella data --}}
                @if($article_to_check->updated_at && $article_to_check->updated_at != $article_to_check->created_at)
                    <p class="text-secondary fst-italic mt-3">{{ __('ui.last_modified') }}: {{ $article_to_check->updated_at->format('d/m/Y H:i') }}</p>
                @endif
            </div>

            {{-- Qui inserisco i due pulsanti per accettare o rifiutare l'articolo --}}
            <div class="d-flex justify-content-between my-4">
                <form action="{{ route('reject', ['article' => $article_to_check]) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button class="btn btn-outline-danger px-4 fw-bold">{{ __('ui.reject') }}</button>
                </form>

                <form action="{{ route('accept', ['article' => $article_to_check]) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button class="btn btn-outline-success px-4 fw-bold">{{ __('ui.accept') }}</button>
                </form>
            </div>

        @else
            {{-- Se non c'è nessun articolo da revisionare, lo dico all’utente --}}
            <div class="row justify-content-center align-items-center text-center min-vh-50">
                <div class="col-12">
                    <div class="alert alert-warning shadow">
                        {{ __('ui.no_articles_to_review') }}
                    </div>
                </div>
            </div>
        @endif

    </div>
</x-layout>
