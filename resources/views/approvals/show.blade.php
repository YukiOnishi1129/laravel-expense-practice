@extends('layouts.app')

@section('title', '承認')

@section('content')
    <div class="card">
        <h1>承認</h1>

        @include('expenses._detail')

        @if ($errors->any())
            <div class="errors" style="margin-top: 20px;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="actions">
            <form method="POST" action="{{ route('approvals.approve', $expense) }}">
                @csrf
                <button type="submit" class="button">承認する</button>
            </form>
            <a href="{{ route('expenses.index') }}" class="button button-secondary">一覧に戻る</a>
        </div>

        <form method="POST" action="{{ route('approvals.reject', $expense) }}" style="margin-top: 24px;">
            @csrf
            <label for="comment">差し戻しの理由（必須・500文字まで）</label>
            <textarea id="comment" name="comment" rows="4" maxlength="500" style="width: 100%; padding: 8px; border: 1px solid #ccd0d5; border-radius: 4px; font-size: 14px;">{{ old('comment') }}</textarea>
            <div class="actions">
                <button type="submit" class="button button-danger">差し戻す</button>
            </div>
        </form>
    </div>
@endsection
