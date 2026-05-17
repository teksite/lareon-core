<?php

namespace Lareon\CMS\App\Http\Controllers\Web\Admin\Appearance;

use Illuminate\Http\Request;
use Lareon\CMS\App\Http\Controllers\Controller;

class MediaController extends Controller
{
    public function index()
    {
        return view('lareon::admin.pages.appearance.media.index');
    }

    public function preview()
    {
        return <<<HTML
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">

<title>Document</title>
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
       <link rel="stylesheet" href="/vendor/file-manager/css/file-manager.css">
</head>
<body>
<div id="fm" style="height: 600px;" dir="ltr"></div>
<script src="/vendor/file-manager/js/file-manager.js"></script>

</body>
</html>
HTML;
    }
}

