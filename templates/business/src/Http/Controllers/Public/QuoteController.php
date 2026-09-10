<?php

namespace Templates\Business\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Public\ContactController;
use App\Models\ContactMessage;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Templates\Business\Http\Requests\QuoteRequest;
use Templates\Business\Support\QuoteOptions;

/**
 * Quick quote / order form: /teklif?hizmet={slug}, ?fiyat={id} or ?urun={slug}
 * pre-select the service or prefill the message with a product name. Both the
 * full form and the hero mini form post here; the message is stored as a
 * ContactMessage (type: quote) and e-mailed to the site owner.
 */
class QuoteController extends Controller
{
    public function show(Request $request): View
    {
        $product = $request->filled('urun') ? Product::query()->active()->where('slug', $request->string('urun')->toString())->first() : null;

        return view('template::quote', [
            'options' => QuoteOptions::options(),
            'selected' => QuoteOptions::fromQuery($request->query('hizmet'), $request->query('fiyat')),
            'prefill' => $product ? '"'.$product->title.'" ürünü için sipariş vermek istiyorum.' : '',
        ]);
    }

    public function store(QuoteRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $service = QuoteOptions::label($data['service'] ?? null);
        $date = isset($data['preferred_date']) ? Carbon::parse($data['preferred_date'])->translatedFormat('d F Y') : null;

        $message = ContactMessage::query()->create([
            'type' => 'quote',
            'name' => $data['name'],
            'email' => $data['email'] ?? '',
            'phone' => $data['phone'],
            'subject' => 'Sipariş / Teslimat Talebi'.($service ? ': '.$service : ''),
            'message' => trim((string) ($data['message'] ?? '')) ?: 'Teklif / sipariş almak istiyorum, lütfen beni arayın.',
            'meta' => array_filter(['Hizmet' => $service, 'Tercih Edilen Tarih' => $date]),
            'ip' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 500),
        ]);

        ContactController::notify($this->notifiable($message));

        return redirect()->route('quote.show')->with('success', setting('quote_success', 'Talebiniz alındı. En kısa sürede sizi arayacağız.'));
    }

    /**
     * The notification mail sets Reply-To from the message e-mail, which is
     * optional here; fall back to the site address so the mail still goes out.
     */
    private function notifiable(ContactMessage $message): ContactMessage
    {
        if ($message->email !== '') {
            return $message;
        }

        return (clone $message)->setAttribute('email', (string) (setting('contact_recipient') ?: setting('email')));
    }
}
