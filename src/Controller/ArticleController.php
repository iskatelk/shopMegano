<?php

namespace App\Controller;

use App\Entity\Products;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController {
    /**
     * @Route("/")
     */
    public function homepage() {
        return new Response('Это наша первая страница.');
}


    /**
     * @Route("/catalog")
     */
    public function show(Request $request, EntityManagerInterface $em)
    {
        $repository = $em->getRepository(Products::class);
        $comments = $repository->findAll();
       /* $comments = [
            [
            'title' => 'Corsair Carbide Series Arctic White Steel',
            'price' => 85.00,
            'populate' => 10,
             'seller' => 'sdfsdf',
             'model' => '2Gb',
             'product_id' => 1,
                ],
            [
            'title' => 'Barand New Phone Smart Business',
            'price' => 185.00,
            'populate' => 5,
                'seller' => 'sdfsdf',
                'model' => '2Gb',
                'product_id' => 2,
                ],
            [
            'title' => 'Mavic PRO Mini Drones Hobby RC Quadcopter',
            'price' => 125.00,
            'populate' => 2,
                'seller' => 'sdfsdf',
                'model' => '4Gb',
                'product_id' => 3,
                ],
            [
            'title' => 'Corsair Carbide Series Arctic White Steel',
            'price' => 145.,
            'populate' => 4,
                'seller' => 'sdfsdf',
                'model' => '2Gb',
                'product_id' => 4,
                ],
            [
            'title' => 'Corsair Carbide Series Arctic White Steel',
            'price' => 165.00,
            'populate' => 3,
                'seller' => 'sdfsdf',
                'model' => '2Gb',
                'product_id' => 5,
                ],
            [
            'title' => 'Barand New Phone Smart Business',
            'price' => 135.00,
            'populate' => 7,
                'seller' => 'sdfsdf',
                'model' => '2Gb',
                'product_id' => 6,
                ],
            [
            'title' => 'Mavic PRO Mini Drones Hobby RC Quadcopter',
            'price' => 75.00,
            'populate' => 8,
                'seller' => 'sdfsdf',
                'model' => '2Gb',
                'product_id' => 7,
                ],
            [
            'title' => 'Corsair Carbide Series Arctic White Steel',
            'price' => 120.00,
            'populate' => 9,
                'seller' => 'sdfsdf',
                'model' => '2Gb',
                'product_id' => 8,
                ]
        ];*/
        $price = $request->query->get('price');
//            if ($price > 1) {
//                $title = $request->query->get('title');
//                $seller = $request->query->get('seller');
//                $model = $request->query->get('model');
//
//                $comments1 = array_filter($comments, function ($comment) use ($title) {
//                    return stripos($comment['title'], $title) !== false;
//                });
//                $comments2 = array_filter($comments, function ($comment) use ($model) {
//                    return stripos($comment['model'], $model) !== false;
//                });
//               // $comments = $comments1 && $comments2;
//              //  foreach ($comments1 as $comment1) {
//                  //  if($comments1 === $comments2){
//                      //  $comments = $comment1 ;
//                  //  }
//              //  }
//            }
        $q = $request->query->get('q');

        if ($q == 1) {

            uasort($comments,  function ($a, $b){
                return ($a['price'] > $b['price']);
            });

            } else if ($q == 2) {
            uasort($comments,  function ($a, $b){
                return ($a['populate'] < $b['populate']);
            });
            } else if ($q && ($q != '1' || $q != '2')){
            $comments = array_filter($comments, function ($comment) use ($q) {
                return stripos($comment['title'], $q) !== false;
            });
        }

        return $this->render('catalog/catalog.html.twig', [
            //'article' => ucwords(str_replace('-', ' ', $slug)),
            'comments' => $comments,
        ]);
    }

}