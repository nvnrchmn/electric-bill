@extends('layouts.app')

@section('content')
    <div class="flex h-screen bg-base-100">
        @include('admin.sidebar')
        <div class="flex-1 p-10">
            <h1 class="text-3xl font-bold mb-6">Edit Level: {{ $level->nama_level }}</h1>

            <div class="card shadow-xl max-w-lg mx-auto">
                <div class="card-body">
                    <form action="{{ route('admin.levels.update', $level->id_level) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-control mb-4">
                            <label class="label" for="nama_level">
                                <span class="label-text">Nama Level</span>
                            </label>
                            <input type="text" id="nama_level" name="nama_level"
                                placeholder="Contoh: Administrator, Petugas, Pelanggan"
                                class="input input-bordered w-full @error('nama_level') input-error @enderror"
                                value="{{ old('nama_level', $level->nama_level) }}" required autofocus />
                            @error('nama_level')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <div class="form-control mt-6">
                            <button type="submit" class="btn btn-primary">Update Level</button>
                            <a href="{{ route('admin.levels.index') }}" class="btn btn-ghost">Kembali ke Daftar Level</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
