@if ($errors->any())
    <div class="errors">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@csrf

<label for="amount">金額（円）</label>
<input type="number" id="amount" name="amount" min="1" value="{{ old('amount', $expense->amount ?? '') }}" required>

<label for="used_on">使った日</label>
<input type="date" id="used_on" name="used_on" value="{{ old('used_on', isset($expense) ? $expense->used_on->format('Y-m-d') : '') }}" required>

<label for="description">内容（交通費・備品代など）</label>
<input type="text" id="description" name="description" value="{{ old('description', $expense->description ?? '') }}" required>

<label for="receipt">領収書の画像</label>
<input type="file" id="receipt" name="receipt" accept="image/*">
@if (isset($expense) && $expense->receipt_path)
    <p class="muted">画像を選ばなければ、今の領収書のままになります</p>
@endif
