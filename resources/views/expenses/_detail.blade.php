<dl>
    <dt>申請した人</dt>
    <dd>{{ $expense->user->name }}</dd>
    <dt>金額</dt>
    <dd>{{ number_format($expense->amount) }}円</dd>
    <dt>使った日</dt>
    <dd>{{ $expense->used_on->format('Y/m/d') }}</dd>
    <dt>内容</dt>
    <dd>{{ $expense->description }}</dd>
    <dt>状態</dt>
    <dd>@include('expenses._status')</dd>
    <dt>領収書</dt>
    <dd>
        @if ($expense->receipt_path)
            <img src="{{ asset('storage/' . $expense->receipt_path) }}" alt="領収書" class="receipt">
        @else
            <span class="muted">なし</span>
        @endif
    </dd>
</dl>
