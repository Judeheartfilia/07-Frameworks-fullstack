<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $products = [
            [
                'name' => 'Saumon fumé écossais',
                'description' => 'Saumon fumé artisanal d\'Ecosse, tranché main',
                'price' => 8.50,
                'category' => 'Poissons',
                'stock' => 15,
                'image' => 'saumon-fume.jpg'
            ],
            [
                'name' => 'Feuilletés au fromage',
                'description' => 'Délicieux feuilletés croustillants au fromage',
                'price' => 4.20,
                'category' => 'Apéritifs',
                'stock' => 20,
                'image' => 'feuillete-fromage.jpg'
            ],
            [
                'name' => 'Ratatouille provençale',
                'description' => 'Légumes du soleil mijotés selon la tradition',
                'price' => 5.80,
                'category' => 'Légumes',
                'stock' => 12,
                'image' => 'ratatouille.jpg'
            ],
            [
                'name' => 'Tiramisu authentique',
                'description' => 'Tiramisu italien traditionnel au mascarpone',
                'price' => 6.90,
                'category' => 'Desserts',
                'stock' => 8,
                'image' => 'tiramisu.jpg'
            ],
            [
                'name' => 'Nuggets de poulet',
                'description' => 'Nuggets de filet de poulet panés',
                'price' => 7.40,
                'category' => 'Volailles',
                'stock' => 25,
                'image' => 'nuggets-poulet.jpg'
            ],
            [
                'name' => 'Poêlée de légumes asiatiques',
                'description' => 'Mélange de légumes croquants sauce soja',
                'price' => 4.60,
                'category' => 'Légumes',
                'stock' => 18,
                'image' => 'poele-asiatique.jpg'
            ],
            [
                'name' => 'Tarte aux fruits rouges',
                'description' => 'Tarte sablée garnie de fruits rouges',
                'price' => 8.20,
                'category' => 'Desserts',
                'stock' => 6,
                'image' => 'tarte-fruits-rouges.jpg'
            ],
            [
                'name' => 'Crevettes à l\'ail',
                'description' => 'Crevettes sautées à l\'ail et au persil',
                'price' => 9.80,
                'category' => 'Poissons',
                'stock' => 10,
                'image' => 'crevettes-ail.jpg'
            ],
            [
                'name' => 'Quiche lorraine',
                'description' => 'Quiche traditionnelle aux lardons et gruyère',
                'price' => 5.90,
                'category' => 'Plats préparés',
                'stock' => 14,
                'image' => 'quiche-lorraine.jpg'
            ],
            [
                'name' => 'Sorbet citron',
                'description' => 'Sorbet artisanal au citron de Sicile',
                'price' => 3.80,
                'category' => 'Desserts',
                'stock' => 22,
                'image' => 'sorbet-citron.jpg'
            ],
            [
                'name' => 'Escalope milanaise',
                'description' => 'Escalope de veau panée à l\'italienne',
                'price' => 12.50,
                'category' => 'Viandes',
                'stock' => 8,
                'image' => 'escalope-milanaise.jpg'
            ],
            [
                'name' => 'Gaspacho andalou',
                'description' => 'Soupe froide de tomates et légumes d\'Espagne',
                'price' => 4.10,
                'category' => 'Soupes',
                'stock' => 16,
                'image' => 'gaspacho.jpg'
            ]
        ];

        foreach ($products as $productData) {
            $product = new Product();
            $product->setName($productData['name']);
            $product->setDescription($productData['description']);
            $product->setPrice($productData['price']);
            $product->setCategory($productData['category']);
            $product->setStock($productData['stock']);
            $product->setImage($productData['image']);
            $product->setAvailable(true);

            $manager->persist($product);
        }

        $manager->flush();
    }
}