@extends('layouts.app')

@section('title', 'Profil Admin - Deva Laundry')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4 text-primary fw-bold">Profil & Pengaturan Situs</h3>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm border-0 mb-4" style="max-width: 600px;">
        <div class="card-body">
            <div class="d-flex align-items-center mb-4">
                <div class="flex-shrink-0 me-3">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width:64px;height:64px;font-size:1.8rem;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                </div>
                <div>
                    <h5 class="mb-1">{{ $user->name }}</h5>
                    <span class="badge bg-primary">Administrator</span>
                </div>
            </div>

            <dl class="row mb-0">
                <dt class="col-sm-4">Email</dt>
                <dd class="col-sm-8">{{ $user->email }}</dd>

                <dt class="col-sm-4">Role</dt>
                <dd class="col-sm-8 text-capitalize">{{ $user->role }}</dd>

                @if(!empty($user->phone))
                    <dt class="col-sm-4">Telepon</dt>
                    <dd class="col-sm-8">{{ $user->phone }}</dd>
                @endif

                @if(!empty($user->address))
                    <dt class="col-sm-4">Alamat</dt>
                    <dd class="col-sm-8">{{ $user->address }}</dd>
                @endif
            </dl>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.profile.update') }}" class="mt-4">
        @csrf

        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h5 class="mb-3 text-primary">Contact</h5>

                        <div class="mb-3">
                            <label class="form-label">Telepon / WhatsApp</label>
                            <input type="text" name="contact_phone" class="form-control"
                                   value="{{ old('contact_phone', $settings['contact_phone'] ?? '') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="text" name="contact_email" class="form-control"
                                   value="{{ old('contact_email', $settings['contact_email'] ?? '') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Instagram</label>
                            <input type="text" name="contact_instagram" class="form-control"
                                   value="{{ old('contact_instagram', $settings['contact_instagram'] ?? '') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Facebook</label>
                            <input type="text" name="contact_facebook" class="form-control"
                                   value="{{ old('contact_facebook', $settings['contact_facebook'] ?? '') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">TikTok</label>
                            <input type="text" name="contact_tiktok" class="form-control"
                                   value="{{ old('contact_tiktok', $settings['contact_tiktok'] ?? '') }}">
                        </div>

                        <div class="mb-0">
                            <label class="form-label">Link Google Maps</label>
                            <input type="text" name="contact_maps_link" class="form-control"
                                   value="{{ old('contact_maps_link', $settings['contact_maps_link'] ?? '') }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h5 class="mb-3 text-primary">About (Visi, Misi, Lokasi)</h5>

                        <div class="mb-3">
                            <label class="form-label">Visi kami</label>
                            <textarea name="about_vision" class="form-control" rows="2">{{ old('about_vision', $settings['about_vision'] ?? '') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Misi kami</label>
                            <textarea name="about_mission" class="form-control" rows="4">{{ old('about_mission', $settings['about_mission'] ?? '') }}</textarea>
                            <small class="text-muted">Pisahkan setiap misi dengan enter supaya tampil sebagai list.</small>
                        </div>

                        <div class="mb-0">
                            <label class="form-label">Lokasi</label>
                            <textarea name="about_location" class="form-control" rows="2">{{ old('about_location', $settings['about_location'] ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-3">
            <div class="card-body">
                <h5 class="mb-3 text-primary">Promo di Halaman Beranda</h5>

                <p class="text-muted" style="font-size: .9rem;">Tambah, edit, atau hapus promo yang tampil di bagian Promo Spesial beranda.</p>

                <div id="promo-list">
                    @php
                        $oldTitles = old('promo_titles', []);
                        $oldDescs = old('promo_descs', []);
                        $useOld = count($oldTitles) > 0 || count($oldDescs) > 0;
                        $promoItems = $useOld ? collect($oldTitles)->map(function ($t, $i) use ($oldDescs) {
                            return ['title' => $t, 'desc' => $oldDescs[$i] ?? ''];
                        })->all() : ($promos ?? []);
                    @endphp

                    @forelse($promoItems as $index => $promo)
                        <div class="border rounded-3 p-3 mb-3 promo-item bg-light bg-opacity-50">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <strong>Promo {{ $index + 1 }}</strong>
                                <button type="button" class="btn btn-sm btn-outline-danger btn-remove-promo">Hapus</button>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Judul Promo</label>
                                <input type="text" name="promo_titles[]" class="form-control"
                                       value="{{ $promo['title'] ?? '' }}">
                            </div>
                            <div class="mb-0">
                                <label class="form-label">Deskripsi Promo</label>
                                <textarea name="promo_descs[]" class="form-control" rows="2">{{ $promo['desc'] ?? '' }}</textarea>
                            </div>
                        </div>
                    @empty
                        <div class="border rounded-3 p-3 mb-3 promo-item bg-light bg-opacity-50">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <strong>Promo 1</strong>
                                <button type="button" class="btn btn-sm btn-outline-danger btn-remove-promo">Hapus</button>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Judul Promo</label>
                                <input type="text" name="promo_titles[]" class="form-control">
                            </div>
                            <div class="mb-0">
                                <label class="form-label">Deskripsi Promo</label>
                                <textarea name="promo_descs[]" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                    @endforelse
                </div>

                <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="btn-add-promo">+ Tambah Promo</button>
            </div>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-primary px-4">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const list = document.getElementById('promo-list');
        const addBtn = document.getElementById('btn-add-promo');

        function attachRemoveHandlers(scope) {
            (scope || document).querySelectorAll('.btn-remove-promo').forEach(function (btn) {
                btn.onclick = function () {
                    const item = this.closest('.promo-item');
                    if (!item) return;
                    if (list.querySelectorAll('.promo-item').length <= 1) {
                        item.querySelectorAll('input, textarea').forEach(el => el.value = '');
                        return;
                    }
                    item.remove();
                };
            });
        }

        addBtn.addEventListener('click', function () {
            const count = list.querySelectorAll('.promo-item').length;
            const wrapper = document.createElement('div');
            wrapper.className = 'border rounded-3 p-3 mb-3 promo-item bg-light bg-opacity-50';
            wrapper.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <strong>Promo ${count + 1}</strong>
                    <button type="button" class="btn btn-sm btn-outline-danger btn-remove-promo">Hapus</button>
                </div>
                <div class="mb-2">
                    <label class="form-label">Judul Promo</label>
                    <input type="text" name="promo_titles[]" class="form-control">
                </div>
                <div class="mb-0">
                    <label class="form-label">Deskripsi Promo</label>
                    <textarea name="promo_descs[]" class="form-control" rows="2"></textarea>
                </div>
            `;
            list.appendChild(wrapper);
            attachRemoveHandlers(wrapper);
        });

        attachRemoveHandlers(document);
    });
</script>
@endpush
