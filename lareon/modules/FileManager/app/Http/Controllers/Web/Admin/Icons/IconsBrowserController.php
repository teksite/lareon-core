<?php

namespace Lareon\Modules\FileManager\App\Http\Controllers\Web\Admin\Icons;

use Lareon\Modules\FileManager\App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Teksite\IconLaravel\Service\IconManager;

class IconsBrowserController extends Controller
{
    public function index()
    {
        $groups =(new  IconManager)->getIconNames();
        return view('filemanager::admin.pages.icons.index' , compact('groups'));
    }
}
