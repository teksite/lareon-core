<?php
namespace Lareon\Modules\Ticketing\Database\Factory;


use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Lareon\Modules\User\App\Models\User;

/**
 * @extends Factory<User>
 */
class TicketMessageFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [

        ];
    }


}
