<?php

namespace Tests\Feature;

use App\Mail\ExpenseRejectedMail;
use App\Models\Expense;
use App\Models\ExpenseApprovalHistory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RejectExpenseTest extends TestCase
{
    use RefreshDatabase;

    public function test_コメントを付けて差し戻すと差し戻しになり履歴にコメント付きで残る(): void
    {
        Mail::fake();
        $expense = Expense::factory()->create();

        $response = $this->actingAs($expense->approver)
            ->post(route('approvals.reject', $expense), ['comment' => '領収書の日付が読めません']);

        $response->assertRedirect(route('expenses.index'));
        $this->assertSame(Expense::STATUS_REJECTED, $expense->fresh()->status);
        $this->assertDatabaseCount('expense_approval_histories', 1);
        $this->assertDatabaseHas('expense_approval_histories', [
            'expense_id' => $expense->id,
            'user_id' => $expense->approver_id,
            'action' => ExpenseApprovalHistory::ACTION_REJECTED,
            'comment' => '領収書の日付が読めません',
        ]);
    }

    public function test_コメントなしで差し戻すとエラーで戻り何も変わらない(): void
    {
        Mail::fake();
        $expense = Expense::factory()->create();

        $response = $this->actingAs($expense->approver)
            ->from(route('approvals.show', $expense))
            ->post(route('approvals.reject', $expense), ['comment' => '']);

        $response->assertRedirect(route('approvals.show', $expense));
        $response->assertSessionHasErrors('comment');
        $this->assertSame(Expense::STATUS_PENDING, $expense->fresh()->status);
        $this->assertDatabaseCount('expense_approval_histories', 0);
        Mail::assertNothingSent();
    }

    public function test_差し戻した申請だけ状態が変わりほかの申請は変わらない(): void
    {
        Mail::fake();
        $expense = Expense::factory()->create();
        $other = Expense::factory()->create(['approver_id' => $expense->approver_id]);

        $this->actingAs($expense->approver)
            ->post(route('approvals.reject', $expense), ['comment' => '金額が違います']);

        $this->assertSame(Expense::STATUS_REJECTED, $expense->fresh()->status);
        $this->assertSame(Expense::STATUS_PENDING, $other->fresh()->status);
    }

    public function test_存在しない申請だと404(): void
    {
        $expense = Expense::factory()->create();

        $response = $this->actingAs($expense->approver)
            ->post(route('approvals.reject', 99999), ['comment' => '金額が違います']);

        $response->assertNotFound();
    }

    public function test_申請者に通知メールが1通送られ本文にコメントが入っている(): void
    {
        Mail::fake();
        $expense = Expense::factory()->create();

        $this->actingAs($expense->approver)
            ->post(route('approvals.reject', $expense), ['comment' => '領収書の日付が読めません']);

        Mail::assertSent(ExpenseRejectedMail::class, 1);
        Mail::assertSent(ExpenseRejectedMail::class, function (ExpenseRejectedMail $mail) use ($expense) {
            $mail->assertSeeInText('領収書の日付が読めません');

            return $mail->hasTo($expense->user->email);
        });
    }

    public function test_前からある差し戻し済みの申請は詳細にコメントなしと出る(): void
    {
        $expense = Expense::factory()->rejected()->create();
        $expense->histories()->create([
            'user_id' => $expense->approver_id,
            'action' => ExpenseApprovalHistory::ACTION_REJECTED,
            'comment' => null,
        ]);

        $response = $this->actingAs($expense->user)->get(route('expenses.show', $expense));

        $response->assertOk();
        $response->assertSee('差し戻しの理由');
        $response->assertSee('コメントなし');
    }

    public function test_2回差し戻された申請の詳細には新しい方のコメントだけが出る(): void
    {
        Mail::fake();
        $expense = Expense::factory()->create();

        $this->actingAs($expense->approver)
            ->post(route('approvals.reject', $expense), ['comment' => '1回目の理由']);
        $expense->update(['status' => Expense::STATUS_PENDING]);
        $this->actingAs($expense->approver)
            ->post(route('approvals.reject', $expense), ['comment' => '2回目の理由']);

        $response = $this->actingAs($expense->user)->get(route('expenses.show', $expense));

        $response->assertSee('2回目の理由');
        $response->assertDontSee('1回目の理由');
    }

    public function test_差し戻した後の申請一覧の最終更新日は差し戻した日時になる(): void
    {
        Mail::fake();
        $expense = Expense::factory()->create();
        $expense->forceFill(['updated_at' => now()->subDays(3)])->saveQuietly();

        $this->travelTo(now()->setDate(2030, 4, 1)->setTime(10, 30));
        $this->actingAs($expense->approver)
            ->post(route('approvals.reject', $expense), ['comment' => '金額が違います']);

        $response = $this->actingAs($expense->user)->get(route('expenses.index'));

        $response->assertSee('2030/04/01 10:30');
    }
}
