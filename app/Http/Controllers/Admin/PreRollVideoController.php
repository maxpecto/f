<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PreRollVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PreRollVideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $preRollVideos = PreRollVideo::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.pre_roll_videos.index', compact('preRollVideos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.pre_roll_videos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'video_url' => 'required|url|max:2048',
            'target_url' => 'nullable|url|max:2048',
            'skippable_after_seconds' => 'nullable|integer|min:0',
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.pre-roll-videos.create')
                        ->withErrors($validator)
                        ->withInput();
        }

        // Eğer bu video aktif ediliyorsa, diğer tüm videoları pasif yap
        if ($request->input('is_active') == 1) {
            PreRollVideo::where('is_active', true)->update(['is_active' => false]);
        }

        PreRollVideo::create($request->only(['name', 'video_url', 'target_url', 'skippable_after_seconds', 'is_active']));

        return redirect()->route('admin.pre-roll-videos.index')->with('success', 'Ön yükleme videosu başarıyla eklendi.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PreRollVideo  $preRollVideo
     * @return \Illuminate\Http\Response
     */
    public function show(PreRollVideo $preRollVideo)
    {
        // Genellikle admin panelde show metodu kullanılmaz, index veya edit tercih edilir.
        // İsterseniz burayı da doldurabiliriz veya boş bırakabiliriz.
        // Şimdilik edit'e yönlendirelim.
        return redirect()->route('admin.pre-roll-videos.edit', $preRollVideo->id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PreRollVideo  $preRollVideo
     * @return \Illuminate\Http\Response
     */
    public function edit(PreRollVideo $preRollVideo)
    {
        return view('admin.pre_roll_videos.edit', compact('preRollVideo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PreRollVideo  $preRollVideo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PreRollVideo $preRollVideo)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'video_url' => 'required|url|max:2048',
            'target_url' => 'nullable|url|max:2048',
            'skippable_after_seconds' => 'nullable|integer|min:0',
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.pre-roll-videos.edit', $preRollVideo->id)
                        ->withErrors($validator)
                        ->withInput();
        }

        // Eğer bu video aktif ediliyorsa, diğer tüm videoları pasif yap (kendisi hariç)
        if ($request->input('is_active') == 1) {
            PreRollVideo::where('id', '!=', $preRollVideo->id)
                         ->where('is_active', true)
                         ->update(['is_active' => false]);
        }
        
        $preRollVideo->update($request->only(['name', 'video_url', 'target_url', 'skippable_after_seconds', 'is_active']));

        return redirect()->route('admin.pre-roll-videos.index')->with('success', 'Ön yükleme videosu başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PreRollVideo  $preRollVideo
     * @return \Illuminate\Http\Response
     */
    public function destroy(PreRollVideo $preRollVideo)
    {
        $preRollVideo->delete();
        return redirect()->route('admin.pre-roll-videos.index')->with('success', 'Ön yükleme videosu başarıyla silindi.');
    }
}
