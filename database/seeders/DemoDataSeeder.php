<?php

namespace Database\Seeders;

use App\Models\Dish;
use App\Models\Event;
use App\Models\Ingredient;
use App\Models\Inventory;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Refrigerator;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $ref1 = Refrigerator::create(['name' => 'Основной холодильник', 'location' => 'Кухня', 'description' => 'Для замороженных продуктов']);
        $ref2 = Refrigerator::create(['name' => 'Морозильная камера', 'location' => 'Подсобка', 'description' => 'Долгосрочное хранение']);

        $ingredients = [
            Ingredient::create(['name' => 'Куриное филе', 'unit' => 'kg', 'category' => 'frozen', 'cost_per_unit' => 350]),
            Ingredient::create(['name' => 'Картофель', 'unit' => 'kg', 'category' => 'fresh', 'cost_per_unit' => 45]),
            Ingredient::create(['name' => 'Морковь', 'unit' => 'kg', 'category' => 'fresh', 'cost_per_unit' => 35]),
            Ingredient::create(['name' => 'Лук репчатый', 'unit' => 'kg', 'category' => 'fresh', 'cost_per_unit' => 30]),
            Ingredient::create(['name' => 'Сметана', 'unit' => 'l', 'category' => 'fresh', 'cost_per_unit' => 120]),
            Ingredient::create(['name' => 'Сливки', 'unit' => 'l', 'category' => 'fresh', 'cost_per_unit' => 180]),
            Ingredient::create(['name' => 'Сыр твердый', 'unit' => 'kg', 'category' => 'fresh', 'cost_per_unit' => 600]),
            Ingredient::create(['name' => 'Макароны', 'unit' => 'kg', 'category' => 'fresh', 'cost_per_unit' => 80]),
            Ingredient::create(['name' => 'Помидоры', 'unit' => 'kg', 'category' => 'fresh', 'cost_per_unit' => 150]),
            Ingredient::create(['name' => 'Огурцы', 'unit' => 'kg', 'category' => 'fresh', 'cost_per_unit' => 80]),
            Ingredient::create(['name' => 'Зелень', 'unit' => 'kg', 'category' => 'fresh', 'cost_per_unit' => 200]),
            Ingredient::create(['name' => 'Масло растительное', 'unit' => 'l', 'category' => 'fresh', 'cost_per_unit' => 100]),
            Ingredient::create(['name' => 'Соль', 'unit' => 'kg', 'category' => 'fresh', 'cost_per_unit' => 20]),
            Ingredient::create(['name' => 'Перец черный', 'unit' => 'kg', 'category' => 'fresh', 'cost_per_unit' => 500]),
            Ingredient::create(['name' => 'Лаваш', 'unit' => 'pcs', 'category' => 'frozen', 'cost_per_unit' => 25]),
            Ingredient::create(['name' => 'Фарш мясной', 'unit' => 'kg', 'category' => 'frozen', 'cost_per_unit' => 400]),
            Ingredient::create(['name' => 'Рис', 'unit' => 'kg', 'category' => 'fresh', 'cost_per_unit' => 90]),
            Ingredient::create(['name' => 'Гречка', 'unit' => 'kg', 'category' => 'fresh', 'cost_per_unit' => 70]),
            Ingredient::create(['name' => 'Масло сливочное', 'unit' => 'kg', 'category' => 'frozen', 'cost_per_unit' => 500]),
            Ingredient::create(['name' => 'Яйца', 'unit' => 'pcs', 'category' => 'fresh', 'cost_per_unit' => 8]),
            Ingredient::create(['name' => 'Молоко', 'unit' => 'l', 'category' => 'fresh', 'cost_per_unit' => 65]),
            Ingredient::create(['name' => 'Мука', 'unit' => 'kg', 'category' => 'fresh', 'cost_per_unit' => 40]),
            Ingredient::create(['name' => 'Сахар', 'unit' => 'kg', 'category' => 'fresh', 'cost_per_unit' => 55]),
            Ingredient::create(['name' => 'Грибы шампиньоны', 'unit' => 'kg', 'category' => 'frozen', 'cost_per_unit' => 200]),
            Ingredient::create(['name' => 'Капуста', 'unit' => 'kg', 'category' => 'fresh', 'cost_per_unit' => 40]),
        ];

        $chicken = $ingredients[0];
        $potato = $ingredients[1];
        $carrot = $ingredients[2];
        $onion = $ingredients[3];
        $smetana = $ingredients[4];
        $cream = $ingredients[5];
        $cheese = $ingredients[6];
        $pasta = $ingredients[7];
        $tomato = $ingredients[8];
        $cucumber = $ingredients[9];
        $greens = $ingredients[10];
        $oil = $ingredients[11];
        $salt = $ingredients[12];
        $pepper = $ingredients[13];
        $lavash = $ingredients[14];
        $mince = $ingredients[15];
        $rice = $ingredients[16];
        $buckwheat = $ingredients[17];
        $butter = $ingredients[18];
        $eggs = $ingredients[19];
        $milk = $ingredients[20];
        $flour = $ingredients[21];
        $sugar = $ingredients[22];
        $mushrooms = $ingredients[23];
        $cabbage = $ingredients[24];

        $dish1 = Dish::create(['name' => 'Куриный суп с лапшой', 'category' => 'Супы', 'price_per_person' => 150, 'is_active' => true]);
        $dish1->ingredients()->attach([
            $chicken->id => ['quantity_per_person' => 0.150],
            $carrot->id => ['quantity_per_person' => 0.050],
            $onion->id => ['quantity_per_person' => 0.030],
            $potato->id => ['quantity_per_person' => 0.100],
            $pasta->id => ['quantity_per_person' => 0.050],
            $salt->id => ['quantity_per_person' => 0.003],
            $oil->id => ['quantity_per_person' => 0.010],
        ]);

        $dish2 = Dish::create(['name' => 'Салат Цезарь', 'category' => 'Салаты', 'price_per_person' => 200, 'is_active' => true]);
        $dish2->ingredients()->attach([
            $chicken->id => ['quantity_per_person' => 0.100],
            $tomato->id => ['quantity_per_person' => 0.080],
            $cheese->id => ['quantity_per_person' => 0.030],
            $eggs->id => ['quantity_per_person' => 1],
            $lavash->id => ['quantity_per_person' => 0.5],
            $oil->id => ['quantity_per_person' => 0.015],
        ]);

        $dish3 = Dish::create(['name' => 'Макароны по-флотски', 'category' => 'Горячее', 'price_per_person' => 180, 'is_active' => true]);
        $dish3->ingredients()->attach([
            $mince->id => ['quantity_per_person' => 0.150],
            $pasta->id => ['quantity_per_person' => 0.200],
            $onion->id => ['quantity_per_person' => 0.050],
            $oil->id => ['quantity_per_person' => 0.015],
            $salt->id => ['quantity_per_person' => 0.003],
            $pepper->id => ['quantity_per_person' => 0.001],
        ]);

        $dish4 = Dish::create(['name' => 'Гречка с грибами', 'category' => 'Горячее', 'price_per_person' => 160, 'is_active' => true]);
        $dish4->ingredients()->attach([
            $buckwheat->id => ['quantity_per_person' => 0.150],
            $mushrooms->id => ['quantity_per_person' => 0.100],
            $onion->id => ['quantity_per_person' => 0.040],
            $butter->id => ['quantity_per_person' => 0.020],
            $salt->id => ['quantity_per_person' => 0.003],
        ]);

        Inventory::create(['refrigerator_id' => $ref1->id, 'ingredient_id' => $chicken->id, 'quantity' => 5, 'expiration_date' => now()->addMonths(3)]);
        Inventory::create(['refrigerator_id' => $ref1->id, 'ingredient_id' => $mince->id, 'quantity' => 3, 'expiration_date' => now()->addMonths(2)]);
        Inventory::create(['refrigerator_id' => $ref1->id, 'ingredient_id' => $mushrooms->id, 'quantity' => 2, 'expiration_date' => now()->addMonths(4)]);
        Inventory::create(['refrigerator_id' => $ref2->id, 'ingredient_id' => $chicken->id, 'quantity' => 8, 'expiration_date' => now()->addMonths(6)]);
        Inventory::create(['refrigerator_id' => $ref2->id, 'ingredient_id' => $lavash->id, 'quantity' => 20, 'expiration_date' => now()->addMonths(1)]);

        $event = Event::create([
            'client_name' => 'Иванов Иван',
            'client_phone' => '+7-999-123-45-67',
            'event_type' => 'banquet',
            'event_date' => now()->addDays(14),
            'event_time' => '18:00',
            'people_count' => 20,
            'status' => 'confirmed',
            'notes' => 'День рождения, нужен торт',
        ]);
        $event->dishes()->attach([
            $dish1->id => ['servings' => 20],
            $dish2->id => ['servings' => 20],
            $dish3->id => ['servings' => 20],
        ]);

        $event2 = Event::create([
            'client_name' => 'Петрова Анна',
            'client_phone' => '+7-999-987-65-43',
            'event_type' => 'wedding',
            'event_date' => now()->addDays(7),
            'people_count' => 10,
            'status' => 'new',
            'notes' => 'Юбилей',
        ]);
        $event2->dishes()->attach([
            $dish4->id => ['servings' => 10],
            $dish2->id => ['servings' => 10],
        ]);

        $purchase = Purchase::create([
            'event_id' => $event->id,
            'purchase_date' => now()->addDays(1),
            'status' => 'pending',
            'total_cost' => 4500,
            'notes' => 'Закупка к мероприятию Иванова',
        ]);
        PurchaseItem::create(['purchase_id' => $purchase->id, 'ingredient_id' => $potato->id, 'quantity' => 3, 'cost' => 135]);
        PurchaseItem::create(['purchase_id' => $purchase->id, 'ingredient_id' => $smetana->id, 'quantity' => 2, 'cost' => 240]);
        PurchaseItem::create(['purchase_id' => $purchase->id, 'ingredient_id' => $cheese->id, 'quantity' => 1.5, 'cost' => 900]);
        PurchaseItem::create(['purchase_id' => $purchase->id, 'ingredient_id' => $pasta->id, 'quantity' => 4, 'cost' => 320]);
    }
}
