<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recipe_ingredients', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('recipe_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ingredient_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name', 120);
            $table->string('original_name')->nullable();
            $table->string('amount', 40)->nullable();
            $table->string('unit', 40)->nullable();
            $table->string('notes')->nullable();
            $table->unsignedSmallInteger('position');
            $table->timestamps();

            $table->index(['recipe_id', 'position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recipe_ingredients');
    }
};
