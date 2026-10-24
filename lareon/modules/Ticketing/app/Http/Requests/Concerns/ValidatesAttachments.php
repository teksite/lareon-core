<?php

namespace Lareon\Modules\Ticketing\App\Http\Requests\Concerns;

trait ValidatesAttachments
{
    public function attachmentRules(): array
    {
        return [
            'attachments'   => $this->overallAttachmentFileRule(),
            'attachments.*' => $this->attachmentFileRules(),
        ];
    }

    /**
     * @return array<int, string>
     */
    protected function overallAttachmentFileRule(): array
    {
        if (!config('ticketing.attachments.enabled', true)) return ['nullable', 'prohibited'];

        return ['nullable', 'array', 'max:'.(int)config('ticketing.attachments.max_files_per_message', 5),];
    }

    /**
     * @return array<int, string>
     */
    protected function attachmentFileRules(): array
    {
        $maxKb = (int)config('ticketing.attachments.max_size_kb', 5120);
        $extensions = implode(',', config('ticketing.attachments.allowed_extensions', []));

        return array_filter(['file', "max:{$maxKb}", $extensions !== '' ? "mimes:{$extensions}" : null,]);
    }
}
