<?php

namespace Lareon\Steward\App\Http\Controllers\Web\Admin\Admins;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Lareon\Steward\App\Enums\CrudTypeEnum;
use Lareon\Steward\App\Events\AdminCrudEvent;
use Lareon\Steward\App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lareon\Steward\App\Http\Requests\Admin\NewAdminRequest;
use Lareon\Steward\App\Http\Requests\Admin\UpdateAdminRequest;
use Lareon\Steward\App\Logics\AdminLogic;
use Lareon\Steward\App\Models\Admin;
use Teksite\Handler\Facade\Responder;

class AdminsController extends Controller implements HasMiddleware
{
    public function __construct(public AdminLogic $logic) {}

    public static function middleware()
    {
        return [
            new Middleware('can:admin.admin.read'),
            new Middleware('can:admin.admin.create', only: ['create', 'store']),
            new Middleware('can:admin.admin.edit', only: ['edit', 'update']),
            new Middleware('can:admin.admin.delete', only: ['destroy']),
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
        $admins = $res->result;
        return view('lareon::admin.pages.admins.index', compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $admin=new Admin();
        return view('lareon::admin.pages.admins.create' , compact('admin'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @throws \Throwable
     */
    public function store(NewAdminRequest $request)
    {
        $res = $this->logic->create($request->validated());
        if ($res->success) {
            event(new AdminCrudEvent($res->result, CrudTypeEnum::CREATE, $request->validated()));
            return Responder::success(trans('lareon::global.crud.success.created', ['attribute' => __('admin')]))->route('admin.admins.edit', $res->result)->go();
        }
        return Responder::failed(trans('lareon::global.crud.error.created', ['attribute' => __('admin')]))->go();

    }

    /**
     * Display the specified resource.
     */
    public function show(Admin $admin)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Admin $admin)
    {
        return view('lareon::admin.pages.admins.edit', compact('admin'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @throws \Throwable
     */
    public function update(UpdateAdminRequest $request, Admin $admin)
    {
        $res = $this->logic->update($admin, $request->validated());

        if ($res->success) {
            event(new AdminCrudEvent($admin, CrudTypeEnum::UPDATE, $request->validated()));
            return Responder::success(trans('lareon::global.crud.success.updated', ['attribute' => __('admin')]))->go();
        }
        return Responder::failed(trans('lareon::global.crud.error.updated', ['attribute' => __('admin')]))->go();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @throws \Throwable
     */
    public function destroy(Admin $admin)
    {
        $res = $this->logic->delete($admin);

        if ($res->success) {
            event(new AdminCrudEvent($admin, CrudTypeEnum::DELETE));
            return Responder::success(trans('lareon::global.crud.success.deleted', ['attribute' => __('admin')]))->route('admin.admins.index')->go();
        }
        return Responder::failed(trans('lareon::global.crud.error.deleted', ['attribute' => __('admin')]))->go();
    }
}
