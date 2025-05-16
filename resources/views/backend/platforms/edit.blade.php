@extends('layouts.backend') {{-- admin. -> backend. --}}

@section('title', 'Platform Düzenle: ' . $platform->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Platform Düzenleme Formu</h3>
                </div>
                <form action="{{ route('admin.platforms.update', $platform->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Platform Adı <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $platform->name) }}" required>
                            @error('name')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="slug">Platform Slug (URL Dostu Ad - Değiştirmek istemiyorsanız boş bırakın)</label>
                            <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $platform->slug) }}">
                            <small class="form-text text-muted">Eğer burayı boş bırakırsanız ve platform adını değiştirirseniz, slug otomatik olarak yeni ada göre güncellenecektir. Özel bir slug girmek isterseniz burayı doldurun.</small>
                            @error('slug')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="logo_image">Platform Logosu (Değiştirmek istemiyorsanız boş bırakın)</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('logo_image') is-invalid @enderror" id="logo_image" name="logo_image">
                                    <label class="custom-file-label" for="logo_image">Yeni logo seçin</label>
                                </div>
                            </div>
                            @error('logo_image')
                                <span class="text-danger d-block" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                            @if ($platform->logo_image_path)
                                <div class="mt-2">
                                    <label>Mevcut Logo:</label><br>
                                    <img src="{{ Storage::url($platform->logo_image_path) }}" alt="{{ $platform->name }}" width="150">
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Güncelle</button>
                        <a href="{{ route('admin.platforms.index') }}" class="btn btn-secondary">İptal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/backend/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script> {{-- Projedeki yol farklı olabilir, kontrol edilmeli --}}
<script>
$(function () {
  bsCustomFileInput.init();
});
</script>
@endpush 