<?php

namespace App\Models;
use Illuminate\Support\Facades\Storage;
use App\Models\Article;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;



//-------------------------------------USER STORY 5----------------------------------------------------------------------------------------------------




class Image extends Model
{
    use HasFactory;

    protected $fillable = [

        'path'

    ];


    public function article(): BelongsTo

    {

        return $this->belongsTo(Article::class);

    }


    //  Le immagini saranno caratterizzate dal loro path e saranno legate
    //  agli articoli da una relazione 1-N: un articolo può avere tante immagini

    //  una immagine è relazionata ad un unico articolo, salvato in article_id .


    // Abbiamo quindi creato la funzione di relazione article() , in cui specifichiamo che una singola immagine appartiene a un singolo oggetto di classe Article.

    // !!! Ricordiamoci di importare le classi






//------------------------------------------------USER STORY 6 PUNTO 9------------------------------------------------------------------------------------------------------------------------------------------------


public static function getUrlByFilePath($filePath, $w = null, $h = null)
{
    if (!$w && !$h) {
        return Storage::url($filePath);
    }

    $path = dirname($filePath);
    $filename = basename($filePath);
    $file = "{$path}/crop_{$w}x{$h}_{$filename}";
    return Storage::url($file);
}

public function getUrl($w = null, $h = null)
{
    return self::getUrlByFilePath($this->path, $w, $h);
}


// Abbiamo creato due funzioni, analizziamole.
// getUrlByFilePath() , riga 1:

//                 ............................................

// Funzione statica: Questa funzione è definita come static , 
// il che significa che può essere chiamata direttamente sulla classe senza bisogno di creare un'istanza della classe.


//                 ............................................


// Parametri:
//           $filePath : Il percorso del file dell'immagine memorizzato nell'archiviazione di Laravel.
//           $w e $h : rispettivamente, larghezza e altezza desiderate dell'immagine in pixel



//                 ............................................


// Logica:

// Riga 57-59: se $w e $h sono entrambi null , la funzione chiama semplicemente Storage::url($filePath) per restituire l'URL
// dell'immagine originale memorizzata nell'archiviazione.


//  Se $w o $h sono specificati, la funzione esegue il seguente codice:

// Riga 61: Estrae la directory e il nome del file dal $filePath originale.  
// Riga 63: Costruisce un nuovo percorso del file con il nome crop_{w}x{h}_{filename} , che è la stessa struttura che abbiamo impostato in ResizeImage .  
// Riga 64: Restituisce l'URL del percorso del file ritagliato utilizzando Storage::url($file) .


//                 ............................................



// Riassumendo, questa funzione consente di recuperare l'URL di un'immagine memorizzata nell'archiviazione,
//  con la possibilità di ottenere l'URL di una versione ridimensionata dell'immagine fornendo larghezza e altezza desiderate.



// getUrl() :

// Verrà richiamata a partire da un oggetto di classe Image
// parametri: $w e $h , rispettivamente, larghezza e altezza desiderate dell'immagine in pixel
// Logica: richiama la funzione statica appena creata getUrlByFilePath() passandole i parametri reali:
// il path dell’immagine da cui viene fatta partire la funzione stessa
// i parametri $w e $h che a sua volta arrivano in questa funzione quando viene richiamata.

//                        ......................................................




// In sostanza, la funzione getUrlByFilePath fornisce la logica principale 
// per recuperare l'URL dell'immagine, gestendo sia le immagini originali che quelle ritagliate.

// La funzione getUrl consente alle istanze di classe immagine di recuperare facilmente l'URL dell'immagine
// restituita da getUrlByFilePath .





//------------------------------------------------------USER STORY 6 PUNTO 9 FINE-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------






//------------------------------------------------- USER STORY 7 PUNTO 11 INIZIO ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------


 protected function casts(): array
 {
    return [
        'labels' => 'array',
    ];
 }


// Il metodo casts() fornito da laravel consente di fare il casting: il casting in Laravel è un processo di conversione automatica dei dati tra
// formati diversi. Viene utilizzato principalmente per convertire i valori memorizzati nel database in tipi di dati PHP più adatti per l'utilizzo nelle
// applicazioni Laravel. Questo semplifica la gestione e la manipolazione dei dati all'interno del framework.
// In questo caso stiamo facendo il casting della colonna labels in array.



//------------------------------------------------- USER STORY 7 PUNTO 11 FINE ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------


}