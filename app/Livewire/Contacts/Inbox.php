<?php

namespace App\Livewire\Contacts;

use App\Models\ContactMessage;
use Livewire\Component;
use Livewire\WithPagination;

class Inbox extends Component
{
    use WithPagination;

    public $search = '';
    public $activeMessage = null;
    public $isModalOpen = false;

    public function openMessage($id)
    {
        $this->activeMessage = ContactMessage::findOrFail($id);
        
        // Tandai sudah dibaca jika belum
        if (!$this->activeMessage->is_read) {
            $this->activeMessage->update(['is_read' => true]);
        }
        
        $this->isModalOpen = true;
    }

    public function delete($id)
    {
        ContactMessage::findOrFail($id)->delete();
        $this->isModalOpen = false;
        $this->dispatch('notify', message: 'Pesan berhasil dihapus.', type: 'success');
    }

    public function render()
    {
        return view('livewire.contacts.inbox', [
            'messages' => ContactMessage::where('subjek', 'like', "%{$this->search}%")
                ->orWhere('nama', 'like', "%{$this->search}%")
                ->latest()
                ->paginate(10)
        ]);
    }
}