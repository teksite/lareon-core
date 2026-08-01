<?php

namespace Lareon\Modules\Meta\Database\Seeders;

use Illuminate\Database\Seeder;
use Lareon\Modules\Meta\App\Models\MetaElement;
use Lareon\Modules\Meta\App\Models\MetaTemplate;
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
                'element'  => 'content-content.blade',
                'title'    => 'content +  dynamic_content',
                'settings' => null,
            ],
            [
                'element'  => 'faq.blade',
                'title'    => 'dynamic_faq',
                'settings' => null,
            ],

        ]);

        MetaTemplate::query()->insert([
            /* Elements */
            [
                'template'   => 'pages/pages/templates/about-us.blade',
                'title'      => 'about page',
                'model_type' => 'page',
            ],
            [
                'template'   => 'pages/pages/templates/contact-us.blade',
                'title'      => 'contact page',
                'model_type' => 'page',
            ],
            [
                'template'   => 'pages/pages/templates/faq.blade',
                'title'      => 'faq page',
                'model_type' => 'page',
            ],
        ]);

    }
}
