<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $announcements = Announcement::latest()->paginate(15); // Son eklenenler üste gelecek şekilde ve sayfalayarak
        return view('admin.announcements.index', compact('announcements'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.announcements.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'link_url' => 'nullable|url|max:2048',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'nullable|boolean',
        ]);

        // is_active checkbox işaretlenmemişse false olarak ayarla
        $validatedData['is_active'] = $request->has('is_active');

        Announcement::create($validatedData);

        return redirect()->route('admin.announcements.index')->with('success', 'Duyuru başarıyla oluşturuldu.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Announcement $announcement)
    {
        // Genellikle admin panelinde direkt show kullanılmaz, index veya edit tercih edilir.
        // return view('admin.announcements.show', compact('announcement'));
        return redirect()->route('admin.announcements.edit', $announcement->id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Announcement $announcement)
    {
        return view('admin.announcements.edit', compact('announcement'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Announcement $announcement)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'link_url' => 'nullable|url|max:2048',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'nullable|boolean',
        ]);

        $validatedData['is_active'] = $request->has('is_active');

        $announcement->update($validatedData);

        return redirect()->route('admin.announcements.index')->with('success', 'Duyuru başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return redirect()->route('admin.announcements.index')->with('success', 'Duyuru başarıyla silindi.');
    }
}
