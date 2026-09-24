<?php

namespace Lareon\Modules\Questionnaire\App\Exports;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FormInboxesExport implements FromQuery, WithMapping/*, WithHeadings , ShouldAutoSize,*/
{
    use Exportable;

    public function __construct(public Builder $query,) {}

    public function query(): Relation|Builder|\Illuminate\Database\Query\Builder
    {
        return $this->query;
    }

    public function map($row,): array
    {
        $dataForm = $row->data;
        return collect($dataForm)->map(function ($item,) {
            return is_array($item) ? implode(", ", $item) : $item;
        })->merge([
            $row->title ?? $row->form->title,
            $row->url ?? config('app.url'),
            $row->created_at instanceof \Carbon\Carbon ? $row->created_at->format('Y-m-d H:i:s') : $row->created_at,
            $row->ip_address,
        ])->toArray();
    }

    public function headings(): array
    {
        $baseHeadings = $this->query->count() ? array_keys($this->query->first()->data) : [];

        return array_merge($baseHeadings, [
            'form title',
            'address',
            'created at',
            'ip',
        ]);
    }

}
