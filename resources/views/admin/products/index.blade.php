@extends('layouts.app')

@section('title', 'Productos e Inventario')

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <h2 class="mb-0"><i class="bi bi-box-seam"></i> <span class="d-none d-sm-inline">Productos e </span>Inventario</h2>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#productModal" onclick="resetProductForm()">
            <i class="bi bi-plus-circle"></i> <span class="btn-text-mobile">Nuevo</span>
        </button>
    </div>

    {{-- Resumen --}}
    <div class="row mb-3 stats-row">
        <div class="col-md-3 col-6 mb-2">
            <div class="card border-start border-primary border-4">
                <div class="card-body py-2 px-3">
                    <small class="text-muted">Total</small>
                    <h5 class="mb-0">{{ $totalProducts }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-2">
            <div class="card border-start border-success border-4">
                <div class="card-body py-2 px-3">
                    <small class="text-muted">Valor Inv.</small>
                    <h5 class="mb-0">${{ number_format($inventoryValue, 2) }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-2">
            <div class="card border-start border-warning border-4">
                <div class="card-body py-2 px-3">
                    <small class="text-muted">Stock Bajo</small>
                    <h5 class="mb-0 {{ $lowStock > 0 ? 'text-warning' : '' }}">{{ $lowStock }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-2">
            <div class="card border-start border-danger border-4">
                <div class="card-body py-2 px-3">
                    <small class="text-muted">Sin Stock</small>
                    <h5 class="mb-0 {{ $outOfStock > 0 ? 'text-danger' : '' }}">{{ $outOfStock }}</h5>
                </div>
            </div>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="card mb-3">
        <div class="card-body py-2">
            <form method="GET" action="{{ route('products.index') }}" class="row g-2 align-items-end">
                <div class="col-12 col-md-4">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Buscar nombre, codigo..." value="{{ request('search') }}">
                </div>
                <div class="col-6 col-md-3">
                    <select name="category" class="form-select form-select-sm">
                        <option value="">Categoría</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>{{ $category }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <select name="stock_filter" class="form-select form-select-sm">
                        <option value="">Stock</option>
                        <option value="low" {{ request('stock_filter') == 'low' ? 'selected' : '' }}>Bajo</option>
                        <option value="out" {{ request('stock_filter') == 'out' ? 'selected' : '' }}>Agotado</option>
                        <option value="ok" {{ request('stock_filter') == 'ok' ? 'selected' : '' }}>Normal</option>
                    </select>
                </div>
                <div class="col-12 col-md-2">
                    <button type="submit" class="btn btn-secondary btn-sm w-100"><i class="bi bi-search"></i> Filtrar</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="card">
        <div class="card-body p-0 p-md-3">
            @if($products->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="hide-mobile">Código</th>
                                <th>Producto</th>
                                <th class="hide-tablet">Categoría</th>
                                <th class="text-end hide-mobile">Costo</th>
                                <th class="text-end">Precio</th>
                                <th class="text-center">Stock</th>
                                <th class="text-center hide-tablet">IVA</th>
                                <th class="text-center">Acc.</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                            <tr>
                                <td class="hide-mobile"><code>{{ $product->code }}</code></td>
                                <td>
                                    <strong>{{ $product->name }}</strong>
                                    <span class="d-md-none d-block"><small class="text-muted">{{ $product->code }}</small></span>
                                </td>
                                <td class="hide-tablet">
                                    @if($product->category)
                                        <span class="badge bg-secondary">{{ $product->category }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-end hide-mobile">${{ number_format($product->cost_price, 2) }}</td>
                                <td class="text-end"><strong>${{ number_format($product->sale_price, 2) }}</strong></td>
                                <td class="text-center">
                                    @if($product->stock_type === 'service')
                                        <span class="badge bg-info">Serv</span>
                                    @elseif($product->stock <= 0)
                                        <span class="badge bg-danger">{{ $product->stock }}</span>
                                    @elseif($product->needsRestocking())
                                        <span class="badge bg-warning text-dark">{{ $product->stock }}</span>
                                    @else
                                        <span class="badge bg-success">{{ $product->stock }}</span>
                                    @endif
                                </td>
                                <td class="text-center hide-tablet">
                                    @if($product->has_iva)
                                        <span class="badge bg-success">15%</span>
                                    @else
                                        <span class="badge bg-light text-dark">0%</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="editProduct({{ json_encode($product) }})">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        @if($product->stock_type === 'product')
                                        <button type="button" class="btn btn-outline-success btn-sm" onclick="adjustStock({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->stock }})">
                                            <i class="bi bi-box-arrow-in-down"></i>
                                        </button>
                                        @endif
                                        <form method="POST" action="{{ route('products.destroy', $product) }}" class="d-inline" onsubmit="return confirm('Eliminar?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-2">{{ $products->withQueryString()->links() }}</div>
            @else
                <div class="text-center text-muted py-5">
                    <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                    <p class="mt-2">No hay productos</p>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#productModal" onclick="resetProductForm()">
                        <i class="bi bi-plus-circle"></i> Crear Producto
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Modal Crear/Editar --}}
<div class="modal fade" id="productModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalTitle"><i class="bi bi-box-seam"></i> Nuevo Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="productForm" action="{{ route('products.store') }}">
                @csrf
                <div id="methodField"></div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-6 col-md-4 mb-3">
                            <label class="form-label">Código *</label>
                            <input type="text" name="code" id="p_code" class="form-control" required>
                        </div>
                        <div class="col-6 col-md-8 mb-3">
                            <label class="form-label">Nombre *</label>
                            <input type="text" name="name" id="p_name" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea name="description" id="p_description" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-6 col-md-4 mb-3">
                            <label class="form-label">Categoría</label>
                            <input type="text" name="category" id="p_category" class="form-control" list="categoryList">
                            <datalist id="categoryList">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}">
                                @endforeach
                            </datalist>
                        </div>
                        <div class="col-6 col-md-4 mb-3">
                            <label class="form-label">Marca</label>
                            <input type="text" name="brand" id="p_brand" class="form-control">
                        </div>
                        <div class="col-12 col-md-4 mb-3">
                            <label class="form-label">Tipo *</label>
                            <select name="stock_type" id="p_stock_type" class="form-select" required onchange="toggleStockFields()">
                                <option value="product">Producto</option>
                                <option value="service">Servicio</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">P. Costo *</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="cost_price" id="p_cost_price" class="form-control" step="0.01" min="0" required>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">P. Venta *</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="sale_price" id="p_sale_price" class="form-control" step="0.01" min="0" required>
                            </div>
                            <small class="text-muted" id="marginInfo"></small>
                        </div>
                    </div>
                    <div class="row" id="stockFields">
                        <div class="col-6 mb-3">
                            <label class="form-label">Stock *</label>
                            <input type="number" name="stock" id="p_stock" class="form-control" min="0" value="0" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Stock Mín *</label>
                            <input type="number" name="min_stock" id="p_min_stock" class="form-control" min="0" value="5" required>
                        </div>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input type="checkbox" name="has_iva" class="form-check-input" id="p_has_iva" checked>
                        <label class="form-check-label" for="p_has_iva">IVA (15%)</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-sm" id="productSubmitBtn"><i class="bi bi-save"></i> Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Stock --}}
<div class="modal fade" id="stockModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="bi bi-box-arrow-in-down"></i> Ajustar Stock</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="stockForm">
                @csrf @method('PUT')
                <div class="modal-body">
                    <h6 id="stockProductName" class="mb-3"></h6>
                    <div class="alert alert-info py-2">Stock actual: <strong id="currentStockDisplay"></strong></div>
                    <div class="mb-3">
                        <label class="form-label">Tipo de Ajuste</label>
                        <select id="stockAdjustType" class="form-select" onchange="updateStockPreview()">
                            <option value="add">Agregar</option>
                            <option value="set">Establecer exacto</option>
                            <option value="subtract">Reducir</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cantidad</label>
                        <input type="number" id="stockAdjustQty" class="form-control form-control-lg" min="0" value="0" oninput="updateStockPreview()">
                    </div>
                    <div class="alert alert-warning py-2">Nuevo stock: <strong id="newStockDisplay">0</strong></div>
                    <input type="hidden" name="stock" id="stockFinalValue">
                    <input type="hidden" name="stock_adjust_only" value="1">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success btn-sm"><i class="bi bi-check-circle"></i> Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
let currentStock = 0;
function resetProductForm() {
    document.getElementById('productModalTitle').innerHTML = '<i class="bi bi-box-seam"></i> Nuevo Producto';
    document.getElementById('productForm').action = '{{ route("products.store") }}';
    document.getElementById('methodField').innerHTML = '';
    document.getElementById('productSubmitBtn').innerHTML = '<i class="bi bi-save"></i> Guardar';
    ['p_code','p_name','p_description','p_category','p_brand','p_cost_price','p_sale_price'].forEach(id => document.getElementById(id).value = '');
    document.getElementById('p_stock').value = '0';
    document.getElementById('p_min_stock').value = '5';
    document.getElementById('p_has_iva').checked = true;
    document.getElementById('p_stock_type').value = 'product';
    document.getElementById('marginInfo').textContent = '';
    toggleStockFields();
}
function editProduct(product) {
    document.getElementById('productModalTitle').innerHTML = '<i class="bi bi-pencil"></i> Editar: ' + product.name;
    document.getElementById('productForm').action = '/products/' + product.id;
    document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    document.getElementById('productSubmitBtn').innerHTML = '<i class="bi bi-save"></i> Actualizar';
    document.getElementById('p_code').value = product.code;
    document.getElementById('p_name').value = product.name;
    document.getElementById('p_description').value = product.description || '';
    document.getElementById('p_category').value = product.category || '';
    document.getElementById('p_brand').value = product.brand || '';
    document.getElementById('p_cost_price').value = product.cost_price;
    document.getElementById('p_sale_price').value = product.sale_price;
    document.getElementById('p_stock').value = product.stock;
    document.getElementById('p_min_stock').value = product.min_stock;
    document.getElementById('p_has_iva').checked = !!product.has_iva;
    document.getElementById('p_stock_type').value = product.stock_type;
    toggleStockFields();
    new bootstrap.Modal(document.getElementById('productModal')).show();
}
function toggleStockFields() {
    document.getElementById('stockFields').style.display = document.getElementById('p_stock_type').value === 'service' ? 'none' : '';
}
function adjustStock(productId, productName, stock) {
    currentStock = stock;
    document.getElementById('stockProductName').textContent = productName;
    document.getElementById('currentStockDisplay').textContent = stock;
    document.getElementById('stockForm').action = '/products/' + productId;
    document.getElementById('stockAdjustQty').value = 0;
    document.getElementById('stockAdjustType').value = 'add';
    updateStockPreview();
    new bootstrap.Modal(document.getElementById('stockModal')).show();
}
function updateStockPreview() {
    const type = document.getElementById('stockAdjustType').value;
    const qty = parseInt(document.getElementById('stockAdjustQty').value) || 0;
    let n = type === 'add' ? currentStock + qty : type === 'subtract' ? Math.max(0, currentStock - qty) : qty;
    document.getElementById('newStockDisplay').textContent = n;
    document.getElementById('stockFinalValue').value = n;
}
document.getElementById('p_sale_price').addEventListener('input', function() {
    const cost = parseFloat(document.getElementById('p_cost_price').value) || 0;
    const sale = parseFloat(this.value) || 0;
    if (cost > 0 && sale > 0) {
        const m = ((sale - cost) / cost * 100).toFixed(1);
        document.getElementById('marginInfo').textContent = 'Margen: ' + m + '%';
        document.getElementById('marginInfo').className = m > 0 ? 'text-success small' : 'text-danger small';
    }
});
</script>
@endpush
@endsection
