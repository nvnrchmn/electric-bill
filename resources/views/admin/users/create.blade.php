@extends('layouts.app')

@section('content')
    <div class="flex h-screen bg-base-100">
        @include('admin.sidebar')

        <div class="flex-1 p-10">
            <h1 class="text-3xl font-bold  mb-6">Tambah User Baru</h1>

            <div class="card  shadow-xl max-w-lg mx-auto">
                <div class="card-body">
                    <form action="{{ route('admin.users.store') }}" method="POST">
                        @csrf
                        <div class="form-control mb-4">
                            <label class="label" for="username">
                                <span class="label-text">Username</span>
                            </label>
                            <input type="text" id="username" name="username" placeholder="Username"
                                class="input input-bordered w-full @error('username') input-error @enderror"
                                value="{{ old('username') }}" required autofocus />
                            @error('username')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <div class="form-control mb-4">
                            <label class="label" for="email">
                                <span class="label-text">Email</span>
                            </label>
                            <input type="email" id="email" name="email" placeholder="Email"
                                class="input input-bordered w-full @error('email') input-error @enderror"
                                value="{{ old('email') }}" required />
                            @error('email')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <div class="form-control mb-4">
                            <label class="label" for="password">
                                <span class="label-text">Password</span>
                            </label>
                            <input type="password" id="password" name="password" placeholder="Password"
                                class="input input-bordered w-full @error('password') input-error @enderror" required />
                            @error('password')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <div class="form-control mb-4">
                            <label class="label" for="password_confirmation">
                                <span class="label-text">Konfirmasi Password</span>
                            </label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                placeholder="Konfirmasi Password" class="input input-bordered w-full" required />
                        </div>

                        <div class="form-control mb-4">
                            <label class="label" for="nama_admin">
                                <span class="label-text">Nama Admin/Petugas (Opsional)</span>
                            </label>
                            <input type="text" id="nama_admin" name="nama_admin" placeholder="Nama Lengkap"
                                class="input input-bordered w-full @error('nama_admin') input-error @enderror"
                                value="{{ old('nama_admin') }}" />
                            @error('nama_admin')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <div class="form-control mb-4">
                            <label class="label" for="id_level">
                                <span class="label-text">Level Pengguna</span>
                            </label>
                            <select id="id_level" name="id_level"
                                class="select select-bordered w-full @error('id_level') select-error @enderror" required>
                                <option value="">Pilih Level</option>
                                @foreach ($levels as $level)
                                    <option value="{{ $level->id_level }}"
                                        {{ old('id_level') == $level->id_level ? 'selected' : '' }}>
                                        {{ $level->nama_level }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_level')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <div class="form-control mt-6">
                            <button type="submit" class="btn btn-primary">Simpan User</button>
                            {{-- <div class="text-center"> --}}
                            <a href="{{ route('admin.users.index') }}" class="btn btn-ghost">Kembali ke Daftar User</a>
                            {{-- </div> --}}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
