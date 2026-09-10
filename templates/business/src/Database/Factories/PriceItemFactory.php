<?php

namespace Templates\Business\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Templates\Business\Models\PriceItem;

/**
 * @extends Factory<PriceItem>
 */
class PriceItemFactory extends Factory
{
    protected $model = PriceItem::class;

    public function definition(): array
    {
        return [
            'name' => rtrim(fake()->unique()->words(2, true), '.'),
            'description' => fake()->sentence(8),
            'price' => fake()->numberBetween(10, 120) * 10,
            'price_note' => null,
            'group' => fake()->randomElement(['Saç', 'Sakal', 'Bakım']),
            'sort_order' => 0,
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }
}
