<?php

namespace App\Jobs;

use Illuminate\Foundation\Bus\Dispatchable;      // trait Dispatchable
use Illuminate\Bus\Queueable;                    // trait Queueable
use Illuminate\Contracts\Queue\ShouldQueue;      // interface ShouldQueue
use Illuminate\Queue\InteractsWithQueue;         // trait InteractsWithQueue
use Illuminate\Queue\SerializesModels;           // trait SerializesModels

// CLASSI IMPORTATE
use Spatie\Image\Enums\Fit;
use App\Models\Image;
use Spatie\Image\Image as SpatieImage;
use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use Spatie\Image\Enums\AlignPosition;
// CLASSI IMPORTATE FINE

class RemoveFaces implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $article_image_id;

    public function __construct($article_image_id)
    {
        $this->article_image_id = $article_image_id;
    }

    public function handle()
    {
        $i = Image::find($this->article_image_id);
        if (!$i) {
            return;
        }

        $srcPath = storage_path('app/public/' . $i->path);
        $image = file_get_contents($srcPath);

        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . base_path('google_credential.json'));

        $imageAnnotator = new ImageAnnotatorClient();

        // Fino a riga 26 , il codice è sostanzialmente identico ai due precedenti.
        // Analizziamo le differenze:

        // A riga 27, a partire dall'oggetto ImageAnnotatorClient , salvato in $response , facciamo partire faceDetection() sull’immagine, 
        // utilizzato per rilevare i volti.
        $response = $imageAnnotator->faceDetection($image);

        // A riga 28, sul risultato di questo metodo facciamo partire getFaceAnnotations . Questo metodo serve a recuperare informazioni sui
        // volti individuati nell'immagine. Restituisce un campo ripetuto contenente una lista di oggetti FaceAnnotation . Il risultato è quindi salvato
        // in $faces .
        $faces = $response->getFaceAnnotations();

        // A riga 30, il ciclo foreach vuoto foreach ($faces as $face) : stiamo iterando sui volti rilevati.
        // All'interno del ciclo, il codice estrae le coordinate dei vertici del bounding box (un modo di definire la posizione e le dimensioni di un
        // oggetto in un'immagine) che circonda ogni volto utilizzando $face->getBoundingPoly()->getVertices().
        foreach ($faces as $face) {
            $vertices = $face->getBoundingPoly()->getVertices();

            // A riga 33 creiamo un array $bounds per memorizzare le coordinate dei vertici.
            $bounds = [];

            foreach ($vertices as $vertex) {
                $bounds[] = [$vertex->getX(), $vertex->getY()];
            }

            // Righe 35-37, calcola la larghezza e l'altezza del bounding box del volto.
            $w = $bounds[2][0] - $bounds[0][0];
            $h = $bounds[2][1] - $bounds[0][1];

            // A riga 42, carichiamo quindi l'immagine utilizzando la libreria Spatie Image ( SpatieImage::load($srcPath) ) per poter effettuare la
            // censura dei volti.
            $image = SpatieImage::load($srcPath);

            // Da riga 44, a partire da questa immagine, utilizziamo il metodo watermark per sovrapporre a ogni volto rilevato una immagine di
            // censura da noi scelta. N.B.: dare il percorso giusto dell’immagine nel vostro progetto.
            $image->watermark(
                base_path('resources/img/smile.png'),
                AlignPosition::TopLeft,
                paddingX: $bounds[0][0],
                paddingY: $bounds[0][1],
                width: $w,
                height: $h,
                fit: Fit::Stretch
            );

            // paddingX e paddingY specificano lo spostamento orizzontale e verticale della sovrapposizione rispetto all'angolo superiore sinistro
            // del bounding box.
            // width e height definiscono la larghezza e l'altezza dell'immagine di sovrapposizione, forzata ad adattarsi alle dimensioni del
            // bounding box con fit: Fit::Stretch .

            // A riga 56, infine, salviamo l'immagine modificata con la sovrapposizione sovrascritta sul percorso originale utilizzando $image->save($srcPath).
            $image->save($srcPath);
        }

        $imageAnnotator->close();
    }
}



//---------------------------------  USER STORY 8 PUNTO 2-------------------------------------------------------------------------------------------------------------------------------------------------

// Fino a riga 26 , il codice è sostanzialmente identico ai due precedenti.
// Analizziamo le differenze:

// A riga 27, a partire dall'oggetto ImageAnnotatorClient , salvato in $response , facciamo partire faceDetection() sull’immagine, 
// utilizzato per rilevare i volti.

// A riga 28, sul risultato di questo metodo facciamo partire getFaceAnnotations . Questo metodo serve a recuperare informazioni sui
// volti individuati nell'immagine. Restituisce un campo ripetuto contenente una lista di oggetti FaceAnnotation . Il risultato è quindi salvato
// in $faces .

// A riga 30, il ciclo foreach vuoto foreach ($faces as $face) : stiamo iterando sui volti rilevati.
// All'interno del ciclo, il codice estrae le coordinate dei vertici del bounding box (un modo di definire la posizione e le dimensioni di un
// oggetto in un'immagine) che circonda ogni volto utilizzando $face->getBoundingPoly()->getVertices() .

// A riga 33 creiamo un array $bounds per memorizzare le coordinate dei vertici.

// Righe 35-37, calcola la larghezza e l'altezza del bounding box del volto.

// A riga 42, carichiamo quindi l'immagine utilizzando la libreria Spatie Image ( SpatieImage::load($srcPath) ) per poter effettuare la
// censura dei volti.

// Da riga 44, a partire da questa immagine, utilizziamo il metodo watermark per sovrapporre a ogni volto rilevato una immagine di
// censura da noi scelta. N.B.: dare il percorso giusto dell’immagine nel vostro progetto.

// paddingX e paddingY specificano lo spostamento orizzontale e verticale della sovrapposizione rispetto all'angolo superiore sinistro
// del bounding box.
// width e height definiscono la larghezza e l'altezza dell'immagine di sovrapposizione, forzata ad adattarsi alle dimensioni del
// bounding box con fit: Fit::Stretch .

// A riga 56, infine, salviamo l'immagine modificata con la sovrapposizione sovrascritta sul percorso originale utilizzando $image->save($srcPath) .
