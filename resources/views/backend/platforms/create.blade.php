@extends('layouts.backend') {{-- admin. -> backend. --}}

@section('title', 'Yeni Platform Ekle')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Yeni Platform Formu</h3>
                </div>
                <form action="{{ route('admin.platforms.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Platform Adı <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="slug">Platform Slug (URL Dostu Ad - Boş bırakılırsa otomatik oluşturulur)</label>
                            <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug') }}">
                            @error('slug')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="logo_image">Platform Logosu</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('logo_image') is-invalid @enderror" id="logo_image" name="logo_image">
                                    <label class="custom-file-label" for="logo_image">Dosya seçin</label>
                                </div>
                            </div>
                            @error('logo_image')
                                <span class="text-danger d-block" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success">Kaydet</button>
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