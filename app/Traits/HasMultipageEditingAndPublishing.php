<?php

namespace App\Traits;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

trait HasMultipageEditingAndPublishing
{
    public function getSingularName(): string
    {
        return __(Str::kebab(class_basename($this)).'.singular_name');
    }

    public function publish(): void
    {
        $this->published_at = date('Y-m-d h:i:s', time());
        $this->save();
        flash(__('Congratulations! Your have successfully published your :model page.', ['model' => $this->getSingularName()]), 'success');
    }

    public function unpublish($silent = false): void
    {
        $this->published_at = null;
        $this->save();
        if (! $silent) {
            flash(__('Your :model page has been unpublished.', ['model' => $this->getSingularName()]), 'success');
        }
    }

    public function handleUpdateRequest(mixed $request, int $step = 0): RedirectResponse
    {
        $back = ($step > 0)
            ? localized_route($this->getRoutePrefix().'.edit', [$this->getRoutePlaceholder() => $this, 'step' => $step])
            : localized_route($this->getRoutePrefix().'.edit', [$this->getRoutePlaceholder() => $this]);

        if (is_null($request->input('preview')) && is_null($request->input('publish')) && is_null($request->input('unpublish'))) {
            if ($this->checkStatus('draft')) {
                flash(__('You have successfully saved your draft :model page.', ['model' => $this->getSingularName()]), 'success');
            } else {
                flash(__('You have successfully saved your :model page.', ['model' => $this->getSingularName()]), 'success');
            }
        }

        if ($request->input('save')) {
            return redirect($back);
        } elseif ($request->input('save_and_previous')) {
            return redirect(localized_route($this->getRoutePrefix().'.edit', [$this->getRoutePlaceholder() => $this, 'step' => $step - 1]));
        } elseif ($request->input('save_and_next')) {
            return redirect(localized_route($this->getRoutePrefix().'.edit', [$this->getRoutePlaceholder() => $this, 'step' => $step + 1]));
        } elseif ($request->input('preview')) {
            flash(__('You are previewing your :item page.', ['item' => $this->getSingularName()]).' <a href="'.localized_route($this->getRoutePrefix().'.edit', $this).'">'.__('Return to edit mode').'</a>', 'warning');

            return redirect(localized_route($this->getRoutePrefix().'.show', $this));
        } elseif ($request->input('publish')) {
            Gate::authorize('publish', $this);

            $this->publish();

            return redirect(localized_route($this->getRoutePrefix().'.show', $this));
        } elseif ($request->input('unpublish')) {
            Gate::authorize('unpublish', $this);

            $this->unpublish();

            return redirect($back);
        }

        return redirect($back);
    }
}
