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
                        <div class="input-group input-group-sm mb-2">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" id="customerSearch" class="form-control" placeholder="Buscar por nombre, cédula o RUC...">
                        </div>
                        <div id="customerSearchStatus" class="border rounded px-2 py-2 mb-2 bg-light" style="min-height: 42px;">
                            <div class="d-flex justify-content-between align-items-center gap-2">
                                <small id="customerSearchStatusText" class="mb-0 text-muted">
                                    <i class="bi bi-info-circle"></i> Escriba nombre, cédula o RUC para buscar cliente.
                                </small>
                                <button type="button" id="customerRegisterBtn" class="btn btn-sm btn-warning d-none" onclick="openNewCustomerModalFromSearch()">
                                    <i class="bi bi-person-plus"></i> Registrar
                                </button>
                            </div>
                        </div>
                        <select name="customer_id" id="customerSelect" class="form-select form-select-sm">
                            <option value="">Consumidor Final</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}"
                                        data-name="{{ strtolower($customer->name) }}"
                                        data-identification="{{ strtolower($customer->identification ?? '') }}"
                                        data-search="{{ strtolower($customer->name.' '.$customer->identification.' '.$customer->email) }}">
                                    {{ $customer->name }} {{ $customer->identification ? '- '.$customer->identification : '' }}
                                </option>
                            @endforeach
                        </select>
                        <div class="d-grid mt-2">
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="openNewCustomerModal()">
                                <i class="bi bi-person-plus"></i> Nuevo Cliente
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card mb-2">
                    <div class="card-header py-2"><i class="bi bi-credit-card"></i> Pago</div>
                    <div class="card-body py-2">
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="payment_method" value="cash" id="pay_cash" checked>
                            <label class="btn btn-outline-success btn-sm" for="pay_cash"><i class="bi bi-cash"></i> <span class="d-none d-xl-inline">Efectivo</span></label>
                            @if(in_array('payment_card', $planFeatures ?? []))
                            <input type="radio" class="btn-check" name="payment_method" value="card" id="pay_card">
                            <label class="btn btn-outline-primary btn-sm" for="pay_card"><i class="bi bi-credit-card"></i> <span class="d-none d-xl-inline">Tarjeta</span></label>
                            @endif
                            @if(in_array('payment_transfer', $planFeatures ?? []))
                            <input type="radio" class="btn-check" name="payment_method" value="transfer" id="pay_transfer">
                            <label class="btn btn-outline-info btn-sm" for="pay_transfer"><i class="bi bi-bank"></i> <span class="d-none d-xl-inline">Transf.</span></label>
                            @endif
                            @if(in_array('payment_credit', $planFeatures ?? []))
                            <input type="radio" class="btn-check" name="payment_method" value="credit" id="pay_credit">
                            <label class="btn btn-outline-warning btn-sm" for="pay_credit"><i class="bi bi-receipt"></i> <span class="d-none d-xl-inline">Crédito</span></label>
                            @endif
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
                <input type="hidden" name="amount_received" id="amountReceivedInput" value="">
                <input type="hidden" name="change_amount" id="changeAmountInput" value="">

                <div class="d-grid gap-1">
                    <button type="button" class="btn btn-success" id="saveButton" disabled onclick="openPaymentModal()">
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

