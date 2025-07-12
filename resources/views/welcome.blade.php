<x-layout>

 <div class="container-fluid home-bg vh-100 d-flex justify-content-center align-items-center">
  <div class="row justify-content-center align-items-center text-center w-100 mt-5">
    <div class="col-12 col-md-6 bg-white bg-opacity-75 p-4 rounded shadow home-content mt-5">
      <h1 class="text-center display-4 mb-4 text-gold ">{{ __('ui.welcome_home') }}</h1>

      @if (session()->has('errorMessage'))
        <div class="alert alert-danger text-center shadow rounded w-50 mx-auto">
           {{ session('errorMessage') }}
        </div>          
      @endif

      @if (session()->has('message'))
        <div class="alert alert-danger text-center shadow rounded w-50 mx-auto">
           {{ session('message') }}
        </div>          
      @endif

     @auth
  <a class="btn btn-primary btn-dark d-flex align-items-center justify-content-center h-100" href="{{ route('create.article') }}">
    {{ __('ui.create_new_article') }}
  </a>
@endauth

<a href="{{ route('warhammer.story') }}" class="btn btn-warning mt-3 d-flex align-items-center justify-content-center h-100">
  {{ __('ui.discover_game_phases') }}
</a>



    </div>
  </div>
</div>


<div class="d-flex align-items-center justify-content-center gap-3 mt-5 mb-3">
  <h2 class="title-gold display-3 m-0">{{ __('ui.new_articles') }}</h2>
  <img src="https://s1.qwant.com/thumbr/474x472/1/9/3b3e60e9d483a987b03ca56966feb21f3b48b2e8681ba96e9dcaecc5def6bb/th.jpg?u=https%3A%2F%2Ftse.mm.bing.net%2Fth%3Fid%3DOIP.AkIfFuYB5D1hdBMaTxrOsQHaHY%26pid%3DApi&q=0&b=1&p=0&a=0" alt="Icona articoli" class="img-small-title">
</div>






  <div class="container my-5">
    <div class="row justify-content-center gy-4">
      
      @forelse($articles as $article)  
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 d-flex justify-content-center">
          <x-card :article="$article" />
        </div>    
      @empty
        <div class="col-12 text-center">
          <h3>{{ __('ui.no_articles_created_yet') }}</h3>
        </div>
      @endforelse

    </div>
  </div>




<!--Recensioni-->
<section class="container my-5"> <!-- aggiunto margine verticale -->
    <div class="row justify-content-center">
        <div class="col-12 col-md-8">
            <!-- Slider main container -->
            <!-- Il primo div è il main container dello slider -->
            <div class="swiper swiper1 mx-auto p-3 bg-light rounded shadow"> <!-- padding, sfondo chiaro, bordo arrotondato e ombra -->
                <!-- Additional required wrapper -->
                <!--Obbligatoriamente è richiesto un div con una classe swiper-wrapper che è colui che contiene le slide -->
                <div class="swiper-wrapper">
                </div>
                <!-- If we need pagination, ossia i cambi opzionali -->
            </div>
        </div>
    </div>
</section>
<!--Recensioni end-->











  
  </x-layout>
