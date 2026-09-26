<?php

namespace Lareon\Modules\Questionnaire\App\Logics;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HigherOrderWhenProxy;
use Lareon\Modules\Questionnaire\App\Exports\FormInboxesExport;
use Lareon\Modules\Questionnaire\App\Models\FormInbox;
use Maatwebsite\Excel\Facades\Excel;
use Teksite\Handler\Services\ServiceWrapper;
use Throwable;


class ExportInboxLogic
{
    /**
     * @throws Throwable
     */
    public function export(array $inputs)
    {
        return ServiceWrapper::make(true)->do(function () use ($inputs) {
            $filename = sprintf('receives-%s.xlsx', Carbon::now()->format('Y_m_d_H_i_s'));
            $query = $this->buildQuery($inputs);
            return Excel::download(new FormInboxesExport($query), $filename ,\Maatwebsite\Excel\Excel::XLSX);
        })->run();
    }

    private function buildQuery(array $input): Builder|HigherOrderWhenProxy
    {
        return FormInbox::query()
                    ->when( isset($input['form']), fn($query) => $query->where('form_id', $input['form']))
                    ->when(isset($input['date']['start']), fn($query, $start) => $query->whereDate('created_at', '>=', $input['date']['start']))
                    ->when(isset($input['date']['end']) , fn($query, $end) => $query->whereDate('created_at', '<=', $input['date']['end']));
    }
}

