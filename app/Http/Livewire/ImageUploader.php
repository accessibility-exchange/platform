<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

class ImageUploader extends Component
{
    use WithFileUploads;

    public $name = false;
    public $image = null;
    public $alt = false;

    protected function rules()
    {
        return [
            'image' => 'image|dimensions:min_width=200,min_height=200',
        ];
    }

    public function updated()
    {
        $this->validate();
    }

    public function remove()
    {
        $this->image = null;
    }

    public function render()
    {
        return view('livewire.image-uploader');
    }
}
