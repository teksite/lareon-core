<?php

namespace Lareon\Modules\Questionnaire\Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Teksite\Authorize\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::query()->insert([

            /* Form */
            [
                'title'=>'admin.questionnaire.form.read',
                'description'=>'have access to read one or all questionnaire forms (in the admin panel)',
            ],
            [
                'title'=>'admin.questionnaire.form.create',
                'description'=>'have access to create a new questionnaire form (in the admin panel)',
            ],
            [
                'title'=>'admin.questionnaire.form.edit',
                'description'=>'have access to edit questionnaire forms (in the admin panel)',
            ],
            [
                'title'=>'admin.questionnaire.form.delete',
                'description'=>'have access to delete questionnaire forms (in the admin panel)',
            ],
            [
                'title'=>'admin.questionnaire.form.trash',
                'description'=>'have access to delete questionnaire forms from database (in the admin panel)',
            ],
            /* inbox */
            [
                'title'=>'admin.questionnaire.inbox.read',
                'description'=>'have access to read one or all questionnaire inboxes (in the admin panel)',
            ],
            [
                'title'=>'admin.questionnaire.inbox.create',
                'description'=>'have access to create a new questionnaire inbox (in the admin panel)',
            ],
            [
                'title'=>'admin.questionnaire.inbox.edit',
                'description'=>'have access to edit questionnaire inboxes (in the admin panel)',
            ],
            [
                'title'=>'admin.questionnaire.inbox.delete',
                'description'=>'have access to delete questionnaire inboxes (in the admin panel)',
            ],
            [
                'title'=>'admin.questionnaire.inbox.trash',
                'description'=>'have access to delete questionnaire inboxes from database (in the admin panel)',
            ],

            /* Export */
            [
                'title'=>'admin.questionnaire.inbox.export',
                'description'=>'have access to export one or all questionnaire inboxes (in the admin panel)',
            ],
            /* client inbox */
            [
                'title'=>'client.questionnaire.inbox.read',
                'description'=>'have access to read one or all questionnaire inboxes (in the client panel)',
            ],
        ]);
    }
}
