<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bidang_dinas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('opd_id')->constrained('opds')->cascadeOnDelete();
            $table->foreignUuid('parent_id')->nullable()->constrained('bidang_dinas')->nullOnDelete();
            $table->unsignedTinyInteger('level')->default(1)->comment('1=Bidang, 2=Sub Bidang/Sub Bagian');
            $table->string('name');
            $table->string('abbreviation')->nullable()->comment('Singkatan');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bidang_dinas');
    }
};
