@props(['options' => [], 'selected' => '', 'prefill' => '', 'compact' => false])
{{-- compact: ana sayfadaki mini sipariş formu (ad, telefon, ürün/hizmet, KVKK, gönder). Her iki biçim de quote.store'a gönderir. --}}
<form method="post" action="{{ route('quote.store') }}" {{ $attributes->merge(['class' => $compact ? 'space-y-3' : 'space-y-5']) }}>
    @csrf
    <div class="hidden" aria-hidden="true"><label>Web sitesi <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
    @if($compact)
        <div class="grid gap-3 md:grid-cols-4">
            <input name="name" type="text" value="{{ old('name') }}" placeholder="Adınız *" required maxlength="120" class="form-input" autocomplete="name">
            <input name="phone" type="tel" value="{{ old('phone') }}" placeholder="Telefon *" required maxlength="40" class="form-input" autocomplete="tel">
            <select name="service" class="form-input text-slate-700">
                <option value="">Ürün / hizmet seçin</option>
                @foreach($options as $group => $items)
                    <optgroup label="{{ $group }}">@foreach($items as $key => $label)<option value="{{ $key }}" @selected(old('service', $selected) === $key)>{{ $label }}</option>@endforeach</optgroup>
                @endforeach
            </select>
            <button type="submit" class="btn-brand"><x-icon name="truck" class="h-4 w-4" /> Sipariş Ver</button>
        </div>
        <label class="flex items-start gap-2 text-xs text-slate-600">
            <input type="checkbox" name="kvkk" value="1" class="mt-0.5 h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary" @checked(old('kvkk'))>
            <span><a href="{{ route('page.show', 'kvkk') }}" target="_blank" class="font-medium text-primary underline">KVKK metnini</a> okudum, aranmayı kabul ediyorum.</span>
        </label>
    @else
        <div class="grid gap-5 sm:grid-cols-2">
            <div><label for="name" class="form-label">Ad Soyad *</label><input id="name" name="name" type="text" value="{{ old('name') }}" required maxlength="120" class="form-input" autocomplete="name">@error('name')<p class="form-error">{{ $message }}</p>@enderror</div>
            <div><label for="phone" class="form-label">Telefon *</label><input id="phone" name="phone" type="tel" value="{{ old('phone') }}" required maxlength="40" class="form-input" autocomplete="tel">@error('phone')<p class="form-error">{{ $message }}</p>@enderror</div>
            <div><label for="email" class="form-label">E-posta</label><input id="email" name="email" type="email" value="{{ old('email') }}" maxlength="190" class="form-input" autocomplete="email" placeholder="İsteğe bağlı">@error('email')<p class="form-error">{{ $message }}</p>@enderror</div>
            <div><label for="service" class="form-label">Ürün / Hizmet</label>
                <select id="service" name="service" class="form-input">
                    <option value="">Seçiniz (isteğe bağlı)</option>
                    @foreach($options as $group => $items)
                        <optgroup label="{{ $group }}">
                            @foreach($items as $key => $label)<option value="{{ $key }}" @selected(old('service', $selected) === $key)>{{ $label }}</option>@endforeach
                        </optgroup>
                    @endforeach
                </select>@error('service')<p class="form-error">{{ $message }}</p>@enderror</div>
            <div><label for="preferred_date" class="form-label">Teslimat Tarihi</label><input id="preferred_date" name="preferred_date" type="date" value="{{ old('preferred_date') }}" min="{{ now()->toDateString() }}" class="form-input">@error('preferred_date')<p class="form-error">{{ $message }}</p>@enderror</div>
        </div>
        <div><label for="message" class="form-label">Mesajınız</label><textarea id="message" name="message" rows="4" maxlength="2000" class="form-input" placeholder="Sipariş listeniz (ürün, adet), teslimat adresi, saat tercihi...">{{ old('message', $prefill) }}</textarea>@error('message')<p class="form-error">{{ $message }}</p>@enderror</div>
        <div>
            <label class="flex items-start gap-3 text-sm text-slate-600">
                <input type="checkbox" name="kvkk" value="1" class="mt-1 h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary" @checked(old('kvkk'))>
                <span><a href="{{ route('page.show', 'kvkk') }}" target="_blank" class="font-medium text-primary underline">KVKK Aydınlatma Metni</a>'ni okudum ve kişisel verilerimin işlenmesini kabul ediyorum.</span>
            </label>
            @error('kvkk')<p class="form-error">{{ $message }}</p>@enderror
        </div>
        <button type="submit" class="btn-primary w-full !py-3.5 sm:w-auto">Siparişi Gönder <x-icon name="arrow-right" class="h-4 w-4" /></button>
    @endif
</form>
