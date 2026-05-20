<?php

namespace Lareon\Modules\Auth\App\Http\Controllers\Web\Admin\Authorization;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Lareon\Modules\Auth\App\Http\Controllers\Controller;
use Lareon\Modules\Auth\App\Http\Requests\Admin\NewRoleRequest;
use Lareon\Modules\Auth\App\Http\Requests\Admin\UpdateRoleRequest;
use Lareon\Modules\Auth\App\Logics\RoleLogic;
use Teksite\Authorize\Models\Role;
use Teksite\Handler\Facade\Responder;

class RolesController extends Controller /*implements HasMiddleware*/
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
     * @throws \Throwable
     */
    public function index()
    {
        $res = $this->logic->all();
        $roles = $res->result;

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
     * @throws \Throwable
     */
    public function store(NewRoleRequest $request)
    {
        $result = $this->logic->create($request->validated());
        return Responder::fromResult($result,
            trans('lareon::global.created_successfully', ['attribute' => __('role')]),
            trans('lareon::global.created_failed'),
            route('admin.authorize.roles.edit'),
            back())
                        ->go();
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
        return view('lareon::admin.pages.authorization.roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     * @throws \Throwable
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        $res = $this->logic->update($role, $request->validated());

        return Responder::fromResult($res,
            trans('lareon::global.update_successfully', ['attribute' => __('role')]),
            trans('lareon::global.update_failed'),
            route('admin.authorize.roles.edit'),
            back())
                        ->go();
    }

    /**
     * Remove the specified resource from storage.
     * @throws \Throwable
     */
    public function destroy(Role $role)
    {
        $res = $this->logic->delete($role);

        return Responder::fromResult($res,
            trans('lareon::global.deleted_successfully', ['attribute' => __('role')]),
            trans('lareon::global.deleted_failed'),
            route('admin.authorize.roles.index'),
            back())
                        ->go();
    }


}
