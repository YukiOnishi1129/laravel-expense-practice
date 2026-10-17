@extends('layouts.app')

@section('title', '申請の詳細')

@section('content')
    <div class="card">
        <h1>申請の詳細</h1>

        @include('expenses._detail')

        <div class="actions">
            @can('update', $expense)
                <a href="{{ route('expenses.edit', $expense) }}" class="button">直して出し直す</a>
            @endcan
            @can('approve', $expense)
                <a href="{{ route('approvals.show', $expense) }}" class="button">承認画面へ</a>
            @endcan
            <a href="{{ route('expenses.index') }}" class="button button-secondary">一覧に戻る</a>
        </div>
    </div>
@endsection