{{-- Modal Nuevo Cliente --}}
<div class="modal fade" id="newCustomerModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title"><i class="bi bi-person-plus"></i> Nuevo Cliente</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="newCustomerForm">
                    @csrf
                    <div class="row g-2 mb-2">
                        <div class="col-12 col-md-6">
                            <label class="form-label form-label-sm">Nombre / Razón Social *</label>
                            <input type="text" class="form-control form-control-sm" id="ncName" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label form-label-sm">Tipo de Identificación *</label>
                            <select id="ncIdType" class="form-select form-select-sm" required>
                                <option value="cedula">Cédula</option>
                                <option value="ruc">RUC</option>
                                <option value="pasaporte">Pasaporte</option>
                                <option value="consumidor_final">Consumidor Final</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label form-label-sm">N° Identificación</label>
                            <input type="text" class="form-control form-control-sm" id="ncIdentification" placeholder="Ingrese cédula, RUC o pasaporte">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label form-label-sm">Teléfono</label>
                            <input type="text" class="form-control form-control-sm" id="ncPhone">
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label form-label-sm"><i class="bi bi-envelope-at text-primary"></i> Email <small class="text-muted">(para factura electrónica)</small></label>
                        <input type="email" class="form-control form-control-sm" id="ncEmail" placeholder="cliente@ejemplo.com -- la factura se envía a este correo">
                    </div>

                    <div class="mb-2">
                        <label class="form-label form-label-sm">Dirección</label>
                        <input type="text" class="form-control form-control-sm" id="ncAddress">
                    </div>

                    <div class="row g-2 mb-2">
                        <div class="col-12 col-md-6">
                            <label class="form-label form-label-sm">Ciudad</label>
                            <input type="text" class="form-control form-control-sm" id="ncCity">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label form-label-sm">Provincia</label>
                            <input type="text" class="form-control form-control-sm" id="ncProvince">
                        </div>
                    </div>

                    <div class="row g-2 mb-2">
                        <div class="col-12 col-md-6">
                            <label class="form-label form-label-sm">Límite de Crédito</label>
                            <input type="number" class="form-control form-control-sm" id="ncCreditLimit" value="0" min="0" step="0.01">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label form-label-sm">Días de Crédito</label>
                            <input type="number" class="form-control form-control-sm" id="ncCreditDays" value="0" min="0" step="1">
                        </div>
                    </div>

                    <div class="mb-1">
                        <label class="form-label form-label-sm">Notas</label>
                        <textarea class="form-control form-control-sm" id="ncNotes" rows="2"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary btn-sm" id="saveNewCustomerBtn" onclick="submitNewCustomer()">
                    <i class="bi bi-check-circle"></i> Guardar Cliente
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Cobro Efectivo --}}
<div class="modal fade" id="paymentModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white py-2">
                <h6 class="modal-title"><i class="bi bi-cash-coin"></i> Cobro</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <small class="text-muted">Total a Cobrar</small>
                    <h2 class="text-success fw-bold mb-0" id="modalTotal">$0.00</h2>
                    <small class="text-muted" id="modalPayMethod"></small>
                </div>

                <div id="cashSection">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Monto Recibido del Cliente</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text">$</span>
                            <input type="number" id="modalAmountReceived" class="form-control text-end fs-4"
                                   step="0.01" min="0" placeholder="0.00" autofocus
                                   oninput="calculateChange()">
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center p-3 rounded" id="changeBox" style="background: #f8f9fa;">
                            <span class="fw-bold fs-5">Vuelto:</span>
                            <span class="fw-bold fs-3" id="modalChange">$0.00</span>
                        </div>
                    </div>

                    {{-- Botones rápidos de billetes --}}
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Monto exacto o billetes:</small>
                        <div class="d-flex flex-wrap gap-1">
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setAmount('exact')">Exacto</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setAmount(1)">$1</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setAmount(5)">$5</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setAmount(10)">$10</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setAmount(20)">$20</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setAmount(50)">$50</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setAmount(100)">$100</button>
                        </div>
                    </div>
                </div>

                <div id="nonCashSection" style="display:none;">
                    <div class="alert alert-info py-2 text-center">
                        <i class="bi bi-info-circle"></i> Pago registrado automáticamente
                    </div>
                </div>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="confirmPayBtn" onclick="confirmPayment()" disabled>
                    <i class="bi bi-check-circle"></i> Confirmar Cobro
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let cart = [];
const IVA_RATE = 0.15;
const createCustomerUrl = "{{ route('customers.store') }}";

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
    const btn = document.getElementById('saveButton');
    const badge = document.getElementById('cartCount');

    if (cart.length === 0) {
        tbody.innerHTML = '<tr id="emptyCart"><td colspan="4" class="text-center text-muted py-3">' +
            '<i class="bi bi-cart-x fs-4"></i><p class="mb-0 small">Toca un producto para agregar</p></td></tr>';
        btn.disabled = true;
        badge.textContent = '0';
        updateTotals();
        return;
    }

    btn.disabled = false;
    let total = cart.reduce((s, i) => s + i.quantity, 0);
    badge.textContent = total;

    let html = '';
    cart.forEach((item, i) => {
        const sub = item.price * item.quantity;
        html += '<tr>' +
            '<td class="small">' + item.name +
                '<input type="hidden" name="items[' + i + '][product_id]" value="' + item.id + '">' +
                '<input type="hidden" name="items[' + i + '][quantity]" value="' + item.quantity + '">' +
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
    document.getElementById('confirmPayBtn').disabled = true;
    document.getElementById('confirmPayBtn').innerHTML = '<span class="spinner-border spinner-border-sm"></span> Procesando...';
});

