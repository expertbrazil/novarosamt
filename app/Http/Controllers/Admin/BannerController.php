<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage products');
    }

    public function index(Request $request)
    {
        $query = Banner::query();
        
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->get('search') . '%');
        }
        
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }
        
        $banners = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image_desktop' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_mobile' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active' => 'boolean',
        ]);

        // Upload das imagens
        if ($request->hasFile('image_desktop')) {
            $validated['image_desktop'] = $request->file('image_desktop')->store('banners', 'public');
        }
        
        if ($request->hasFile('image_mobile')) {
            $validated['image_mobile'] = $request->file('image_mobile')->store('banners', 'public');
        }

        $validated['is_active'] = $request->has('is_active');

        Banner::create($validated);

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner criado com sucesso!');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image_desktop' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_mobile' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active' => 'boolean',
        ]);

        // Upload de nova imagem desktop
        if ($request->hasFile('image_desktop')) {
            // Deletar imagem antiga se existir
            if ($banner->image_desktop && Storage::disk('public')->exists($banner->image_desktop)) {
                Storage::disk('public')->delete($banner->image_desktop);
            }
            $validated['image_desktop'] = $request->file('image_desktop')->store('banners', 'public');
        } else {
            // Manter imagem existente se não foi enviada nova
            $validated['image_desktop'] = $banner->image_desktop;
        }

        // Upload de nova imagem mobile
        if ($request->hasFile('image_mobile')) {
            // Deletar imagem antiga se existir
            if ($banner->image_mobile && Storage::disk('public')->exists($banner->image_mobile)) {
                Storage::disk('public')->delete($banner->image_mobile);
            }
            $validated['image_mobile'] = $request->file('image_mobile')->store('banners', 'public');
        } else {
            // Manter imagem existente se não foi enviada nova
            $validated['image_mobile'] = $banner->image_mobile;
        }

        $validated['is_active'] = $request->has('is_active');

        $banner->update($validated);

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner atualizado com sucesso!');
    }

    public function destroy(Banner $banner)
    {
        // Deletar imagens
        if ($banner->image_desktop && Storage::disk('public')->exists($banner->image_desktop)) {
            Storage::disk('public')->delete($banner->image_desktop);
        }
        
        if ($banner->image_mobile && Storage::disk('public')->exists($banner->image_mobile)) {
            Storage::disk('public')->delete($banner->image_mobile);
        }

        $banner->delete();

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner excluído com sucesso!');
    }

    public function toggle(Banner $banner)
    {
        $banner->is_active = !$banner->is_active;
        $banner->save();

        return back()->with('success', 'Status do banner atualizado.');
    }
}

