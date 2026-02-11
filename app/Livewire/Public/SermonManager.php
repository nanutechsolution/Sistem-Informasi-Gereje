<?php

namespace App\Livewire\Public;

use App\Models\Sermon;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class SermonManager extends Component
{
    use WithPagination;

    public $isModalOpen = false;
    public $editId = null;

    // Form
    public $judul, $pengkhotbah, $youtube_url, $tanggal, $ringkasan;

    protected $rules = [
        'judul' => 'required',
        'pengkhotbah' => 'required',
        'youtube_url' => 'required|url',
        'tanggal' => 'required|date',
    ];

    public function mount()
    {
        $this->tanggal = date('Y-m-d');
    }

    // Ekstrak ID YouTube dari URL (Support Short & Long URL)
    private function extractYoutubeId($url)
    {
        $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i';
        if (preg_match($pattern, $url, $match)) {
            return $match[1];
        }
        return null;
    }

    public function create()
    {
        $this->reset(['editId', 'judul', 'pengkhotbah', 'youtube_url', 'ringkasan']);
        $this->tanggal = date('Y-m-d');
        $this->isModalOpen = true;
    }

    public function save()
    {
        $this->validate();

        $videoId = $this->extractYoutubeId($this->youtube_url);

        if (!$videoId) {
            $this->addError('youtube_url', 'Link YouTube tidak valid.');
            return;
        }

        Sermon::updateOrCreate(['id' => $this->editId], [
            'uuid' => $this->editId ? Sermon::find($this->editId)->uuid : (string) Str::uuid(),
            'judul' => $this->judul,
            'pengkhotbah' => $this->pengkhotbah,
            'youtube_url' => $this->youtube_url,
            'youtube_id' => $videoId,
            'tanggal' => $this->tanggal,
            'ringkasan' => $this->ringkasan,
        ]);

        $this->dispatch('notify', message: 'Video khotbah berhasil diterbitkan.', type: 'success');
        $this->isModalOpen = false;
    }

    public function delete($id)
    {
        Sermon::findOrFail($id)->delete();
        $this->dispatch('notify', message: 'Video dihapus.', type: 'success');
    }

    public function render()
    {
        return view('livewire.public.sermon-manager', [
            'sermons' => Sermon::latest('tanggal')->paginate(9)
        ]);
    }
}