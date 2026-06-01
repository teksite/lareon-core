<?php

namespace Lareon\Modules\Auth\App\Http\Controllers\Web\Admin\Authorization;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Lareon\Modules\Auth\App\Events\RoleCrudEvent;
use Lareon\Modules\Auth\App\Http\Controllers\Controller;
use Lareon\Modules\Auth\App\Http\Requests\Admin\NewRoleRequest;
use Lareon\Modules\Auth\App\Http\Requests\Admin\UpdateRoleRequest;
use Lareon\Modules\Auth\App\Logics\PermissionLogic;
use Lareon\Modules\Auth\App\Logics\RoleLogic;
use Lareon\Steward\App\Enums\CrudTypeEnum;
use Teksite\Authorize\Models\Role;
use Teksite\Handler\Facade\Responder;

class RolesController extends Controller implements HasMiddleware
{

    public function __construct(public RoleLogic $logic , public PermissionLogic $permissionLogic) {}

    public static function middleware()
    {
        return [
            new Middleware('can:admin.role.read'),
            new Middleware('can:admin.role.create', only: ['create', 'store']),
            new Middleware('can:admin.role.edit', only: ['edit', 'update']),
            new Middleware('can:admin.role.delete', only: ['destroy']),
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
        $roles = $res->result;
        return view('auth::admin.pages.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permission=$this->permissionLogic->tree();
        return view('auth::admin.pages.roles.create',compact('permission'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @throws \Throwable
     */
    public function store(NewRoleRequest $request)
    {
        $res = $this->logic->create($request->validated());

        if ($res->success) {
            event(new RoleCrudEvent($res->result, CrudTypeEnum::CREATE, $request->validated()));
            return Responder::success(trans('lareon::global.created_successfully', ['attribute' => __('role')]))->go();
        }
        return Responder::failed(trans('lareon::global.created_failed', ['attribute' => __('role')]));

    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        return view('auth::admin.pages.roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @throws \Throwable
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        $res = $this->logic->update($role, $request->validated());

        if ($res->success) {
            event(new RoleCrudEvent($role, CrudTypeEnum::UPDATE, $request->validated()));
            return Responder::success(trans('lareon::global.updated_successfully', ['attribute' => __('role')]))->go();
        }
        return Responder::failed(trans('lareon::global.updated_failed', ['attribute' => __('role')]))->go();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @throws \Throwable
     */
    public function destroy(Role $role)
    {
        $res = $this->logic->delete($role);

        if ($res->success) {
            event(new RoleCrudEvent($role, CrudTypeEnum::DELETE));
            return Responder::success(trans('lareon::global.delete_successfully', ['attribute' => __('role')]))->route('admin.authorize.roles.index')->go();
        }
        return Responder::failed(trans('lareon::global.delete_failed', ['attribute' => __('role')]))->go();
    }
}
