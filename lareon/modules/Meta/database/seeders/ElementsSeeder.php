<?php

namespace Lareon\Modules\Meta\Database\Seeders;

use Illuminate\Database\Seeder;
use Lareon\Modules\Meta\App\Models\MetaElement;
use Teksite\Authorize\Models\Permission;

class ElementsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MetaElement::query()->insert([
            /* Elements */
            [
                'element'=>'content-content.blade',
                'title'=>'content +  dynamic_content',
                'settings'=>null,
            ],
            [
                'element'=>'faq.blade',
                'title'=>'dynamic_faq',
                'settings'=>null,
            ],

        ]);

    }
}
