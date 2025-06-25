@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto p-6">
    <h2 class="text-xl font-bold mb-4">Reset Password</h2>

    <form action="{{ route('password.update') }}" method="POST" class="space-y-4">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div>
            <label for="email">Email:</label>
            <input type="email" name="email" class="w-full border rounded p-2" required>
        </div>
        <div>
            <label for="password">Password Baru:</label>
            <input type="password" name="password" class="w-full border rounded p-2" required>
        </div>
        <div>
            <label for="password_confirmation">Konfirmasi Password:</label>
            <input type="password" name="password_confirmation" class="w-full border rounded p-2" required>
        </div>
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Reset Password</button>
    </form>
</div>
@endsection