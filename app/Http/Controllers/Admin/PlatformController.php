<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Platform;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

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

        $platform = new Platform();
        $platform->name = $request->name;
        $platform->slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);

        if ($request->hasFile('logo_image')) {
            $image = $request->file('logo_image');
            $extension = $image->getClientOriginalExtension();
            $original_logo_name = 'platform_logo_'.$platform->slug.'_'.time().'.'.$extension;
            $webp_logo_name = 'platform_logo_'.$platform->slug.'_'.time().'.webp';

            $original_logo_path_relative = 'assets/platforms/'.$original_logo_name;
            $webp_logo_path_relative = 'assets/platforms/'.$webp_logo_name;

            // Resize, encode and save original image to storage
            $img_original = Image::make($image)->resize(200, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->encode($extension, 85);
            Storage::disk('public')->put($original_logo_path_relative, (string) $img_original);

            // Resize, encode to WebP and save to storage
            $img_webp = Image::make($image)->resize(200, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->encode('webp', 85);
            Storage::disk('public')->put($webp_logo_path_relative, (string) $img_webp);
            
            $platform->logo_image_path = $original_logo_path_relative; // DB'ye orijinalin yeni göreli yolunu kaydet
        }

        $platform->save();

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
            'name' => 'required|string|max:255|unique:platforms,name,'.$platform->id,
            'logo_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'slug' => 'nullable|string|max:255|unique:platforms,slug,'.$platform->id,
        ]);

        $platform->name = $request->name;
        $platform->slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);

        if ($request->hasFile('logo_image')) {
            // Delete old logo files from storage
            if ($platform->logo_image_path) {
                $old_original_logo_path = $platform->logo_image_path;
                // Eski WebP yolunu bulmak için, orijinal path'in uzantısını değiştiriyoruz.
                // Eğer orijinal path'in kendisi zaten .webp ise (eski bir kayıttan dolayı), bu durumda replaceLast bir şey yapmaz.
                $old_webp_logo_path = Str::replaceLast(pathinfo($old_original_logo_path, PATHINFO_EXTENSION), 'webp', $old_original_logo_path);
                
                if (Storage::disk('public')->exists($old_original_logo_path)) {
                    Storage::disk('public')->delete($old_original_logo_path);
                }
                // Orijinal ve WebP yolları farklıysa ve WebP dosyası varsa sil
                if ($old_original_logo_path !== $old_webp_logo_path && Storage::disk('public')->exists($old_webp_logo_path)) {
                    Storage::disk('public')->delete($old_webp_logo_path);
                }
            }

            $image = $request->file('logo_image');
            $extension = $image->getClientOriginalExtension();
            $original_logo_name = 'platform_logo_'.$platform->slug.'_updated_'.time().'.'.$extension;
            $webp_logo_name = 'platform_logo_'.$platform->slug.'_updated_'.time().'.webp';

            $original_logo_path_relative = 'assets/platforms/'.$original_logo_name;
            $webp_logo_path_relative = 'assets/platforms/'.$webp_logo_name;

            $img_original = Image::make($image)->resize(200, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->encode($extension, 85);
            Storage::disk('public')->put($original_logo_path_relative, (string) $img_original);

            $img_webp = Image::make($image)->resize(200, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->encode('webp', 85);
            Storage::disk('public')->put($webp_logo_path_relative, (string) $img_webp);

            $platform->logo_image_path = $original_logo_path_relative;
        }

        $platform->save();

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
            Storage::delete('public/' . $platform->logo_image_path);
        }
        $platform->delete();
        return redirect()->route('admin.platforms.index')->with('success', 'Platform başarıyla silindi.');
    }
}
