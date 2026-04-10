<?php

namespace App\Collections;

use Illuminate\Database\Eloquent\Collection;

class BlogCollection extends Collection
{
    public function feed()
    {
        // $this->flatMap->posts가 Collection<Post>를 반환하고, 이것을 날짜순으로 재정렬
        return $this->flatMap->posts->sortByDesc('created_at');
    }
}