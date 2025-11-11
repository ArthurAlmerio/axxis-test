<?php


namespace Axxis\Bundle\StockBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Oro\Bundle\EntityExtendBundle\Entity\EnumOption;
use Oro\Bundle\EntityExtendBundle\Entity\EnumOptionInterface;
use Oro\Bundle\InventoryBundle\Entity\InventoryLevel;
use Oro\Bundle\ProductBundle\Entity\Product;
use Oro\Bundle\ProductBundle\Entity\ProductName;
use Oro\Bundle\ProductBundle\Entity\ProductUnit;
use Oro\Bundle\ProductBundle\Entity\ProductUnitPrecision;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateProductCommand extends Command
{
    protected static $defaultName = 'axxis:product:create';

    public function __construct(private EntityManagerInterface $em)
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Cria um produto com estoque inicial');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Creating products...</info>');

        $product = new Product();
        $product->setSku('stock-001');
        $product->setStatus('enabled');

        $name = new ProductName();
        $name->setString('Axxis');
        $product->setNames([$name])
            ->setInventoryStatus($this->createInventoryStatus(Product::INVENTORY_STATUS_IN_STOCK, 'In Stock'));

        $unit = $this->em->getRepository(ProductUnit::class)->findOneBy(['code' => 'item']);
        if (!$unit) {
            $unit = new ProductUnit();
            $unit->setCode('item');
            $this->em->persist($unit);
        }

        $precision = new ProductUnitPrecision();
        $precision->setUnit($unit);
        $precision->setPrecision(0);
        $product->setPrimaryUnitPrecision($precision);


        $this->em->persist($product);
        $this->em->persist($precision);

        $inventory = new InventoryLevel();
        $inventory->setProductUnitPrecision($precision);
        $inventory->setQuantity(47);

        $this->em->persist($inventory);

        $product2 = new Product();
        $product2->setSku('stock-002');
        $product2->setStatus('enabled');
        $product2->setNames([$name]);

        $this->em->persist($product2);
        $this->em->flush();

        return Command::SUCCESS;
    }

    private function createInventoryStatus(string $id, string $name): EnumOptionInterface
    {
        return new EnumOption(
            Product::INVENTORY_STATUS_ENUM_CODE,
            $name,
            $id,
        );
    }
}