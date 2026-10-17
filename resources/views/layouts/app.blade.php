<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | 経費精算</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: sans-serif; color: #222; background: #f5f6f8; }
        header { display: flex; justify-content: space-between; align-items: center; padding: 12px 24px; background: #2f4f7f; color: #fff; }
        header a { color: #fff; text-decoration: none; font-weight: bold; }
        header form { display: inline; }
        main { max-width: 960px; margin: 24px auto; padding: 0 16px; }
        .card { background: #fff; border-radius: 6px; padding: 20px 24px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08); }
        h1 { font-size: 22px; margin: 0 0 16px; }
        h2 { font-size: 18px; margin: 0 0 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px 10px; border-bottom: 1px solid #e3e5e8; text-align: left; }
        th { background: #f0f2f5; font-weight: normal; color: #555; }
        td.amount { text-align: right; }
        .status { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 13px; }
        .status-pending { background: #fff4d6; color: #8a6100; }
        .status-approved { background: #dff3e4; color: #1e6b34; }
        .status-rejected { background: #fde2e1; color: #a12622; }
        .button { display: inline-block; padding: 8px 16px; border: none; border-radius: 4px; background: #2f4f7f; color: #fff; font-size: 14px; text-decoration: none; cursor: pointer; }
        .button-secondary { background: #6b7280; }
        .button-danger { background: #c0392b; }
        .flash { padding: 10px 16px; margin-bottom: 20px; border-radius: 4px; background: #dff3e4; color: #1e6b34; }
        .errors { padding: 10px 16px; margin-bottom: 16px; border-radius: 4px; background: #fde2e1; color: #a12622; }
        .errors ul { margin: 0; padding-left: 20px; }
        label { display: block; margin: 12px 0 4px; font-size: 14px; color: #555; }
        input[type=text], input[type=number], input[type=date], input[type=email], input[type=password] { width: 100%; padding: 8px; border: 1px solid #ccd0d5; border-radius: 4px; font-size: 14px; }
        dl { display: grid; grid-template-columns: 140px 1fr; gap: 8px 16px; margin: 0; }
        dt { color: #555; }
        dd { margin: 0; }
        .actions { display: flex; gap: 8px; margin-top: 20px; }
        .actions form { margin: 0; }
        .receipt { max-width: 320px; border: 1px solid #e3e5e8; }
        .muted { color: #888; }
    </style>
</head>
<body>
    <header>
        <a href="{{ route('expenses.index') }}">経費精算</a>
        @auth
            <div>
                {{ auth()->user()->name }}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="button button-secondary">ログアウト</button>
                </form>
            </div>
        @endauth
    </header>
    <main>
        @if (session('status'))
            <div class="flash">{{ session('status') }}</div>
        @endif

        @yield('content')
    </main>
</body>
</html>
