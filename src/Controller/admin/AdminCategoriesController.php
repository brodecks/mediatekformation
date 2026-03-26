<?php
namespace App\Controller\admin;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur de gestion des catégories dans la partie admin
 *
 * @author rapha
 */
class AdminCategoriesController extends AbstractController{
    
    /**
     * Repository des catégories
     * @var CategorieRepository
     */
    private $categorieRepository;
    
    /**
     * Constructeur de la classe
     * @param CategorieRepository $categorieRepository
     */
    public function __construct(CategorieRepository $categorieRepository) {
        $this->categorieRepository = $categorieRepository;
    }
    
    /**
     * Affiche la liste des catégories et le formulaire d'ajout
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/categories', name: 'admin.categories')]
    public function index(Request $request): Response{
        $categorie = new Categorie();
        $formCategorie = $this->createForm(CategorieType::class, $categorie);
        
        $formCategorie->handleRequest($request);
        if($formCategorie->isSubmitted() && $formCategorie->isValid()){
            $this->categorieRepository->add($categorie);
            return $this->redirectToRoute('admin.categories');
        }
        $categories = $this->categorieRepository->findAll();
        return $this->render("admin/admin.categories.html.twig", [
            'categories' => $categories,
            'formcategorie' => $formCategorie->createView()
        ]);
    }
    
    /**
     * Supprime une catégorie si elle n'est pas utilisée par des formations
     * @param int $id
     * @return Response
     */
    #[Route('/admin/categorie/suppr/{id}', name: 'admin.categorie.suppr')]
    public function suppr(int $id): Response {
        $categorie = $this->categorieRepository->find($id);
        if ($categorie) {
            if ($categorie->getFormations()->count() > 0) {
                $this->addFlash('danger', 'Suppression impossible : cette catégorie est utilisée par des formations.');
            } else {
                $this->categorieRepository->remove($categorie, true);
            }
        }
        return $this->redirectToRoute('admin.categories');
    }
    
    /**
     * Affiche le formulaire d'ajout d'une catégorie et traite sa soumission
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/categorie/ajout', name: 'admin.categorie.ajout')]
    public function ajout(Request $request): Response{
        $categorie = new Categorie();
        $formCategorie = $this->createForm(CategorieType::class, $categorie);
        $formCategorie->handleRequest($request);
        if($formCategorie->isSubmitted() && $formCategorie->isValid()){
            $this->categorieRepository->add($categorie);
            return $this->redirectToRoute('admin.categories');
        }
        return $this->render("admin/admin.categories.html.twig", [
            'categorie' => $categorie,
            'formcategorie' => $formCategorie->createView()
        ]);
    }
}