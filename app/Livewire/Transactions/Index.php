<?php

namespace App\Livewire\Transactions;

use App\Models\Transaction;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $filterJenis = ''; // masuk, keluar, pindah_buku

    public function delete($id)
    {
        // Hanya Admin & Bendahara yang boleh hapus (Role perlu disesuaikan nanti)
        if (!in_array(Auth::user()->role, ['admin', 'bendahara'])) {
            $this->dispatch('notify', message: 'AKSES DITOLAK: Anda tidak berhak menghapus transaksi.', type: 'error');
            return;
        }

        $trx = Transaction::find($id);
        if ($trx) {
            $trx->delete();
            $this->dispatch('notify', message: 'Transaksi berhasil dihapus.', type: 'success');
        }
    }

    public function render()
    {
        $query = Transaction::with(['account', 'budgetPost', 'user']) // Eager load agar cepat
            ->latest('tanggal')
            ->latest('created_at'); // Urutan sekunder

        if ($this->search) {
            $query->where('keterangan', 'like', '%' . $this->search . '%')
                ->orWhere('nominal', 'like', '%' . $this->search . '%');
        }

        if ($this->filterJenis) {
            $query->where('jenis', $this->filterJenis);
        }

        return view('livewire.transactions.index', [
            'transactions' => $query->paginate(15)
        ]);
    }
}
