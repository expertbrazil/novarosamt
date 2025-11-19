<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        $cart = Session::get('cart', []);
        $cartItems = [];
        $total = 0;

        foreach ($cart as $item) {
            $product = Product::find($item['product_id']);
            if ($product) {
                $subtotal = ($product->sale_price ?? $product->price) * $item['quantity'];
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'subtotal' => $subtotal,
                ];
                $total += $subtotal;
            }
        }

        $useMobileLayout = request()->attributes->get('useMobileLayout', false);
        $viewPath = $useMobileLayout ? 'mobile.cart.index' : 'cart.index';
        
        return view($viewPath, [
            'cartItems' => $cartItems,
            'total' => $total,
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        
        if (!$product->is_active) {
            return back()->with('error', 'Este produto não está disponível.');
        }

        if ($product->stock < $request->quantity) {
            return back()->with('error', 'Quantidade solicitada maior que o estoque disponível.');
        }

        $cart = Session::get('cart', []);
        
        // Verificar se o produto já está no carrinho
        $existingIndex = null;
        foreach ($cart as $index => $item) {
            if ($item['product_id'] == $request->product_id) {
                $existingIndex = $index;
                break;
            }
        }

        if ($existingIndex !== null) {
            // Atualizar quantidade
            $newQuantity = $cart[$existingIndex]['quantity'] + $request->quantity;
            if ($product->stock < $newQuantity) {
                return back()->with('error', 'Quantidade total maior que o estoque disponível.');
            }
            $cart[$existingIndex]['quantity'] = $newQuantity;
        } else {
            // Adicionar novo item
            $cart[] = [
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ];
        }

        Session::put('cart', $cart);

        return back()->with('success', 'Produto adicionado ao carrinho!');
    }

    public function update(Request $request, $productId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($productId);
        
        if ($request->quantity > $product->stock) {
            return back()->with('error', 'Quantidade solicitada maior que o estoque disponível.');
        }

        $cart = Session::get('cart', []);
        
        foreach ($cart as $index => $item) {
            if ($item['product_id'] == $productId) {
                if ($request->quantity == 0) {
                    unset($cart[$index]);
                } else {
                    $cart[$index]['quantity'] = $request->quantity;
                }
                break;
            }
        }

        $cart = array_values($cart); // Reindexar array
        Session::put('cart', $cart);

        return back()->with('success', 'Carrinho atualizado!');
    }

    public function remove($productId)
    {
        $cart = Session::get('cart', []);
        
        foreach ($cart as $index => $item) {
            if ($item['product_id'] == $productId) {
                unset($cart[$index]);
                break;
            }
        }

        $cart = array_values($cart); // Reindexar array
        Session::put('cart', $cart);

        return back()->with('success', 'Produto removido do carrinho!');
    }

    public function clear()
    {
        Session::forget('cart');
        return redirect()->route('cart.index')->with('success', 'Carrinho limpo!');
    }

    public function getCount()
    {
        $cart = Session::get('cart', []);
        $count = 0;
        foreach ($cart as $item) {
            $count += $item['quantity'];
        }
        return response()->json(['count' => $count]);
    }
}

