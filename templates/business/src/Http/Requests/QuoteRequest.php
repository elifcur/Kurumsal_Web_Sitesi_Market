<?php

namespace Templates\Business\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Templates\Business\Support\QuoteOptions;

/**
 * Public quick quote / order form (/teklif and the hero mini form). Phone is
 * the primary contact channel for small businesses, so e-mail is optional.
 */
class QuoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:40'],
            'email' => ['nullable', 'email:rfc', 'max:190'],
            'service' => ['nullable', 'string', Rule::in(QuoteOptions::keys())],
            'preferred_date' => ['nullable', 'date', 'after_or_equal:today'],
            'message' => ['nullable', 'string', 'max:2000'],
            'kvkk' => ['accepted'],
            'website' => ['prohibited'], // honeypot
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return ['name' => 'ad soyad', 'phone' => 'telefon', 'email' => 'e-posta', 'service' => 'hizmet', 'preferred_date' => 'tercih edilen tarih', 'message' => 'mesaj'];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'service.in' => 'Lütfen listeden geçerli bir hizmet seçin.',
            'preferred_date.after_or_equal' => 'Tercih edilen tarih bugünden önce olamaz.',
            'kvkk.accepted' => 'Devam etmek için KVKK aydınlatma metnini onaylamalısınız.',
            'website.prohibited' => 'Form doğrulaması başarısız oldu.',
        ];
    }
}
