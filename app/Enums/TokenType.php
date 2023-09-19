<?php

namespace App\Enums;

enum TokenType: int
{
    /**
     * 存取權杖
     */
    case ACCESS_TOKEN = 1;

    /**
     * 重整權杖
     */
    case REFRESH_TOKEN = 2;

    /**
     * 服務跳轉權杖
     */
    case SERVICE_ACCESS_TOKEN = 3;
}
