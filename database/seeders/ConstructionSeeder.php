<?php

namespace Database\Seeders;

use App\Models\RefAccount;
use App\Models\RefBudgetPost;
use App\Models\FiscalYear;
use App\Models\OpeningBalance;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ConstructionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. BUAT DOMPET KHUSUS PEMBANGUNAN
        $akunBangun = RefAccount::firstOrCreate(
            ['nama' => 'Kas Pembangunan'],
            [
                'uuid' => Str::uuid(),
                'jenis' => 'kas_tunai', // Atau 'bank' jika ada rekeningnya
                'is_active' => true
            ]
        );

        // 2. SET SALDO AWAL (Sesuai Dokumen: Rp 745.000)
        $fiscalYear = FiscalYear::active();
        if ($fiscalYear) {
            OpeningBalance::updateOrCreate(
                [
                    'fiscal_year_id' => $fiscalYear->id,
                    'ref_account_id' => $akunBangun->id,
                ],
                [
                    'uuid' => Str::uuid(),
                    'nominal' => 0
                ]
            );
        }

        // 3. BUAT POS ANGGARAN PEMBANGUNAN (KODE 3)
        // Kita pisah dari kode 1 (Pendapatan Rutin) dan 2 (Belanja Rutin)
        // Kode 3 khusus untuk Proyek Pembangunan
        // INDUK
        $parent = RefBudgetPost::firstOrCreate(
            ['kode' => '3'],
            ['uuid' => Str::uuid(), 'nama' => 'DANA PEMBANGUNAN', 'jenis' => 'pengeluaran']
        );

        // KATEGORI PEMASUKAN PEMBANGUNAN (3.1)
        $catMasuk = $this->createCategory('3.1', 'Penerimaan Pembangunan', 'pemasukan', $parent);
        $this->createPost('3.1.1', 'Lelang Pembangunan', 'pemasukan', $catMasuk);
        $this->createPost('3.1.2', 'Sumbangan Material / Natura', 'pemasukan', $catMasuk);
        $this->createPost('3.1.3', 'Janji Iman', 'pemasukan', $catMasuk);

        // KATEGORI PENGELUARAN PEMBANGUNAN (3.2)
        $catKeluar = $this->createCategory('3.2', 'Belanja Pembangunan', 'pengeluaran', $parent);
        
        // Kita kelompokkan item dari foto agar rapi di laporan:
        
        // A. Material Utama (Semen, Besi, Pasir, Seng, Triplek)
        $this->createPost('3.2.1', 'Material Bangunan (Semen, Pasir, Kayu)', 'pengeluaran', $catKeluar);
        
        // B. Alat & Perlengkapan (Paku, Benang, Amplas, Kunci, Hensel)
        $this->createPost('3.2.2', 'Alat & Perlengkapan Tukang', 'pengeluaran', $catKeluar);
        
        // C. Elektrikan (Kabel, Terminal, Colokan)
        $this->createPost('3.2.3', 'Instalasi Listrik', 'pengeluaran', $catKeluar);
        
        // D. Operasional (Konsumsi, Transport)
        $this->createPost('3.2.4', 'Konsumsi Kerja & Rapat', 'pengeluaran', $catKeluar);
        $this->createPost('3.2.5', 'Transportasi Material', 'pengeluaran', $catKeluar);
        
        // E. Jasa (Upah Tukang - jika ada di masa depan)
        $this->createPost('3.2.6', 'Upah Tukang', 'pengeluaran', $catKeluar);
        
        // F. Lain-lain (Biaya Berkas, Admin)
        $this->createPost('3.2.7', 'Administrasi Pembangunan', 'pengeluaran', $catKeluar);
    }

    private function createCategory($kode, $nama, $jenis, $parent)
    {
        return RefBudgetPost::firstOrCreate(
            ['kode' => $kode],
            ['uuid' => Str::uuid(), 'nama' => $nama, 'jenis' => $jenis, 'parent_id' => $parent->id]
        );
    }

    private function createPost($kode, $nama, $jenis, $parent)
    {
        RefBudgetPost::firstOrCreate(
            ['kode' => $kode],
            ['uuid' => Str::uuid(), 'nama' => $nama, 'jenis' => $jenis, 'parent_id' => $parent->id]
        );
    }
}