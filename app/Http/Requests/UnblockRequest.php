<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UnblockRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'blockable_type' => 'required|string|in:App\Models\CommunityMember,App\Models\Organization,App\Models\RegulatedOrganization',
            'blockable_id' => 'required|integer|exists:' . $this->input('blockable_type') . ',id'
        ];
    }
}
