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
        Schema::create('forms', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('team_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('hash')->unique();
            $table->json('subscriber_groups'); // Array de IDs dos grupos
            $table->json('fields'); // Campos customizados do formulário
            $table->string('referral')->nullable(); // Campo de referência
            $table->longText('content')->nullable(); // Conteúdo/descrição do formulário
            $table->boolean('is_active')->default(true);
            $table->timestamp('expires_at')->nullable(); // Data de expiração do formulário
            $table->timestamps();

            $table->index(['team_id', 'is_active']);
            $table->index('hash');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forms');
    }
};
