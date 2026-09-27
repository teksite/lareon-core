<?php

namespace Lareon\Modules\Menu\App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['title', 'classes'])]
class Menu extends Model
{
    public static function rules(): array
    {
        return [
            'title'   => 'required|max:255|string',
            'classes' => 'nullable|string',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($post,) {
            $post->label = 'menu_'.time();
        });
    }


    public function subs(): HasMany
    {
        return $this->hasMany(SubMenu::class, 'menu_id');
    }

    public function treeItems()
    {
        return SubMenu::query()
                      ->treeOf(function ($query,) {
                          $query->where('menu_id', $this->id)->whereNull('parent_id');
                      })
                      ->breadthFirst()->orderBy('position')
                      ->get()
                      ->toTree();
    }


    public static function treeByLabel(string $label,)
    {
        $menu = static::query()->where('label', $label)->first();

        return $menu ? $menu->treeItems() : collect();
    }
}
