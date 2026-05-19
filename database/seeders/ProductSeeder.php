<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'prod_name'        => 'Solar Panel Kit 100W',
                'prod_description' => 'Complete 100W monocrystalline solar panel kit for home or agricultural use. Includes panel, mounting hardware, and wiring guide. Ideal for off-grid setups in rural Bicol.',
                'price'            => 4500.00,
                'stock_qty'        => 25,
                'category'         => 'Solar Energy',
                'image_url'        => null,
                'variants' => [
                    ['var_name' => '100W Single Panel',  'price_modifier' => 0,      'stock_qty' => 15, 'specification' => '100W, 18V, Monocrystalline'],
                    ['var_name' => '200W Dual Panel Kit','price_modifier' => 4000.00, 'stock_qty' => 8,  'specification' => '2×100W, mounting bracket included'],
                    ['var_name' => '400W Full Kit',      'price_modifier' => 10500.00,'stock_qty' => 2,  'specification' => '4×100W + charge controller'],
                ],
            ],
            [
                'prod_name'        => 'Biogas Digester Unit',
                'prod_description' => 'Portable biogas digester for households and small farms. Converts organic waste into clean cooking gas. Low maintenance, high efficiency.',
                'price'            => 8200.00,
                'stock_qty'        => 10,
                'category'         => 'Biogas',
                'image_url'        => null,
                'variants' => [
                    ['var_name' => '1m³ Household Unit', 'price_modifier' => 0,       'stock_qty' => 6,  'specification' => '1 cubic meter capacity'],
                    ['var_name' => '2m³ Farm Unit',      'price_modifier' => 5500.00,  'stock_qty' => 4,  'specification' => '2 cubic meter capacity'],
                ],
            ],
            [
                'prod_name'        => 'Organic Fertilizer (Vermicast)',
                'prod_description' => 'Premium vermicast organic fertilizer produced from earthworm composting. Enriches soil with natural nutrients for vegetables, rice, and fruit trees.',
                'price'            => 180.00,
                'stock_qty'        => 200,
                'category'         => 'Agriculture',
                'image_url'        => null,
                'variants' => [
                    ['var_name' => '2kg Bag',  'price_modifier' => 0,     'stock_qty' => 100, 'specification' => '2 kg, air-dried'],
                    ['var_name' => '5kg Bag',  'price_modifier' => 220.00, 'stock_qty' => 70,  'specification' => '5 kg, air-dried'],
                    ['var_name' => '10kg Sack','price_modifier' => 570.00, 'stock_qty' => 30,  'specification' => '10 kg bulk sack'],
                ],
            ],
            [
                'prod_name'        => 'Solar Water Pump',
                'prod_description' => 'DC solar-powered water pump for irrigation and household water supply. No electricity bills — runs directly from solar panel.',
                'price'            => 3800.00,
                'stock_qty'        => 15,
                'category'         => 'Solar Energy',
                'image_url'        => null,
                'variants' => [
                    ['var_name' => '12V 1/2HP Pump', 'price_modifier' => 0,      'stock_qty' => 10, 'specification' => '12V DC, 40L/min flow rate'],
                    ['var_name' => '24V 1HP Pump',   'price_modifier' => 2200.00, 'stock_qty' => 5,  'specification' => '24V DC, 80L/min flow rate'],
                ],
            ],
            [
                'prod_name'        => 'Rainwater Harvesting Tank',
                'prod_description' => 'Food-grade polyethylene tank for collecting and storing rainwater. UV-resistant material with inlet filter and drain valve.',
                'price'            => 2400.00,
                'stock_qty'        => 30,
                'category'         => 'Water Management',
                'image_url'        => null,
                'variants' => [
                    ['var_name' => '500L Tank',  'price_modifier' => 0,       'stock_qty' => 15, 'specification' => '500 liters, with lid and valve'],
                    ['var_name' => '1000L Tank', 'price_modifier' => 2000.00,  'stock_qty' => 10, 'specification' => '1000 liters, with overflow fitting'],
                    ['var_name' => '2000L Tank', 'price_modifier' => 5000.00,  'stock_qty' => 5,  'specification' => '2000 liters, heavy-duty'],
                ],
            ],
            [
                'prod_name'        => 'Seedling Growing Kit',
                'prod_description' => 'Complete seedling propagation kit with seed trays, coco peat growing medium, and nutrient solution. Perfect for vegetable nursery production.',
                'price'            => 350.00,
                'stock_qty'        => 8,
                'category'         => 'Agriculture',
                'image_url'        => null,
                'variants' => [
                    ['var_name' => 'Starter Kit (50 cells)',  'price_modifier' => 0,     'stock_qty' => 5,  'specification' => '50-cell tray + 2kg coco peat'],
                    ['var_name' => 'Pro Kit (200 cells)',     'price_modifier' => 480.00, 'stock_qty' => 3,  'specification' => '4×50-cell trays + 8kg coco peat + nutrients'],
                ],
            ],
            [
                'prod_name'        => 'Solar LED Street Light',
                'prod_description' => 'All-in-one solar LED street light with built-in panel and battery. Automatic dusk-to-dawn operation. Suitable for barangay roads and pathways.',
                'price'            => 2800.00,
                'stock_qty'        => 20,
                'category'         => 'Solar Energy',
                'image_url'        => null,
                'variants' => [
                    ['var_name' => '20W LED',  'price_modifier' => 0,      'stock_qty' => 12, 'specification' => '20W, 1800 lm, 6000K daylight'],
                    ['var_name' => '40W LED',  'price_modifier' => 1600.00, 'stock_qty' => 8,  'specification' => '40W, 3600 lm, motion sensor'],
                ],
            ],
            [
                'prod_name'        => 'Compost Bin (Rotary)',
                'prod_description' => 'Dual-chamber rotary compost bin for efficient organic waste management. Speeds up composting by 2–3× with easy turning mechanism.',
                'price'            => 1950.00,
                'stock_qty'        => 3,
                'category'         => 'Waste Management',
                'image_url'        => null,
                'variants' => [
                    ['var_name' => '50L Single Chamber', 'price_modifier' => 0,     'stock_qty' => 2, 'specification' => '50L capacity'],
                    ['var_name' => '100L Dual Chamber',  'price_modifier' => 850.00, 'stock_qty' => 1, 'specification' => '2×50L dual chamber'],
                ],
            ],
        ];

        foreach ($products as $data) {
            $variants = $data['variants'];
            unset($data['variants']);

            $product = Product::create(array_merge($data, [
                'stock_level' => match(true) {
                    $data['stock_qty'] === 0  => 'out_of_stock',
                    $data['stock_qty'] <= 10  => 'low_stock',
                    default                   => 'in_stock',
                },
                'is_active' => true,
            ]));

            foreach ($variants as $variant) {
                ProductVariant::create(array_merge($variant, [
                    'product_id'  => $product->id,
                    'description' => null,
                ]));
            }
        }
    }
}
