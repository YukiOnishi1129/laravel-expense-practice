@extends('layouts.app')

@section('title', 'ログイン')

@section('content')
    <div class="card" style="max-width: 420px; margin: 40px auto;">
        <h1>ログイン</h1>

        @if ($errors->any())
            <div class="errors">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <label for="email">メールアドレス</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>

            <label for="password">パスワード</label>
            <input type="password" id="password" name="password" required>

            <div class="actions">
                <button type="submit" class="button">ログイン</button>
            </div>
        </form>
    </div>
@endsection
