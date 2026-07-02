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
            $table->tinyInteger('show')->nullable();
            $table->foreignId('template')->nullable()->constrained('templates');
            $table->string('url')->nullable();
            $table->string('addr')->nullable();
            $table->string('name')->nullable();
            $table->date('date')->nullable();
            $table->string('img')->nullable();
            $table->text('text')->nullable();
            $table->text('options')->nullable();
            $table->string('seo_title')->nullable();
            $table->string('seo_description')->nullable();
            $table->string('seo_keywords')->nullable();
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
