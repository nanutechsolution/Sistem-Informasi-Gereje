<?php

namespace Database\Seeders;

use App\Models\Budget;
use App\Models\FiscalYear;
use App\Models\RefAccount;
use App\Models\RefBudgetPost;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class FinanceSeeder2 extends Seeder
{
    public function run(): void
    {
        // 1. SETUP TAHUN ANGGARAN 2026
        $fiscalYear = FiscalYear::firstOrCreate(
            ['tahun' => '2026'],
            [
                'uuid' => Str::uuid(),
                'is_active' => true,
                'keterangan' => 'RAPB Jemaat Reda 2026'
            ]
        );

        // 2. SETUP AKUN KAS (DOMPET)
        $accounts = [
            ['nama' => 'Kas Jemaat (Umum)', 'jenis' => 'kas_tunai'],
            ['nama' => 'Bank (Rekening Gereja)', 'jenis' => 'bank'],
            ['nama' => 'Kas Pembangunan', 'jenis' => 'kas_tunai'],
        ];

        foreach ($accounts as $acc) {
            RefAccount::firstOrCreate(
                ['nama' => $acc['nama']],
                array_merge($acc, ['uuid' => Str::uuid()])
            );
        }

        $kasUmum = RefAccount::where('nama', 'Kas Jemaat (Umum)')->first();
        $kasBangun = RefAccount::where('nama', 'Kas Pembangunan')->first();
        $admin = User::first();

        // 3. INPUT POS ANGGARAN & TARGET RAPB
        // --- A. PENDAPATAN (Kode 1) ---
        $parentPendapatan = RefBudgetPost::firstOrCreate(
            ['kode' => '1'],
            ['uuid' => Str::uuid(), 'nama' => 'PENDAPATAN', 'jenis' => 'pemasukan']
        );

        $pendapatan = [
            '1' => ['Mingguan', 35000000],
            '2' => ['PKS', 25000000],
            '11' => ['Lelang Umum', 10000000],
            '18' => ['SMKA', 1000000],
        ];

        foreach ($pendapatan as $no => $data) {
            $this->createPostAndBudget($fiscalYear, $parentPendapatan, '1.' . $no, $data[0], 'pemasukan', $data[1]);
        }

        // POS KHUSUS PEMBANGUNAN
        $catPembangunanIn = $this->createCategory('1.21', 'Pembangunan Jemaat', $parentPendapatan);
        $posLelangBangun = $this->createPostAndBudget($fiscalYear, $catPembangunanIn, '1.21.1', 'Lelang Pembangunan', 'pemasukan', 50000000);

        // --- B. BELANJA (Kode 2) ---
        $parentBelanja = RefBudgetPost::firstOrCreate(
            ['kode' => '2'],
            ['uuid' => Str::uuid(), 'nama' => 'BELANJA', 'jenis' => 'pengeluaran']
        );

        $catBelanjaUmum = $this->createCategory('2.5', 'Belanja Lain-Lain', $parentBelanja);
        $posListrik = $this->createPostAndBudget($fiscalYear, $catBelanjaUmum, '2.5.23', 'Listrik', 'pengeluaran', 1320000);

        $catPembangunanOut = $this->createCategory('2.6', 'Belanja Pembangunan', $parentBelanja);
        $posMaterial = $this->createPostAndBudget($fiscalYear, $catPembangunanOut, '2.6.1', 'Pembelian Material Bangunan', 'pengeluaran', 100000000);

        // 4. DATA TRANSAKSI SAMPEL (Agar Dashboard Langsung Terisi)
        $now = Carbon::now();

        // Sampel Pemasukan Umum
        Transaction::create([
            'uuid' => Str::uuid(),
            'fiscal_year_id' => $fiscalYear->id,
            'tanggal' => $now->subDays(2),
            'jenis' => 'masuk',
            'ref_account_id' => $kasUmum->id,
            'ref_budget_post_id' => RefBudgetPost::where('kode', '1.1')->first()->id,
            'nominal' => 2500000,
            'keterangan' => 'Kolekte Ibadah Minggu Raya',
            'user_id' => $admin->id
        ]);

        // Sampel PKS (Dua Keluarga)
        Transaction::create([
            'uuid' => Str::uuid(),
            'fiscal_year_id' => $fiscalYear->id,
            'tanggal' => $now->subDay(),
            'jenis' => 'masuk',
            'ref_account_id' => $kasUmum->id,
            'ref_budget_post_id' => RefBudgetPost::where('kode', '1.2')->first()->id,
            'nominal' => 150000,
            'keterangan' => 'PKS Kel. Bapak Yohanes',
            'user_id' => $admin->id
        ]);

        // Sampel Lelang Pembangunan
        Transaction::create([
            'uuid' => Str::uuid(),
            'fiscal_year_id' => $fiscalYear->id,
            'tanggal' => $now,
            'jenis' => 'masuk',
            'ref_account_id' => $kasBangun->id, // MASUK KE KAS PEMBANGUNAN
            'ref_budget_post_id' => $posLelangBangun->id,
            'nominal' => 5000000,
            'keterangan' => 'Lunas Lelang 1 Unit Lemari (Donatur A)',
            'user_id' => $admin->id
        ]);

        // Sampel Pengeluaran (Bayar Listrik)
        Transaction::create([
            'uuid' => Str::uuid(),
            'fiscal_year_id' => $fiscalYear->id,
            'tanggal' => $now,
            'jenis' => 'keluar',
            'ref_account_id' => $kasUmum->id,
            'ref_budget_post_id' => $posListrik->id,
            'nominal' => 250000,
            'keterangan' => 'Bayar Tagihan Listrik Gereja Jan 2026',
            'user_id' => $admin->id
        ]);
    }

    private function createCategory($kode, $nama, $parent)
    {
        return RefBudgetPost::firstOrCreate(
            ['kode' => $kode],
            ['uuid' => Str::uuid(), 'nama' => $nama, 'jenis' => $parent->jenis, 'parent_id' => $parent->id]
        );
    }

    private function createPostAndBudget($fiscalYear, $parent, $kode, $nama, $jenis, $target)
    {
        $post = RefBudgetPost::firstOrCreate(
            ['kode' => $kode],
            ['uuid' => Str::uuid(), 'nama' => $nama, 'jenis' => $jenis, 'parent_id' => $parent->id]
        );

        Budget::updateOrCreate(
            ['fiscal_year_id' => $fiscalYear->id, 'ref_budget_post_id' => $post->id],
            ['uuid' => Str::uuid(), 'nominal_target' => $target]
        );

        return $post;
    }
}