<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('list_categories', function (Blueprint $table) {
            $table->id('id_list_categories');                 // PK (personalizada)

            // FK → lists(id)
            $table->foreignId('id_list')
                  ->constrained('lists')                      // por defecto referencia 'id'
                    ->cascadeOnDelete()
                    ->cascadeOnUpdate();

            // FK → categories(id_category)  (PK personalizada)
            $table->foreignId('id_category')
                  ->constrained('categories', 'id_category')  // columna PK
                    ->cascadeOnDelete()
                    ->cascadeOnUpdate();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('list_categories');
    }
};
