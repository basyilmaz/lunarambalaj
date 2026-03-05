<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Post;
use App\Models\ServiceItem;
use Illuminate\Support\Str;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Categories
        $categories = [
            'frozen-straws' => [
                'tr' => ['name' => 'Frozen Pipetler'],
                'en' => ['name' => 'Frozen Straws'],
                'ru' => ['name' => 'Трубочки для смузи'],
                'ar' => ['name' => 'شفاطات مجمدة'],
            ],
            'paper-straws' => [
                'tr' => ['name' => 'Kağıt Pipetler'],
                'en' => ['name' => 'Paper Straws'],
                'ru' => ['name' => 'Бумажные трубочки'],
                'ar' => ['name' => 'شفاطات ورقية'],
            ],
            'plastic-cups' => [
                'tr' => ['name' => 'Plastik Bardaklar'],
                'en' => ['name' => 'Plastic Cups'],
                'ru' => ['name' => 'Пластиковые стаканчики'],
                'ar' => ['name' => 'أكواب بلاستيكية'],
            ],
        ];

        foreach ($categories as $key => $trans) {
            $cat = ProductCategory::firstOrCreate(['order' => 0], ['is_active' => true]);
            foreach ($trans as $lang => $data) {
                $cat->translations()->updateOrCreate(
                    ['lang' => $lang],
                    ['name' => $data['name'], 'slug' => Str::slug($data['name']) . '-' . $lang]
                );
            }
        }

        // 2. Products (Sample)
        $frozenCat = ProductCategory::first(); 
        
        $products = [
            [
                'image' => 'images/product-showcase.png',
                'specs' => ['Diameter' => '8mm', 'Length' => '24cm'],
                'trans' => [
                    'tr' => [
                        'name' => 'Renkli Frozen Pipet',
                        'short_desc' => 'Yoğun kıvamlı içecekler için geniş ağızlı pipet.',
                        'description' => '<p>Smoothie, milkshake ve frozen gibi içecekler için idealdir.</p>',
                    ],
                    'en' => [
                        'name' => 'Colorful Frozen Straw',
                        'short_desc' => 'Wide bore straw for thick drinks.',
                        'description' => '<p>Ideal for smoothies, milkshakes and frozen drinks.</p>',
                    ],
                    'ru' => [
                        'name' => 'Цветная трубочка для смузи',
                        'short_desc' => 'Широкая трубочка для густых напитков.',
                        'description' => '<p>Идеально подходит для смузи, молочных коктейлей и густых напитков.</p>',
                    ],
                    'ar' => [
                        'name' => 'شفاطة مجمدة ملونة',
                        'short_desc' => 'شفاطة واسعة للمشروبات الكثيفة.',
                        'description' => '<p>مثالي للعصائر والحليب والمشروبات المجمدة.</p>',
                    ],
                ]
            ]
        ];

        foreach ($products as $p) {
            $prod = Product::create([
                'category_id' => $frozenCat->id,
                'min_order' => 10000,
                'has_print' => true,
                'has_wrapping' => true,
                'is_active' => true,
                'image' => $p['image'],
                'specs' => $p['specs'],
            ]);

            foreach ($p['trans'] as $lang => $data) {
                $prod->translations()->create([
                    'lang' => $lang,
                    'name' => $data['name'],
                    'slug' => Str::slug($data['name']) . '-' . $lang,
                    'short_desc' => $data['short_desc'],
                    'description' => $data['description'],
                    'seo_title' => $data['name'],
                    'seo_desc' => $data['short_desc'],
                ]);
            }
        }

        // 3. Blog Posts
        $posts = [
            [
                'cover' => 'images/social-coffee.png', // Using the coffee image we moved
                'trans' => [
                    'tr' => [
                        'title' => 'Neden Frozen Pipet Tercih Edilmeli?',
                        'short_desc' => 'İşletmeniz için doğru pipet seçimi rehberi.',
                        'body' => '<p>Standart pipetlerin aksine frozen ve smoothie gibi yoğun içeceklerde tıkanma yapmayan, geniş çaplı ve dayanıklı pipetlerin avantajları.</p>',
                    ],
                    'en' => [
                        'title' => 'Why Choose Frozen Straws?',
                        'short_desc' => 'Guide to choosing the right straw for your business.',
                        'body' => '<p>Benefits of wide-bore and durable straws that do not clog in dense drinks like frozen and smoothies, unlike standard straws.</p>',
                    ],
                    'ru' => [
                        'title' => 'Почему стоит выбрать трубочки для смузи?',
                        'short_desc' => 'Руководство по выбору правильной трубочки.',
                        'body' => '<p>Преимущества широких и прочных трубочек, которые не забиваются в густых напитках.</p>',
                    ],
                    'ar' => [
                        'title' => 'لماذا تختار شفاطات المشروبات المجمدة؟',
                        'short_desc' => 'دليل اختيار الشفاطة المناسبة لعملك.',
                        'body' => '<p>فوائد الشفاطات الواسعة والمتينة التي لا تنسد في المشروبات الكثيفة.</p>',
                    ],
                ]
            ]
        ];

        foreach ($posts as $postData) {
            $post = Post::create([
                'cover' => $postData['cover'],
                'published_at' => now(),
                'is_active' => true,
            ]);

            foreach ($postData['trans'] as $lang => $data) {
                $post->translations()->create([
                    'lang' => $lang,
                    'title' => $data['title'],
                    'slug' => Str::slug($data['title']) . '-' . $lang,
                    'short_desc' => $data['short_desc'],
                    'body' => $data['body'],
                    'seo_title' => $data['title'],
                    'seo_desc' => $data['short_desc'],
                ]);
            }
        }
    }
}
