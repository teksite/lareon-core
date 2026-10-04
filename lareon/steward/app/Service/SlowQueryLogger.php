<?php

namespace Lareon\Steward\App\Service;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\Log;
use Throwable;

class SlowQueryLogger
{
    private ?string $month = null;

    private mixed $detailLogger = null;

    private mixed $statisticsLogger = null;

    public function log(QueryExecuted $query): void
    {
        if (!config('lareon.slow-query.enabled', true)) return;

        $threshold = (int)config('lareon.slow-query.threshold', 500);

        if ($query->time < $threshold) return;

        try {
            $this->ensureLoggers();

            $data = $this->buildData($query);

            $this->detailLogger->warning('Slow query detected.', $data);

            $this->statisticsLogger->info('Slow query statistics.', [
                    'time_ms'    => $query->time,
                    'connection' => $query->connectionName,
                    'route'      => request()->route()?->getName(),
                    'controller' => request()->route()?->getActionName(),
                ]
            );
        } catch (Throwable $exception) {
            Log::warning('Unable to write slow query log.', ['exception' => $exception->getMessage(),]);
        }
    }

    private function ensureLoggers(): void
    {
        $month = now()->format('Y-m');

        if ($this->month === $month) return;

        $path = config('lareon.slow-query.path', storage_path('logs'));

        if (!is_dir($path)) mkdir($path, 0755, true);

        $this->detailLogger = Log::build([
            'driver' => 'single',
            'path'   => "{$path}/slow-query-{$month}.log",
            'level'  => 'warning',
        ]);

        $this->statisticsLogger = Log::build([
            'driver' => 'single',
            'path'   => "{$path}/slow-query-statistics-{$month}.log",
            'level'  => 'info',
        ]);

        $this->month = $month;
    }

    private function buildData(QueryExecuted $query,): array
    {
        $data = [
            'time_ms' => $query->time,
            'connection' => $query->connectionName,
            'sql' => $query->toRawSql(),
            'method' => request()->method(),
            'url' => request()->fullUrl(),
            'route' => request()->route()?->getName(),
            'controller' => request()->route()?->getActionName(),
        ];
        if (config('lareon.slow-query.log_bindings', false))$data['bindings'] = $query->bindings;

        return $data;
    }
}

