<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * Page d'accueil
     * On a l'URL qui mène à cette fonction, puis le nom de la route ici
     * Le nom de la route sert à générer les URLs vers cette page
     * @Route("/", name="home")
     */
    public function home(Request $request, ProductRepository $productRepository)
    {
        //un tableau de produits qu'on veut passer à Twig
        //normalement, ce serait un tableau d'objets provenant de la bdd

        $products = $productRepository->findHomepageProducts();

        //idem
        $categories = ["nature", "bouffe"];

        //s'affichera dans la debug bar, sous le bouton ⌖
        dump($categories);

        //return $this->redirectToRoute('cgv');

        //on affiche le fichier twig (voir dans le répertoire templates/)
        return $this->render('default/home.html.twig', [
            //on lui passe 2 variables !
            "featuredProducts" => $products, //la variable sera featuredProducts dans Twig !!!
            "categories" => $categories //ici, tout simplement categories
        ]);
    }

    /**
     * @Route("/legal/cgv", name="cgv")
     */
    public function cgv()
    {
        $users = ["guillaume", "louis"];

        //la fonction php compact() permet de créer un tableau associatif
        //à partir des variables passées en argument
        //attention, on passe le nom des variables sous forme de chaîne !
        //certaines personnes préfèrent utiliser le compact pour éviter les répétitions
        return $this->render("default/cgv.html.twig", compact("users"));
    }

    /**
     *
     * Route qui afficherait le détail d'un produit
     * L'id du produit a affiché est intégré à la fin de l'URL grâce au {id}
     *
     * @Route(
     *     "/details/{id}",
     *     methods={"GET"},
     *     name="product_detail",
     *     requirements={"id": "\d+"}
     * )
     */
    public function productDetail($id)
    {
        //requête à la bdd pour aller ce produit
        //en fonction de son id dans l'URL

        die($id);
    }

    /**
     * @Route("/test-select", name="test_select")
     */
    public function testSelect(EntityManagerInterface $entityManager, ProductRepository $productRepository)
    {
        //autre manière de récupérer le repository

        //$productRepository = $this->getDoctrine()->getRepository(Product::class);

        //récupère toutes les données de la table, mais sans limite ni tri possible
        $products = $productRepository->findAll();

        //mieux que le findAll car plus souple !
        //findBy(where, order by, limit, offset)
        $products = $productRepository->findBy([], ["price" => "DESC"], 2);

        //récupère un product en fonction de sa clé primaire
        //le résultat sera directement l'instance, sans tableau
        $product = $productRepository->find(2);

        //modifie le produit dans la bdd (change son prix)
        $product->setPrice(6666);
        $entityManager->persist($product);
        $entityManager->flush();

        //supprime le produit #2
        //$entityManager->remove($product);
        //$entityManager->flush();

        dump($product);
        dd($products);
    }


    /**
     * @Route("/test", name="test")
     */
    public function test(EntityManagerInterface $entityManager)
    {
        //crée une instance
        $product = new Product();

        //hydrate l'instance
        $product->setName("Sirop d'érable");
        $product->setPrice(4.99);
        $product->setDateCreated(new \DateTime());


         //puisque les setters sont fluides, on peut enchaîner les appels à chacun d'eux en une seule instruction
        /*
        $product
            ->setName("pifpouf")
            ->setPrice(5235)
            ->setDateCreated(new \DateTime());
        */

        //j'aurais pu utiliser ça, mais je préfère utiliser les arguments de la méthode
        //$entityManager = $this->getDoctrine()->getManager();

        //demande à doctrine de sauvegarder mon instance
        //attention, ça n'exécute pas tout de suite la requête
        $entityManager->persist($product);

        //exécute vraiment la requête sql maintenant
        $entityManager->flush();

        return $this->render('default/test.html.twig', [
            'product' => $product
        ]);
    }
}