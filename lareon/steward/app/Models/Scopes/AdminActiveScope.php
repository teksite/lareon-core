<?php

namespace Lareon\Steward\App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Lareon\Steward\App\Enums\PublishStatusEnum;

class AdminActiveScope implements Scope
{


    public function apply(Builder $builder, Model $model,): void
    {
        $builder->where('active', true);
    }


}
