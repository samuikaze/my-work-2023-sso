<?php

namespace App\Repositories;

use App\Enums\TokenType;
use App\Models\Token;
use Exception;
use Illuminate\Support\Facades\DB;

class TokenRepository extends BaseRepository
{
    public function __construct(Token $token)
    {
        $this->model = $token;
    }

    /**
     * 依據權杖種類與權杖唯一識別碼取得權杖
     *
     * @param \App\Enums\TokenType|null $token_type 權杖種類
     * @param string $uuid 權杖唯一識別碼
     * @return \App\Models\Token|null
     */
    public function getTokenByUuid(TokenType $token_type, string $uuid): Token|null
    {
        return $this->model
            ->where('token_uuid', $uuid)
            ->where('token_type', $token_type->value)
            ->first();
    }

    /**
     * 以權杖唯一識別碼刪除權杖
     *
     * @param string $uuid 權杖唯一識別碼
     */
    public function deleteTokenByUuid(string $uuid): bool
    {
        if ($this->auto_commit) {
            DB::beginTransaction();
        }

        try {
            $effected = $this->model
                ->where('token_uuid', $uuid)
                ->delete();

            if ($this->auto_commit) {
                DB::commit();
            }
        } catch (Exception $e) {
            report($e);

            if ($this->auto_commit) {
                DB::rollBack();
            }

            throw $e;
        }

        return $effected;
    }
}
