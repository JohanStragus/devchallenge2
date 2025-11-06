<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('list_products', function (Blueprint $table) {
            $table->id('id_list_product');                        // PK personalizada

            // FK → lists(id)
            $table->foreignId('id_list')
                    ->constrained('lists')
                    ->cascadeOnDelete()
                    ->cascadeOnUpdate();

            // FK → products(id_product)
            $table->foreignId('id_product')
                    ->constrained('products', 'id_product')
                    ->cascadeOnDelete()
                    ->cascadeOnUpdate();

            $table->timestamps();                                 // created_at / updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('list_products');
    }
};
