<?php

namespace Templates\Business\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Templates\Business\Database\Factories\PriceItemFactory;
use Templates\Business\Support\WhatsApp;

#[UseFactory(PriceItemFactory::class)]
#[Fillable(['name', 'description', 'price', 'price_note', 'group', 'sort_order', 'is_active'])]
class PriceItem extends Model
{
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    #[Scope]
    protected function active(Builder $query): void
    {
        $query->where('is_active', true);
    }

    #[Scope]
    protected function ordered(Builder $query): void
    {
        $query->orderBy('sort_order')->orderBy('id');
    }

    /**
     * "250 ₺" or "1.250,50 ₺"; whole amounts drop the decimals.
     */
    public function formattedPrice(): ?string
    {
        if ($this->price === null) {
            return null;
        }

        $price = (float) $this->price;

        return number_format($price, fmod($price, 1.0) === 0.0 ? 0 : 2, ',', '.').' ₺';
    }

    public function whatsappUrl(): ?string
    {
        return WhatsApp::link('Merhaba, "'.$this->name.'" için bilgi almak istiyorum.');
    }
}
