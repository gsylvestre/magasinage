<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function home()
    {
        //un tableau de produits qu'on veut passer à Twig
        //normalement, ce serait un tableau d'objets provenant de la bdd
        $products = ["caribou", "forêt"];

        //idem
        $categories = ["nature", "bouffe"];

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
}