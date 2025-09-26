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
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->string('domain')->nullable();
            $table->string('subdomain')->nullable();
            $table->string('timezone')->nullable();
            $table->string('currency')->nullable();
            $table->string('language')->nullable();
            $table->enum('category', [
                'AI',
                'Developer',
                'Marketing',
                'Game Development',
                'Journalist',
                'Writer',
                'Travel',
                'E-commerce',
                'Finance',
                'Healthcare',
                'Education',
                'Consulting',
                'Design',
                'Photography',
                'Music',
                'Sports',
                'Food & Beverage',
                'Real Estate',
                'Legal',
                'Non-profit',
                'Other'
            ])->nullable();
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
