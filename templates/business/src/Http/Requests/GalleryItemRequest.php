<?php

namespace Templates\Business\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GalleryItemRequest extends FormRequest
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
            'title' => ['nullable', 'string', 'max:150'],
            'image' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:80'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return ['title' => 'başlık', 'image' => 'görsel', 'category' => 'kategori'];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return ['image.required' => 'Medya kütüphanesinden bir görsel seçin.'];
    }

    /**
     * @return array<string, mixed>
     */
    public function payload(): array
    {
        $data = $this->validated();
        $data['title'] = trim((string) ($data['title'] ?? '')) ?: null;
        $data['category'] = trim((string) ($data['category'] ?? '')) ?: null;
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }
}
