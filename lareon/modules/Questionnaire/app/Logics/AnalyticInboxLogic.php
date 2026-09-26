<?php

namespace Lareon\Modules\Questionnaire\App\Logics;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Lareon\Modules\Questionnaire\App\Models\Form;
use Lareon\Modules\Questionnaire\App\Models\FormInbox;
use Teksite\Handler\Services\ServiceWrapper;


class AnalyticInboxLogic
{
     public function getByForm($startDate = null, $endDate = null, $range = 'month')
    {
        return ServiceWrapper::make(false)->do(function () use ($startDate, $endDate, $range) {

            [$fromDate, $toDate] = $this->parseDates($startDate, $endDate);
            $dates = $this->generateDateRange($fromDate, $toDate, $range);
            $inboxes = $this->queryInboxes($fromDate, $toDate, $range);

            $forms = Form::query()->pluck('id', 'title');

            $result = [];
            foreach ($forms as $title => $id) {
                $result[$title] = collect($dates)->mapWithKeys(function ($date) use ($range, $inboxes, $id) {
                    $key = $this->formatDateKey($date, $range);
                    return [$key => $inboxes[$id][$key] ?? 0];
                })->toArray();
            }

            return $result;

        })->run();
    }

    /**
     * @throws \Throwable
     */
    public function getForChart($startDate = null, $endDate = null, string $range = 'month')
    {
        return ServiceWrapper::make(false)->do(function () use ($startDate, $endDate, $range) {
            [$fromDate, $toDate] = $this->parseDates($startDate, $endDate);
            $dates = $this->generateDateRange($fromDate, $toDate, $range);

            $categories = collect($dates)->map(fn($date) => $this->formatDateKey($date, $range))->toArray();
            $inboxes = $this->queryInboxes($fromDate, $toDate, $range);

            $forms = Form::pluck('id', 'title');

            $series = [];
            foreach ($forms as $title => $id) {
                $series[] = [
                    'name' => $title,
                    'data' => array_map(fn($key) => $inboxes[$id][$key] ?? 0, $categories)
                ];
            }

            return [
                'categories' => $categories,
                'series' => $series
            ];

        })->run();
    }

    // --------------------- Helper Methods --------------------- //

    private function parseDates($startDate, $endDate): array
    {
        return [Carbon::parse($startDate), Carbon::parse($endDate)];
    }

    private function generateDateRange(Carbon $fromDate, Carbon $toDate, string $range): array
    {
        $period = $this->getPeriodUnit($range);

        $dates = [];
        $current = $fromDate->copy();

        while ($current->lte($toDate)) {
            $dates[] = $current->copy();
            $current->add($period);
        }

        return $dates;
    }

    private function getPeriodUnit(string $range): string
    {
        return match ($range) {
            'day' => '1 day',
            'week' => '1 week',
            'month' => '1 month',
            'year' => '1 year',
            default => '1 month'
        };
    }

    private function formatDateKey(Carbon $date, string $range): string
    {
        return match ($range) {
            'day' => $date->format('Y-m-d'),
            'week' => $date->format('o-\WW'),
            'month' => $date->format('Y-m'),
            'year' => $date->format('Y'),
        };
    }

    private function queryInboxes(Carbon $fromDate, Carbon $toDate, string $range)
    {
        return FormInbox::query()->whereBetween('created_at', [$fromDate, $toDate])
                    ->select([
                        'form_id',
                        DB::raw("DATE_FORMAT(created_at,
                CASE
                    WHEN '{$range}' = 'day' THEN '%Y-%m-%d'
                    WHEN '{$range}' = 'week' THEN '%x-%v'
                    WHEN '{$range}' = 'month' THEN '%Y-%m'
                    WHEN '{$range}' = 'year' THEN '%Y'
                END) as period"),
                        DB::raw('COUNT(*) as total')
                    ])
                    ->groupBy('form_id', 'period')
                    ->orderBy('form_id')
                    ->orderBy('period')
                    ->get()
                    ->groupBy('form_id')
                    ->map(fn($group) => $group->pluck('total', 'period'));
    }
}

