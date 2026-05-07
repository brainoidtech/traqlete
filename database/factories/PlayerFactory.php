<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Player>
 */
class PlayerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name'           => $this->faker->firstName,
            'last_name'            => $this->faker->lastName,

            'address1'             => $this->faker->streetAddress,
            'address2'             => $this->faker->secondaryAddress,
            'area'                 => $this->faker->word,
            'city'                 => $this->faker->city,
            'pincode'              => $this->faker->postcode,

            'dob'                  => $this->faker->date('Y-m-d', '-10 years'),
            'age'                  => $this->faker->numberBetween(5, 30),
            'gender'               => $this->faker->randomElement(['male', 'female']),

            'dept_id'              => $this->faker->numberBetween(1, 10),
            'batch_id'             => $this->faker->numberBetween(1, 20),
            'assign_coach_id'      => $this->faker->numberBetween(1, 10),

            'date_of_enrollment'   => $this->faker->date(),

            'player_contact_no'    => $this->faker->numerify('9#########'),
            'parent_contact_no'    => $this->faker->numerify('9#########'),

            'parent_email'         => $this->faker->safeEmail,
            'player_email'         => $this->faker->unique()->safeEmail,

            'fee_detail'           => $this->faker->randomFloat(2, 1000, 50000),
            'receipt_no'           => strtoupper($this->faker->bothify('RCPT-#####')),
            'receipt_date'         => $this->faker->date(),

            'aadhar_number'        => $this->faker->numerify('############'),
            'passport_number'      => strtoupper($this->faker->bothify('?#??????')),

            'player_image'         => 'players/' . $this->faker->image('public/storage/players', 400, 400, null, false),

            'created_at'           => now(),
            'updated_at'           => now(),
            'deleted_at'           => null,
        ];
    }
}
