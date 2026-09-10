<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('price_items', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('description', 500)->nullable();
            $table->decimal('price', 12, 2)->nullable();
            $table->string('price_note', 50)->nullable();
            $table->string('group', 80)->nullable()->index();
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('price_items');
    }
};
