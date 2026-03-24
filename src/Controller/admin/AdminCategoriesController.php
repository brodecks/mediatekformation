<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

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
 * Description of AdminCategoriesController
 *
 * @author rapha
 */
class AdminCategoriesController extends AbstractController{
    private $categorieRepository;
    
    public function __construct(CategorieRepository $categorieRepository) {
        $this->categorieRepository = $categorieRepository;
    }
    
    #[Route('/admin/categories',name: 'admin.categories')]
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
