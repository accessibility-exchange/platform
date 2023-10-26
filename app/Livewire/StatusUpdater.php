<?php

namespace App\Livewire;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Livewire\Component;
use Livewire\Features\SupportRedirects\Redirector;

class StatusUpdater extends Component
{
    use AuthorizesRequests;

    public mixed $model;

    public string $label;

    public string $statusAttribute;

    public string $successMessage;

    public string $redirectUrl;

    public function render()
    {
        return view('livewire.status-updater');
    }

    public function updateStatus(): RedirectResponse|Redirector
    {
        $this->authorize('manage', $this->model);

        $this->model->update([$this->statusAttribute => now()]);

        $this->notify();

        return redirect()->to($this->redirectUrl);
    }

    public function notify(): void
    {
        flash($this->successMessage, 'success');
    }
}
