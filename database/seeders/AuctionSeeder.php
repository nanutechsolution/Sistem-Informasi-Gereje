<?php

namespace Database\Seeders;

use App\Models\Auction;
use App\Models\AuctionEvent;
use App\Models\AuctionPayment;
use App\Models\Transaction;
use App\Models\FiscalYear;
use App\Models\RefAccount;
use App\Models\RefBudgetPost;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AuctionSeeder extends Seeder
{
    public function run(): void
    {
        $activeYear = FiscalYear::active();
        $admin = User::first();
        $kasUmum = RefAccount::where('nama', 'like', '%Umum%')->first();
        $kasBangun = RefAccount::where('nama', 'like', '%Pembangunan%')->first();
        
        // Ambil Pos Anggaran Lelang (Sesuai FinanceSeeder sebelumnya)
        $posLelangUmum = RefBudgetPost::where('kode', '1.11')->first();
        $posLelangBangun = RefBudgetPost::where('kode', '1.21.1')->first();

        // 1. EVENT: LELANG PEMBANGUNAN
        $eventBangun = AuctionEvent::create([
            'uuid' => Str::uuid(),
            'fiscal_year_id' => $activeYear->id,
            'nama_event' => 'Lelang Pembangunan Tahap 1',
            'tanggal_event' => now()->subDays(5),
            'tujuan_kas' => 'pembangunan',
        ]);

        // Item 1: Sudah Lunas (Meja Jati)
        $item1 = Auction::create([
            'uuid' => Str::uuid(),
            'auction_event_id' => $eventBangun->id,
            'nama_barang' => 'Meja Makan Jati',
            'donatur_nama' => 'Kel. Bpk. Albert',
            'pemenang_nama' => 'Ibu Maria',
            'harga_jadi' => 3000000,
        ]);

        $this->recordPayment($item1, 3000000, $kasBangun, $posLelangBangun, $activeYear, $admin, 'Pelunasan Langsung');

        // Item 2: Panjar (Kursi Sofa)
        $item2 = Auction::create([
            'uuid' => Str::uuid(),
            'auction_event_id' => $eventBangun->id,
            'nama_barang' => 'Kursi Sofa Minimalis',
            'donatur_nama' => 'Hamba Tuhan',
            'pemenang_nama' => 'Bpk. Yohanes',
            'harga_jadi' => 5000000,
        ]);

        $this->recordPayment($item2, 2000000, $kasBangun, $posLelangBangun, $activeYear, $admin, 'Panjar Tahap 1');

        // 2. EVENT: LELANG NATAL (KAS UMUM)
        $eventNatal = AuctionEvent::create([
            'uuid' => Str::uuid(),
            'fiscal_year_id' => $activeYear->id,
            'nama_event' => 'Lelang Hari Raya Natal 2026',
            'tanggal_event' => now(),
            'tujuan_kas' => 'umum',
        ]);

        // Item 3: Belum Bayar (Kue Natal)
        Auction::create([
            'uuid' => Str::uuid(),
            'auction_event_id' => $eventNatal->id,
            'nama_barang' => 'Paket Kue Kering Premium',
            'donatur_nama' => 'Ibu Sarah',
            'pemenang_nama' => 'Bpk. Petrus',
            'harga_jadi' => 500000,
            'total_terbayar_cache' => 0
        ]);
    }

    /**
     * Helper untuk simulasi pembayaran dan transaksi keuangan
     */
    private function recordPayment($item, $nominal, $account, $budgetPost, $year, $user, $ket)
    {
        $trx = Transaction::create([
            'uuid' => Str::uuid(),
            'fiscal_year_id' => $year->id,
            'tanggal' => now(),
            'jenis' => 'masuk',
            'ref_account_id' => $account->id,
            'ref_budget_post_id' => $budgetPost->id ?? null,
            'nominal' => $nominal,
            'keterangan' => "Lelang: {$item->nama_barang} ({$item->pemenang_nama})",
            'user_id' => $user->id,
        ]);

        AuctionPayment::create([
            'uuid' => Str::uuid(),
            'auction_id' => $item->id,
            'transaction_id' => $trx->id,
            'nominal' => $nominal,
            'tanggal_bayar' => now(),
            'keterangan' => $ket,
        ]);

        $item->update(['total_terbayar_cache' => $item->payments()->sum('nominal')]);
    }
}