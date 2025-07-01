<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Image;
use Google\Cloud\Vision\V1\ImageAnnotatorClient;


class GoogleVisionLabelImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $article_image_id;

    public function __construct($article_image_id)
    {
        $this->article_image_id = $article_image_id;
    }

    public function handle(): void
    {
        $i = Image::find($this->article_image_id);
        if (!$i) {
            return;
        }

        $image = file_get_contents(storage_path('app/public/' . $i->path));

        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . base_path('google_credential.json'));

        // A riga 24, l'oggetto ImageAnnotatorClient salvato in $imageAnnotator chiama il metodo labelDetection del client, passando il
        // contenuto dell'immagine ( $image ) come parametro. Questo metodo etichetta l'immagine identificando oggetti e scene al suo
        // interno. La risposta del metodo viene memorizzata nella variabile $response .
        $imageAnnotator = new ImageAnnotatorClient();
        $response = $imageAnnotator->labelDetection($image);

        // A riga 25, recuperiamo l'array di etichette ( LabelAnnotations ) dalla risposta utilizzando $labels = $response-
        // >getLabelAnnotations() .
        $labels = $response->getLabelAnnotations();

        // A riga 27, se sono presenti etichette ( if ($labels) ) viene creato un array vuoto $result per memorizzare le descrizioni delle
        // etichette.
        if ($labels) {
            $result = [];

            // A riga 29, si cicla attraverso l'array delle etichette ( foreach ) e per ogni etichetta ( $label ) si estrae la descrizione
            // ( getDescription() ) e la si aggiunge all'array dei risultati ( $result[] ).
            foreach ($labels as $label) {
                $result[] = $label->getDescription();
            }

            // A riga 33, aggiorniamo l'istanza del modello Image ( $i ) con l'array dei risultati ( $i->labels = $result ) che contiene le
            // descrizioni delle etichette.
            $i->labels = $result;

            // Infine, salviamo l'immagine aggiornata nel database utilizzando $i->save() e viene chiuso il client utilizzando $imageAnnotator->close()
            $i->save();
        }

        $imageAnnotator->close();
    }
}
