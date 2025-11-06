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
        Schema::create('list_users', function (Blueprint $table) {
            $table->id('id_list_users');                       // PK
            $table->foreignId('id_list')                       // FK → lists(id)
                    ->constrained('lists')
                    ->cascadeOnDelete()
                    ->cascadeOnUpdate();
            $table->foreignId('id_user')                       // FK → users(id)
                    ->constrained('users')
                    ->cascadeOnDelete()
                    ->cascadeOnUpdate();
            $table->string('role')->default('editor');          // 'owner' | 'editor'
            $table->timestamps();                              // created_at / updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('list_users');
    }
};
