<?php

namespace Axxis\Bundle\StockBundle\Layout\Block;

use Doctrine\ORM\EntityManagerInterface;
use Oro\Bundle\ProductBundle\Entity\Product;
use Oro\Component\Layout\Block\Type\AbstractType;
use Oro\Component\Layout\Block\Type\ContainerType;
use Oro\Component\Layout\BlockView;
use Oro\Component\Layout\BlockInterface;
use Oro\Component\Layout\Block\OptionsResolver\OptionsResolver;
use Oro\Component\Layout\Block\Type\Options;
use Oro\Bundle\InventoryBundle\Entity\InventoryLevel;

class StockInfoBlockType extends AbstractType
{
    public const NAME = 'axxis_stock_info';

    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function buildView(BlockView $view, BlockInterface $block, Options $options)
    {
        $sku = $options['sku'];

        $repo = $this->em->getRepository(Product::class);
        $product = $repo->findOneBy(['sku' => $sku]);

        $stock = null;
        if ($product) {
            $inventoryRepo = $this->em->getRepository(InventoryLevel::class);
            $level = $inventoryRepo->findOneBy(['product' => $product]);
            $stock = $level ? $level->getQuantity() : 0;
        }

        $view->vars['stock'] = $stock;
        $view->vars['sku'] = $sku;
    }

    public function getParent(): ?string
    {
        return ContainerType::NAME;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'sku' => 'PROD123',
        ]);
    }
}