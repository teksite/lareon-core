<?php

namespace Lareon\Steward\App\Http\Controllers\Web\Admin\Admins;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Lareon\Modules\Auth\App\Logics\PermissionLogic;
use Lareon\Steward\App\Enums\CrudTypeEnum;
use Lareon\Steward\App\Events\AdminCrudEvent;
use Lareon\Steward\App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lareon\Steward\App\Http\Requests\Admin\NewAdminRequest;
use Lareon\Steward\App\Http\Requests\Admin\UpdateAdminAclRequest;
use Lareon\Steward\App\Http\Requests\Admin\UpdateAdminRequest;
use Lareon\Steward\App\Logics\AdminLogic;
use Lareon\Steward\App\Models\Admin;
use Teksite\Authorize\Models\Role;
use Teksite\Handler\Facade\Responder;

class AdminsAclController extends Controller implements HasMiddleware
{
    public function __construct(public AdminLogic $logic) {}

    public static function middleware()
    {
        return [
            new Middleware('can:admin.admin.read'),
            new Middleware('can:admin.admin.acl.edit', only: ['edit', 'update']),
        ];
    }


    public function edit(Admin $admin)
    {
        $permissions = (new PermissionLogic())->tree();

        $permissions = array_filter($permissions, fn($item) => in_array($item['title'] ?? '', ['admin'] ,true));
        $rolesGroup = Role::query()->whereBetween('hierarchy', [0,50],)->orderBy('hierarchy')->get(['id', 'title', 'hierarchy'])->groupBy(fn($role) => intdiv($role->hierarchy, 10) * 10)->toArray();
        return view('lareon::admin.pages.admins.acl', compact('admin', 'permissions', 'rolesGroup'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @throws \Throwable
     */
    public function update(UpdateAdminAclRequest $request, Admin $admin)
    {
        $res = $this->logic->updateACL($admin, $request->validated());

        if ($res->success) {
            event(new AdminCrudEvent($admin, CrudTypeEnum::UPDATE, $request->validated()));
            return Responder::success(trans('lareon::global.crud.success.updated', ['attribute' => __('admin')]))->go();
        }
        return Responder::failed(trans('lareon::global.crud.error.updated', ['attribute' => __('admin')]))->go();
    }


}
