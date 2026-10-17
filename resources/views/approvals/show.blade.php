@extends('layouts.app')

@section('title', '承認')

@section('content')
    <div class="card">
        <h1>承認</h1>

        @include('expenses._detail')

        <div class="actions">
            <form method="POST" action="{{ route('approvals.approve', $expense) }}">
                @csrf
                <button type="submit" class="button">承認する</button>
            </form>
            <form method="POST" action="{{ route('approvals.reject', $expense) }}">
                @csrf
                <button type="submit" class="button button-danger">差し戻す</button>
            </form>
            <a href="{{ route('expenses.index') }}" class="button button-secondary">一覧に戻る</a>
        </div>
    </div>
@endsection
