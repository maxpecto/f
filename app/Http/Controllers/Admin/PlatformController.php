<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Platform;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PlatformController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $platforms = Platform::withCount('items')->latest()->paginate(15);
        return view('backend.platforms.index', compact('platforms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.platforms.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:platforms,name',
            'logo_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'slug' => 'nullable|string|max:255|unique:platforms,slug',
        ]);

        $data = $request->only('name', 'slug');

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($request->name);
        } else {
            $data['slug'] = Str::slug($request->slug);
        }
        
        if (Platform::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $data['slug'] . '-' . Str::random(4);
        }

        if ($request->hasFile('logo_image')) {
            $originalName = pathinfo($request->file('logo_image')->getClientOriginalName(), PATHINFO_FILENAME);
            $safeName = Str::slug($originalName);
            $extension = $request->file('logo_image')->getClientOriginalExtension();
            $fileNameToStore = 'platform_logos/' . $safeName . '_' . time() . '.' . $extension;
            
            $path = $request->file('logo_image')->storeAs('public', $fileNameToStore);
            $data['logo_image_path'] = $fileNameToStore;
        }

        Platform::create($data);

        return redirect()->route('admin.platforms.index')->with('success', 'Platform başarıyla oluşturuldu.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Platform  $platform
     * @return \Illuminate\Http\Response
     */
    public function show(Platform $platform)
    {
        return view('backend.platforms.show', compact('platform'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Platform  $platform
     * @return \Illuminate\Http\Response
     */
    public function edit(Platform $platform)
    {
        return view('backend.platforms.edit', compact('platform'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Platform  $platform
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Platform $platform)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:platforms,name,' . $platform->id,
            'logo_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'slug' => 'nullable|string|max:255|unique:platforms,slug,' . $platform->id,
        ]);

        $data = $request->only('name', 'slug');

        if ($request->filled('slug')) {
            $data['slug'] = Str::slug($request->slug);
        } else {
            if ($platform->name !== $request->name) {
                 $data['slug'] = Str::slug($request->name);
            } else {
                unset($data['slug']); 
            }
        }
        
        if (isset($data['slug']) && Platform::where('slug', $data['slug'])->where('id', '!=', $platform->id)->exists()) {
            $data['slug'] = $data['slug'] . '-' . Str::random(4);
        }

        if ($request->hasFile('logo_image')) {
            if ($platform->logo_image_path) {
                // Storage::delete('public/' . $platform->logo_image_path);
            }
            $originalName = pathinfo($request->file('logo_image')->getClientOriginalName(), PATHINFO_FILENAME);
            $safeName = Str::slug($originalName);
            $extension = $request->file('logo_image')->getClientOriginalExtension();
            $fileNameToStore = 'platform_logos/' . $safeName . '_' . time() . '.' . $extension;
            $path = $request->file('logo_image')->storeAs('public', $fileNameToStore);
            $data['logo_image_path'] = $fileNameToStore;
        }

        $platform->update($data);

        return redirect()->route('admin.platforms.index')->with('success', 'Platform başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Platform  $platform
     * @return \Illuminate\Http\Response
     */
    public function destroy(Platform $platform)
    {
        if ($platform->logo_image_path) {
            // Storage::delete('public/' . $platform->logo_image_path);
        }
        $platform->delete();
        return redirect()->route('admin.platforms.index')->with('success', 'Platform başarıyla silindi.');
    }
}
