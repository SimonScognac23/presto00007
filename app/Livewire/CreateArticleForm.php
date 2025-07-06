<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Article;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use App\Jobs\ResizeImage;
use Illuminate\Support\Facades\File;

use App\Jobs\GoogleVisionSafeSearch;
use App\Jobs\GoogleVisionLabelImage;
use App\Jobs\RemoveFaces;





class CreateArticleForm extends Component
{

    use WithFileUploads;


#[Validate('required', message: 'Il titolo è obbligatorio.')]  // queste validate funzionano che: finche non superano lo step numero 1 ossia title, le Validate non vanno alla numero 2 ossia subtitle
 // validate accetta questi parametri dove il primo parametro è la regoladi validazione e il secondo è il messaggio con il valore del messaggio tramite la stringa


     // #[Validate('required|min:3')]  // annotation e sono un modo di attribuire ai attributi pubblici delle regole di validazione, prese da livewire sostituiscono le validate() che andavano a essere inserite nella request
    public $title;  //definiamo i 3 campi del form in modo che istruiamo livewire quale operazione logica deve compiere dentro al form, in questo caso è quella di salvare i dati 


#[Validate('required', message: 'Descrizione obbligatoria')] 
#[Validate('min:5', message: 'Descrizione troppo corta')]
  //   #[Validate('required|min:3')]  // attribuisco delle regole di validazione
     public $description;


#[Validate('required|numeric')] 

    public $price;
    // questi tre valori sono immediatamente disponibili nel create-article.blade
    // Li posso passare alla create-article.blade tramite il wire:model

#[Validate('required')] 
     public $category;

     public $article;

#[Validate('nullable|image')]
public $img;


public $images =[]; // USER STORY 5
public $temporary_images; // USER STORY 5

//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------


     // Nella funzione store richiamiamo il metodo validate per verificare che le regole che abbiamo settato siano rispettate prima di proseguire
     public function store()
{

  

     $this->user_id = Auth::user()->id;       
        //Associo l'attributo pubblico user_id a l'id dell'utente autenticato

        if ($this->img) {         
            //L'utente sta inserendo un immagine?

            $this->validate(['img' => 'image']);  
            //Se si, allora img oltre che ad essere stato inserito deve essere appunto di tipo image

        } else {
            $this->validate();   
            // Faccio partire le regole impostate precedentemente
        } 


        //Creo un nuovo oggetto di classe Article
        //Richiamo la classe Article con il metodo create

    // Creazione dell'articolo (con o senza immagine personalizzata)
     $article = Article::create([
        'title' => $this->title,
        'description' => $this->description,
        'price' => $this->price,
        'category_id' => $this->category,
        'user_id' => Auth::id(),


       

    ]);


// -----------USER STORY 5 PUNTO 12-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
if (count($this->images) > 0) {
    foreach ($this->images as $image) {
      

        
        //....................  USER STORY 6 PUNTO 7 ....................

        $newFileName = "articles/{$article->id}";  // USER STORY 6 PUNTO 7
        $newImage = $article->images()->create(['path' => $image->store($newFileName, 'public')]); // // USER STORY 6 PUNTO 7

    // VECCHIO CODICE
    // dispatch(new ResizeImage($newImage->path, 300, 300)); // USER STORY 6 PUNTO 7

    // dispatch(new GoogleVisionSafeSearch($newImage->id));  // USER STORY 7 PUNTO 8

    //dispatch(new GoogleVisionLabelImage($newImage->id));  // USER STORY 7 PUNTO 12


    //...... USER STORY 8 PUNTO 3 INIZIO.......

    // NUOVO CODICE
    
    RemoveFaces::withChain([
        new ResizeImage($newImage->path, 300, 300),
        new GoogleVisionSafeSearch ($newImage->id),
        new GoogleVisionLabelImage ($newImage->id)
    ])->dispatch($newImage->id);

    //....... USER STORY 8 PUNTO 3 FINE...........

    }
     File::deleteDirectory(storage_path('/app/livewire-tmp')); // USER STORY 6 PUNTO 7


}

                      
// A riga 104, $newFileName = "articles/{$this->article->id}"; : costruisce il nome del file per l'immagine con la struttura "articles/{id_articolo}".

// Riga 105, $newImage = $this->article->images()->create([...]); : crea un nuovo record collegato all'articolo corrente nella tabella images tramite la relazione one-to-many tra articoli e immagini. Il file dell’immagine viene salvato nello Storage in un percorso che avrà questa struttura: storage/app/public/articles/idArticolo/nomeFile .


// Riga 106 dispatch(new ResizeImage($newImage->path, 300, 300)); : il metodo dispatch() serve ad inviare un job ( ResizeImage in questo caso) ad una coda di lavoro. Stiamo quindi creando un nuovo oggetto di classe ResizeImage e passiamo al costruttore i parametri reali: il path dell’immagine appena salvata e le dimensioni che vogliamo per il crop.


// Riga 109: File::deleteDirectory(storage_path('/app/livewire-tmp')); : elimina la directory temporanea di Livewire, utilizzata per caricare temporaneamente le immagini prima del salvataggio.



          //....................  USER STORY 6 PUNTO 7 FINE ....................


// ------------------FINE USER STORY 5 PUNTO 12 --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

    // Reset del form
    $this->clearForm();

      return redirect()->route('homepage')->with('message', 'Articolo creato');

//-------------------------FINE USER STORY 5 PUNTO 12 --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
 
}

