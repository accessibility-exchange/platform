<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlockRequest extends FormRequest
{
    public function authorize(): bool
    {
        $blockable = $this->input('blockable_type')::find($this->input('blockable_id'));

        return $blockable && $this->user()->can('block', $blockable);
    }

    public function rules(): array
    {
        return [
            'blockable_type' => 'required|string|in:App\Models\Individual,App\Models\Organization,App\Models\RegulatedOrganization',
            'blockable_id' => 'required|integer|exists:' . $this->input('blockable_type') . ',id',
        ];
    }
}
