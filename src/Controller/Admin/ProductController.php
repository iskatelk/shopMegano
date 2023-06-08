<?php

namespace App\Controller\Admin;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Products;

class ProductController extends AbstractController
{
    /**
     * @Route("/admin/product/create", name="app_admin_product")
     */

    public function create(EntityManagerInterface $em)
    {
        $products = new Products();
        $products
               ->setTitle ('Mavic')
               ->setPrice (85.99)
               ->setSeller ('sdf')
               ->setModel ('4Gb')
               ->setProductId (rand(1,10));

               $em->persist($products);
               $em->flush();

        return new Response(sprintf('This products id:%d',
        $products->getProductId()));

    }
}
