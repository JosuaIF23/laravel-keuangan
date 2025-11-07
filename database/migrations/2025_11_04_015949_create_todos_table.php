
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
        Schema::create('keuangans', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->string('judul');                  // misalnya: "Bayar Kost"
            $table->text('keterangan')->nullable();   // penjelasan transaksi
            $table->enum('tipe', ['pemasukan', 'pengeluaran']); // tipe transaksi
            $table->decimal('jumlah', 15, 2);         // jumlah uang
            $table->date('tanggal');                  // tanggal transaksi
            $table->string('bukti')->nullable();      // upload bukti (opsional)
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('todos');
    }
};
