<?php

namespace Templates\Business\Support;

use App\Models\Service;
use Templates\Business\Models\PriceItem;

/**
 * Builds the "Hizmet" select of the quote / order form from services and
 * price list items. Keys are prefixed (hizmet:slug, fiyat:id) so the request
 * can be validated against a closed list and resolved back to a label.
 */
final class QuoteOptions
{
    /**
     * @return array<string, array<string, string>> group label => [key => label]
     */
    public static function options(): array
    {
        $groups = [];

        $services = Service::query()->active()->ordered()->get(['title', 'slug']);

        if ($services->isNotEmpty()) {
            $groups[setting('services_label', 'Hizmetler')] = $services->mapWithKeys(fn (Service $service) => ['hizmet:'.$service->slug => $service->title])->all();
        }

        $items = PriceItem::query()->active()->ordered()->get(['id', 'name', 'group']);

        foreach ($items->groupBy(fn (PriceItem $item) => $item->group ?: setting('price_list_label', 'Fiyat Listesi')) as $group => $groupItems) {
            $groups[$group] = array_merge($groups[$group] ?? [], $groupItems->mapWithKeys(fn (PriceItem $item) => ['fiyat:'.$item->id => $item->name])->all());
        }

        return $groups;
    }

    /**
     * @return array<int, string>
     */
    public static function keys(): array
    {
        return array_keys(array_merge([], ...array_values(self::options())));
    }

    public static function label(?string $key): ?string
    {
        if (! $key) {
            return null;
        }

        foreach (self::options() as $items) {
            if (isset($items[$key])) {
                return $items[$key];
            }
        }

        return null;
    }

    /** Key preselected from ?hizmet= / ?fiyat= query parameters. */
    public static function fromQuery(?string $service, ?string $priceItem): string
    {
        if ($service) {
            return 'hizmet:'.$service;
        }

        if ($priceItem) {
            return 'fiyat:'.$priceItem;
        }

        return '';
    }
}
