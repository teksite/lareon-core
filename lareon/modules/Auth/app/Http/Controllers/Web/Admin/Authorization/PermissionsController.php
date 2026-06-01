<?php

namespace Lareon\Modules\Auth\App\Http\Controllers\Web\Admin\Authorization;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Lareon\Modules\Auth\App\Events\PermissionCrudEvent;
use Lareon\Modules\Auth\App\Http\Requests\Admin\NewPermissionRequest;
use Lareon\Modules\Auth\App\Http\Requests\Admin\UpdatePermissionRequest;
use Lareon\Modules\Auth\App\Logics\PermissionLogic;
use Lareon\Modules\Auth\App\Http\Controllers\Controller;
use Lareon\Steward\App\Enums\CrudTypeEnum;
use Teksite\Authorize\Models\Permission;
use Teksite\Handler\Facade\Responder;

class PermissionsController extends Controller implements HasMiddleware
{

    public function __construct(public PermissionLogic $logic) {}

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
     *
     * @throws \Throwable
     */
    public function index()
    {
        $res = $this->logic->all();
        $permissions = $res->result;
        return view('auth::admin.pages.permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->action([self::class , 'index']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @throws \Throwable
     */
    public function store(NewPermissionRequest $request)
    {
        $res = $this->logic->create($request->validated());

        if ($res->success) {
            event(new PermissionCrudEvent($res->result, CrudTypeEnum::CREATE, $request->validated()));
            return Responder::success(trans('lareon::global.created_successfully', ['attribute' => __('permission')]))->go();
        }
        return Responder::failed(trans('lareon::global.created_failed', ['attribute' => __('permission')]));

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
        return view('auth::admin.pages.permissions.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @throws \Throwable
     */
    public function update(UpdatePermissionRequest $request, Permission $permission)
    {
        $res = $this->logic->update($permission, $request->validated());

        if ($res->success) {
            event(new PermissionCrudEvent($permission, CrudTypeEnum::UPDATE, $request->validated()));
            return Responder::success(trans('lareon::global.updated_successfully', ['attribute' => __('permission')]))->go();
        }
        return Responder::failed(trans('lareon::global.updated_failed', ['attribute' => __('permission')]))->go();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @throws \Throwable
     */
    public function destroy(Permission $permission)
    {
        $res = $this->logic->delete($permission);

        if ($res->success) {
            event(new PermissionCrudEvent($permission, CrudTypeEnum::DELETE));
            return Responder::success(trans('lareon::global.delete_successfully', ['attribute' => __('permission')]))->route('admin.authorize.permissions.index')->go();
        }
        return Responder::failed(trans('lareon::global.delete_failed', ['attribute' => __('permission')]))->go();
    }
}
