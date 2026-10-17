@extends('layouts.app')

@section('title', '新しく申請する')

@section('content')
    <div class="card">
        <h1>新しく申請する</h1>
        <form method="POST" action="{{ route('expenses.store') }}" enctype="multipart/form-data">
            @include('expenses._form')
            <div class="actions">
                <button type="submit" class="button">申請を出す</button>
                <a href="{{ route('expenses.index') }}" class="button button-secondary">戻る</a>
            </div>
        </form>
    </div>
@endsection
