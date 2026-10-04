<?php

namespace Lareon\Steward\App\Service;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SlowQueryLogger
{
    public function __construct(private readonly Request $request) {}

    public function log(QueryExecuted $query): void
    {

        $max= env('MAX_QUERY_TIME' , 500);

        if ($query->time < config('slow-query.threshold', $max)) return;


        Log::channel('slow_query')->warning('Slow query detected', [
            'time_ms' => $query->time,
            'sql'     => $query->toRawSql(),
            'connection' => $query->connectionName,
            'method' => $this->request->method(),
            'url'    => $this->request->fullUrl(),
            'route' => $this->request->route()?->getName(),
            'controller' => $this->request->route()?->getActionName(),
            'user_id' => $this->request->user()?->getAuthIdentifier(),
            'ip' => $this->request->ip(),
        ]);
    }
}
