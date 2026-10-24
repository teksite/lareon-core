<?php

namespace Lareon\Modules\Ticketing\App\Logics;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Lareon\Modules\Ticketing\App\Enums\ChangedByType;
use Lareon\Modules\Ticketing\App\Enums\SenderType;
use Lareon\Modules\Ticketing\App\Enums\TicketStatusType;
use Lareon\Modules\Ticketing\App\Events\TicketClosed;
use Lareon\Modules\Ticketing\App\Events\TicketReplyEvent;
use Lareon\Modules\Ticketing\App\Models\Ticket;
use Lareon\Modules\Ticketing\App\Models\TicketAttachment;
use Lareon\Modules\Ticketing\App\Models\TicketMessage;
use Lareon\Modules\Ticketing\App\Services\TicketNumberService;
use Lareon\Modules\User\App\Models\User;
use Lareon\Steward\App\Models\Admin;
use Teksite\Handler\Facade\FetchData;
use Teksite\Handler\Services\ServiceWrapper;
use Throwable;


class AttachmentLogic
{

    /**
     * @param  array<int, UploadedFile>  $files
     * @return array<int, TicketAttachment>
     */
    public function storeMany(Ticket $ticket, TicketMessage $message, SenderType $uploaderType, int $uploaderId, array $files,): array {
        return array_map(fn (UploadedFile $file) => $this->storeOne($ticket, $message, $uploaderType, $uploaderId, $file), $files,);
    }

    private function storeOne(Ticket $ticket, TicketMessage $message, SenderType $uploaderType, int $uploaderId, UploadedFile $file,): TicketAttachment {
        $disk = (string) config('ticketing.attachments.disk', 'ticketing');
        $folder = 'tickets/'.$ticket->id;
        $filename = Str::uuid()->toString().'.'.$file->getClientOriginalExtension();

        $path = $file->storeAs($folder, $filename, $disk);

        return TicketAttachment::query()->create([
            'ticket_id' => $ticket->id,
            'message_id' => $message->id,
            'uploaded_by_type' => $uploaderType,
            'uploaded_by_id' => $uploaderId,
            'disk' => $disk,
            'path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
            'size_bytes' => $file->getSize() ?: 0,
        ]);
    }

}

