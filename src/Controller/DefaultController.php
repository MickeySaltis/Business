<?php

// Ressources
namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Produit;
use App\Entity\Categorie;
use App\Entity\SousCategorie;
use Symfony\Component\HttpFoundation\Request;


// DefaultController
class DefaultController extends AbstractController
{
    private $produitRepository;

    public function __construct(ProduitRepository $produitRepository){
        $this->produitRepository = $produitRepository;
    }

// Méthode Afficher tout les produits ACCEUIL
    // Chemin
    #[Route('/', name: 'default')]

    public function index(): Response
    {

        $produits = $this->produitRepository->findAll();


        // retour des produits sur le template (View)
        return $this->render('default/index.html.twig', [
            'produits' => $produits,
        ]);

    }

// Méthode Afficher un Produit DETAIL
    // Chemin
    #[Route('produit/{id}', name: "detail_product")]

    public function getOne(Produit $produit, Request $request): Response {

        $produits = $this->produitRepository->findAll();

        $session = $request->getSession();
        $quantite = $session->get("quantite");
        if($quantite == 0) {
            $quantite = 1;
        }

        return $this->render('default/produit.html.twig', [
            'produit' => $produit,
            'produits' => $produits,
            'quantite'=> $quantite
        ]);
    }

    // Ajout Quantité Produit 
    #[Route('/plus', name: '+')]  
    public function plusQuantite (Request $request) {
        // Récupération de la SESSION
        $session = $request->getSession();
        // Récupération de Quantite dans la SESSION
        $quantite = $session->get("quantite");

        if($quantite == 0) {
            $quantite = 1;
        }
        // +1 pour Quantite
        $quantite += 1;

        // Mise à jour de la SESSION Quantite
        $session->set("quantite", $quantite);

        //var_dump($quantite);
        //die;
        return $this->redirect($request->headers->get('referer'));
    }
    // Soustraction Quantité Produit 
    #[Route('/moins', name: '-')]  
    public function moinsQuantite (Request $request) {
        // Récupération de la SESSION
        $session = $request->getSession();
        // Récupération de Quantite dans la SESSION
        $quantite = $session->get("quantite");

        if($quantite == 0) {
            $quantite = 1;
        }
        // -1 pour Quantite
        $quantite -= 1;

        // Mise à jour de la SESSION Quantite
        $session->set("quantite", $quantite);

        //var_dump($quantite);
        //die;
        return $this->redirect($request->headers->get('referer'));
    }

    // FOOTER Vue
    #[Route('qui_sommes_nous', name: "qui_sommes_nous")]
    public function nous(): Response
    {
        //(View)
        return $this->render('default/footer/qui_sommes_nous.html.twig', [

        ]);
    }
    #[Route('presse', name: "presse")]
    public function presse(): Response
    {
        //(View)
        return $this->render('default/footer/presse.html.twig', [

        ]);
    }
    #[Route('emplois', name: "emplois")]
    public function emplois(): Response
    {
        //(View)
        return $this->render('default/footer/emplois.html.twig', [

        ]);
    }
    #[Route('mention_legale', name: "mention_legale")]
    public function mentionLegale(): Response
    {
        //(View)
        return $this->render('default/footer/mentionsLegales.html.twig', [

        ]);
    }
    #[Route('cgv', name: "cgv")]
    public function cgv(): Response
    {
        //(View)
        return $this->render('default/footer/cgv.html.twig', [

        ]);
    }
    #[Route('retraction', name: "retraction")]
    public function retraction(): Response
    {
        //(View)
        return $this->render('default/footer/retraction.html.twig', [

        ]);
    }
    #[Route('elimination_dechet', name: "elimination_dechet")]
    public function dechets(): Response
    {
        //(View)
        return $this->render('default/footer/dechets.html.twig', [

        ]);
    }
    #[Route('contact', name: "contact")]
    public function contact(): Response
    {
        //(View)
        return $this->render('default/footer/contact.html.twig', [

        ]);
    }
    #[Route('livraison', name: "livraison")]
    public function livraison(): Response
    {
        //(View)
        return $this->render('default/footer/livraison.html.twig', [

        ]);
    }
    #[Route('moyen_de_paiement', name: "moyen_de_paiement")]
    public function moyenPaiement(): Response
    {
        //(View)
        return $this->render('default/footer/moyenPaiement.html.twig', [

        ]);
    }
    #[Route('affiliation', name: "affiliation")]
    public function affiliation(): Response
    {
        //(View)
        return $this->render('default/footer/affiliation.html.twig', [

        ]);
    }
    #[Route('confidentialite', name: "confidentialite")]
    public function confidentialite(): Response
    {
        //(View)
        return $this->render('default/footer/confidentialite.html.twig', [

        ]);
    }
}
