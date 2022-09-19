<?php

namespace App\Http\Livewire;

use App\Models\Project;
use App\Models\User;
use App\Notifications\EstimateApproved;
use Illuminate\Support\Facades\Notification;

class EstimateApprover extends StatusUpdater
{
    public mixed $model;

    public function mount(Project $model)
    {
        $this->model = $model;
        $this->label = __('Approve estimate');
        $this->statusAttribute = 'estimate_approved_at';
        $this->successMessage = __('You have successfully approved your estimate.');
        $this->redirectUrl = localized_route('projects.manage-estimates-and-agreements', $this->model);
    }

    public function render()
    {
        return view('livewire.status-updater');
    }

    public function notify(): void
    {
        flash($this->successMessage, 'success');

        $administrators = User::administrator()->get();
        Notification::send($administrators, new EstimateApproved($this->model));
    }
}
