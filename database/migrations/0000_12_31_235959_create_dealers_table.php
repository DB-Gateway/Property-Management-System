<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dealers', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('source_no')->unique();
            $table->string('name');
            $table->text('address')->nullable();
            $table->string('area');
            $table->string('brand');
            $table->string('point_person_1')->nullable();
            $table->string('contact_1', 50)->nullable();
            $table->string('point_person_2')->nullable();
            $table->string('contact_2', 50)->nullable();
            $table->timestamps();

            $table->index(['area', 'brand']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dealers');
    }
};
