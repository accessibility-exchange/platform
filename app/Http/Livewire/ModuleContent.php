<?php

namespace App\Http\Livewire;

use App\Models\Module;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ModuleContent extends Component
{
    public Module $module;

    public User $user;

    public ?string $startedContentAt;

    public ?string $finishedContentAt;

    public function mount(Module $module)
    {
        $this->module = $module;
        $this->user = Auth::user();
        $moduleUser = $this->user->modules->where('id', $module->id)->first();
        $this->startedContentAt = $moduleUser->pivot->started_content_at ?? null;
        $this->finishedContentAt = $moduleUser->pivot->finished_content_at ?? null;
    }

    protected $listeners = ['onPlayerEnd', 'onPlayerStart'];

    public function onPlayerEnd()
    {
        if (! $this->finishedContentAt) {
            $this->user->modules()->updateExistingPivot(
                $this->module->id, ['finished_content_at' => now()]
            );
            $this->finishedContentAt = 'finished_content';
            $finishedCourse = true;
            $course = $this->module->course;
            foreach ($course->modules as $module) {
                $moduleUser = $module->users->where('id', $this->user->id)->first()->pivot ?? null;
                if (! $moduleUser) {
                    $finishedCourse = false;
                    break;
                } elseif ($moduleUser && ! $moduleUser->finished_content_at) {
                    $finishedCourse = false;
                    break;
                }
            }
            if ($finishedCourse) {
                $course->users()->updateExistingPivot(
                    $this->user->id, ['finished_at' => now()]
                );
            }
        }
    }

    public function onPlayerStart()
    {
        if (! $this->startedContentAt) {
            $course = $this->module->course->first();
            if (! ($this->user->courses->where('id', $course->id)->first()->pivot ?? null)) {
                $this->user->courses()->attach(
                    $course->id, ['started_at' => now()]
                );
            }
            $this->user->modules()->attach(
                $this->module->id, ['started_content_at' => now()]
            );
            $this->startedContentAt = 'started_content_at';
        }
    }

    public function render()
    {
        return view('livewire.module-content')->layout('layouts.app-wide');
    }
}
