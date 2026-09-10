<?php

namespace Templates\Business\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Templates\Business\Http\Requests\GalleryItemRequest;
use Templates\Business\Models\GalleryItem;

class GalleryItemController extends Controller
{
    public function index(Request $request): View
    {
        $items = GalleryItem::query()
            ->when($request->filled('q'), fn ($q) => $q->where('title', 'like', '%'.$request->string('q')->limit(100).'%'))
            ->when($request->filled('category'), fn ($q) => $q->where('category', $request->input('category')))
            ->when($request->input('status') === 'active', fn ($q) => $q->where('is_active', true))
            ->when($request->input('status') === 'inactive', fn ($q) => $q->where('is_active', false))
            ->ordered()
            ->paginate(config('cms.pagination.admin'))
            ->withQueryString();

        return view('template::admin.gallery.index', ['items' => $items, 'categories' => $this->categoryOptions()]);
    }

    public function create(): View
    {
        return view('template::admin.gallery.form', [
            'item' => new GalleryItem(['is_active' => true, 'sort_order' => (int) GalleryItem::query()->max('sort_order') + 1]),
            'categories' => $this->categoryOptions(),
        ]);
    }

    public function store(GalleryItemRequest $request): RedirectResponse
    {
        GalleryItem::query()->create($request->payload());

        return redirect()->route('admin.gallery.index')->with('success', 'Görsel galeriye eklendi.');
    }

    public function edit(GalleryItem $gallery): View
    {
        return view('template::admin.gallery.form', ['item' => $gallery, 'categories' => $this->categoryOptions()]);
    }

    public function update(GalleryItemRequest $request, GalleryItem $gallery): RedirectResponse
    {
        $gallery->update($request->payload());

        return redirect()->route('admin.gallery.edit', $gallery)->with('success', 'Galeri öğesi güncellendi.');
    }

    public function destroy(GalleryItem $gallery): RedirectResponse
    {
        $gallery->delete();

        return redirect()->route('admin.gallery.index')->with('success', 'Galeri öğesi silindi.');
    }

    /**
     * @return array<int, string>
     */
    private function categoryOptions(): array
    {
        return GalleryItem::query()->whereNotNull('category')->distinct()->orderBy('category')->pluck('category')->all();
    }
}
