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
        Schema::create('lists', function (Blueprint $table) {
            $table->id();                                       // PK
            $table->foreignId('id_user')                        // FK → users(id)
                    ->constrained('users')
                    ->cascadeOnDelete();
            $table->string('name');                             // nombre de la lista
            $table->timestamps();                               // created_at / updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lists');
    }
};
