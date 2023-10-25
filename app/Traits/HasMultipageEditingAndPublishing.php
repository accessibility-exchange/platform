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
        $this->published_at = now();
        $this->save();
        flash(__('Congratulations! You have successfully published your :model page.', ['model' => $this->getSingularName()]), 'success|'.__('Congratulations! You have successfully published your :model page.', ['model' => $this->getSingularName()], 'en'));
    }

    public function unpublish($silent = false): void
    {
        $this->published_at = null;
        $this->save();
        if (! $silent) {
            flash(__('Your :model page has been unpublished.', ['model' => $this->getSingularName()]), 'success|'.__('Your :model page has been unpublished.', ['model' => $this->getSingularName()], 'en'));
        }
    }

    public function handleUpdateRequest(mixed $request, int $step = 0): RedirectResponse
    {
        $back = ($step > 0)
            ? localized_route($this->getRoutePrefix().'.edit', [$this->getRoutePlaceholder() => $this, 'step' => $step])
            : localized_route($this->getRoutePrefix().'.edit', [$this->getRoutePlaceholder() => $this]);

        if (is_null($request->input('preview')) && is_null($request->input('publish')) && is_null($request->input('unpublish'))) {
            if ($this->checkStatus('draft')) {
                flash(__('You have successfully saved your draft :model page.', ['model' => $this->getSingularName()]), 'success|'.__('You have successfully saved your draft :model page.', ['model' => $this->getSingularName()]), 'en');
            } else {
                flash(__('You have successfully saved your :model page.', ['model' => $this->getSingularName()]), 'success|'.__('You have successfully saved your :model page.', ['model' => $this->getSingularName()], 'en'));
            }
        }

        if ($request->input('save')) {
            return redirect($back);
        } elseif ($request->input('save_and_previous')) {
            return redirect(localized_route($this->getRoutePrefix().'.edit', [$this->getRoutePlaceholder() => $this, 'step' => $step - 1]));
        } elseif ($request->input('save_and_next')) {
            return redirect(localized_route($this->getRoutePrefix().'.edit', [$this->getRoutePlaceholder() => $this, 'step' => $step + 1]));
        } elseif ($request->input('preview')) {
            flash(__('You have successfully saved your draft :item page.', ['item' => $this->getSingularName()]), 'success|'.__('You have successfully saved your draft :item page.', ['item' => $this->getSingularName()], 'en'));

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
