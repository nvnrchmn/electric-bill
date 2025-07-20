@extends('layouts.app')

@section('content')
    <div class="flex h-screen bg-base-100">
        @include('admin.sidebar')

        <div class="flex-1 p-10">
            <h1 class="text-3xl font-bold  mb-6">Edit Tarif: {{ $tarif->daya }}</h1>

            <div class="card  shadow-xl max-w-lg mx-auto">
                <div class="card-body">
                    <form action="{{ route('admin.tarifs.update', $tarif->id_tarif) }}" method="POST">
                        @csrf
                        @method('PUT') {{-- Penting untuk metode PUT/PATCH --}}
                        <div class="form-control mb-4">
                            <label class="label" for="daya">
                                <span class="label-text">Daya (Contoh: 900 VA, 1300 VA)</span>
                            </label>
                            <input type="text" id="daya" name="daya" placeholder="Contoh: 900 VA"
                                class="input input-bordered w-full @error('daya') input-error @enderror"
                                value="{{ old('daya', $tarif->daya) }}" required autofocus />
                            @error('daya')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <div class="form-control mb-4">
                            <label class="label" for="tarifperkwh">
                                <span class="label-text">Tarif per KWH (Rp)</span>
                            </label>
                            <input type="number" step="0.01" id="tarifperkwh" name="tarifperkwh"
                                placeholder="Contoh: 1444.70"
                                class="input input-bordered w-full @error('tarifperkwh') input-error @enderror"
                                value="{{ old('tarifperkwh', $tarif->tarifperkwh) }}" required min="0" />
                            @error('tarifperkwh')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <div class="form-control mt-6">
                            <button type="submit" class="btn btn-primary">Update Tarif</button>
                        </div>
                    </form>
                    <div class="mt-4 text-center">
                        <a href="{{ route('admin.tarifs.index') }}" class="btn btn-ghost">Kembali ke Daftar Tarif</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
