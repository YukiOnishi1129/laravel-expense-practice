# laravel-expense-practice

社内の経費精算システム（架空）です。社員が交通費や備品代を申請し、上長が承認するか差し戻します。

- PHP 8.3 / Laravel 12
- 画面は Blade でサーバー側で作っています（API・Ajax は使っていません）
- DB は MySQL 8.0
- 手元では Docker（docker compose）で動かします

## 動かし方

```bash
docker compose up -d --build
```

起動すると、中で次のことが自動で動きます（`docker/start.sh`）。

- `.env` がなければ `.env.example` からコピー
- `composer install`
- アプリのキーの作成
- マイグレーションと初期データの投入

立ち上がったら http://localhost:8000 を開きます。初回は `composer install` があるので、少し時間がかかります。

止める時：

```bash
docker compose down
```

データを最初の状態に戻す時：

```bash
docker compose exec app php artisan migrate:fresh --seed
```

## ログインできるユーザー

パスワードは全員 `password` です。

| メールアドレス | 名前 | 役割 |
|---|---|---|
| sato@example.com | 佐藤 | 上長（田中・鈴木の承認者） |
| takahashi@example.com | 高橋 | 別の部署の上長（田中・鈴木の承認者ではない） |
| tanaka@example.com | 田中 | 社員 |
| suzuki@example.com | 鈴木 | 社員 |

## 画面

| 画面 | URL | 内容 |
|---|---|---|
| ログイン | /login | |
| 申請一覧 | /expenses | 自分の申請と状態・最終更新日。上長には承認待ちの申請も並ぶ |
| 申請フォーム | /expenses/create | 金額・使った日・内容・領収書の画像を入れて出す |
| 申請の詳細 | /expenses/{id} | 申請の中身と今の状態。差し戻されていれば「直して出し直す」 |
| 直して出し直す | /expenses/{id}/edit | 差し戻された申請を直して出し直す |
| 上長の承認画面 | /approvals/{id} | 「承認する」「差し戻す」のボタン |

差し戻すと、申請者に「申請が差し戻されました」というメールが送られます。

## メールの確かめ方

このアプリは、メールを実際には送りません。送ったことにして、メールの中身をログのファイル（`storage/logs/laravel.log`）に書き出します（`.env` の `MAIL_MAILER=log`）。外のアドレスに届くことはありません。

差し戻した後に、ログの最後を見ると、送られたはずのメールの宛先・件名・本文が出ています。

```bash
docker compose exec app tail -n 40 storage/logs/laravel.log
```

件名と宛先の名前は、`=?utf-8?Q?...?=` のような形に変換されて出ます（メールの決まりで日本語をこう書く）。本文はそのまま日本語で読めます。

テストでは、メールは `Mail::fake()` で送ったことにして、「誰に・どのメールが送られたか」を確かめています（`tests/Feature/RejectExpenseTest.php`）。

## テーブル

| テーブル | 中身 |
|---|---|
| users | 社員と上長。社員は `manager_id` で自分の上長を持つ |
| expenses | 申請1件ごとの金額・使った日・内容・領収書・状態（申請中・承認済み・差し戻し）・承認者 |
| expense_approval_histories | 誰が・いつ・承認したか差し戻したかの履歴 |

## テスト

```bash
docker compose exec app php artisan test
```

| 種類 | 場所 | 中身 |
|---|---|---|
| 単体テスト（ユニットテスト） | `tests/Unit` | DB につながずに動かす。1つの部品（権限のルールなど）だけを確かめる |
| 結合テスト（インテグレーションテスト） | `tests/Feature` | DB につないで動かす。リクエストを送って、コントローラから DB まで通して確かめる |

- 結合テストの DB は、テスト用の SQLite（メモリ上の DB）です。MySQL は使いません（`phpunit.xml`）
- メールはテストでは本当には送れないので、`Mail::fake()` のモックで送ったことにして、誰にどのメールを送ったかを確かめます
