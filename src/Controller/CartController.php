<?php

namespace App\Controller;

use App\Entity\Products;
use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class CartController extends AbstractController
{
    public $session;
    public $goods=[];
    public $gids=[];
    public $counts;
    public $combine;
    public $cart=[];
    public $items;
    public $total;
    public $product_id;
    public $total_price;
   public function __construct() {

       $this->session = new Session();
      if(!isset($this->session)) $this->session->start();
    }

    public function addCart(Request $request, ProductsRepository $repository)
    {
        if(isset($_POST['clear'])) {
             $this->session->remove('goods');
             $this->session->remove('counts');
        }
        if(isset($_POST['recalculate'])) {
            $this->counts = $this->session->get('counts');
            $this->goods = $this->session->get('goods');
            // $count_item = $this->counts;

            foreach ($_POST['amount'] as $item_id => $count_item) {

                for ($i = 0; $i < count($this->goods); $i++)
                    if ($this->goods[$i] == intval($item_id)) {
                        // $count_item = min($count_item, $is);
                        $this->counts[$i] = intval($count_item);
                    }
            }
           // var_dump($this->counts);
            $this->session->set('counts', $this->counts);
        }

        $d = $request->query->get('d');
        if ($d > 0) {

            $this->product_id = $d;
            $this->counts = $this->session->get('counts');
            $this->goods = $this->session->get('goods');

            $i = 0;
            while ($this->goods[$i] != $this->product_id && $i < count($this->goods)) $i++;
          //  $this->goods[$i] = 0;
            $this->counts[$i] = 0;

            $this->session->set('goods', $this->goods);
            $this->session->set('counts', $this->counts);
        }


        $p = $request->query->get('p');
        if ($p > 0) {
            $this->product_id = $p;
      //  $this->session = $request->getSession();
        $this->goods = $this->session->get('goods');
        $this->counts = $this->session->get('counts');
        $i = 0;
        if(isset($this->goods)) $cnt = count($this->goods);
        else $cnt = 1;
        if(isset($this->goods)) {
            foreach ($this->goods as $item) {
                if ($item == $this->product_id) {
                    $this->counts[$i]++;
                    break;
                } else if ($i + 1 == $cnt) {
                    $this->goods[$i + 1] = $this->product_id;
                    $this->counts[$i + 1] = 1;
                }
                $i++;
            }
        } else {
            $this->goods[0] = $this->product_id;
            $this->counts[0] = 1;
        }
            $this->session->set('goods',$this->goods);
            $this->session->set('counts',$this->counts);

            if(isset($this->goods)) {
                foreach ($this->goods as $key => $prd_id) {
                    //  dd($prd_id);
                    if ($prd_id > 0) {
                        $this->gids[] = $repository->findSelectProduct(intval($prd_id));


                        $prc = $repository->getPriceItem($prd_id);
                        if(isset($this->counts)) {
                            $this->total += $prc[0]['price'] * $this->counts[$key];
                        }
                    }

                }
            }

            var_dump($this->cart);


           // $this->session->remove('goods');
          //  $this->session->remove('counts');
            if(isset($this->goods)) {
                return $this->goods;
            }
        }
        if(isset($this->goods)) {
            foreach ($this->goods as $key => $prd_id) {
                //  dd($prd_id);
                if ($prd_id > 0) {
                    $this->gids[] = $repository->findSelectProduct(intval($prd_id));

                    $prc = $repository->getPriceItem($prd_id);
                    if(isset($this->counts)) {
                        $this->total += $prc[0]['price'] * $this->counts[$key];
                    }
                }

            }
        }
        var_dump($this->total);
    }
    /**
     * @Route("/cart", name = "cart")
     * @Method("POST")
     */
   public function show(Request $request, ProductsRepository $repository)
    {
      //  $this->session = $request->getSession();
       $this->goods = $this->session->get('goods');
        //$this->goods = &$this->cart;

 // dd($prd_id);
        var_dump($this->goods);
        return $this->render('cart/cart.html.twig.', [
           // 'controller_name' => 'CartController',
            'goods2' => $this->addCart($request,$repository),
            'goods' => $this->goods,
            'gids' => $this->gids,
            'counts' => $this->counts,
           'total' => $this->total
           // dd($this->goods)
        ]);
    }
}
