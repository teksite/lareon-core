<?php

namespace Lareon\Steward\App\Service;


use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\Log;
use Throwable;

class SlowQueryLogger
{
    private ?string $currentMonth = null;

    public function log(QueryExecuted $query): void
    {
        if (!config('lareon.slow_query.enabled', true)) return;

        $threshold = (int)config('lareon.slow_query.threshold', 500);

        if ($query->time < $threshold) return;

        try {
            $this->write($query);
        } catch (\Throwable $exception) {
            Log::warning('Unable to write slow query log.', ['exception' => $exception->getMessage(),]);
        }
    }

    private function write(QueryExecuted $query): void
    {
        $month = now()->format('Y-m');

        $data = [
            'time_ms'    => $query->time,
            'connection' => $query->connectionName,
            'sql'        => $query->toRawSql(),
            'method'     => request()->method(),
            'url'        => request()->fullUrl(),
            'route'      => request()->route()?->getName(),
            'controller' => request()->route()?->getActionName(),
        ];

        if (config('lareon.slow_query.log_bindings', false)) $data['bindings'] = $query->bindings;

        $this->writeDetailedLog($month, $data);
        $this->writeStatisticsLog($month, $data);

        $this->currentMonth = $month;
    }

    private function writeDetailedLog(string $month, array $data): void
    {
        $path = $this->logPath("slow-query-{$month}.log");

        Log::build([
            'driver' => 'single',
            'path'   => $path,
            'level'  => 'warning',
        ])->warning('Slow query detected.', $data);
    }

    private function writeStatisticsLog(string $month, array $data): void
    {
        $path = $this->logPath("slow-query-statistics-{$month}.log");

        Log::build([
            'driver' => 'single',
            'path'   => $path,
            'level'  => 'info',
        ])->info('Slow query statistics.', [
            'time_ms'    => $data['time_ms'],
            'connection' => $data['connection'],
            'route'      => $data['route'],
            'controller' => $data['controller'],
        ]);
    }

    private function logPath(string $filename): string
    {
        $path = config('lareon.slow-query.path', storage_path('logs'));

        if (!is_dir($path)) mkdir($path, 0755, true);
        
        return "{$path}/{$filename}";
    }
}