function getSelectedPaymentMethod() {
    return document.querySelector('input[name="payment_method"]:checked')?.value || 'cash';
}

function getCurrentTotal() {
    let subtotal = 0, iva = 0;
    cart.forEach(item => {
        const s = item.price * item.quantity;
        subtotal += s;
        if (item.hasIva) iva += s * IVA_RATE;
    });
    const discount = parseFloat(document.getElementById('discountInput').value) || 0;
    return Math.max(0, subtotal + iva - discount);
}

function openPaymentModal() {
    if (cart.length === 0) { alert('Agregue productos'); return; }

    const total = getCurrentTotal();
    const method = getSelectedPaymentMethod();
    const methodNames = {cash: 'Efectivo', card: 'Tarjeta', transfer: 'Transferencia', credit: 'Crédito'};

    document.getElementById('modalTotal').textContent = '$' + total.toFixed(2);
    document.getElementById('modalPayMethod').textContent = methodNames[method] || method;

    if (method === 'cash') {
        document.getElementById('cashSection').style.display = '';
        document.getElementById('nonCashSection').style.display = 'none';
        document.getElementById('modalAmountReceived').value = '';
        document.getElementById('modalChange').textContent = '$0.00';
        document.getElementById('changeBox').style.background = '#f8f9fa';
        document.getElementById('changeBox').className = 'd-flex justify-content-between align-items-center p-3 rounded';
        document.getElementById('confirmPayBtn').disabled = true;
    } else {
        document.getElementById('cashSection').style.display = 'none';
        document.getElementById('nonCashSection').style.display = '';
        document.getElementById('amountReceivedInput').value = '';
        document.getElementById('changeAmountInput').value = '';
        document.getElementById('confirmPayBtn').disabled = false;
    }

    const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
    modal.show();

    if (method === 'cash') {
        setTimeout(() => document.getElementById('modalAmountReceived').focus(), 500);
    }
}

function calculateChange() {
    const total = getCurrentTotal();
    const received = parseFloat(document.getElementById('modalAmountReceived').value) || 0;
    const change = received - total;
    const changeEl = document.getElementById('modalChange');
    const changeBox = document.getElementById('changeBox');
    const confirmBtn = document.getElementById('confirmPayBtn');

    if (received >= total && received > 0) {
        changeEl.textContent = '$' + change.toFixed(2);
        changeEl.className = 'fw-bold fs-3 text-success';
        changeBox.style.background = '#d1e7dd';
        confirmBtn.disabled = false;
    } else {
        changeEl.textContent = received > 0 ? '-$' + Math.abs(change).toFixed(2) : '$0.00';
        changeEl.className = 'fw-bold fs-3 ' + (received > 0 ? 'text-danger' : '');
        changeBox.style.background = received > 0 ? '#f8d7da' : '#f8f9fa';
        confirmBtn.disabled = true;
    }
}

function setAmount(val) {
    const input = document.getElementById('modalAmountReceived');
    if (val === 'exact') {
        input.value = getCurrentTotal().toFixed(2);
    } else {
        input.value = parseFloat(val).toFixed(2);
    }
    calculateChange();
}

function confirmPayment() {
    const method = getSelectedPaymentMethod();
    const total = getCurrentTotal();

    if (method === 'cash') {
        const received = parseFloat(document.getElementById('modalAmountReceived').value) || 0;
        if (received < total) { alert('El monto recibido es insuficiente'); return; }
        document.getElementById('amountReceivedInput').value = received.toFixed(2);
        document.getElementById('changeAmountInput').value = (received - total).toFixed(2);
    }

    document.getElementById('posForm').submit();
}

function openNewCustomerModal() {
    const modal = new bootstrap.Modal(document.getElementById('newCustomerModal'));
    modal.show();
    setTimeout(() => document.getElementById('ncName').focus(), 250);
}

