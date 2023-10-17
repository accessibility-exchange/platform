<?php

namespace App\Livewire;

use App\Models\Module;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ModuleContent extends Component
{
    public Module $module;

    public User $user;

    public bool $isStarted = false;

    public bool $isFinished = false;

    public function mount(Module $module)
    {
        $this->module = $module;
        $this->user = Auth::user();
        $moduleUser = $this->user->modules->find($module->id);
        $this->isStarted = (bool) $moduleUser?->getRelationValue('pivot')->started_content_at;
        $this->isFinished = (bool) $moduleUser?->getRelationValue('pivot')->finished_content_at;
    }

    protected $listeners = ['onPlayerEnd', 'onPlayerStart'];

    public function onPlayerEnd()
    {
        if (! $this->isFinished) {
            $this->user->modules()->updateExistingPivot(
                $this->module->id, ['finished_content_at' => now()]
            );
            $this->isFinished = true;
        }
    }

    public function onPlayerStart()
    {
        if (! $this->isStarted) {
            $course = $this->module->course;
            if (! ($this->user->courses->find($course->id))) {
                $this->user->courses()->attach(
                    $course->id, ['started_at' => now()]
                );
            }
            $this->user->modules()->attach(
                $this->module->id, ['started_content_at' => now()]
            );
            $this->isStarted = true;
        }
    }

    public function render()
    {
        return view('livewire.module-content')->layout('layouts.app', ['bodyClass' => 'page', 'headerClass' => 'stack', 'pageWidth' => 'wide']);
    }
}
