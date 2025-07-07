<?php

//  ------------------------------USER STORY 6  ------------------------------------------------------------------------------------------------------------------------------------//
     
namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\Image\Image;  // USER STORY 6 PUNTO 4
use App\Helpers\CropPosition;

use Spatie\Image\Enums\Unit; // USER STORY 9


class ResizeImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    private $w, $h, $fileName, $path;


//------------------------------------USER STORY 6 PUNTO 4---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
 
    public function __construct($filePath, $w, $h)
    {
        $this->path = dirname($filePath);
        $this->fileName = basename($filePath);
        $this->w = $w;
        $this->h = $h;
    }

//  Abbiamo dichiarato le proprietà private all'interno della classe a riga 16
//  Le proprietà private sono accessibili solo dal codice della classe
//  stessa e non dagli oggetti esterni:



//                      ........................................................

// $w e $h : memorizzano rispettivamente la larghezza e l’altezza desiderata per la manipolazione dell'immagine
// $fileName : memorizza il nome del file dell'immagine che viene manipolata.
// $path : memorizza il percorso della directory in cui risiede l'immagine.


//                      ..........................................................


//  public function __construct($w, $h, $filePath) : Questa è la funzione costruttore della classe. Viene chiamata ogni volta che
//  viene creata una nuova istanza della classe. Il costruttore accetta tre argomenti:

// $w e $h: corrispondono rispettivamente alla larghezza e all’altezza desiderata per la manipolazione dell'immagine.
// $filePath : rappresenta il percorso completo del file immagine che necessita di manipolazione.


//                       .............................................................


//  Riga 23: $this->path = dirname($filePath); : estrae il percorso della directory
//  dall'argomento $filePath fornito utilizzando la
//  funzione dirname . Assegna quindi il percorso della directory estratto alla proprietà $path della classe.

//  Data una stringa contenente il percorso di un file o di una directory, dirname restituirà 
// il percorso della directory padre che si trova a livelli di distanza dalla directory corrente.


//                       ...............................................................


//  Riga 24: $this->fileName = basename($filePath); : estrae il nome del file dall'argomento
//  $filePath fornito utilizzando la funzione basename . Assegna quindi il nome del file estratto alla 
//  proprietà $fileName della classe.

// Data una stringa contenente il percorso di un file o di una directory, basename restituisce il nome del file dal percorso indicato



//                         ................................................................


//  Riga 25 - 26 

//  $this->w = $w; e $this->h = $h; : assegnano rispettivamente il valore dell'argomento $w e $h direttamente alle
//  proprietà $w e $h della classe.



  //-------------------------------------------------USER STORY 6 PUNTO 4 FINE--------------------------------------------------------------------------------------------------------------------------------------------------------








//--------------------------------------------------- USER STORY 6 PUNTO 5----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------



  public function handle(): void
{
     $w = $this->w;
     $h = $this->h;
     $srcPath = storage_path() . '/app/public/' . $this->path . '/' . $this->fileName;
     $destPath = storage_path() . '/app/public/' . $this->path . "/crop_{$w}x{$h}_" . $this->fileName;

     Image::load($srcPath)
        ->crop($w, $h, CropPosition::Center)

        //............... USER STORY 9 PUNTO 1 INIZIO.......................
        ->watermark(
          base_path('resources/img/watermark.png'),
          width: 50,
          height: 50,
          paddingX: 5,
          paddingY: 5,
          paddingUnit: Unit::Percent
        )

        // Come vediamo, abbiamo concatenato a riga 10 il metodo watermark() fornito sempre dalla classe 
        // ---> Spatie/Image/Image <-------

        //   watermark() accetta diversi parametri

        // il percorso del file che vogliamo utilizzare come filigrana (utilizzate il percorso del file presente nel vostro progetto)
        // possiamo poi specificare diversi parametri opzionali a nostro piacimento:
        //   le dimensioni, di default prese in pixels
        //   il padding
        //   la posizione: di default sarà posizionato in basso a destra.



        //.................... USER STORY 9 PUNTO 1 FINE............................


        ->save($destPath);
}

// !!!!!! Mi raccomando: replica ESATTAMENTE la sintassi qui presentata

//                                  ...........................................................

// Riga 99-100 : $w = $this->w; e $h = $this->h; : queste righe assegnano il valore delle proprietà $w e $h della classe alle variabili $w
// e $h rispettivamente. Queste proprietà memorizzano la larghezza e l'altezza desiderate per l'immagine ritagliata.



//                                   ...............................................................


// Riga 101 : $srcPath = storage_path() . '/app/public/' . $this->path . '/' . $this->fileName; : questa riga costruisce il
// percorso completo del file immagine originale. Essa utilizza la funzione storage_path() di Laravel per ottenere il percorso della
// cartella di storage dell'applicazione, e poi aggiunge i percorsi relativi alla sottocartella 
// specificata in $this->path e il nome del file memorizzato in $this->fileName


//                                     ..............................................................

// Riga 102 : $destPath = storage_path() . '/app/public/' . $this->path . "/crop_{$w}x{$h}_" . $this->fileName; :
// similmente alla precedente, questa riga costruisce il percorso di destinazione del file immagine ritagliata. È simile al percorso
// dell'originale, ma aggiunge un prefisso "crop_{w}x{h}_" prima del nome del file originale per indicare le dimensioni di ritaglio. Le
// parentesi graffe intorno a $w e $h consentono l'interpolazione delle variabili per creare dinamicamente il nome del file.



//                                     ....................................................................

// Riga 104 :   Image::load($srcPath) questa riga utilizza la facciata Image fornita dalla libreria Spatie/Image per caricare l'immagine
// originale dal percorso $srcPath .


//                                   ..........................................................................


// Riga 105 : ->crop($w, $h, CropPosition::Center) - Questo metodo ritaglia l'immagine caricata. Gli argomenti specificano la larghezza
// ( $w ), l'altezza ( $h ) e la posizione di ritaglio. In questo caso, viene utilizzato CropPosition::Center per ritagliare l'immagine dal
// centro.


//                                  ..............................................................................


// Riga 106 : ->save($destPath); - infine, salviamo l'immagine ritagliata al percorso di destinazione $destPath












//--------------------------------------------------- USER STORY 6 PUNTO 5 FINE----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

}
