<?php

namespace Lareon\Modules\Auth\App\Http\Controllers\Web\Admin\Authorization;

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Lareon\Modules\Auth\App\Http\Requests\Admin\NewPermissionRequest;
use Lareon\Modules\Auth\App\Http\Requests\Admin\UpdatePermissionRequest;
use Lareon\Modules\Auth\App\Logics\PermissionLogic;
use Lareon\Modules\Auth\App\Http\Controllers\Controller;
use Teksite\Authorize\Models\Permission;
use Teksite\Handler\Facade\Responder;

class PermissionsController extends Controller implements HasMiddleware
{
    public function __construct(public PermissionLogic $logic)
    {
    }

    public static function middleware()
    {
        return [
            new Middleware('can:admin.permission.read'),
            new Middleware('can:admin.permission.create', only: ['create', 'store']),
            new Middleware('can:admin.permission.edit', only: ['edit', 'update']),
            new Middleware('can:admin.permission.delete', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     * @throws \Throwable
     */
    public function index()
    {
        $res = $this->logic->all();
        $permissions = $res->result;

        return view('lareon::admin.pages.authorization.permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('lareon::admin.pages.authorization.permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     * @throws \Throwable
     */
    public function store(NewPermissionRequest $request)
    {
        $result = $this->logic->create($request->validated());
        return Responder::fromResult($result,
            trans('lareon::global.created_successfully', ['attribute' => __('permission')]),
            trans('lareon::global.created_failed'),
            route('admin.authorize.permissions.index'),
            back())
                        ->go();
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        return view('lareon::admin.pages.authorization.permissions.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     * @throws \Throwable
     */
    public function update(UpdatePermissionRequest $request, Permission $permission)
    {
        $res = $this->logic->update($permission, $request->validated());

        return Responder::fromResult($res,
            trans('lareon::global.update_successfully', ['attribute' => __('permission')]),
            trans('lareon::global.update_failed'),
            route('admin.authorize.permissions.edit'),
            back())
                        ->go();
    }

    /**
     * Remove the specified resource from storage.
     * @throws \Throwable
     */
    public function destroy(Permission $permission)
    {
        $res = $this->logic->delete($permission);

        return Responder::fromResult($res,
            trans('lareon::global.deleted_successfully', ['attribute' => __('permission')]),
            trans('lareon::global.deleted_failed'),
            route('admin.authorize.permissions.index'),
            back())
                        ->go();
    }


}
