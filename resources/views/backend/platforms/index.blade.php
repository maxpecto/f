@extends('layouts.backend') {{-- admin. -> backend. --}}

@section('title', 'Platformlar')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Platform Listesi</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.platforms.create') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> Yeni Platform Ekle
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Logo</th>
                                <th>Adı</th>
                                <th>Slug</th>
                                <th>İçerik Sayısı</th>
                                <th>Oluşturulma T.</th>
                                <th>Eylemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($platforms as $platform)
                                <tr>
                                    <td>{{ $platform->id }}</td>
                                    <td>
                                        @if ($platform->logo_image_path)
                                            <img src="{{ Storage::url($platform->logo_image_path) }}" alt="{{ $platform->name }}" width="100">
                                        @else
                                            Logo Yok
                                        @endif
                                    </td>
                                    <td>{{ $platform->name }}</td>
                                    <td>{{ $platform->slug }}</td>
                                    <td>{{ $platform->items_count }}</td> {{-- withCount kullandığımız için direkt erişebiliriz --}}
                                    <td>{{ $platform->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('admin.platforms.edit', $platform->id) }}" class="btn btn-primary btn-sm" title="Düzenle"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('admin.platforms.destroy', $platform->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu platformu silmek istediğinizden emin misiniz? İlişkili içeriklerin platform bilgisi kaldırılacaktır.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Sil"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Kayıtlı platform bulunamadı.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    {{ $platforms->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Gerekirse buraya özel JavaScript kodları eklenebilir.
</script>
@endpush 