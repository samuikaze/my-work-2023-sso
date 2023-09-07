<?php

namespace App\Repositories;

use App\Models\ActionLog;

class ActionLogRepository extends BaseRepository
{
    public function __construct(ActionLog $action_log)
    {
        $this->model = $action_log;
    }
}
