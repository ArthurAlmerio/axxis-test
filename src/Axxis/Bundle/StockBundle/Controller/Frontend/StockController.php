<?php

namespace Axxis\Bundle\StockBundle\Controller\Frontend;

use Oro\Bundle\InventoryBundle\Entity\InventoryLevel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Oro\Bundle\ProductBundle\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;

#[Route('/stock', name: 'axxis_stock_')]
class StockController extends AbstractController
{
    #[Route('/product/{sku}', name: 'product', methods: ['GET'])]
    public function productStock(string $sku, ManagerRegistry $doctrine): Response
    {
        $product = $doctrine->getRepository(Product::class)->findOneBy(['sku' => $sku]);

        if (!$product) {
            return $this->render('@AxxisStock/Stock/index.html.twig', [
                'message' => sprintf('Product with SKU "%s" not found.', $sku),
                'stock' => null,
            ]);
        }

        $inventoryLevel = $doctrine->getRepository(InventoryLevel::class)->findOneBy(
            ['product' => $product]
        );

        $message = !empty($inventoryLevel) && $inventoryLevel->getQuantity() > 0
            ? sprintf(
                'Available Stock: %d unit%s',
                $inventoryLevel->getQuantity(),
                (1 < $inventoryLevel->getQuantity()) ? 's' : ''
            )
            : 'Out of stock';

        return $this->render('@AxxisStock/Stock/index.html.twig', [
            'message' => $message,
        ]);
    }
}