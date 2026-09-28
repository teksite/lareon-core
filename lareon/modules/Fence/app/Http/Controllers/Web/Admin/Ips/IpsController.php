<?php

namespace Lareon\Modules\Fence\App\Http\Controllers\Web\Admin\Ips;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Lareon\Modules\Fence\App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lareon\Modules\Fence\App\Http\Requests\Admin\DeleteIpRequest;
use Lareon\Modules\Fence\App\Http\Requests\Admin\NewIpRequest;
use Lareon\Modules\Fence\App\Logics\FenceLogic;
use Lareon\Modules\Fence\App\Models\Fence;
use Teksite\Handler\Facade\Responder;

class IpsController extends Controller implements HasMiddleware
{
    public function __construct(public FenceLogic $logic,) {}

    public static function middleware()
    {
        return [
            new Middleware('can:admin.setting.edit'),
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @throws \Throwable
     */
    public function index(Request $request)
    {
        $ips = $this->logic->all($request->only(['search', 'type']))->result;
        return view('fence::admin.pages.ips.index', compact('ips'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('fence::admin.pages.ips.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(NewIpRequest $request,)
    {
        $res = $this->logic->create($request->validated());
        return Responder::fromResult($res, success_url: route('admin.settings.ips.index'))->go();
    }

    /**
     * Display the specified resource.
     */
    public function show(Fence $ip)
    {
        abort(404);
    }

    public function destroy(string|array $ip)
    {
        $res = $this->logic->delete($ip);
        return Responder::fromResult($res, success_url: route('admin.settings.ips.index'))->go();
    }
}
