{{ $expense->user->name }}さん

申請が差し戻されました。

内容：{{ $expense->description }}
金額：{{ number_format($expense->amount) }}円
使った日：{{ $expense->used_on->format('Y/m/d') }}

申請の詳細はこちら
{{ route('expenses.show', $expense) }}
