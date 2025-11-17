@extends('layouts.app')

@section('title', 'Edit Nota')

@section('content')

<style>
    body { background: #eef1f7; }
    .glass-card {
        backdrop-filter: blur(12px);
        background: rgba(255, 255, 255, 0.68);
        border-radius: 18px;
        padding: 25px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.07);
        border: 1px solid rgba(255,255,255,0.4);
    }
    .title-mac { font-size: 26px; font-weight: 700; color: #1a1a1a; margin-bottom: 20px; }
    .label-mac { font-weight: 600; color: #333; }
    .input-mac { border-radius: 12px !important; border: 1px solid #dcdcdc !important; padding: 10px 14px !important; }
    .btn-mac { border-radius: 12px; padding: 10px 18px; font-weight: 600; }
    .btn-primary-mac { background: #0071e3; border-color: #0071e3; color: #fff; }
    .btn-outline-dark-mac {
        border-radius: 12px; padding: 10px 18px; font-weight: 600;
        border: 1px solid #444; color: #111; background: transparent;
    }
    .error-box { background:#ffecec; border:1px solid #f5c2c2; padding:12px; border-radius:8px; margin-bottom:16px; color:#a94442;}
    .field-errors { color:#a94442; font-size:13px; margin-top:6px;}

    /* Catalog styles */
    .catalog {
        margin-top: 18px;
        background: #fff;
        border-radius: 12px;
        padding: 12px;
        border: 1px solid #e6e6e6;
    }
    .catalog h6 { margin: 0 0 8px 0; font-weight:700; }
    .catalog-table { max-height: 320px; overflow:auto; }
    .catalog-table table { width:100%; }
    .catalog-row { cursor: pointer; transition: background .12s; }
    .catalog-row:hover { background: #f4f7fb; }
    .catalog-search { width:100%; margin-bottom:8px; padding:8px 10px; border-radius:10px; border:1px solid #ddd; }

    @media(min-width: 992px) {
        .layout-split {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 18px;
            align-items: start;
        }
    }
</style>

<div class="container mt-4">
    <div class="glass-card">
        <div class="title-mac">Edit Nota Laundry #{{ $nota->id }}</div>

        <!-- Error container -->
        <div id="errorContainer" style="display:none;" class="error-box"></div>

        <div class="layout-split">
            <div>
                <form id="notaForm" action="{{ route('admin.nota.update', $nota->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- ======================= -->
                    <!-- CUSTOMER INFO -->
                    <!-- ======================= -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="label-mac">Nama Pelanggan</label>
                            <input type="text" name="customer_name"
                                value="{{ $nota->customer_name }}"
                                class="form-control input-mac" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="label-mac">Alamat</label>
                            <input type="text" name="customer_address"
                                value="{{ $nota->customer_address }}"
                                class="form-control input-mac">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="label-mac">Tanggal Keluar</label>

                            <!-- FIX: Format date agar tampil -->
                            <input type="date" name="tgl_keluar"
                                value="{{ $nota->tgl_keluar ? date('Y-m-d', strtotime($nota->tgl_keluar)) : '' }}"
                                class="form-control input-mac">
                        </div>
                    </div>

                    <hr>

                    <!-- ======================= -->
                    <!-- TABLE ITEM -->
                    <!-- ======================= -->
                    <h5 class="mb-3 fw-bold">Daftar Item Laundry</h5>

                    <table class="table table-bordered" id="itemTable">
                        <thead class="table-light">
                            <tr>
                                <th width="40">No</th>
                                <th>Jenis Pakaian</th>
                                <th width="120">Harga (Rp)</th>
                                <th width="100">Jumlah</th>
                                <th width="130">Subtotal (Rp)</th>
                                <th width="40">❌</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($nota->items as $i => $item)
                            <tr>
                                <td class="text-center">{{ $i+1 }}</td>

                                <td>
                                    <input type="text" name="name[{{ $i }}]"
                                        value="{{ $item->item->name }}"
                                        class="form-control input-mac name-input" data-index="{{ $i }}">
                                </td>

                                <td>
                                    <input type="number" name="price[{{ $i }}]"
                                        value="{{ $item->price }}"
                                        class="form-control input-mac price-input" 
                                        data-index="{{ $i }}" min="0" step="0.01">
                                </td>

                                <td>
                                    <input type="number" name="quantity[{{ $i }}]"
                                        value="{{ $item->quantity }}"
                                        class="form-control input-mac qty-input" 
                                        data-index="{{ $i }}" min="0">
                                </td>

                                <td>
                                    <input type="text" id="subtotal_{{ $i }}"
                                        value="{{ $item->subtotal }}"
                                        class="form-control input-mac bg-light subtotal-field" readonly>
                                </td>

                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm remove-row">X</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <button type="button" class="btn btn-primary-mac btn-mac my-2" id="addRow">
                        ➕ Tambah Item
                    </button>

                    <hr>

                    <!-- ======================= -->
                    <!-- TOTAL & PAYMENT -->
                    <!-- ======================= -->
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="label-mac">Total Jumlah Pakaian</label>
                            <input type="number" id="total_items" name="total_items"
                                value="{{ $nota->total_items }}"
                                class="form-control input-mac bg-light" readonly>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="label-mac">Jumlah Total (Rp)</label>
                            <input type="number" id="grand_total" name="grand_total"
                                value="{{ $nota->grand_total }}"
                                class="form-control input-mac bg-light" readonly>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="label-mac">Uang Muka (Rp)</label>
                            <input type="number" id="uang_muka" name="uang_muka"
                                value="{{ $nota->uang_muka }}"
                                class="form-control input-mac">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="label-mac">Sisa Pembayaran (Rp)</label>
                            <input type="number" id="sisa_bayar" name="sisa_bayar"
                                value="{{ $nota->sisa_bayar }}"
                                class="form-control input-mac bg-light" readonly>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('admin.nota.show', $nota->id) }}" class="btn btn-outline-dark-mac">⬅Kembali</a>

                        <button type="submit" id="saveBtn" class="btn btn-primary-mac btn-mac">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            <!-- ======================= -->
            <!-- CATALOG / LIST ITEM -->
            <!-- ======================= -->
            <div class="catalog">
                <h6>Katalog Item Laundry</h6>
                <input type="text" id="catalogSearch" class="catalog-search" placeholder="Cari item...">
                <div class="catalog-table">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th width="90">Harga (Rp)</th>
                                <th width="80">Pilih</th>
                            </tr>
                        </thead>
                        <tbody id="catalogBody">
                            <!-- rows injected by JS -->
                        </tbody>
                    </table>
                </div>
                <p class="mt-2 text-muted" style="font-size:13px">Klik tombol "Pilih" untuk mengisi nama & harga ke baris yang aktif. Jika belum ada baris yang aktif, item akan ditambahkan ke baris baru.</p>
            </div>
        </div>
    </div>
</div>

<!-- =============================== -->
<!-- JAVASCRIPT -->
<!-- =============================== -->
<script>
    const indexUrl = "{{ route('admin.nota.index') }}";
    let rowIndex = {{ count($nota->items) }};
    let currentRowIndex = null; // index of the row currently selected for catalog fill

    // Katalog items (dari daftar yang kamu kasih)
    const CATALOG = [
        { name: 'Baju Kaos/panjang', price: 1500 },
        { name: 'kemeja berkerah/panjang', price: 1500 },
        { name: 'Safari', price: 2000 },
        { name: 'kaos dalam', price: 1000 },
        { name: 'kaos kaki', price: 500 },
        { name: 'sapu tangan', price: 500 },
        { name: 'celana panjang', price: 2000 },
        { name: 'celana pendek', price: 1500 },
        { name: 'piyama', price: 1500 },
        { name: 'rompi kain/jeans', price: 1500 },
        { name: 'Sarung/kamen', price: 1500 },
        { name: 'baju hangat', price: 1500 },
        { name: 'jaket/jas', price: 2500 },
        { name: 'udeng', price: 500 },
        { name: 'topi', price: 1500 },
        { name: 'baju anak', price: 1000 },
        { name: 'dasi', price: 500 },
        { name: 'kebaya', price: 1500 },
        { name: 'rok', price: 1500 },
        { name: 'longdress', price: 2000 },
        { name: 'BH', price: 500 },
        { name: 'celana dalam', price: 500 },
        { name: 'Selendang', price: 500 },
        { name: 'longtorse/saput', price: 1000 },
        { name: 'sprei s/m/l', price: 4000 },
        { name: 'Sarung bantal', price: 1000 },
        { name: 'Handuk mandi S/M/L', price: 2500},
        { name: 'Handuk tangan', price: 1000 },
        { name: 'Bed cover S/M/L', price: 11000 },
        { name: 'selimut blangket', price: 5000 },
        { name: 'sepatu kain', price: 4000 },
        { name: 'korden L/S dll', price: 4000 },
        { name: 'Boneka K/S/B', price: 4000 },
        { name: 'Tas', price: 3000 },
        { name: 'karpet per meter ', price: 2500 },
        { name: 'Setrika baju anak', price: 500 },
        { name: 'Setrika Baju dewasa', price: 1000 },
        { name: 'Setrika baju bayi', price: 500 }
    ];

    function toNumber(v) {
        let n = Number(v);
        return isNaN(n) ? 0 : n;
    }

    function calcSubtotal(i) {
        const priceEl = document.querySelector(`input[name="price[${i}]"]`);
        const qtyEl = document.querySelector(`input[name="quantity[${i}]"]`);
        const subtotalEl = document.getElementById(`subtotal_${i}`);

        let qty = qtyEl ? toNumber(qtyEl.value) : 0;
        let price = priceEl ? toNumber(priceEl.value) : 0;

        let subtotal = qty * price;
        if (subtotalEl) subtotalEl.value = subtotal;

        calcTotal();
    }

    function calcTotal() {
        let allSubtotal = document.querySelectorAll(".subtotal-field");
        let total = 0, totalQty = 0;

        allSubtotal.forEach((sub) => {
            total += toNumber(sub.value);
        });

        document.querySelectorAll(".qty-input").forEach(q => {
            totalQty += toNumber(q.value);
        });

        document.getElementById("total_items").value = totalQty;
        document.getElementById("grand_total").value = total;

        let dp = toNumber(document.getElementById("uang_muka").value || 0);
        document.getElementById("sisa_bayar").value = total - dp;
    }

    function reIndexRows() {
        const rows = document.querySelectorAll('#itemTable tbody tr');

        rows.forEach((row, idx) => {
            row.children[0].textContent = idx + 1;

            const nameInput = row.querySelector('.name-input');
            const priceInput = row.querySelector('.price-input');
            const qtyInput = row.querySelector('.qty-input');
            const subtotalField = row.querySelector('.subtotal-field');

            if (nameInput) {
                nameInput.name = `name[${idx}]`;
                nameInput.dataset.index = idx;
            }
            if (priceInput) {
                priceInput.name = `price[${idx}]`;
                priceInput.dataset.index = idx;
            }
            if (qtyInput) {
                qtyInput.name = `quantity[${idx}]`;
                qtyInput.dataset.index = idx;
            }
            if (subtotalField) {
                subtotalField.id = `subtotal_${idx}`;
            }
        });

        rowIndex = rows.length;
    }

    // Fill a row with name & price (and set qty=1 if empty)
    function fillRow(index, name, price) {
        // if index out of range, add new row first
        if (index === null || index === undefined || index < 0 || index >= rowIndex) {
            addRow(); // adds at the end
            index = rowIndex - 1;
        }

        const nameEl = document.querySelector(`input[name="name[${index}]"]`);
        const priceEl = document.querySelector(`input[name="price[${index}]"]`);
        const qtyEl = document.querySelector(`input[name="quantity[${index}]"]`);

        if (nameEl) nameEl.value = name;
        if (priceEl) priceEl.value = price;
        if (qtyEl && toNumber(qtyEl.value) === 0) qtyEl.value = 1;

        calcSubtotal(index);
    }

    document.addEventListener('click', function(e) {
        // when user clicks a name-input, mark that row as current
        if (e.target.classList && e.target.classList.contains('name-input')) {
            currentRowIndex = Number(e.target.dataset.index);
            // visual hint (optional): focus the input
            e.target.focus();
            return;
        }

        // catalog select
        if (e.target && e.target.classList.contains('catalog-select')) {
            const idx = Number(e.target.dataset.idx);
            const item = CATALOG[idx];
            fillRow(currentRowIndex, item.name, item.price);
            // after filling, reset currentRowIndex to that row
            currentRowIndex = currentRowIndex !== null ? currentRowIndex : (rowIndex - 1);
            return;
        }
    });

    document.addEventListener("input", (e) => {
        if (e.target.classList.contains("qty-input") || e.target.classList.contains("price-input")) {
            const idx = e.target.dataset.index;
            calcSubtotal(idx);
        }

        if (e.target.id === "uang_muka") calcTotal();
        if (e.target.id === "catalogSearch") renderCatalog(e.target.value);
    });

    document.addEventListener("click", e => {
        if (e.target.classList.contains("remove-row")) {
            e.target.closest("tr").remove();
            reIndexRows();
            calcTotal();
        }
    });

    document.getElementById("addRow").addEventListener("click", function () {
        addRow();
    });

    function addRow(prefillName = '', prefillPrice = 0, prefillQty = 1) {
        let table = document.querySelector("#itemTable tbody");

        let row = document.createElement('tr');
        row.innerHTML = `
            <td class="text-center">${rowIndex + 1}</td>

            <td>
                <input type="text" name="name[${rowIndex}]" class="form-control input-mac name-input" data-index="${rowIndex}" value="${escapeHtml(prefillName)}">
            </td>

            <td>
                <input type="number" name="price[${rowIndex}]" class="form-control input-mac price-input" 
                       data-index="${rowIndex}" min="0" step="0.01" value="${prefillPrice}">
            </td>

            <td>
                <input type="number" name="quantity[${rowIndex}]" class="form-control input-mac qty-input" 
                       data-index="${rowIndex}" min="0" value="${prefillQty}">
            </td>

            <td>
                <input type="text" id="subtotal_${rowIndex}" class="form-control input-mac bg-light subtotal-field" 
                       value="${toNumber(prefillPrice) * toNumber(prefillQty)}" readonly>
            </td>

            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm remove-row">X</button>
            </td>
        `;

        table.appendChild(row);
        rowIndex++;
        reIndexRows();
        calcTotal();
    }

    document.getElementById('notaForm').addEventListener('submit', function (ev) {
        ev.preventDefault();

        const saveBtn = document.getElementById('saveBtn');
        saveBtn.disabled = true;
        saveBtn.textContent = 'Menyimpan...';

        reIndexRows();
        calcTotal();

        const formData = new FormData(this);

        fetch(this.action, {
            method: "POST",
            headers: { "X-Requested-With": "XMLHttpRequest" },
            body: formData,
            credentials: "same-origin"
        })
        .then(async (response) => {
            if (response.redirected) {
                window.location.href = response.url;
                return;
            }
            if (response.ok) {
                window.location.href = indexUrl;
                return;
            }
            if (response.status === 422) {
                const data = await response.json();
                showValidationErrors(data.errors || {});
                throw new Error("Validation failed");
            }

            throw new Error("Server error");
        })
        .catch(err => {
            showGenericError("Terjadi kesalahan saat menyimpan data.");
        })
        .finally(() => {
            saveBtn.disabled = false;
            saveBtn.innerHTML = "Simpan Perubahan";
        });
    });

    function showValidationErrors(errors) {
        const container = document.getElementById('errorContainer');
        let html = '<strong>Ada kesalahan validasi:</strong><ul>';

        for (const key in errors) {
            errors[key].forEach(m => html += `<li>${m}</li>`);
        }
        html += '</ul>';

        container.innerHTML = html;
        container.style.display = 'block';
        // scroll to errors
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function showGenericError(msg) {
        const container = document.getElementById('errorContainer');
        container.innerHTML = msg;
        container.style.display = 'block';
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    document.addEventListener("DOMContentLoaded", function () {
        // render catalog initially
        renderCatalog('');
        calcTotal();

        // attach click listener on name inputs to set currentRowIndex
        document.querySelectorAll('.name-input').forEach(n => {
            n.addEventListener('focus', (ev) => {
                currentRowIndex = Number(ev.target.dataset.index);
            });
        });
    });

    // ----------------------------
    // Catalog rendering + search
    // ----------------------------
    function renderCatalog(filter = '') {
        const body = document.getElementById('catalogBody');
        body.innerHTML = '';
        const f = String(filter || '').toLowerCase().trim();

        CATALOG.forEach((it, i) => {
            if (f && !(it.name.toLowerCase().includes(f))) return;

            const tr = document.createElement('tr');
            tr.className = 'catalog-row';
            tr.innerHTML = `
                <td>${escapeHtml(it.name)}</td>
                <td>${numberWithCommas(it.price)}</td>
                <td><button type="button" class="btn btn-sm btn-outline-dark catalog-select" data-idx="${i}">Pilih</button></td>
            `;
            body.appendChild(tr);
        });

        if (!body.children.length) {
            const tr = document.createElement('tr');
            tr.innerHTML = '<td colspan="3" class="text-center text-muted">Item tidak ditemukan</td>';
            body.appendChild(tr);
        }
    }

    // small helpers
    function numberWithCommas(x) {
        if (x === null || x === undefined) return '';
        return String(x).replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function escapeHtml(unsafe) {
        return String(unsafe)
          .replace(/&/g, "&amp;")
          .replace(/</g, "&lt;")
          .replace(/>/g, "&gt;")
          .replace(/"/g, "&quot;")
          .replace(/'/g, "&#039;");
    }
</script>

@endsection
