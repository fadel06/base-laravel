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
        Schema::create('opds', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('level'); // 1 = Dinas, 2 = UPTD
            $table->uuid('parent_id')->nullable(); // UPTD punya parent Dinas
            $table->string('code')->unique();
            $table->string('name');
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('head_name')->nullable(); // Nama Kepala OPD
            $table->string('head_nip')->nullable(); // NIP Kepala OPD
            $table->timestamps();

            // Foreign key
            $table->foreign('parent_id')
                ->references('id')
                ->on('opds')
                ->onDelete('cascade');

            // Indexes
            $table->index('level');
            $table->index('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opds');
    }
};
