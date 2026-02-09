<?php

namespace App\Livewire\Transactions;

use App\Models\Transaction;
use App\Models\RefAccount;
use App\Models\RefBudgetPost;
use App\Models\FiscalYear;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Create extends Component
{
    // Properti Form
    public $jenis = 'masuk'; // Default
    public $tanggal;
    public $ref_account_id;
    public $ref_budget_post_id;
    public $nominal;
    public $keterangan;
    
    // Properti Khusus Transfer (Pindah Buku)
    public $target_account_id; 

    protected $messages = [
        'ref_account_id.required' => 'Akun Kas/Bank wajib dipilih.',
        'ref_budget_post_id.required' => 'Pos Anggaran wajib dipilih.',
        'nominal.required' => 'Nominal wajib diisi.',
        'nominal.min' => 'Nominal tidak boleh nol.',
        'keterangan.required' => 'Keterangan transaksi wajib diisi.',
        'target_account_id.required' => 'Akun tujuan transfer wajib dipilih.',
        'target_account_id.different' => 'Akun tujuan tidak boleh sama dengan akun asal.',
    ];

    public function mount()
    {
        // Tangkap parameter 'jenis' dari URL (jika ada)
        $this->jenis = request()->query('jenis', 'masuk');
        
        // Set tanggal hari ini
        $this->tanggal = date('Y-m-d');
    }

    public function save()
    {
        // 1. Bersihkan format nominal (hapus titik ribuan)
        // Contoh input: "1.000.000" -> "1000000"
        $cleanNominal = (float) str_replace('.', '', $this->nominal);

        // 2. Validasi Dasar
        $rules = [
            'tanggal' => 'required|date',
            'jenis' => 'required|in:masuk,keluar,pindah_buku',
            'ref_account_id' => 'required|exists:ref_accounts,id',
            'nominal' => 'required', // Validasi angka dilakukan setelah cleaning
            'keterangan' => 'required|string|max:255',
        ];

        // Validasi Tambahan berdasarkan Jenis
        if ($this->jenis !== 'pindah_buku') {
            $rules['ref_budget_post_id'] = 'required|exists:ref_budget_posts,id';
        } else {
            $rules['target_account_id'] = 'required|exists:ref_accounts,id|different:ref_account_id';
        }

        $this->validate($rules);

        // Validasi Nominal Manual
        if ($cleanNominal <= 0) {
            $this->addError('nominal', 'Nominal harus lebih besar dari 0.');
            return;
        }

        // 3. Ambil Tahun Anggaran Aktif
        $fiscalYear = FiscalYear::active();
        if (!$fiscalYear) {
            $this->dispatch('notify', message: 'ERROR: Belum ada Tahun Anggaran yang aktif. Hubungi Admin.', type: 'error');
            return;
        }

        // 4. Simpan Transaksi Utama
        $trx = Transaction::create([
            'fiscal_year_id' => $fiscalYear->id,
            'tanggal' => $this->tanggal,
            'jenis' => $this->jenis,
            'ref_account_id' => $this->ref_account_id,
            'ref_budget_post_id' => $this->jenis === 'pindah_buku' ? null : $this->ref_budget_post_id,
            'nominal' => $cleanNominal,
            'keterangan' => $this->keterangan,
            'user_id' => Auth::id(),
        ]);

        // 5. Khusus Transfer: Buat Transaksi Pasangan (Otomatis Masuk ke Tujuan)
        if ($this->jenis === 'pindah_buku') {
            Transaction::create([
                'fiscal_year_id' => $fiscalYear->id,
                'tanggal' => $this->tanggal,
                'jenis' => 'masuk', // Uang masuk ke akun tujuan
                'ref_account_id' => $this->target_account_id, // Akun Tujuan
                'ref_budget_post_id' => null, // Transfer tidak punya pos anggaran
                'nominal' => $cleanNominal,
                'keterangan' => 'Transfer Masuk dari ' . $trx->account->nama . ': ' . $this->keterangan,
                'user_id' => Auth::id(),
                'related_transaction_id' => $trx->id, // Link ke transaksi keluar
            ]);
            
            // Update link balik
            $trx->update(['related_transaction_id' => $trx->id + 1]); // Asumsi ID urut, idealnya ambil ID dr create kedua
        }

        $this->dispatch('notify', message: 'Transaksi berhasil disimpan!', type: 'success');
        return redirect()->route('transactions.index');
    }

    public function render()
    {
        // Filter Pos Anggaran sesuai Jenis Transaksi
        $postsQuery = RefBudgetPost::query();
        if ($this->jenis === 'masuk') {
            $postsQuery->where('jenis', 'pemasukan');
        } elseif ($this->jenis === 'keluar') {
            $postsQuery->where('jenis', 'pengeluaran');
        }
        
        return view('livewire.transactions.create', [
            'accounts' => RefAccount::where('is_active', true)->get(),
            'budgetPosts' => $postsQuery->orderBy('kode')->get(),
        ]);
    }
}
