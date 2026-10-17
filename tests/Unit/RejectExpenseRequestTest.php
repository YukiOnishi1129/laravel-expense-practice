<?php

namespace Tests\Unit;

use App\Http\Requests\RejectExpenseRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Tests\TestCase;

class RejectExpenseRequestTest extends TestCase
{
    private function passes(array $data): bool
    {
        return Validator::make($data, (new RejectExpenseRequest)->rules())->passes();
    }

    public function test_コメントが空ならエラー(): void
    {
        $this->assertFalse($this->passes(['comment' => '']));
        $this->assertFalse($this->passes([]));
    }

    public function test_コメントが500文字ちょうどなら通る(): void
    {
        $this->assertTrue($this->passes(['comment' => Str::repeat('あ', 500)]));
    }

    public function test_コメントが501文字ならエラー(): void
    {
        $this->assertFalse($this->passes(['comment' => Str::repeat('あ', 501)]));
    }
}
