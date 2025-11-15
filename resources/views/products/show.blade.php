@extends('layouts.public')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="md:flex">
            @if($product->image)
            <div class="md:w-1/2">
                <img src="{{ $product->image_url }}" 
                     alt="{{ $product->name }}" 
                     class="w-full h-full object-cover">
            </div>
            @endif
            
            <div class="p-6 md:w-{{ $product->image ? '1/2' : 'full' }}">
                @if($product->category)
                <span class="inline-block px-3 py-1 bg-indigo-100 text-indigo-700 text-sm font-medium rounded-full mb-4">
                    {{ $product->category->name }}
                </span>
                @endif
                
                <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $product->name }}</h1>
                
                @if($product->description)
                <p class="text-gray-600 mb-6">{{ $product->description }}</p>
                @endif
                
                <div class="grid grid-cols-1 gap-4 mb-6">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Preço de Venda</p>
                        <p class="text-2xl font-bold text-indigo-600">
                            R$ {{ number_format($product->sale_price ?? $product->price, 2, ',', '.') }}
                        </p>
                    </div>
                </div>
                
                @if($product->description)
                <div class="mb-6">
                    <p class="text-sm text-gray-600 mb-1">Descrição</p>
                    <p class="text-gray-900">{{ $product->description }}</p>
                </div>
                @endif
                
                <div class="flex gap-3">
                    <a href="{{ route('order.create', ['product_id' => $product->id, 'qty' => 1]) }}" 
                       class="flex-1 px-6 py-3 bg-indigo-600 text-white rounded-lg font-semibold text-center hover:bg-indigo-700 transition-colors">
                        Adicionar ao Pedido
                    </a>
                    @auth
                    <a href="{{ route('admin.products.edit', $product) }}" 
                       class="px-6 py-3 bg-gray-600 text-white rounded-lg font-semibold hover:bg-gray-700 transition-colors">
                        Editar
                    </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

