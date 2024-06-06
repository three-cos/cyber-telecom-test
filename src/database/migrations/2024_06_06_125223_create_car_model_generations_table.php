<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('car_model_generations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('model_id')
                ->references('id')
                ->on('car_models')
                ->cascadeOnDelete();

            /** @todo might be indexed in future */
            $table->string('market');

            $table->string('name');
            $table->string('human_readable_name');
            $table->string('external_id')->unique();
            $table->string('time');
            $table->string('image')->nullable();
            $table->string('url');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_model_generations');
    }
};
