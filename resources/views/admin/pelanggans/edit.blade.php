@extends('layouts.app')

@section('content')
    <div class="flex h-screen bg-base-100">
        @include('admin.sidebar')
        <div class="flex-1 p-10">
            <h1 class="text-3xl font-bold  mb-6">Edit Pelanggan: {{ $pelanggan->nama_pelanggan }}</h1>

            <div class="card  shadow-xl max-w-lg mx-auto">
                <div class="card-body">
                    <form action="{{ route('admin.pelanggans.update', $pelanggan->id_pelanggan) }}" method="POST">
                        @csrf
                        @method('PUT')
                        {{-- ... existing fields (username, nomor_kwh, nama_pelanggan, alamat, id_tarif) ... --}}
                        <div class="form-control mb-4">
                            <label class="label" for="username">
                                <span class="label-text">Username Pelanggan</span>
                            </label>
                            <input type="text" id="username" name="username" placeholder="Username Pelanggan"
                                class="input input-bordered w-full @error('username') input-error @enderror"
                                value="{{ old('username', $pelanggan->username) }}" required autofocus />
                            @error('username')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <div class="form-control mb-4">
                            <label class="label" for="nomor_kwh">
                                <span class="label-text">Nomor KWH</span>
                            </label>
                            <input type="text" id="nomor_kwh" name="nomor_kwh" placeholder="Nomor KWH"
                                class="input input-bordered w-full @error('nomor_kwh') input-error @enderror"
                                value="{{ old('nomor_kwh', $pelanggan->nomor_kwh) }}" required />
                            @error('nomor_kwh')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <div class="form-control mb-4">
                            <label class="label" for="nama_pelanggan">
                                <span class="label-text">Nama Pelanggan</span>
                            </label>
                            <input type="text" id="nama_pelanggan" name="nama_pelanggan"
                                placeholder="Nama Lengkap Pelanggan"
                                class="input input-bordered w-full @error('nama_pelanggan') input-error @enderror"
                                value="{{ old('nama_pelanggan', $pelanggan->nama_pelanggan) }}" required />
                            @error('nama_pelanggan')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <div class="form-control mb-4">
                            <label class="label" for="alamat">
                                <span class="label-text">Alamat</span>
                            </label>
                            <textarea id="alamat" name="alamat" placeholder="Alamat Lengkap"
                                class="textarea textarea-bordered h-24 w-full @error('alamat') textarea-error @enderror" required>{{ old('alamat', $pelanggan->alamat) }}</textarea>
                            @error('alamat')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <div class="form-control mb-4">
                            <label class="label" for="id_tarif">
                                <span class="label-text">Daya / Tarif</span>
                            </label>
                            <select id="id_tarif" name="id_tarif"
                                class="select select-bordered w-full @error('id_tarif') select-error @enderror" required>
                                <option value="">Pilih Daya / Tarif</option>
                                @foreach ($tarifs as $tarif)
                                    <option value="{{ $tarif->id_tarif }}"
                                        {{ old('id_tarif', $pelanggan->id_tarif) == $tarif->id_tarif ? 'selected' : '' }}>
                                        {{ $tarif->daya }} (Rp {{ number_format($tarif->tarifperkwh, 2, ',', '.') }}/KWH)
                                    </option>
                                @endforeach
                            </select>
                            @error('id_tarif')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <div class="form-control mb-4">
                            <label class="label" for="id_user">
                                <span class="label-text">User Terkait (Opsional)</span>
                            </label>
                            <select id="id_user" name="id_user"
                                class="select select-bordered w-full @error('id_user') select-error @enderror">
                                <option value="">Tidak Ada User Terkait</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id_user }}"
                                        {{ old('id_user', $pelanggan->id_user) == $user->id_user ? 'selected' : '' }}>
                                        {{ $user->username }} ({{ $user->level->nama_level ?? 'Level Tidak Dikenal' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('id_user')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <div class="form-control mt-6">
                            <button type="submit" class="btn btn-primary">Update Pelanggan</button>
                            <a href="{{ route('admin.pelanggans.index') }}" class="btn btn-ghost">Kembali ke Daftar
                                Pelanggan</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
