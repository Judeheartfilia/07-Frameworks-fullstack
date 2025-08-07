<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/products')]
class ProductController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('', name: 'products_list', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $category = $request->query->get('category');
        $available = $request->query->get('available', true);

        $criteria = [];
        if ($available) {
            $criteria['available'] = true;
        }
        if ($category) {
            $criteria['category'] = $category;
        }

        $products = $this->entityManager->getRepository(Product::class)->findBy($criteria);

        $productData = [];
        foreach ($products as $product) {
            $productData[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'description' => $product->getDescription(),
                'price' => $product->getPrice(),
                'image' => $product->getImage(),
                'category' => $product->getCategory(),
                'stock' => $product->getStock(),
                'available' => $product->isAvailable()
            ];
        }

        return new JsonResponse($productData);
    }

    #[Route('/{id}', name: 'product_show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $product = $this->entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            return new JsonResponse(['error' => 'Produit non trouvé'], 404);
        }

        return new JsonResponse([
            'id' => $product->getId(),
            'name' => $product->getName(),
            'description' => $product->getDescription(),
            'price' => $product->getPrice(),
            'image' => $product->getImage(),
            'category' => $product->getCategory(),
            'stock' => $product->getStock(),
            'available' => $product->isAvailable()
        ]);
    }

    #[Route('/categories', name: 'product_categories', methods: ['GET'])]
    public function categories(): JsonResponse
    {
        $repository = $this->entityManager->getRepository(Product::class);
        $query = $repository->createQueryBuilder('p')
            ->select('DISTINCT p.category')
            ->getQuery();
        
        $categories = array_column($query->getResult(), 'category');

        return new JsonResponse($categories);
    }
}