<?php
// SOUCIS ne Rajoute pas sur le productOrder existant/ ne modifie pas ni ne supprime
// $produit == $productOrder->getProduct()
namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Produit;
use App\Entity\ProductOrder;

#[Route('/panier', name: 'panier_')]

class PanierController extends AbstractController
{
    // AJOUT D'UN PRODUIT DANS LE PANIER
    #[Route('/{id}', name: 'add', requirements: ['id' => '\d+'])]
    public function addPanier(Produit $produit, Request $request){

        // Récupération de la Session depuis l'Objet Request SYMFONY
        $session = $request->getSession();

        $quantite = $session->get("quantite");

        // Création d'un nouvelle Objet ProductOrder
        $productOrder = new ProductOrder();
        $productOrder ->setProduct($produit);
        $productOrder ->setQuantity($quantite);


        // Déclaration du tableau Panier
        $panier=[];

        // Si il y a déjà un Panier dans la Session
        if($session->has("panier")) {
            $panier = $session->get("panier");
        }

        // Variable dont sa Valeur dépendra de si il y a le Produit en question ou pas dans le Panier
        $exist = false;

        // Vérification : si il y a déjà le Produit choisi dans le Panier
        // NOTE: Ne pas indiqué le même nom de variable dans la boucle que 
        // le productOrder sous peine de reprendre l'élément qui est présent dans le panier et le dupliquer
        foreach ( $panier as $productOrderElem ) {
//var_dump($productOrderElem->getProduct()->getNom());
//var_dump($produit->getNom());
//die();
            // == $produit
            if ($productOrderElem->getProduct()->getNom() == $produit->getNom()) {
                $exist = true;
                // Si Il y a déjà le Produit dans le Panier, Modifier sa quantité
                $productOrderElem->setQuantity($productOrderElem->getQuantity()+1);
            }
        }

        // Si $exist = false; Ajout de l'Objet productOrder dans le tableau Panier
        if(! $exist) {
            $panier[]=$productOrder;
        }

        // Mise à jour du panier dans la Session
        $session->set("panier", $panier);

        // Dirige l'utilisateur vers le Panier
        return $this->redirectToRoute('panier_display');
    }

    // SUPRESSION D'UN PRODUIT DU PANIER
    #[Route('/remove-product/{id}', name: 'remove')]
    public function removeProductPanier (Produit $produit, Request $request) {

        // Récupération de la SESSION
        $session = $request->getSession();
        // Récupération du Panier dans la SESSION
        $panier = $session->get("panier");

        // Variable Témoins clé
        $delete = null;
        
        // Boucle pour vérifier s'il y a la clé du Produit ciblé dans le Panier
        foreach ($panier as $key=>$productOrder) {

            if ($produit->getNom() == $productOrder->getProduct()->getNom()) {
                $delete = $key;
                
            }
        }

        // Suppression de la clé du Produit du Panier
        unset($panier[$delete]);
        // Mise à jour de la SESSION après la suppression de la clé
        $session->set("panier", $panier);
        // Retour sur la vue du Panier
        return $this->redirectToRoute("panier_display");
    }

    //AJOUT D'UNE QUANTITE POUR UN PRODUIT DANS LE PANIER  
    #[Route('/plus/{id}', name: '+')]  
    public function incrementProductPanier (Produit $produit, Request $request) {
        // Récupération de la SESSION
        $session = $request->getSession();
        // Récupération du Panier dans la SESSION
        $panier = $session->get("panier");

        // Boucle pour vérifier s'il y a le Produit ciblé dans le Panier
        // +1 pour le Produit ciblé
        foreach ($panier as $po) {

         //var_dump($po->getProduct()->getNom());
//var_dump($produit->getNom());
//die();   
            if ($po->getProduct()->getNom() == $produit->getNom()) {
                $po->setQuantity($po->getQuantity() +1);
            }
        }

        // Mise à jour de la SESSION Panier après l'addition
        $session->set("panier", $panier);
        // Retour sur la vue du Panier
        return $this->redirectToRoute("panier_display");
    }

    //SOUSTRACTION D'UNE QUANTITE POUR UN PRODUIT DANS LE PANIER  
    #[Route('/moins/{id}', name: '-')]  
    public function decrementProductPanier (Produit $produit, Request $request) {
        // Récupération de la SESSION
        $session = $request->getSession();
        // Récupération du Panier dans la SESSION
        $panier = $session->get("panier");

        // Boucle pour vérifier s'il y a le Produit ciblé dans le Panier
        // -1 pour le Produit ciblé
        foreach ($panier as $po) {
            if ($po->getProduct()->getNom() == $produit->getNom()) {
                $po->setQuantity($po->getQuantity() -1);
            }
        }

        // Mise à jour de la SESSION Panier après la soustraction
        $session->set("panier", $panier);
        // Retour sur la vue du Panier
        return $this->redirectToRoute("panier_display");
    }

    // VUE SUR LE PANIER
    #[Route('/', name: 'display')]
    public function index(Request $request): Response
    {
        // Récupération du Panier dans la SESSION
        $panier = $request->getSession()->get("panier");

        // Montant total du Panier
        //Variable
        $prix = 0;
        // Boucle pour calculer le montant total
        if($panier != null) {
            foreach ($panier as $po) {
                $prix += $po->getProduct()->getPrix() * $po->getQuantity();
            }
        }

        return $this->render('panier/index.html.twig', [
            'panier' => $panier,
            'prix' => $prix
        ]);
    }
}
