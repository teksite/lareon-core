<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Lareon\Modules\Page\App\Logics\PageLogic;
use Teksite\Authorize\Models\Permission;
use Teksite\Authorize\Models\Role;

class FactorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $i = 0;
        $group = ['page', 'post', 'service', 'article'];
        while ($i < 1000) {
            (new PageLogic())->create([
                "title" => "title $i",
                "slug"  => "title-$i",

                "body"           => fake()->sentences(6, true),
                "publish_status" => "1",
                "seo"            => [
                    "meta"    => [
                        "title"         => null,
                        "description"   => null,
                        "keywords"      => null,
                        "canonical_url" => null,
                        "indexable"     => "1",
                        "followable"    => "1",
                    ],
                    "sitemap" => [
                        "priority"         => "0.8",
                        "change_frequency" => "yearly",
                        'group'=>$group[array_rand($group, 1)],
                    ],
                    "schema"  => [
                        "type"     => "WebPage",
                        "web page" => [
                            "title"       => null,
                            "description" => null,
                            "image"       => null,
                        ],
                    ],
                ],
            ]);
            $i++;

        }


    }
}
