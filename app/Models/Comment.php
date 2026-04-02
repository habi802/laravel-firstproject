<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Comment extends Model
{
    // SoftDeletes 를 사용하면,
    // 데이터를 삭제할 때 레코드가 실제로 삭제되지 않고, deleted_at에 삭제한 시각만 기록됨
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'parent_id',
        'content'
    ];

    // 댓글은 사용자와 1:N 관계
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 다형성 관계(Polymorphic Relations): 하나의 모델이 다른 여러 모델에 소속될 가능성이 있는 관계
    // morphTo() 메소드를 사용하여 다형성 관계를 표현할 수 있음
    // post_id가 될 외래키는 commentable_id가 대신하고,
    // commentable_type에는 네임스페이스를 포함한 클래스의 경로가 저장됨
    // 예) 1번 글에 소속된 댓글의 commentable_type은 App\Models\Post, commentable_id는 1이 지정됨
    public function commentable()
    {
        return $this->morphTo();
    }

    // 댓글은 어떨 때는 부모 댓글이 되고, 어떨 때는 자식 댓글이 될 수 있는 재귀적 관계를 가질 수 있어서 두 관계를 모두 정의해야 함
    // SoftDeletes::withTrashed() 메소드를 사용하여 댓글을 조회할 때 삭제된 데이터까지 포함하여 조회함
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id')
                    ->withTrashed();
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')
                    ->withTrashed();
    }
}
