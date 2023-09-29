<?php

namespace Database\Factories;

use App\Models\EntityInfo;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EntityInfoFactory extends Factory
{
    protected $model = EntityInfo::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'company_name' => $this->faker->company,
            'company_tax_id' => $this->faker->randomNumber(9), // 9 digits
            'legal_address' => $this->faker->address,
            'postal_address' => $this->faker->secondaryAddress,
            'kpp' => $this->faker->randomNumber(9), // 9 digits
            'checking_account' => $this->faker->bankAccountNumber,
            'bic' => $this->faker->swiftBicNumber,
            'correspondent_account' => $this->faker->bankAccountNumber,
            'bank_name' => $this->faker->company,
            'contact_name' => $this->faker->name,
            'contact_phone' => $this->faker->phoneNumber,
            'contact_email' => $this->faker->companyEmail,
        ];
    }
}
