<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProduitRepository;

// Affichage des Produits
class PaginateProductController extends AbstractController
{
    // Méthode pour Afficher les Produits en Pagination
    #[Route('/paginate/product/{currentPage}/{nbResult}', name: 'paginate_product')]
    public function index(ProduitRepository $produitRepository, $currentPage, $nbResult): Response
    {
        // Calcul nombre de Produits
        $nbProduit = $produitRepository->count([]);
        // Calcul nombre de Pages
        $nbPage = $nbProduit/$nbResult;

        // Si il y a un Résultat à virgule pour le Calcul des Pages
        // Rajouter une page supplémentaire à la variable $nbPage
        if($nbProduit % $nbResult != 0) {
            $nbPage = (int) ($nbProduit/$nbResult) + 1;
        }

        // Affichage des Produits par Pagination
        $produits = $produitRepository->findByPagination($currentPage, $nbResult);

        return $this->render('paginate_product/index.html.twig', [
            'produits' => $produits,
            'nbPage' => $nbPage,
            'currentPage' => $currentPage,
            'nbResult' => $nbResult
        ]);
    }

    // Méthode Afficher les Produits CATEGORIE/SOUS CATEGORIE Pagination
    #[Route('categorie/{categorie}/{sousCategorie}', name: "categorie")]
    public function filtre(ProduitRepository $produitRepository, $categorie, $sousCategorie): Response {
        
        // Filtrer les Produits
        $produits = $produitRepository->categorieSousCategorie($categorie, $sousCategorie);

        return $this->render('default/categorie.html.twig', [
            'produits' => $produits
        ]);
    }
}
