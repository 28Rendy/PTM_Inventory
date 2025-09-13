@extends('layout.main')

@section('content')
<div class="container py-5 "style="margin-top: 180px;">
    <div class="row justify-content-center">
        <div class="col-md-8">

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card shadow rounded-4 border-0">
                <div class="card-body text-center p-5">

                    @if($user->foto)
                        <img src="{{ asset($user->foto) }}" alt="Foto Profil"
                            class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=ff6f00&color=fff&size=120"
                            alt="Default Avatar" class="rounded-circle mb-3" style="width: 120px; height: 120px;">
                    @endif

                    <h4 class="mb-1">{{ $user->name }}</h4>
                    <p class="text-muted">{{ $user->email }}</p>

                    <div class="mt-4">
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary rounded-pill px-4">
                            <i class="bi bi-pencil-square me-1"></i> Edit Profil
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
