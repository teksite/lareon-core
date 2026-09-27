<?php

namespace Lareon\Modules\Menu\App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

#[Fillable(['menu_id', 'parent_id', 'position', 'title', 'subtitle', 'pre_icon', 'next_icon', 'image', 'url', 'classes', 'attributes',])]
class SubMenu extends Model
{
    protected $table = 'menus_items';

    use HasRecursiveRelationships;


    public static function rules(): array
    {
        return [
            'parent_id'  => 'nullable|string',
            'position'   => 'nullable|string',
            'title'      => 'nullable|string',
            'subtitle'   => 'nullable|string',
            'pre_icon'   => 'nullable|string',
            'next_icon'  => 'nullable|string',
            'image'      => 'nullable|string',
            'url'        => 'nullable|string',
            'classes'    => 'nullable|string',
            'attributes' => 'nullable|string',
        ];
    }

    public function menu(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }
}
