@extends('layouts.app')

@section('title', 'Punto de Venta')

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-2 gap-2">
        <h2 class="mb-0"><i class="bi bi-cart3"></i> <span class="d-none d-sm-inline">Punto de </span>Venta</h2>
        @if($openRegister)
            <span class="badge bg-success"><i class="bi bi-cash-coin"></i> Caja Abierta</span>
        @else
            <span class="badge bg-warning text-dark"><i class="bi bi-exclamation-triangle"></i> Sin Caja - <a href="{{ route('cash.index') }}" class="text-dark fw-bold">Abrir</a></span>
        @endif
    </div>

    <form method="POST" action="{{ route('pos.store') }}" id="posForm">
        @csrf
        <div class="row">
            {{-- Productos --}}
            <div class="col-lg-8 mb-3">
                <div class="card mb-2">
                    <div class="card-body py-2">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" id="productSearch" class="form-control" placeholder="Buscar producto..." autofocus>
                        </div>
                    </div>
                </div>

                <div id="productGrid" class="row g-1 g-md-2 mb-2" style="max-height: 240px; overflow-y: auto; -webkit-overflow-scrolling: touch;">
                    @foreach($products as $product)
                    <div class="col-4 col-md-3 col-lg-3 product-item"
                         data-name="{{ strtolower($product->name) }}"
                         data-code="{{ strtolower($product->code) }}">
                        <div class="card h-100 border {{ $product->stock <= 0 && $product->stock_type === 'product' ? 'border-danger opacity-50' : '' }}"
                             style="cursor:pointer"
                             onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', '{{ $product->code }}', {{ $product->sale_price }}, {{ $product->has_iva ? 1 : 0 }}, {{ $product->stock }}, '{{ $product->stock_type }}')">
                            <div class="card-body text-center p-1 p-md-2">
                                <i class="bi {{ $product->stock_type === 'service' ? 'bi-tools' : 'bi-box-seam' }} text-primary d-none d-md-inline" style="font-size:1.3rem;"></i>
                                <p class="mb-0 small fw-bold text-truncate" style="font-size:0.78rem;">{{ $product->name }}</p>
                                <p class="mb-0 fw-bold text-primary" style="font-size:0.85rem;">${{ number_format($product->sale_price, 2) }}</p>
                                @if($product->stock_type === 'product')
                                    <small class="{{ $product->stock <= 0 ? 'text-danger' : 'text-muted' }}" style="font-size:0.65rem;">St: {{ $product->stock }}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Carrito --}}
                <div class="card">
                    <div class="card-header py-2 d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-cart-check"></i> <span class="d-none d-sm-inline">Detalle</span></span>
                        <span class="badge bg-primary" id="cartCount">0</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Producto</th>
                                        <th class="text-center" style="width:90px;">Cant.</th>
                                        <th class="text-end">Subt.</th>
                                        <th style="width:30px;"></th>
                                    </tr>
                                </thead>
                                <tbody id="cartItems">
                                    <tr id="emptyCart">
                                        <td colspan="4" class="text-center text-muted py-3">
                                            <i class="bi bi-cart-x fs-4"></i>
                                            <p class="mb-0 small">Toca un producto para agregar</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Resumen --}}
            <div class="col-lg-4">
                <div class="card mb-2">
                    <div class="card-header py-2"><i class="bi bi-person"></i> Cliente</div>
                    <div class="card-body py-2">
                        <select name="customer_id" id="customerSelect" class="form-select form-select-sm" required>
                            <option value="">-- Seleccionar --</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }} {{ $customer->identification ? '- '.$customer->identification : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="card mb-2">
                    <div class="card-header py-2"><i class="bi bi-credit-card"></i> Pago</div>
                    <div class="card-body py-2">
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="payment_method" value="cash" id="pay_cash" checked>
                            <label class="btn btn-outline-success btn-sm" for="pay_cash"><i class="bi bi-cash"></i> <span class="d-none d-xl-inline">Efectivo</span></label>
                            <input type="radio" class="btn-check" name="payment_method" value="card" id="pay_card">
                            <label class="btn btn-outline-primary btn-sm" for="pay_card"><i class="bi bi-credit-card"></i> <span class="d-none d-xl-inline">Tarjeta</span></label>
                            <input type="radio" class="btn-check" name="payment_method" value="transfer" id="pay_transfer">
                            <label class="btn btn-outline-info btn-sm" for="pay_transfer"><i class="bi bi-bank"></i> <span class="d-none d-xl-inline">Transf.</span></label>
                        </div>
                    </div>
                </div>

                <div class="card mb-2">
                    <div class="card-header py-2"><i class="bi bi-calculator"></i> Resumen</div>
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Subtotal:</span>
                            <strong id="subtotalDisplay">$0.00</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>IVA 15%:</span>
                            <strong id="ivaDisplay">$0.00</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>Desc:</span>
                            <div class="input-group input-group-sm" style="width:100px;">
                                <span class="input-group-text">$</span>
                                <input type="number" name="discount" id="discountInput" class="form-control text-end" value="0" step="0.01" min="0" onchange="updateTotals()">
                            </div>
                        </div>
                        <hr class="my-1">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">TOTAL:</h5>
                            <h4 class="mb-0 text-success fw-bold" id="totalDisplay">$0.00</h4>
                        </div>
                    </div>
                </div>

                <textarea name="notes" class="form-control form-control-sm mb-2" rows="1" placeholder="Notas (opcional)"></textarea>

                <div class="d-grid gap-1">
                    <button type="submit" class="btn btn-success" id="saveButton" disabled>
                        <i class="bi bi-check-circle"></i> FACTURAR
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="clearCart()">
                        <i class="bi bi-x-circle"></i> Limpiar
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
let cart = [];
const IVA_RATE = 0.15;

function addToCart(id, name, code, price, hasIva, stock, stockType) {
    if (stockType === 'product' && stock <= 0) { alert('Sin stock'); return; }
    const existing = cart.find(item => item.id === id);
    if (existing) {
        if (stockType === 'product' && existing.quantity >= stock) { alert('Stock máximo: ' + stock); return; }
        existing.quantity++;
    } else {
        cart.push({ id, name, code, price: parseFloat(price), hasIva: !!hasIva, quantity: 1, stock, stockType });
    }
    renderCart();
}

function removeFromCart(i) { cart.splice(i, 1); renderCart(); }

function changeQty(i, v) {
    v = parseInt(v);
    if (v < 1) { removeFromCart(i); return; }
    if (cart[i].stockType === 'product' && v > cart[i].stock) { v = cart[i].stock; }
    cart[i].quantity = v;
    renderCart();
}

function renderCart() {
    const tbody = document.getElementById('cartItems');
    const empty = document.getElementById('emptyCart');
    const btn = document.getElementById('saveButton');
    const badge = document.getElementById('cartCount');
    document.querySelectorAll('.cart-hidden-input').forEach(el => el.remove());

    if (cart.length === 0) {
        empty.style.display = '';
        btn.disabled = true;
        badge.textContent = '0';
        updateTotals();
        return;
    }
    empty.style.display = 'none';
    btn.disabled = false;
    let total = cart.reduce((s, i) => s + i.quantity, 0);
    badge.textContent = total;

    let html = '';
    cart.forEach((item, i) => {
        const sub = item.price * item.quantity;
        html += '<tr>' +
            '<td class="small">' + item.name +
                '<input type="hidden" class="cart-hidden-input" name="items[' + i + '][product_id]" value="' + item.id + '">' +
                '<input type="hidden" class="cart-hidden-input" name="items[' + i + '][quantity]" value="' + item.quantity + '">' +
                '<br><small class="text-muted">$' + item.price.toFixed(2) + (item.hasIva ? ' +IVA' : '') + '</small>' +
            '</td>' +
            '<td class="text-center">' +
                '<div class="input-group input-group-sm">' +
                    '<button type="button" class="btn btn-outline-secondary px-1" onclick="changeQty('+i+','+(item.quantity-1)+')">-</button>' +
                    '<input type="number" class="form-control text-center px-0" value="'+item.quantity+'" min="1"' +
                        (item.stockType==='product'?' max="'+item.stock+'"':'') +
                        ' onchange="changeQty('+i+',this.value)" style="width:36px">' +
                    '<button type="button" class="btn btn-outline-secondary px-1" onclick="changeQty('+i+','+(item.quantity+1)+')">+</button>' +
                '</div>' +
            '</td>' +
            '<td class="text-end fw-bold small">$' + sub.toFixed(2) + '</td>' +
            '<td><button type="button" class="btn btn-sm btn-outline-danger px-1" onclick="removeFromCart('+i+')"><i class="bi bi-x"></i></button></td>' +
        '</tr>';
    });
    tbody.innerHTML = html;
    updateTotals();
}

function updateTotals() {
    let subtotal = 0, iva = 0;
    cart.forEach(item => {
        const s = item.price * item.quantity;
        subtotal += s;
        if (item.hasIva) iva += s * IVA_RATE;
    });
    const discount = parseFloat(document.getElementById('discountInput').value) || 0;
    document.getElementById('subtotalDisplay').textContent = '$' + subtotal.toFixed(2);
    document.getElementById('ivaDisplay').textContent = '$' + iva.toFixed(2);
    document.getElementById('totalDisplay').textContent = '$' + Math.max(0, subtotal + iva - discount).toFixed(2);
}

function clearCart() {
    if (cart.length > 0 && confirm('Limpiar venta?')) { cart = []; renderCart(); }
}

document.getElementById('productSearch').addEventListener('input', function() {
    const s = this.value.toLowerCase();
    document.querySelectorAll('.product-item').forEach(el => {
        el.style.display = (el.dataset.name.includes(s) || el.dataset.code.includes(s)) ? '' : 'none';
    });
});

document.getElementById('posForm').addEventListener('submit', function(e) {
    if (cart.length === 0) { e.preventDefault(); alert('Agregue productos'); return; }
    if (!document.getElementById('customerSelect').value) { e.preventDefault(); alert('Seleccione cliente'); return; }
    document.getElementById('saveButton').disabled = true;
    document.getElementById('saveButton').innerHTML = '<span class="spinner-border spinner-border-sm"></span> Procesando...';
});
</script>
@endpush
@endsection
