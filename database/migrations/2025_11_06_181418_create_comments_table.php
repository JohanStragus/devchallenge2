<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id('id_comment');                            // PK personalizada

            // FK → lists(id)
            $table->foreignId('id_list')
                    ->constrained('lists')
                    ->cascadeOnDelete()
                    ->cascadeOnUpdate();

            // FK → users(id)
            $table->foreignId('id_user')
                    ->constrained('users')
                    ->cascadeOnDelete()
                    ->cascadeOnUpdate();

            $table->text('content');                             // contenido del comentario
            $table->timestamps();                                // created_at / updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
