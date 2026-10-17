@extends('layouts.app')

@section('title', '直して出し直す')

@section('content')
    <div class="card">
        <h1>直して出し直す</h1>
        <form method="POST" action="{{ route('expenses.update', $expense) }}" enctype="multipart/form-data">
            @method('PUT')
            @include('expenses._form')
            <div class="actions">
                <button type="submit" class="button">出し直す</button>
                <a href="{{ route('expenses.show', $expense) }}" class="button button-secondary">戻る</a>
            </div>
        </form>
    </div>
@endsection
