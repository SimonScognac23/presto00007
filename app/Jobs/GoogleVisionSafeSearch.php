<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Image;
use Google\Cloud\Vision\V1\ImageAnnotatorClient;

class GoogleVisionSafeSearch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    // Analizziamo per bene il codice:
    // Proprietà della classe:
    // Riga 5, private $article_image_id : Questa proprietà privata memorizza l'ID dell'immagine dell'articolo da analizzare.
    private $article_image_id;

    // Riga 6-9, il costruttore ( __construct ) prende $article_image_id come parametro e lo assegna alla proprietà privata.
    public function __construct($article_image_id)
    {
        $this->article_image_id = $article_image_id;
    }

    // Metodo handle :
    public function handle(): void
    {
        // A riga 14, innanzitutto recuperiamo l'istanza del modello Image dal database 
        // utilizzando il metodo find con il $article_image_id memorizzato e la salva nella variabile $i .
        // Se non viene trovata alcuna immagine, il job termina subito utilizzando return .
        $i = Image::find($this->article_image_id);
        if (!$i)
            return;

        // A riga 18, in caso contrario, recuperiamo il contenuto dell'immagine dallo storage 
        // utilizzando file_get_contents e lo salva nella variabile $image .
        // file_get_contents ritorna il file sotto forma di stringa
        // Il percorso viene costruito combinando il percorso di storage ( storage_path ), 
        // il percorso pubblico dell'applicazione ( app/public ) e il percorso dell'immagine 
        // memorizzato nel modello ( $i->path ).
        $image = file_get_contents(storage_path('app/public/' . $i->path));

        // A riga 19, viene poi impostata una variabile di ambiente chiamata GOOGLE_APPLICATION_CREDENTIALS 
        // utilizzando putenv . Questa variabile punta al file JSON associato a questa user story 
        // contenente le credenziali Google Cloud per accedere all'API Vision.
        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . base_path('google_credential.json'));

        // A riga 21, viene salvato nella variabile $imageAnnotator un oggetto ImageAnnotatorClient , 
        // che interagisce con l'API Google Cloud Vision.
        $imageAnnotator = new ImageAnnotatorClient();

        // A riga 22, in $response salviamo il risultato del metodo safeSearchDetection 
        // lanciato a partire dall’oggetto appena creato, passando il contenuto dell'immagine ( $image ) 
        // come argomento. Questo metodo esegue un'analisi di ricerca sicura sull'immagine. 
        // Il client viene quindi chiuso utilizzando $imageAnnotator->close() .
        $response = $imageAnnotator->safeSearchDetection($image);
        $imageAnnotator->close();

        // A riga 25, recuperiamo l'oggetto SafeSearchAnnotation dalla risposta utilizzando 
        // $safe = $response->getSafeSearchAnnotation() : in questa maniera estraiamo i valori 
        // di probabilità delle categorie per cui abbiamo creato le colonne nella tabella images 
        // ( adult , spoof , racy , medical e violence ) dall'oggetto SafeSearchAnnotation 
        // utilizzando, tra riga 29 e 32, metodi getter come $adult = $safe->getAdult() , ecc.
        $safe = $response->getSafeSearchAnnotation();
        $adult = $safe->getAdult();
        $medical = $safe->getMedical();
        $spoof = $safe->getSpoof();
        $violence = $safe->getViolence();
        $racy = $safe->getRacy();

        // A riga 34 viene definito un array chiamato $likelihoodName .
        // Questo array mappa i valori di probabilità (numerici) ai corrispondenti 
        // nomi delle classi Bootstrap Icons, per consentire una più facile rappresentazione visiva).
        $likelihoodName = [
            'text-secondary bi bi-circle-fill',
            'text-success bi bi-check-circle-fill',
            'text-warning bi bi-exclamation-circle-fill',
            'text-danger bi bi-x-circle-fill',
        ];

        // Tra riga 43 e 47, il job aggiorna l'istanza del modello Image ( $i ) 
        // con i nomi delle classi di icone mappate per ogni categoria 
        // utilizzando proprietà come $i->adult = $likelihoodName[$adult] .
        $i->adult = $likelihoodName[$adult];
        $i->spoof = $likelihoodName[$spoof];
        $i->medical = $likelihoodName[$medical];
        $i->violence = $likelihoodName[$violence];
        $i->racy = $likelihoodName[$racy];

        // Infine, il modello immagine aggiornato viene salvato nel database utilizzando $i->save() .
        $i->save();
    }
}





//  !!!!! Assicuriamoci di aver chiamato il file json, che vi forniremo noi, esattamente in questa maniera