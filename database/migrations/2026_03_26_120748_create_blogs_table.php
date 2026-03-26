<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            // foreignIdFor(): users 테이블의 기본키를 참조하는 외래키인 user_id 컬럼 생성
            // constrained(): 외래키 제약 조건 추가
            // cascadeOnDelete(): 부모의 데이터가 삭제되면, 참조하는 자식 데이터도 삭제
            $table->foreignIdFor(\App\Models\User::class)
                  ->constrained()
                  ->cascadeOnDelete();
            $table->string('name')->unique();
            $table->string('display_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
