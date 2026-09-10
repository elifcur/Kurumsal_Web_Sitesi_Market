<?php

namespace Templates\Business\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Templates\Business\Http\Requests\PriceItemRequest;
use Templates\Business\Models\PriceItem;

class PriceItemController extends Controller
{
    public function index(Request $request): View
    {
        $items = PriceItem::query()
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%'.$request->string('q')->limit(100).'%'))
            ->when($request->filled('group'), fn ($q) => $q->where('group', $request->input('group')))
            ->when($request->input('status') === 'active', fn ($q) => $q->where('is_active', true))
            ->when($request->input('status') === 'inactive', fn ($q) => $q->where('is_active', false))
            ->ordered()
            ->paginate(config('cms.pagination.admin'))
            ->withQueryString();

        return view('template::admin.price-items.index', ['items' => $items, 'groups' => $this->groupOptions()]);
    }

    public function create(): View
    {
        return view('template::admin.price-items.form', [
            'item' => new PriceItem(['is_active' => true, 'sort_order' => (int) PriceItem::query()->max('sort_order') + 1]),
            'groups' => $this->groupOptions(),
        ]);
    }

    public function store(PriceItemRequest $request): RedirectResponse
    {
        $item = PriceItem::query()->create($request->payload());

        return redirect()->route('admin.price-items.edit', $item)->with('success', 'Fiyat kalemi oluşturuldu.');
    }

    public function edit(PriceItem $priceItem): View
    {
        return view('template::admin.price-items.form', ['item' => $priceItem, 'groups' => $this->groupOptions()]);
    }

    public function update(PriceItemRequest $request, PriceItem $priceItem): RedirectResponse
    {
        $priceItem->update($request->payload());

        return redirect()->route('admin.price-items.edit', $priceItem)->with('success', 'Fiyat kalemi güncellendi.');
    }

    public function destroy(PriceItem $priceItem): RedirectResponse
    {
        $priceItem->delete();

        return redirect()->route('admin.price-items.index')->with('success', 'Fiyat kalemi silindi.');
    }

    /**
     * Persist a new ordering: expects {"order": [id, id, ...]}.
     */
    public function reorder(Request $request): JsonResponse
    {
        $data = $request->validate(['order' => ['required', 'array', 'max:500'], 'order.*' => ['integer']]);

        foreach (array_values($data['order']) as $position => $id) {
            PriceItem::query()->whereKey($id)->update(['sort_order' => $position]);
        }

        return response()->json(['ok' => true]);
    }

    /**
     * Existing group names, used for the filter and as datalist suggestions.
     *
     * @return array<int, string>
     */
    private function groupOptions(): array
    {
        return PriceItem::query()->whereNotNull('group')->distinct()->orderBy('group')->pluck('group')->all();
    }
}
