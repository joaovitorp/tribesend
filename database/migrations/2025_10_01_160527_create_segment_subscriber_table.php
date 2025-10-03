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
        Schema::create('segment_subscriber', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('segment_id');
            $table->uuid('subscriber_id');
            $table->timestamps();

            $table->foreign('segment_id')->references('id')->on('segments')->onDelete('cascade');
            $table->foreign('subscriber_id')->references('id')->on('subscribers')->onDelete('cascade');
            $table->unique(['segment_id', 'subscriber_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('segment_subscriber');
    }
};
