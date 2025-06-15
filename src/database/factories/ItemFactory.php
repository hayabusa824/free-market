<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Item;
use App\Models\User;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => 'test',
            'price' => $this->faker->numberBetween(100, 10000),
            'description' => $this->faker->sentence,
            'image' => $this->faker->imageUrl(640, 480, 'items'),
            'condition' => $this->faker->randomElement(['新品', '良好', 'やや傷や汚れあり', '状態が悪い']),
            'category' => $this->faker->randomElement(['アクセサリー', '家電', 'キッチン', 'ファッション']),
            'user_id' => User::factory(),
            'is_sold' => $this->faker->boolean(40), // 20%の確率で売り切れ
            'buyer_id' => $this->faker->optional()->randomElement(User::pluck('id')->toArray()), // 購入者はオプション
        ];
    }
}
