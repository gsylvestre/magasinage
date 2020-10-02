<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Review;
use App\Form\ProductType;
use App\Form\ReviewType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/product/detail/{id}", name="product_detail")
     */
    public function detail(Product $product, Request $request)
    {
        $review = new Review();
        $reviewForm = $this->createForm(ReviewType::class, $review);

        $reviewForm->handleRequest($request);

        if ($reviewForm->isSubmitted() && $reviewForm->isValid())
        {
            //hydrate
            //associe cette review au produit actuel en lui passant l'objet au complet !
            $review->setProduct($product);

            $em = $this->getDoctrine()->getManager();
            $em->persist($review);
            $em->flush();
        }

        return $this->render('product/detail.html.twig', [
            'product' => $product,
            'reviewForm' => $reviewForm->createView()
        ]);
    }

    /**
     * @Route("/product/add", name="product_add")
     */
    public function add(Request $request, EntityManagerInterface $entityManager)
    {
        //crée une instance vide de produit
        $product = new Product();
        //crée une instance de notre formulaire, en lui associant cette entité vide
        $productForm = $this->createForm(ProductType::class, $product);

        //demande à Symfony d'hydrater mon $product avec les données reçues
        $productForm->handleRequest($request);

        //dd($product);

        //si le form est soumis et valide... on va sauvegarder en bdd
        if ($productForm->isSubmitted() && $productForm->isValid()){
            //hydrate les propriétés qui ne se trouvent pas le formulaire
            $product->setDateCreated(new \DateTime());

            //on sauvegarde en bdd avec l'entity manager !
            $entityManager->persist($product);
            $entityManager->flush();

            //ajoute un message qui sera affiché à la prochaine page
            $this->addFlash('success', 'Le produit a bien été ajouté !');

            //rediriger l'utilisateur soit sur une autre, soit sur la même page
            //pour vider le formulaire et empêcher sa resoumission involontaire
            return $this->redirectToRoute('home');
        }

        return $this->render('product/add.html.twig', [
            "productForm" => $productForm->createView()
        ]);
    }
}
