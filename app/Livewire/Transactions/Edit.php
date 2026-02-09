<?php

namespace App\Livewire\Transactions;

use App\Models\Transaction;
use App\Models\RefAccount;
use App\Models\RefBudgetPost;
use Livewire\Component;

class Edit extends Component
{
    public Transaction $transaction;

    // Properti Form
    public $jenis;
    public $tanggal;
    public $ref_account_id;
    public $ref_budget_post_id;
    public $nominal;
    public $keterangan;
    public $target_account_id; // Untuk transfer

    protected $messages = [
        'ref_account_id.required' => 'Akun Kas/Bank wajib dipilih.',
        'nominal.required' => 'Nominal wajib diisi.',
    ];

    public function mount(Transaction $transaction)
    {
        $this->transaction = $transaction;

        $this->jenis = $transaction->jenis;
        $this->tanggal = $transaction->tanggal->format('Y-m-d');
        $this->ref_account_id = $transaction->ref_account_id;
        $this->ref_budget_post_id = $transaction->ref_budget_post_id;
        $this->nominal = number_format($transaction->nominal, 0, '', ''); // Format tanpa desimal
        $this->keterangan = $transaction->keterangan;

        // Jika Transfer, coba cari akun pasangannya
        if ($transaction->jenis === 'pindah_buku' && $transaction->relatedTransaction) {
            $this->target_account_id = $transaction->relatedTransaction->ref_account_id;
        }
    }

    public function update()
    {
        $cleanNominal = (float) str_replace('.', '', $this->nominal);

        $rules = [
            'tanggal' => 'required|date',
            'ref_account_id' => 'required|exists:ref_accounts,id',
            'nominal' => 'required',
            'keterangan' => 'required|string|max:255',
        ];

        if ($this->jenis !== 'pindah_buku') {
            $rules['ref_budget_post_id'] = 'required|exists:ref_budget_posts,id';
        } else {
            $rules['target_account_id'] = 'required|exists:ref_accounts,id|different:ref_account_id';
        }

        $this->validate($rules);

        // 1. Update Transaksi Utama
        $this->transaction->update([
            'tanggal' => $this->tanggal,
            'ref_account_id' => $this->ref_account_id,
            'ref_budget_post_id' => $this->jenis === 'pindah_buku' ? null : $this->ref_budget_post_id,
            'nominal' => $cleanNominal,
            'keterangan' => $this->keterangan,
        ]);

        // 2. Jika Transfer, Update Pasangannya juga
        if ($this->jenis === 'pindah_buku' && $this->transaction->relatedTransaction) {
            $this->transaction->relatedTransaction->update([
                'tanggal' => $this->tanggal,
                'ref_account_id' => $this->target_account_id, // Update akun tujuan
                'nominal' => $cleanNominal,
                // Keterangan pasangan biasanya otomatis, kita biarkan atau update sedikit
                'keterangan' => 'Transfer Masuk dari ' . $this->transaction->account->nama . ': ' . $this->keterangan,
            ]);
        }

        $this->dispatch('notify', message: 'Transaksi berhasil diperbarui!', type: 'success');
        return redirect()->route('transactions.index');
    }

    public function render()
    {
        $postsQuery = RefBudgetPost::query();
        if ($this->jenis === 'masuk') {
            $postsQuery->where('jenis', 'pemasukan');
        } elseif ($this->jenis === 'keluar') {
            $postsQuery->where('jenis', 'pengeluaran');
        }

        return view('livewire.transactions.edit', [
            'accounts' => RefAccount::where('is_active', true)->get(),
            'budgetPosts' => $postsQuery->orderBy('kode')->get(),
        ]);
    }
}
