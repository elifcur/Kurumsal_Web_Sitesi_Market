<?php

namespace Templates\Business\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Templates\Business\Models\GalleryItem;

/**
 * @extends Factory<GalleryItem>
 */
class GalleryItemFactory extends Factory
{
    protected $model = GalleryItem::class;

    public function definition(): array
    {
        return [
            'title' => rtrim(fake()->unique()->words(3, true), '.'),
            'image' => 'media/galeri-'.fake()->unique()->numberBetween(1, 999).'.jpg',
            'category' => fake()->randomElement(['Saç', 'Sakal', 'Mekan']),
            'sort_order' => 0,
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }
}
