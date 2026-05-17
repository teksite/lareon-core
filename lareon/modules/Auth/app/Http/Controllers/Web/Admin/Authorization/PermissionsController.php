<?php

namespace Lareon\CMS\App\Http\Controllers\Web\Admin\Authorization;

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Lareon\Modules\Auth\App\Logics\PermissionLogic;
use Lareon\Modules\Auth\App\Http\Controllers\Controller;
use Teksite\Authorize\Models\Permission;

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
     */
    public function index()
    {
        $res=$this->logic->get();
        $permissions=$res->result;

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
     */
    public function store(NewPermissionRequest $request)
    {
        $result = $this->logic->register($request->validated());
        return WebResponse::byResult($result)->go();
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
     */
    public function update(UpdatePermissionRequest $request, Permission $permission)
    {
        Gate::denyIf(is_null($permission->created_at) ,'you cannot edit this permission');

        $res = $this->logic->change($request->validated() , $permission);
        return WebResponse::byResult($res, route('admin.authorize.permissions.edit', $permission))->go();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        Gate::denyIf(is_null($permission->created_at) ,'you cannot delete this permission');

        $res = $this->logic->delete($permission);
        return WebResponse::byResult($res, route('admin.authorize.permissions.index'))->go();
    }


}
