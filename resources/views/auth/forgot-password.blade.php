@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto p-6">
    <h2 class="text-xl font-bold mb-4">Lupa Password</h2>

    @if (session('status'))
    <div class="text-green-600">{{ session('status') }}</div>
    @endif

    <form action="{{ route('password.email') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label for="email">Email:</label>
            <input type="email" name="email" class="w-full border rounded p-2" required>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Kirim Link Reset</button>
    </form>
</div>
@endsection