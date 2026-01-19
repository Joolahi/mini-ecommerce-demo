<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void 
    {
        $tenant = Tenant::create([
            'name' => 'Demo-Shop',
            'slug' => 'demo-shop',
            'domain' => 'demo-shop.local',
            'is_active' => true,
        ]);

        // different categories
        $clothes = Category::create([
            'tenant_id' => $tenant->id,
            'name' => 'Clothes',
            'slug' => 'clothes',
            'description' => 'All kinds of clothing items.',
            'is_active' => true,
        ]);

        $sports = Category::create([
            'tenant_id' => $tenant->id,
            'name' => 'Sports',
            'slug' => 'sports',
            'description' => 'Sports equipment.',
            'is_active' => true,
        ]);

        $electronics = Category::create([
            'tenant_id' => $tenant->id,
            'name' => 'Electronics',
            'slug' => 'electronics',
            'description' => 'Electronic gadgets and devices.',
            'is_active' => true,
        ]);

        // products 
        $products = [
            [
                'name' => 'T-Shirt',
                'category_id' => $clothes->id,
                'description' => 'Comfortable cotton t-shirt.',
                'short_description' => 'Cotton t-shirt', 
                'price' => 19.99,
                'compare_price' => 24.99, 
                'stock_quantity' => 100,
                'stock' => 100,
            ],
            [
                'name' => 'Jeans',
                'category_id' => $clothes->id,
                'description' => 'Stylish jeans.',
                'short_description' => 'Stylish jeans', 
                'price' => 49.99,
                'stock_quantity' => 60,
                'stock' => 60,
            ],
            [
                'name' => 'Running Shoes',
                'category_id' => $sports->id,
                'description' => 'Lightweight running shoes.',
                'short_description' => 'Running shoes', 
                'price' => 49.99,
                'compare_price' => 59.99, 
                'stock_quantity' => 50,
                'stock' => 50,
            ],
            [
                'name' => 'Golf clubs',
                'category_id' => $sports->id,
                'description' => 'Professional golf clubs.',
                'short_description' => 'Pro golf clubs',
                'price' => 749.99,
                'stock_quantity' => 20,
                'stock' => 10,
            ],
            [
                'name' => 'Smartphone',
                'category_id' => $electronics->id,
                'description' => 'Latest model smartphone with advanced features.',
                'short_description' => 'Latest smartphone',
                'price' => 699.99,
                'compare_price' => 799.99, 
                'stock_quantity' => 30,
                'stock' => 30,
            ],
            [
                'name' => 'Computer',
                'category_id' => $electronics->id,
                'description' => 'High-performance laptop computer.',
                'short_description' => 'Performance laptop',  
                'price' => 999.99,
                'compare_price' => 1199.99, 
                'stock_quantity' => 25,           
                'stock' => 25,
            ],
        ];

        foreach ($products as $productData) {
            Product::create([
                'tenant_id' => $tenant->id,
                'category_id' => $productData['category_id'],
                'sku' => 'SKU-' . strtoupper(Str::random(8)),
                'name' => $productData['name'],
                'slug' => Str::slug($productData['name']),
                'description' => $productData['description'],
                'short_description' => $productData['short_description'],
                'price' => $productData['price'],
                'compare_price' => $productData['compare_price'] ?? null,
                'stock_quantity' => $productData['stock_quantity'],
                'track_inventory' => $productData['track_inventory'] ?? true,
                'is_active' => true,
                'is_featured' => rand(0, 1) === 1,
                'stock' => $productData['stock'],
            ]);
        }

        $this->command->info('✅ Testdata luotu onnistuneesti!');
        $this->command->info('📊 Tenant: ' . $tenant->name);
        $this->command->info('📁 Kategoriat: ' . Category::count());
        $this->command->info('📦 Tuotteet: ' . Product::count());
    }
}