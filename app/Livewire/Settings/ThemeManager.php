<?php

namespace App\Livewire\Settings;

use App\Models\ChurchSetting;
use Livewire\Component;

class ThemeManager extends Component
{
    public $color_primary, $color_accent, $color_background, $color_sidebar;
    public $appearance_mode, $ui_rounded;

    public function mount()
    {
        $setting = ChurchSetting::current();
        $this->color_primary = $setting->color_primary;
        $this->color_accent = $setting->color_accent;
        $this->color_background = $setting->color_background;
        $this->color_sidebar = $setting->color_sidebar;
        $this->appearance_mode = $setting->appearance_mode;
        $this->ui_rounded = $setting->ui_rounded;
    }

    public function save()
    {
        $setting = ChurchSetting::first() ?? new ChurchSetting();
        $setting->fill([
            'color_primary' => $this->color_primary,
            'color_accent' => $this->color_accent,
            'color_background' => $this->color_background,
            'color_sidebar' => $this->color_sidebar,
            'appearance_mode' => $this->appearance_mode,
            'ui_rounded' => $this->ui_rounded,
        ])->save();

        $this->dispatch('notify', message: 'Tema berhasil diperbarui secara global!', type: 'success');
    }

    public function render()
    {
        return view('livewire.settings.theme-manager');
    }
}