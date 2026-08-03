<?php

namespace Lareon\Steward\App\Traits;

use Illuminate\Support\Str;
use Teksite\Handler\Facade\Responder;

trait UseTrashController
{

    public function index(): \Illuminate\View\View
    {
        $items = $this->logic->getTrashes()->result;
        return view($this->view ?? 'lareon::admin.layouts.trash',
            [
                'items'     => $items,
                'pageTitle' => Str::of($this->attribute)->plural()->toString(),
                'backTo'    => $this->backTo,

                'indexRoute'     => $this->indexRoute,
                'pruneRoute'     => $this->pruneRoute,
                'reinstateRoute' => $this->reinstateRoute,
                'flushRoute'     => $this->flushRoute,
                'restoreRoute'   => $this->restoreRoute,
            ]);
    }


    public function reinstate($id)
    {
        $res = $this->logic->restoreOne($id);

        return Responder::fromResult($res,
            trans('lareon::global.crud.success.restored', ['attribute' => __($this->attribute)]),
            trans('lareon::global.crud.error.restored', ['attribute' => __($this->attribute)]),
            route($this->indexRoute)
        )->go();

    }


    public function prune($id)
    {
        $res = $this->logic->wipeOne($id);

        return Responder::fromResult($res,
            trans('lareon::global.crud.success.pruned', ['attribute' => __($this->attribute)]),
            trans('lareon::global.crud.error.pruned', ['attribute' => __($this->attribute)]),
            route($this->indexRoute)
        )->go();
    }

    public function restore()
    {
        $res = $this->logic->restoreAll();

        return Responder::fromResult($res,
            trans('lareon::global.crud.success.allRestored'),
            trans('lareon::global.crud.error.allRestored'),
            route($this->backTo)
        )->go();

    }


    public function flush()
    {
        $res = $this->logic->wipeAll();

        return Responder::fromResult($res,
            trans('lareon::global.crud.success.allPruned'),
            trans('lareon::global.crud.error.allPruned'),
            route($this->backTo)
        )->go();
    }


}
