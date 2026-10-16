<?php

namespace Lareon\Modules\Ticketing\Database\Factory;

use Illuminate\Database\Eloquent\Factories\Factory;
use Lareon\Modules\Ticketing\App\Enums\PriorityType;
use Lareon\Modules\Ticketing\App\Enums\TicketStatusType;
use Lareon\Modules\Ticketing\App\Models\Ticket;
use Lareon\Modules\User\App\Models\User;

/**
 * @extends Factory<User>
 */
class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    public function definition(): array
    {
        return [
            'ticket_number'    => 'TCK-'.now()->format('ymd').'-'.strtoupper($this->faker->bothify('?????')),
            'user_id'          => 1,
            'subject'          => $this->faker->sentence(6),
            'priority'         => $this->faker->randomElement(PriorityType::cases())->value,
            'status'           => TicketStatusType::Opened->value,
            'source'           => 'web',
            'last_activity_at' => now(),
        ];
    }


}
