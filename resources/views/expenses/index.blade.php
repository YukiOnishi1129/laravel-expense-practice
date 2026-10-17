@extends('layouts.app')

@section('title', '申請一覧')

@section('content')
    @if (auth()->user()->isManager())
        <div class="card">
            <h2>承認待ちの申請</h2>
            @if ($waitingApprovals->isEmpty())
                <p class="muted">承認待ちの申請はありません</p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>申請した人</th>
                            <th>使った日</th>
                            <th>内容</th>
                            <th>金額</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($waitingApprovals as $expense)
                            <tr>
                                <td>{{ $expense->user->name }}</td>
                                <td>{{ $expense->used_on->format('Y/m/d') }}</td>
                                <td>{{ $expense->description }}</td>
                                <td class="amount">{{ number_format($expense->amount) }}円</td>
                                <td><a href="{{ route('approvals.show', $expense) }}">承認画面へ</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    @endif

    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h1>自分の申請</h1>
            <a href="{{ route('expenses.create') }}" class="button">新しく申請する</a>
        </div>

        @if ($expenses->isEmpty())
            <p class="muted">まだ申請はありません</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>使った日</th>
                        <th>内容</th>
                        <th>金額</th>
                        <th>状態</th>
                        <th>最終更新日</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($expenses as $expense)
                        <tr>
                            <td>{{ $expense->used_on->format('Y/m/d') }}</td>
                            <td>{{ $expense->description }}</td>
                            <td class="amount">{{ number_format($expense->amount) }}円</td>
                            <td>@include('expenses._status')</td>
                            <td>{{ \Illuminate\Support\Carbon::parse($expense->histories_max_created_at ?? $expense->updated_at)->format('Y/m/d H:i') }}</td>
                            <td><a href="{{ route('expenses.show', $expense) }}">詳細</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
