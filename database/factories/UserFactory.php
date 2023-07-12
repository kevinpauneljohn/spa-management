<?php

namespace Database\Factories;

use App\Models\EmployeeTable;
use App\Models\Spa;
use App\Models\Therapist;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'spa_id' => collect(Spa::all())->pluck('id')->random(),
            'firstname' => $this->faker->firstName(),
            'middlename' => null,
            'lastname' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'username' => $this->faker->unique()->userName(),
            'mobile_number' => $this->faker->unique()->phoneNumber(),
            'date_of_birth' => null,
            'password' => bcrypt(123), // password
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    public function configure()
    {
        return $this->afterCreating(function(User $user){
            $roles = collect(['front desk','manager','therapist'])->random();
            $user->assignRole($roles);

            $offerType = collect([
                'percentage_only',
                'percentage_plus_allowance',
                'amount_only',
                'amount_plus_allowance'
            ])->random();

            if($roles === 'therapist')
            Therapist::create([
                'spa_id' => $user->spa_id,
                'user_id' => $user->id,
                'gender' => collect(['male','female'])->random(),
                'certificate' => collect(['NC2','DOH'])->random(),
                'commission_percentage' => $offerType == 'percentage_only'
                    || $offerType == 'percentage_plus_allowance' ? rand(10,40) : null,
                'commission_flat' => $offerType == 'amount_only'
                || $offerType == 'amount_plus_allowance' ? rand(100,200) : null,
                'allowance' => $offerType == 'percentage_plus_allowance'
                || $offerType == 'amount_plus_allowance' ? rand(200,350) : null,
                'offer_type' => $offerType
            ]);
        });
    }

}
