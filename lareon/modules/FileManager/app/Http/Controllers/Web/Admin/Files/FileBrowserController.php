<?php

namespace Lareon\Modules\FileManager\App\Http\Controllers\Web\Admin\Files;

use Lareon\Modules\FileManager\App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FileBrowserController extends Controller
{
    public function index()
    {
        return view('filemanager::admin.pages.browser.index');
    }
}
