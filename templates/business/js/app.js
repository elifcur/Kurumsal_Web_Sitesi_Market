import Alpine, { startAlpine } from '../../../resources/js/site.js';

/*
 * Galeri: kategori sekmeleri (x-show ile sunucu tarafında basılmış kartlar)
 * + basit lightbox. items: [{ src, title, category }] — indeksler DOM sırasıyla aynı.
 */
Alpine.data('workGallery', (items = []) => ({
    items,
    filter: '',
    open: false,
    index: 0,
    get visibleIndices() {
        return this.items.map((item, i) => (this.filter === '' || item.category === this.filter ? i : -1)).filter((i) => i >= 0);
    },
    get current() {
        return this.items[this.index] ?? null;
    },
    matches(category) {
        return this.filter === '' || category === this.filter;
    },
    show(i) {
        if (!this.items[i]?.src) return;
        this.index = i;
        this.open = true;
        document.body.classList.add('overflow-hidden');
    },
    close() {
        this.open = false;
        document.body.classList.remove('overflow-hidden');
    },
    step(direction) {
        const list = this.visibleIndices;
        if (list.length === 0) return;
        const position = Math.max(list.indexOf(this.index), 0);
        this.index = list[(position + direction + list.length) % list.length];
    },
    next() {
        this.step(1);
    },
    prev() {
        this.step(-1);
    },
    setFilter(value) {
        this.filter = value;
    },
    get position() {
        const list = this.visibleIndices;
        return list.length ? `${list.indexOf(this.index) + 1} / ${list.length}` : '';
    },
}));

startAlpine();