function openNewCustomerModalFromSearch() {
    const term = (document.getElementById('customerSearch').value || '').trim();
    openNewCustomerModal();

    if (!term) return;

    const looksLikeId = /^[0-9]{6,13}$/.test(term);
    if (looksLikeId) {
        document.getElementById('ncIdentification').value = term;
        document.getElementById('ncIdType').value = term.length === 13 ? 'ruc' : 'cedula';
        document.getElementById('ncName').value = '';
    } else {
        document.getElementById('ncName').value = term;
        document.getElementById('ncIdentification').value = '';
    }

    document.getElementById('ncIdType').dispatchEvent(new Event('change'));
}

function setCustomerSearchStatus(type, text) {
    const statusBox = document.getElementById('customerSearchStatus');
    const statusText = document.getElementById('customerSearchStatusText');
    const registerBtn = document.getElementById('customerRegisterBtn');
    if (!statusBox || !statusText || !registerBtn) return;

    statusBox.className = 'border rounded px-2 py-2 mb-2';
    registerBtn.classList.add('d-none');

    if (type === 'found') {
        statusBox.classList.add('bg-success-subtle', 'border-success');
        statusText.className = 'mb-0 text-success';
        statusText.innerHTML = '<i class="bi bi-check-circle"></i> ' + text;
    } else if (type === 'not-found') {
        statusBox.classList.add('bg-warning-subtle', 'border-warning');
        statusText.className = 'mb-0 text-warning-emphasis';
        statusText.innerHTML = '<i class="bi bi-exclamation-triangle"></i> ' + text;
        registerBtn.classList.remove('d-none');
    } else {
        statusBox.classList.add('bg-light', 'border-secondary-subtle');
        statusText.className = 'mb-0 text-muted';
        statusText.innerHTML = '<i class="bi bi-info-circle"></i> ' + text;
    }
}

function customerOptionLabel(customer) {
    return customer.identification
        ? `${customer.name} - ${customer.identification}`
        : customer.name;
}

async function submitNewCustomer() {
    const name = document.getElementById('ncName').value.trim();
    const identificationType = document.getElementById('ncIdType').value;
    const identification = document.getElementById('ncIdentification').value.trim();
    const phone = document.getElementById('ncPhone').value.trim();
    const email = document.getElementById('ncEmail').value.trim();
    const address = document.getElementById('ncAddress').value.trim();
    const city = document.getElementById('ncCity').value.trim();
    const province = document.getElementById('ncProvince').value.trim();
    const creditLimit = parseFloat(document.getElementById('ncCreditLimit').value) || 0;
    const creditDays = parseInt(document.getElementById('ncCreditDays').value, 10) || 0;
    const notes = document.getElementById('ncNotes').value.trim();
    const saveBtn = document.getElementById('saveNewCustomerBtn');

    if (!name) {
        alert('Ingrese el nombre del cliente');
        return;
    }

    if (identificationType !== 'consumidor_final' && !identification) {
        alert('Ingrese cédula/RUC/pasaporte del cliente');
        return;
    }

    saveBtn.disabled = true;
    saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Guardando...';

    try {
        const response = await fetch(createCustomerUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                name,
                identification_type: identificationType,
                identification,
                phone,
                email,
                address,
                city,
                province,
                credit_limit: creditLimit,
                credit_days: creditDays,
                notes,
            })
        });

        if (!response.ok) {
            const err = await response.json();
            const firstError = err?.errors ? Object.values(err.errors)[0][0] : 'No se pudo crear el cliente';
            throw new Error(firstError);
        }

        const data = await response.json();
        const customer = data.customer;
        const select = document.getElementById('customerSelect');
        const option = document.createElement('option');
        option.value = customer.id;
        option.textContent = customerOptionLabel(customer);
        option.dataset.search = `${(customer.name || '').toLowerCase()} ${(customer.identification || '').toLowerCase()} ${(email || '').toLowerCase()}`;
        select.appendChild(option);
        select.value = String(customer.id);

        const modalEl = document.getElementById('newCustomerModal');
        const modal = bootstrap.Modal.getInstance(modalEl);
        modal.hide();
        document.getElementById('newCustomerForm').reset();
        alert('Cliente creado y seleccionado');
    } catch (error) {
        alert(error.message || 'Error al crear cliente');
    } finally {
        saveBtn.disabled = false;
        saveBtn.innerHTML = '<i class="bi bi-check-circle"></i> Guardar Cliente';
    }
}

