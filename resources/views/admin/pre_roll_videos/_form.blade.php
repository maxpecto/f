<div class="mb-4">
    <label for="name" class="block text-sm font-medium text-gray-700">Video Adı (Opsiyonel)</label>
    <input type="text" name="name" id="name" value="{{ old('name', $preRollVideo->name ?? '') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
</div>

<div class="mb-4">
    <label for="video_url" class="block text-sm font-medium text-gray-700">Video URL (.mp4, .webm vb.) <span class="text-red-500">*</span></label>
    <input type="url" name="video_url" id="video_url" value="{{ old('video_url', $preRollVideo->video_url ?? '') }}" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
    @error('video_url')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label for="target_url" class="block text-sm font-medium text-gray-700">Hedef URL (Tıklandığında gidilecek adres, opsiyonel)</label>
    <input type="url" name="target_url" id="target_url" value="{{ old('target_url', $preRollVideo->target_url ?? '') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
</div>

<div class="mb-4">
    <label for="skippable_after_seconds" class="block text-sm font-medium text-gray-700">Atlanabilir Saniye (Boş bırakılırsa atlanamaz)</label>
    <input type="number" name="skippable_after_seconds" id="skippable_after_seconds" value="{{ old('skippable_after_seconds', $preRollVideo->skippable_after_seconds ?? '') }}" min="0" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
    <p class="text-xs text-gray-500 mt-1">Reklamın kaç saniye sonra atlanabileceğini belirtin. Örneğin: 10. Boş bırakırsanız veya 0 girerseniz reklam atlanamaz.</p>
</div>

<div class="mb-4">
    <label for="is_active" class="block text-sm font-medium text-gray-700">Aktif mi?</label>
    <select name="is_active" id="is_active" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        <option value="1" {{ (old('is_active', $preRollVideo->is_active ?? 0) == 1) ? 'selected' : '' }}>Evet</option>
        <option value="0" {{ (old('is_active', $preRollVideo->is_active ?? 0) == 0) ? 'selected' : '' }}>Hayır</option>
    </select>
</div>

<div>
    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
        Kaydet
    </button>
    <a href="{{ route('admin.pre-roll-videos.index') }}" class="ml-2 inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
        İptal
    </a>
</div> 