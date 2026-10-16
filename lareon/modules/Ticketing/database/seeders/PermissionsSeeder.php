<?php

namespace Lareon\Modules\Ticketing\Database\Seeders;

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
            [
                'title'       => 'admin.ticket.read',
                'description' => 'have access to read one or all ticket (in the admin panel)',
            ],
            [
                'title'       => 'admin.ticket.create',
                'description' => 'have access to create a new ticket (in the admin panel)',
            ],
            [
                'title'       => 'admin.ticket.edit',
                'description' => 'have access to edit ticket (in the admin panel)',
            ],
            [
                'title'       => 'admin.ticket.delete',
                'description' => 'have access to delete ticket (in the admin panel)',
            ],
            [
                'title'       => 'admin.ticket.close',
                'description' => 'have access to close ticket (in the admin panel)',
            ],

            [
                'title'       => 'admin.ticket.trash',
                'description' => 'have access to delete ticket from database (in the admin panel)',
            ],

            [
                'title'       => 'admin.ticket.assign',
                'description' => 'have access to assign ticket (in the admin panel)',
            ],
            [
                'title'       => 'admin.ticket.message.edit',
                'description' => 'have access to edit message of the ticket (in the admin panel)',
            ],
            [
                'title'       => 'admin.ticket.message.delete',
                'description' => 'have access to remove message of the ticket (in the admin panel)',
            ],
            [
                'title'       => 'admin.ticket.reply',
                'description' => 'have access to reply the ticket (in the admin panel)',
            ],


            // Panel

            [
                'title'       => 'panel.ticket.read',
                'description' => 'have access to read one or all ticket (in the panel panel)',
            ],
            [
                'title'       => 'panel.ticket.create',
                'description' => 'have access to create a new ticket (in the panel panel)',
            ],
            [
                'title'       => 'panel.ticket.edit',
                'description' => 'have access to edit ticket (in the panel panel)',
            ],
            [
                'title'       => 'panel.ticket.close',
                'description' => 'have access to close the ticket (in the panel panel)',
            ],

        ]);
    }
}
