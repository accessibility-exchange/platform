<?php

namespace App\Livewire;

use Livewire\Component;

class Prompt extends Component
{
    public mixed $model;

    public string $prompt;

    public int $level;

    public string $heading;

    public string $description;

    public string $actionLabel;

    public string $actionUrl;

    public ?string $interpretationName;

    public ?string $interpretationNameSpace;

    public function mount(mixed $model, string $prompt, string $heading, string $description, string $actionLabel, string $actionUrl, int $level = 3, ?string $interpretationName = null, ?string $interpretationNameSpace = null)
    {
        $this->model = $model;
        $this->prompt = $prompt;
        $this->level = $level;
        $this->heading = $heading;
        $this->description = $description;
        $this->actionLabel = $actionLabel;
        $this->actionUrl = $actionUrl;
        $this->interpretationName = $interpretationName;
        $this->interpretationNameSpace = $interpretationNameSpace;
    }

    public function render()
    {
        return view('livewire.prompt');
    }

    public function dismiss(): void
    {
        $this->model->prompts[$this->prompt] = now();
        $this->model->save();
    }
}
