<?php

namespace Lareon\Steward\App\Http\Controllers\Web\Admin;

use Lareon\Steward\App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view("lareon::admin.pages.dashboard");
    }
}
