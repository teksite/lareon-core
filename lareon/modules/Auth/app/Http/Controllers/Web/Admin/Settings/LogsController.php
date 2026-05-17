<?php

namespace Lareon\CMS\App\Http\Controllers\Web\Admin\Settings;

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Validation\Rule;
use Lareon\CMS\App\Http\Controllers\Controller;
use Lareon\CMS\App\Logic\LogLogic;
use Teksite\Lareon\Facade\WebResponse;

class LogsController extends Controller implements HasMiddleware
{
    public function __construct(public LogLogic $logic)
    {
    }

    public static function middleware()
    {
        return [
            new Middleware('can:admin.setting.log.read'),
            new Middleware('can:admin.setting.log.clear', only: ['clear']),
            new Middleware('can:admin.setting.log.delete', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $logs = $this->logic->getLogFiles()->result;
        $content = $this->logic->getLogContent()->result;
        $name = request()->input('name', 'laravel');
        return view('lareon::admin.pages.settings.logs.show', compact('logs', 'content', 'name'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function clear(Request $request)
    {
        $this->validatingFileName($request);
        $result = $this->logic->clearContent($request->input('log'));
        return WebResponse::byResult($result)->go();
    }

    /**
     * Store a newly created resource in storage.
     */

    public function destroy(Request $request)
    {
        $this->validatingFileName($request);
        $result = $this->logic->delete($request->input('log'));
        return WebResponse::byResult($result)->go();
    }
    private function validatingFileName(Request $request): void
    {
        $request->validate([
            'log' => ['required' , Rule::in($this->logic->getLogFiles()->result), ],
        ]);
    }
}
