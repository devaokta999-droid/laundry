@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h3 class="mb-4 text-center">Form Pemesanan Laundry</h3>

    <form method="POST" action="{{ route('order.store') }}" id="orderForm">
        @csrf

        {{-- ✅ Data Pelanggan --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <h5 class="mb-3">Data Pelanggan</h5>

                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input class="form-control" name="customer_name"
                        value="{{ auth()->check() ? auth()->user()->name : old('customer_name') }}"
                        placeholder="Masukkan nama lengkap" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Telepon</label>
                    <input class="form-control" name="customer_phone"
                        value="{{ auth()->check() ? auth()->user()->phone : old('customer_phone') }}"
                        placeholder="Masukkan nomor WhatsApp" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Alamat Lengkap</label>
                    <textarea class="form-control" name="customer_address" rows="3" required
                        placeholder="Masukkan alamat penjemputan/pengantaran">{{ old('customer_address') }}</textarea>
                </div>
            </div>
        </div>

        {{-- ✅ Pilih Layanan --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <h5 class="mb-3">Pilih Layanan</h5>

                <div id="servicesList">
                    @foreach($services as $s)
                        <div class="d-flex align-items-center mb-2 border-bottom pb-2">
                            <input type="checkbox"
                                class="me-2 service-checkbox"
                                value="{{ $s->id }}"
                                name="service_ids[]">
                            <div class="flex-grow-1">
                                <strong>{{ $s->title }}</strong>
                                <small class="text-muted d-block">{{ $s->description }}</small>
                            </div>
                            <input type="number" name="qty[]" min="1" value="1"
                                class="form-control w-25 ms-3 qty-input">
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ✅ Tanggal & Waktu Penjemputan --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <h5 class="mb-3">Penjadwalan</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal Jemput ke Rumah</label>
                        <input type="date" class="form-control" name="pickup_date" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Waktu Jemput ke Rumah</label>
                        <input type="time" class="form-control" name="pickup_time" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Catatan Tambahan</label>
                    <textarea class="form-control" name="notes" rows="2"
                        placeholder="Contoh: Pisahkan pakaian putih dan berwarna">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>

        {{-- ❌ Bagian Total Pembayaran Dihapus --}}
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <button type="submit" class="btn btn-primary px-5">
                    Kirim Pesanan & Chat WA Admin
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
// Tidak ada perhitungan total karena harga sudah tidak digunakan
</script>
@endpush
