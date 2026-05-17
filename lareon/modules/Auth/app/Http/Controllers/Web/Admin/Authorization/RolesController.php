<?php

namespace Lareon\CMS\App\Http\Controllers\Web\Admin\Authorization;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Lareon\Modules\Auth\App\Http\Controllers\Controller;
use Lareon\Modules\Auth\App\Logics\RoleLogic;
use Teksite\Authorize\Models\Role;

class RolesController extends Controller implements HasMiddleware
{

    public function __construct(public RoleLogic $logic)
    {
    }

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
     */
    public function index()
    {
        $res=$this->logic->get();
        $roles=$res->result;

        return view('lareon::admin.pages.authorization.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('lareon::admin.pages.authorization.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(NewRoleRequest $request)
    {
        $result = $this->logic->register($request->validated());
        return WebResponse::byResult($result, route('admin.authorize.roles.edit', $result->result))->go();
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
        Gate::authorize('update', $role);
        return view('lareon::admin.pages.authorization.roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        Gate::authorize('update', $role);
        $res = $this->logic->change($request->validated() , $role);
        return WebResponse::byResult($res, route('admin.authorize.roles.edit', $role))->go();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        Gate::authorize('delete', $role);

        $res = $this->logic->delete($role);
        return WebResponse::byResult($res, route('admin.authorize.roles.index'))->go();
    }


}
