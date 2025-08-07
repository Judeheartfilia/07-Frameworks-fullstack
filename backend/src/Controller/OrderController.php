<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/orders')]
#[IsGranted('ROLE_USER')]
class OrderController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('', name: 'orders_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $user = $this->getUser();
        $orders = $this->entityManager->getRepository(Order::class)->findBy(
            ['user' => $user],
            ['createdAt' => 'DESC']
        );

        $orderData = [];
        foreach ($orders as $order) {
            $items = [];
            foreach ($order->getOrderItems() as $item) {
                $items[] = [
                    'productId' => $item->getProduct()->getId(),
                    'productName' => $item->getProduct()->getName(),
                    'quantity' => $item->getQuantity(),
                    'price' => $item->getPrice(),
                    'subtotal' => $item->getSubtotal()
                ];
            }

            $orderData[] = [
                'id' => $order->getId(),
                'createdAt' => $order->getCreatedAt()->format('Y-m-d H:i:s'),
                'totalAmount' => $order->getTotalAmount(),
                'status' => $order->getStatus(),
                'cardLastFour' => $order->getCardLastFour(),
                'items' => $items
            ];
        }

        return new JsonResponse($orderData);
    }

    #[Route('', name: 'order_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $user = $this->getUser();

        if (!isset($data['items']) || !isset($data['payment'])) {
            return new JsonResponse(['error' => 'Données manquantes'], 400);
        }

        $cardNumber = $data['payment']['cardNumber'] ?? '';
        $expiryDate = $data['payment']['expiryDate'] ?? '';
        
        if (strlen($cardNumber) < 16 || !$expiryDate) {
            return new JsonResponse(['error' => 'Informations de carte invalides'], 400);
        }

        $order = new Order();
        $order->setUser($user);
        $order->setCardLastFour(substr($cardNumber, -4));

        $total = 0;
        foreach ($data['items'] as $itemData) {
            $product = $this->entityManager->getRepository(Product::class)->find($itemData['productId']);
            
            if (!$product || !$product->isAvailable() || $product->getStock() < $itemData['quantity']) {
                return new JsonResponse(['error' => 'Produit indisponible: ' . $product?->getName()], 400);
            }

            $orderItem = new OrderItem();
            $orderItem->setProduct($product);
            $orderItem->setQuantity($itemData['quantity']);
            $orderItem->setPrice($product->getPrice());

            $order->addOrderItem($orderItem);
            $product->decreaseStock($itemData['quantity']);
            
            $total += $orderItem->getSubtotal();
        }

        $order->setTotalAmount($total);
        $order->setStatus('completed');

        $loyaltyPoints = (int) ($total / 10);
        $user->addLoyaltyPoints($loyaltyPoints);

        $this->entityManager->persist($order);
        $this->entityManager->flush();

        return new JsonResponse([
            'orderId' => $order->getId(),
            'totalAmount' => $order->getTotalAmount(),
            'loyaltyPointsEarned' => $loyaltyPoints,
            'status' => 'success'
        ], 201);
    }

    #[Route('/{id}', name: 'order_show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $user = $this->getUser();
        $order = $this->entityManager->getRepository(Order::class)->findOneBy([
            'id' => $id,
            'user' => $user
        ]);

        if (!$order) {
            return new JsonResponse(['error' => 'Commande non trouvée'], 404);
        }

        $items = [];
        foreach ($order->getOrderItems() as $item) {
            $items[] = [
                'productId' => $item->getProduct()->getId(),
                'productName' => $item->getProduct()->getName(),
                'quantity' => $item->getQuantity(),
                'price' => $item->getPrice(),
                'subtotal' => $item->getSubtotal()
            ];
        }

        return new JsonResponse([
            'id' => $order->getId(),
            'createdAt' => $order->getCreatedAt()->format('Y-m-d H:i:s'),
            'totalAmount' => $order->getTotalAmount(),
            'status' => $order->getStatus(),
            'cardLastFour' => $order->getCardLastFour(),
            'items' => $items
        ]);
    }
}