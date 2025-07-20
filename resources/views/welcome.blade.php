@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-base-200 flex flex-col items-center justify-center text-center">
        <h1 class="text-5xl font-extrabold text-primary mb-4">Selamat Datang di <span class="text-accent">Electric Bill</span>
        </h1>
        <p class="text-lg text-base-content mb-6 max-w-xl">
            Kelola tagihan listrik Anda dengan mudah dan cepat. Aplikasi kami memudahkan pengelolaan tagihan, pembayaran,
            dan monitoring penggunaan listrik kapan saja dan di mana saja.
        </p>

        <div class="flex flex-wrap gap-4 justify-center">
            <a href="{{ route('login') }}" class="btn btn-primary">Masuk</a>
            <a href="{{ route('register') }}" class="btn btn-outline btn-secondary">Daftar</a>
        </div>

        {{-- <div class="mt-10">
            <img src="https://source.unsplash.com/600x300/?electricity,payment" alt="Electric Bill Illustration"
                class="rounded-lg shadow-lg">
        </div> --}}
    </div>
@endsection
