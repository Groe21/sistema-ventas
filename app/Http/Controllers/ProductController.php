<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\PlanService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $businessId = auth()->user()->business_id;
        $query = Product::where('business_id', $businessId);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")
                  ->orWhere('code', 'ilike', "%{$search}%")
                  ->orWhere('category', 'ilike', "%{$search}%")
                  ->orWhere('brand', 'ilike', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('stock_filter')) {
            match ($request->stock_filter) {
                'low' => $query->where('stock_type', 'product')->whereColumn('stock', '<=', 'min_stock')->where('stock', '>', 0),
                'out' => $query->where('stock_type', 'product')->where('stock', '<=', 0),
                'ok' => $query->where(function ($q) {
                    $q->where('stock_type', 'service')->orWhereColumn('stock', '>', 'min_stock');
                }),
            };
        }

        $products = $query->latest()->paginate(20);

        $categories = Product::where('business_id', $businessId)
            ->whereNotNull('category')
            ->distinct()->pluck('category');

        $totalProducts = Product::where('business_id', $businessId)->count();
        $inventoryValue = Product::where('business_id', $businessId)
            ->where('stock_type', 'product')
            ->selectRaw('COALESCE(SUM(cost_price * stock), 0) as total')
            ->value('total');
        $lowStock = Product::where('business_id', $businessId)
            ->where('stock_type', 'product')
            ->whereColumn('stock', '<=', 'min_stock')
            ->where('stock', '>', 0)->count();
        $outOfStock = Product::where('business_id', $businessId)
            ->where('stock_type', 'product')
            ->where('stock', '<=', 0)->count();

        return view('admin.products.index', compact(
            'products', 'categories', 'totalProducts', 'inventoryValue', 'lowStock', 'outOfStock'
        ));
    }

    public function store(Request $request)
    {
        // Check plan product limit
        $planService = app(PlanService::class);
        if (!$planService->canAddProduct(auth()->user()->business)) {
            $info = $planService->getLimitsInfo(auth()->user()->business);
            return redirect()->back()->with('error',
                "Límite de productos alcanzado ({$info['products']['current']}/{$info['products']['limit']}). Mejora tu plan para agregar más."
            );
        }

        $validated = $request->validate([
            'code' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'brand' => 'nullable|string|max:100',
            'cost_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'has_iva' => 'nullable',
            'stock_type' => 'required|in:product,service',
        ]);

        $businessId = auth()->user()->business_id;

        // Validar codigo unico dentro del negocio
        $exists = Product::where('business_id', $businessId)->where('code', $validated['code'])->exists();
        if ($exists) {
            return back()->with('error', 'Ya existe un producto con ese codigo.')->withInput();
        }

        $validated['business_id'] = $businessId;
        $validated['has_iva'] = $request->has('has_iva');

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Producto creado exitosamente.');
    }

    public function update(Request $request, Product $product)
    {
        if ($product->business_id !== auth()->user()->business_id) {
            abort(403);
        }

        // Ajuste rapido de stock
        if ($request->has('stock_adjust_only')) {
            $request->validate(['stock' => 'required|integer|min:0']);
            $product->update(['stock' => $request->stock]);
            return redirect()->route('products.index')->with('success', "Stock de {$product->name} actualizado a {$request->stock} unidades.");
        }

        $validated = $request->validate([
            'code' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'brand' => 'nullable|string|max:100',
            'cost_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'has_iva' => 'nullable',
            'stock_type' => 'required|in:product,service',
        ]);

        $businessId = auth()->user()->business_id;
        $exists = Product::where('business_id', $businessId)
            ->where('code', $validated['code'])
            ->where('id', '!=', $product->id)->exists();
        if ($exists) {
            return back()->with('error', 'Ya existe otro producto con ese codigo.')->withInput();
        }

        $validated['has_iva'] = $request->has('has_iva');
        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Producto actualizado exitosamente.');
    }

    public function destroy(Product $product)
    {
        if ($product->business_id !== auth()->user()->business_id) {
            abort(403);
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Producto eliminado.');
    }
}
