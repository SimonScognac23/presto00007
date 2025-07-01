<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::table('images', function (Blueprint $table) {
        $table->text('labels')->nullable();
        $table->string('adult')->nullable();
        $table->string('spoof')->nullable();
        $table->string('medical')->nullable();
        $table->string('violence')->nullable();
        $table->string('racy')->nullable();
    });
}

public function down(): void
{
    Schema::table('images', function (Blueprint $table) {
        $table->dropColumn(['labels', 'adult', 'spoof', 'racy', 'medical', 'violence']);
    });
}

}; 


//    USER STORY 7 PUNTO 6

// Stiamo aggiungendo sei nuove colonne alla già esistente tabella images :
// labels : servirà a salvare le etichette rilevate dall’AI per descrivere l’immagine
// adult , spoof , racy , medical e violence : serviranno a salvare il grado di eventuale inappropriatezza dell’immagine in base a questi
// argomenti.

