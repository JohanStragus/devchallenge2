<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id('id_product');                            // PK
            $table->foreignId('id_category')                     // FK → categories(id_category)
                    ->constrained('categories', 'id_category')
                    ->cascadeOnDelete()
                    ->cascadeOnUpdate();
            $table->string('name');                             // nombre del producto
            $table->text('details')->nullable();               // detalles del producto
            $table->boolean('completed')->default(false);     // marcado como completado
            $table->timestamps();                            // created_at / updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
