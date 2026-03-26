<?php
namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur de gestion des formations dans la partie publique
 *
 * @author emds
 */
class FormationsController extends AbstractController {
    
    /**
     * Repository des formations
     * @var FormationRepository
     */
    private $formationRepository;
    
    /**
     * Chemin vers le template de la liste des formations
     */
    private const CHEMIN_FORMATIONS = "pages/formations.html.twig";
    
    /**
     * Repository des catégories
     * @var CategorieRepository
     */
    private $categorieRepository;
    
    /**
     * Constructeur de la classe
     * @param FormationRepository $formationRepository
     * @param CategorieRepository $categorieRepository
     */
    public function __construct(FormationRepository $formationRepository, CategorieRepository $categorieRepository) {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository = $categorieRepository;
    }
    
    /**
     * Affiche la liste de toutes les formations
     * @return Response
     */
    #[Route('/formations', name: 'formations')]
    public function index(): Response{
        $formations = $this->formationRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::CHEMIN_FORMATIONS, [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }
    
    /**
     * Affiche la liste des formations triées sur un champ donné
     * @param string $champ
     * @param string $ordre
     * @param string $table
     * @return Response
     */
    #[Route('/formations/tri/{champ}/{ordre}/{table}', name: 'formations.sort')]
    public function sort($champ, $ordre, $table=""): Response{
        $formations = $this->formationRepository->findAllOrderBy($champ, $ordre, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::CHEMIN_FORMATIONS, [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }
    
    /**
     * Affiche les formations dont un champ contient une valeur recherchée
     * @param string $champ
     * @param Request $request
     * @param string $table
     * @return Response
     */
    #[Route('/formations/recherche/{champ}/{table}', name: 'formations.findallcontain')]
    public function findAllContain($champ, Request $request, $table=""): Response{
        if($this->isCsrfTokenValid('filtre_'.$champ, $request->get('_token'))){   
            $valeur = $request->get("recherche");
            $formations = $this->formationRepository->findByContainValue($champ, $valeur, $table);
            $categories = $this->categorieRepository->findAll();
            return $this->render(self::CHEMIN_FORMATIONS, [
                'formations' => $formations,
                'categories' => $categories,
                'valeur' => $valeur,
                'table' => $table
            ]);
        }
        return $this->redirectToRoute("formations");
    }
    
    /**
     * Affiche le détail d'une formation
     * @param int $id
     * @return Response
     */
    #[Route('/formations/formation/{id}', name: 'formations.showone')]
    public function showOne($id): Response{
        $formation = $this->formationRepository->find($id);
        return $this->render("pages/formation.html.twig", [
            'formation' => $formation
        ]);        
    }   
}