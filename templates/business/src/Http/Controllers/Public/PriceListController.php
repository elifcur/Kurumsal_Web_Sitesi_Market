<?php

namespace Templates\Business\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Templates\Business\Models\PriceItem;

class PriceListController extends Controller
{
    public function index(): View
    {
        $items = PriceItem::query()->active()->ordered()->get();

        return view('template::prices.index', [
            'groups' => $items->groupBy(fn (PriceItem $item) => (string) $item->group),
            'total' => $items->count(),
        ]);
    }
}
