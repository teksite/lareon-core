<?php

namespace Lareon\Modules\FileManager\App\Traits;

use Teksite\FileManager\Models\UploadFile;

trait HasImages {
    public function getImage()
    {
        return $this->belongsTo(UploadFile::class , 'image');
    }
}
