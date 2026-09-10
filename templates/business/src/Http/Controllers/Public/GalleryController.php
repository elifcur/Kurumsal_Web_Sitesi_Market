<?php

namespace Templates\Business\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Templates\Business\Models\GalleryItem;

class GalleryController extends Controller
{
    public function index(): View
    {
        $items = GalleryItem::query()->active()->ordered()->get();

        return view('template::gallery.index', [
            'items' => $items,
            'categories' => $items->pluck('category')->filter()->unique()->values(),
        ]);
    }
}