  protected function clearForm(){  //incapsulo la mia logica dentro un altro metodo 
        //ora la funzione clearform non è accessibile all esterno IMPORTANTE!!!

           $this->title = ""; // per far ritornare la stringa vuota
       $this->description = "";
        $this->category = "";
 $this->price = "";
 $this->img = null;

    }
    

    public function render()
    {
        return view('livewire.create-article-form');
    }


// USER STORY 5 PUNTO 12
//  A riga 97-100, una volta creato l’articolo, se l’utente ha inserito delle immagini,
//  per ognuna di queste creiamo, tramite la funzione di
// relazione con article ( $this->article->images() ), un oggetto di classe Image:
//  *   il file sarà salvato nello storage, nel percorso storage/app/public/images
//  *   il percorso dell’immagine sarà salvato nella tabella images del database



//---------------------USER STORY 5 PUNTO 9 -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------


public function updatedTemporaryImages()

{
    
    if ($this->validate([

        'temporary_images.*' => 'image|max:1024',
         'temporary_images.' => 'max:6'
    ])) {
        foreach ($this->temporary_images as $image) {
            $this->images[] = $image;

        }
    }


}

//  Cosa stiamo facendo:



// 1 Abbiamo creato un metodo chiamato updatedTemporaryImages() . Il nome non è casuale: updated + nome attributo è una sintassi
// specifica di livewire, utilizzata per monitorare e aggiornare in tempo reale i cambiamenti della proprietà richiamata nel nome del metodo 
// stesso (nel nostro caso $temporary_images )





// 2 updated è un hook in livewire:
// * In generale, un hook è un punto di accesso predefinito all'interno di un framework o di una libreria che consente agli sviluppatori di
//   intercettare e modificare il comportamento del software in momenti specifici del ciclo di vita di un componente.

// * updated viene chiamato quando una proprietà pubblica di un componente viene modificata sul client. Questo hook fornisce
// un punto di accesso per reagire alle modifiche delle proprietà prima che il componente venga aggiornato sul server.






// 3 --  Riga 131: Iniziamo con un controllo: se le regole di validazione sono rispettate, allora salviamo ognuna delle immagini temporanee
// all’interno dell’array $images.

// 'temporary_images.*' - setterà le regole di validazione per ogni immagine presente nell’array, quindi ogni immagine deve
//  pesare massimo 1024


//  ‘temporary_images' - setta le regole per tutto l’array, quindi possono essere inserite massimo 6 immagini
//  Per questo motivo nella pagina blade abbiamo inserito due direttive @error  ---->create-article-form.blade.php<----






// 4 
// Lo stiamo facendo tramite la cosìdetta sugar syntax $this->images[] = $image .
// images è il nome dell’array che stiamo modificando
// [] indica, appunto, che stiamo lavorando con un array
// = è l’operatore di assegnazione, stiamo specificando che vogliamo dare l’elemento che segue l’uguale come valore all’elemento dell’array
// $image è l’elemento che vogliamo salvare nell’array

// Il corrispettivo di questo codice in sintassi standard sarebbe: array_push($this->images, $image) .
// Questo array sarà responsabile della visualizzazione delle immagini in preview.







//-----------------------------------------FINE USER STORY 5 PUNTO 9---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------









//---------------------USER STORY 5 PUNTO 10 -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------




public function removeImage($key)
{

    if(in_array($key, array_keys($this->images))) {
        unset($this->images[$key]);
    }
}


//  il metodo removeImage() accetta in ingresso un parametro, $key
//  al suo interno facciamo un controllo: se l’immagine selezionata è presente nell’array $images viene eliminata dall’array (e quindi non
//  sarà né visualizzata né salvata.

//  Per fare ciò stiamo utilizzando diverse funzioni ----> built-in di PHP: <------------
//  in_array() verifica se un dato (il primo parametro) è presente all’interno di un array (secondo parametro) 
//  array_keys() : restituisce tutte le chiavi o indici dell’array passato come parametro 
//  unset() : elimina dati elementi dall’interno di un array


// Una volta creata la funzione, diamo la possibilità all’utente di utilizzarla.
// Modifichiamo dunque il form di creazione in  ----> create-article-form.blade.php <----








//----------------------------------FINE USER STORY 5 PUNTO 10----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------


}