<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inventaris', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategori_inventaris')->onDelete('cascade');
            $table->string('nama_barang');
            $table->string('kode_inventaris')->unique();
            $table->integer('jumlah')->default(1);
            $table->enum('kondisi', ['Baik', 'Rusak', 'Hilang', 'Perlu Perbaikan']);
            $table->string('lokasi_penempatan');
            $table->date('tanggal_masuk');
            $table->string('sumber_dana');
            $table->decimal('harga_perolehan', 15, 2)->default(0);
            $table->integer('masa_pakai_tahun')->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventaris');
    }
};