document.getElementById('customerSearch').addEventListener('input', function() {
    filterAndAutoSelectCustomer(this.value);
});

function filterAndAutoSelectCustomer(rawTerm) {
    const term = (rawTerm || '').toLowerCase().trim();
    const select = document.getElementById('customerSelect');
    const matches = [];
    const exactMatches = [];

    Array.from(select.options).forEach((opt, idx) => {
        if (idx === 0) {
            opt.hidden = false;
            return;
        }

        const text = (opt.textContent || '').toLowerCase();
        const dataSearch = (opt.dataset.search || '').toLowerCase();
        const dataName = (opt.dataset.name || '').toLowerCase();
        const dataIdentification = (opt.dataset.identification || '').toLowerCase();
        const isMatch = !term || text.includes(term) || dataSearch.includes(term);
        opt.hidden = !isMatch;
        if (isMatch) matches.push(opt);
        if (term && (dataName === term || dataIdentification === term || text.trim() === term)) {
            exactMatches.push(opt);
        }
    });

    // Si no hay término, por defecto consumidor final
    if (!term) {
        select.value = '';
        setCustomerSearchStatus('idle', 'Escriba nombre, cédula o RUC para buscar cliente.');
        return { count: 0, matches: [] };
    }

    if (matches.length === 0 || exactMatches.length === 0) {
        // Estado persistente: no existe cliente exacto para el dato escrito.
        select.value = '';
        setCustomerSearchStatus('not-found', 'Cliente no existe. Puede registrarlo ahora o facturar como consumidor final.');
    }

    if (matches.length === 0) {
        return { count: 0, matches };
    }

    // Auto-selección inteligente: exacto por identificación o nombre, si no hay exacto y solo uno, selecciona ese.
    const exact = matches.find((opt) => {
        const text = (opt.textContent || '').toLowerCase().trim();
        const parts = text.split('-').map(s => s.trim());
        const idPart = parts[1] || '';
        const dataName = (opt.dataset.name || '').toLowerCase();
        const dataIdentification = (opt.dataset.identification || '').toLowerCase();
        return text === term || idPart === term || dataName === term || dataIdentification === term;
    });

    if (exact) {
        select.value = exact.value;
        setCustomerSearchStatus('found', 'Cliente encontrado y seleccionado automáticamente.');
    } else if (matches.length === 1) {
        select.value = matches[0].value;
        setCustomerSearchStatus('found', 'Cliente encontrado y seleccionado automáticamente.');
    } else {
        setCustomerSearchStatus('idle', 'Hay varias coincidencias. Seleccione un cliente de la lista.');
    }

    return { count: matches.length, matches };
}

document.getElementById('customerSearch').addEventListener('keydown', function(e) {
    if (e.key !== 'Enter') return;
    e.preventDefault();

    const result = filterAndAutoSelectCustomer(this.value);
    if (result.count === 0) {
        openNewCustomerModalFromSearch();
    }
});

document.getElementById('ncIdType').addEventListener('change', function() {
    const identificationInput = document.getElementById('ncIdentification');
    if (this.value === 'consumidor_final') {
        identificationInput.value = '';
        identificationInput.disabled = true;
        identificationInput.placeholder = 'No aplica para consumidor final';
    } else {
        identificationInput.disabled = false;
        identificationInput.placeholder = 'Ingrese cédula, RUC o pasaporte';
    }
});

document.getElementById('customerSelect').addEventListener('change', function() {
    const selectedText = this.options[this.selectedIndex]?.textContent?.trim();
    if (this.value) {
        setCustomerSearchStatus('found', `Cliente seleccionado: ${selectedText}`);
    } else if ((document.getElementById('customerSearch')?.value || '').trim()) {
        setCustomerSearchStatus('not-found', 'Cliente no existe. Puede registrarlo ahora o facturar como consumidor final.');
    } else {
        setCustomerSearchStatus('idle', 'Escriba nombre, cédula o RUC para buscar cliente.');
    }
});

setCustomerSearchStatus('idle', 'Escriba nombre, cédula o RUC para buscar cliente.');
</script>
@endpush
@endsection
