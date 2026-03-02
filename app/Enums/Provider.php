<?php

namespace App\Enums;

enum Provider: string
{
    case Github = 'github';
    case Kakao = 'kakao';
    case Naver = 'naver';
}