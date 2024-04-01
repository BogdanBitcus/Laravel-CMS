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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->integer('parent')->nullable();
            $table->integer('position')->nullable();
            $table->tinyInteger('show')->default('0');
            $table->foreignId('type')->nullable()->constrained('types');
            $table->string('url')->nullable();
            $table->string('addr')->nullable();
            $table->json('name')->nullable();
            $table->date('date')->nullable();
            $table->json('img')->nullable();
            $table->json('text')->nullable();
            $table->json('seo_title')->nullable();
            $table->json('seo_description')->nullable();
            $table->json('seo_keywords')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
