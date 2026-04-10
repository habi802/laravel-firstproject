<?php

namespace Tests\Feature\Providers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Collection;

class PaginateServiceProviderTest extends TestCase
{
    use RefreshDatabase;

    // 페이지네이션 매크로에 관한 검증
    public function testPaginateMacro(): void
    {
        $collection = new Collection(range(1, 10));

        $paginator = $collection->paginate(5, 1);

        $this->assertEquals(1, $paginator->currentPage());
        $this->assertEquals(5, $paginator->perPage());
        $this->assertEquals(10, $paginator->total());
        $this->assertEquals(2, $paginator->lastPage());
        $this->assertEquals(5, $paginator->count());
        $this->assertEquals(range(1, 5), $paginator->items());
    }
}
