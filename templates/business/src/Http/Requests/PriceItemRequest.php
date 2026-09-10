<?php

namespace Templates\Business\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PriceItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('manage-sector') ?? false;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:500'],
            'price' => ['nullable', 'numeric', 'min:0', 'max:99999999'],
            'price_note' => ['nullable', 'string', 'max:50'],
            'group' => ['nullable', 'string', 'max:80'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return ['name' => 'kalem adı', 'price' => 'fiyat', 'price_note' => 'fiyat notu', 'group' => 'grup'];
    }

    /**
     * @return array<string, mixed>
     */
    public function payload(): array
    {
        $data = $this->validated();
        $data['description'] = ($data['description'] ?? null) ?: null;
        $data['price'] = isset($data['price']) && $data['price'] !== '' ? $data['price'] : null;
        $data['price_note'] = trim((string) ($data['price_note'] ?? '')) ?: null;
        $data['group'] = trim((string) ($data['group'] ?? '')) ?: null;
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }
}
