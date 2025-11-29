@extends('layouts.app')

@section('title', 'Profil Admin - Deva Laundry')

@section('content')
<style>
    .profile-shell {
        max-width: 1500px;
        margin: 32px auto 40px;
        font-family: 'SF Pro Display', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }
    .profile-window {
        border-radius: 26px;
        background: radial-gradient(circle at top left, rgba(255,255,255,0.97), rgba(243,246,255,0.94));
        box-shadow: 0 28px 80px rgba(15,23,42,0.18);
        border: 1px solid rgba(255,255,255,0.9);
        overflow: hidden;
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
    }
    .profile-window-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.9rem 1.5rem;
        border-bottom: 1px solid rgba(226,232,240,0.9);
        background: linear-gradient(135deg, #f5f5f7, #e5e7eb);
    }
    .profile-traffic {
        display: flex;
        gap: .45rem;
        align-items: center;
    }
    .profile-traffic-dot {
        width: 12px;
        height: 12px;
        border-radius: 999px;
        border: 1px solid rgba(0,0,0,0.08);
        box-shadow: 0 1px 2px rgba(0,0,0,0.08);
    }
    .profile-traffic-dot.red { background:#ff5f57; }
    .profile-traffic-dot.yellow { background:#febc2e; }
    .profile-traffic-dot.green { background:#28c840; }
    .profile-window-title {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    .profile-window-title h3 {
        font-size: 1.3rem;
        margin: 0;
        font-weight: 800;
        letter-spacing: -.3px;
        color: #111827;
    }
    .profile-window-title span {
        font-size: .85rem;
        color: #6b7280;
    }

    .profile-body {
        padding: 1.6rem 1.6rem 1.9rem;
    }
    .profile-layout {
        display: grid;
        grid-template-columns: minmax(260px, 320px) minmax(0, 1.5fr);
        gap: 20px;
        align-items: flex-start;
    }
    @media (max-width: 992px) {
        .profile-layout {
            grid-template-columns: 1fr;
        }
    }

    .profile-card {
        border-radius: 20px;
        background: linear-gradient(145deg, rgba(255,255,255,0.98), rgba(246,248,255,0.95));
        border: 1px solid rgba(255,255,255,0.9);
        box-shadow: 0 14px 40px rgba(15,23,42,0.13);
        padding: 1.3rem 1.5rem;
    }

    .profile-avatar {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 1.1rem;
    }
    .profile-avatar-circle {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: radial-gradient(circle at 20% 20%, #4f46e5, #0ea5e9);
        color: #fff;
        font-size: 2.1rem;
        font-weight: 700;
        box-shadow: 0 12px 32px rgba(79,70,229,0.55);
    }
    .profile-avatar-meta h4 {
        margin: 0;
        font-weight: 700;
        font-size: 1.15rem;
        letter-spacing: -.25px;
    }
    .badge-role {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: .72rem;
        font-weight: 600;
        background: rgba(37,99,235,0.08);
        color: #1d4ed8;
    }

    .profile-meta dl {
        margin-bottom: 0;
    }
    .profile-meta dt {
        font-size: .78rem;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: #9ca3af;
    }
    .profile-meta dd {
        font-size: .92rem;
        font-weight: 500;
        color: #111827;
    }

    .section-title {
        font-size: .9rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .14em;
        color: #9ca3af;
        margin-bottom: .45rem;
    }
    .section-heading {
        font-size: 1.05rem;
        font-weight: 700;
        margin-bottom: .4rem;
        color: #111827;
    }
    .section-description {
        font-size: .84rem;
        color: #6b7280;
        margin-bottom: 1rem;
    }

    .profile-form-group label {
        font-size: .85rem;
        font-weight: 600;
        color: #374151;
    }
    .profile-form-group input,
    .profile-form-group textarea,
    .profile-form-group select {
        border-radius: 12px;
        border: 1px solid rgba(148,163,184,0.55);
        font-size: .9rem;
        padding: .55rem .8rem;
    }
    .profile-form-group input:focus,
    .profile-form-group textarea:focus,
    .profile-form-group select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59,130,246,0.25);
    }

    .profile-footer {
        display: flex;
        justify-content: flex-end;
        margin-top: 1.4rem;
    }
    .btn-mac-primary {
        border-radius: 999px;
        padding: .6rem 1.8rem;
        font-weight: 600;
        border: none;
        background: linear-gradient(135deg, #007aff, #0051d4);
        color: #fff;
        box-shadow: 0 10px 24px rgba(37,99,235,0.4);
    }
    .btn-mac-primary:hover {
        background: linear-gradient(135deg, #0a84ff, #1d4ed8);
        box-shadow: 0 14px 32px rgba(37,99,235,0.55);
        transform: translateY(-1px);
    }
</style>

<div class="profile-shell">
    @if(session('success'))
        {{-- Disimpan sebagai teks tersembunyi, notifikasi ditampilkan via modal pop up --}}
        <div id="profileSuccessMessage" class="d-none">{{ session('success') }}</div>
    @endif

    <div class="profile-window">
        <div class="profile-window-header">
            <div class="d-flex align-items-center gap-3">
                <div class="profile-traffic" aria-hidden="true">
                    <span class="profile-traffic-dot red"></span>
                    <span class="profile-traffic-dot yellow"></span>
                    <span class="profile-traffic-dot green"></span>
                </div>
                <div class="profile-window-title">
                    <h3>Profil Admin</h3>
                    <span>Kelola identitas & tampilan Deva Laundry</span>
                </div>
            </div>
        </div>

        <div class="profile-body">
            <div class="profile-layout">
                {{-- Kolom kiri: kartu profil & snapshot --}}
                <div>
                    <div class="profile-card mb-3">
                        <div class="profile-avatar">
                            <div class="profile-avatar-circle">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="profile-avatar-meta">
                                <h4>{{ $user->name }}</h4>
                                <span class="badge-role">
                                    <span style="width:7px;height:7px;border-radius:999px;background:#22c55e;display:inline-block;"></span>
                                    {{ ucfirst($user->role ?? 'admin') }}
                                </span>
                            </div>
                        </div>

                        <div class="profile-meta">
                            <dl class="row mb-0">
                                <dt class="col-4">Email</dt>
                                <dd class="col-8">{{ $user->email }}</dd>

                                @if(!empty($user->phone))
                                    <dt class="col-4 mt-2">Telepon</dt>
                                    <dd class="col-8 mt-2">{{ $user->phone }}</dd>
                                @endif

                                @if(!empty($user->address))
                                    <dt class="col-4 mt-2">Alamat</dt>
                                    <dd class="col-8 mt-2">{{ $user->address }}</dd>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <div class="profile-card">
                        <div class="section-title">Snapshot</div>
                        <div class="section-description mb-2">
                            Informasi ini akan muncul di area publik (Beranda, Tentang, Kontak) sebagai identitas resmi Deva Laundry.
                        </div>
                        <ul class="mb-0" style="font-size:.84rem;color:#4b5563;padding-left:1.1rem;">
                            <li>Logo digunakan di sidebar, tab browser, dan header.</li>
                            <li>Kontak dipakai di footer dan halaman Kontak.</li>
                            <li>Promo tampil sebagai highlight di beranda.</li>
                        </ul>
                    </div>
                </div>

                {{-- Kolom kanan: form pengaturan --}}
                <div>
                    <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="profile-card h-100">
                                    <div class="section-title">Kontak Publik</div>
                                    <div class="section-heading">Informasi Kontak & Sosial</div>
                                    <p class="section-description">
                                        Data ini muncul di halaman Kontak dan ikon media sosial di beranda.
                                    </p>

                                    <div class="profile-form-group mb-3">
                                        <label>Telepon / WhatsApp</label>
                                        <input type="text" name="contact_phone" class="form-control"
                                               value="{{ old('contact_phone', $settings['contact_phone'] ?? '') }}">
                                    </div>

                                    <div class="profile-form-group mb-3">
                                        <label>Email</label>
                                        <input type="text" name="contact_email" class="form-control"
                                               value="{{ old('contact_email', $settings['contact_email'] ?? '') }}">
                                    </div>

                                    <div class="profile-form-group mb-3">
                                        <label>Instagram</label>
                                        <input type="text" name="contact_instagram" class="form-control"
                                               value="{{ old('contact_instagram', $settings['contact_instagram'] ?? '') }}">
                                    </div>

                                    <div class="profile-form-group mb-3">
                                        <label>Facebook</label>
                                        <input type="text" name="contact_facebook" class="form-control"
                                               value="{{ old('contact_facebook', $settings['contact_facebook'] ?? '') }}">
                                    </div>

                                    <div class="profile-form-group mb-3">
                                        <label>TikTok</label>
                                        <input type="text" name="contact_tiktok" class="form-control"
                                               value="{{ old('contact_tiktok', $settings['contact_tiktok'] ?? '') }}">
                                    </div>

                                    <div class="profile-form-group mb-0">
                                        <label>Link Google Maps</label>
                                        <input type="text" name="contact_maps_link" class="form-control"
                                               value="{{ old('contact_maps_link', $settings['contact_maps_link'] ?? '') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <div class="profile-card h-100">
                                    <div class="section-title">Branding</div>
                                    <div class="section-heading">Logo & About (Visi, Misi, Lokasi)</div>
                                    <p class="section-description">
                                        Ubah logo dan teks About yang akan tampil di beranda dan halaman Tentang Kami.
                                    </p>

                                    @php
                                        $logoPath = $settings['logo_path'] ?? 'header.png';
                                    @endphp
                                    <div class="mb-3 text-center">
                                        <img src="{{ asset('images/'.$logoPath) }}" alt="Logo Saat Ini" class="rounded-3 shadow-sm" style="max-width:140px;">
                                        <div class="text-muted mt-1" style="font-size:.8rem;">Logo saat ini</div>
                                    </div>

                                    <div class="profile-form-group mb-3">
                                        <label>Ganti Logo (PNG/JPG)</label>
                                        <input type="file" name="logo" class="form-control">
                                        <div class="form-text">Biarkan kosong jika tidak ingin mengganti logo.</div>
                                    </div>

                            <div class="profile-form-group mb-3">
                                <label>Alamat / Lokasi Pendek</label>
                                <textarea name="about_location" class="form-control" rows="2">{{ old('about_location', $settings['about_location'] ?? '') }}</textarea>
                            </div>

                            <hr class="my-3">
                            <div class="section-heading mb-2">Visi &amp; Misi Kami</div>
                            <div class="section-description">
                                Tampilkan visi dan misi Deva Laundry yang akan muncul di halaman Tentang Kami.
                            </div>

                            <div class="profile-form-group mb-3">
                                <label>Visi Kami</label>
                                <textarea name="about_vision" class="form-control" rows="3">{{ old('about_vision', $settings['about_vision'] ?? '') }}</textarea>
                                <small class="text-muted">Isi singkat visi utama bisnis kamu.</small>
                            </div>

                            <div class="profile-form-group mb-3">
                                <label>Misi Kami</label>
                                <textarea name="about_mission" class="form-control" rows="4">{{ old('about_mission', $settings['about_mission'] ?? '') }}</textarea>
                                <small class="text-muted">Pisahkan setiap misi dengan enter supaya tampil sebagai list.</small>
                            </div>

                            <hr class="my-3">
                            <div class="section-heading mb-2">Kenapa Pilih Deva Laundry?</div>
                            <div class="section-description">
                                Ubah judul dan isi bagian alasan kenapa pelanggan harus memilih Deva Laundry.
                            </div>

                            <div class="profile-form-group mb-2">
                                <label>Judul</label>
                                <input type="text" name="about_why_title" class="form-control"
                                       value="{{ old('about_why_title', $settings['about_why_title'] ?? 'Kenapa Pilih Deva Laundry?') }}">
                            </div>
                            <div class="profile-form-group mb-0">
                                <label>Isi (setiap baris = 1 paragraf)</label>
                                <textarea name="about_why_text" class="form-control" rows="4">{{ old('about_why_text', $settings['about_why_text'] ?? '') }}</textarea>
                                <small class="text-muted">Teks ini akan muncul di kolom kanan halaman Tentang Kami.</small>
                            </div>

                            <hr class="my-3">
                            <div class="section-heading mb-2">Jam Operasional (Halaman Tentang)</div>
                            <div class="section-description">
                                Atur jam buka tutup laundry yang tampil di halaman Tentang Kami.
                            </div>

                            <div class="profile-form-group mb-0">
                                <label>Jam Operasional</label>
                                <textarea name="about_hours" class="form-control" rows="3">{{ old('about_hours', $settings['about_hours'] ?? "Senin — Minggu\n08.30 – 17.00 WITA") }}</textarea>
                                <small class="text-muted">Gunakan enter untuk membuat baris baru (misalnya hari dan jam di baris terpisah).</small>
                            </div>
                                </div>
                            </div>
                        </div>

                        <div class="profile-card mb-0">
                            <div class="section-title">Promo</div>
                            <div class="section-heading">Promo di Halaman Beranda</div>
                            <p class="section-description">
                                Tambah, edit, atau hapus promo yang tampil di bagian Promo Spesial beranda.
                            </p>

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
                                    <div class="border rounded-3 p-3 mb-3 promo-item bg-light bg-opacity-60">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <strong>Promo {{ $index + 1 }}</strong>
                                            <button type="button" class="btn btn-sm btn-outline-danger btn-remove-promo">Hapus</button>
                                        </div>
                                        <div class="profile-form-group mb-2">
                                            <label>Judul Promo</label>
                                            <input type="text" name="promo_titles[]" class="form-control"
                                                   value="{{ $promo['title'] ?? '' }}">
                                        </div>
                                        <div class="profile-form-group mb-0">
                                            <label>Deskripsi Promo</label>
                                            <textarea name="promo_descs[]" class="form-control" rows="2">{{ $promo['desc'] ?? '' }}</textarea>
                                        </div>
                                    </div>
                                @empty
                                    <div class="border rounded-3 p-3 mb-3 promo-item bg-light bg-opacity-60">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <strong>Promo 1</strong>
                                            <button type="button" class="btn btn-sm btn-outline-danger btn-remove-promo">Hapus</button>
                                        </div>
                                        <div class="profile-form-group mb-2">
                                            <label>Judul Promo</label>
                                            <input type="text" name="promo_titles[]" class="form-control">
                                        </div>
                                        <div class="profile-form-group mb-0">
                                            <label>Deskripsi Promo</label>
                                            <textarea name="promo_descs[]" class="form-control" rows="2"></textarea>
                                        </div>
                                    </div>
                                @endforelse
                            </div>

                            <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="btn-add-promo">+ Tambah Promo</button>
                        </div>

                        <div class="profile-footer">
                            <button type="submit" class="btn btn-mac-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Modal pop up otomatis untuk notifikasi sukses profil/contact/about/promo
        try {
            var msgEl = document.getElementById('profileSuccessMessage');
            if (msgEl && window.Swal) {
                var text = msgEl.textContent || msgEl.innerText || 'Pengaturan berhasil diperbarui.';
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: text,
                    timer: 2200,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            }
        } catch (e) {}

        // Script promo yang sudah ada
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

        if (addBtn) {
            addBtn.addEventListener('click', function () {
                const count = list.querySelectorAll('.promo-item').length;
                const wrapper = document.createElement('div');
                wrapper.className = 'border rounded-3 p-3 mb-3 promo-item bg-light bg-opacity-60';
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
        }

        attachRemoveHandlers(document);
    });
</script>
@endpush
