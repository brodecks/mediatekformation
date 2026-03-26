<?php
namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur de gestion des playlists dans la partie publique
 *
 * @author emds
 */
class PlaylistsController extends AbstractController {
    
    /**
     * Repository des playlists
     * @var PlaylistRepository
     */
    private $playlistRepository;
    
    /**
     * Repository des formations
     * @var FormationRepository
     */
    private $formationRepository;
    
    /**
     * Repository des catégories
     * @var CategorieRepository
     */
    private $categorieRepository;
    
    /**
     * Chemin vers le template de la liste des playlists
     */
    private const CHEMIN_PLAYLIST = "pages/playlists.html.twig";
    
    /**
     * Constructeur de la classe
     * @param PlaylistRepository $playlistRepository
     * @param CategorieRepository $categorieRepository
     * @param FormationRepository $formationRespository
     */
    public function __construct(PlaylistRepository $playlistRepository, 
            CategorieRepository $categorieRepository,
            FormationRepository $formationRespository) {
        $this->playlistRepository = $playlistRepository;
        $this->categorieRepository = $categorieRepository;
        $this->formationRepository = $formationRespository;
    }
    
    /**
     * Affiche la liste de toutes les playlists triées par nom
     * @return Response
     */
    #[Route('/playlists', name: 'playlists')]
    public function index(): Response{
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::CHEMIN_PLAYLIST, [
            'playlists' => $playlists,
            'categories' => $categories            
        ]);
    }
    
    /**
     * Affiche la liste des playlists triées sur un champ donné
     * @param string $champ
     * @param string $ordre
     * @return Response
     */
    #[Route('/playlists/tri/{champ}/{ordre}', name: 'playlists.sort')]
    public function sort($champ, $ordre): Response{
        switch($champ){
            case "name":
                $playlists = $this->playlistRepository->findAllOrderByName($ordre);
                break;
            case "nbFormations":
                $playlists = $this->playlistRepository->findAllOrderByNbFormations($ordre);
                break;
            default:
                break;
        }
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::CHEMIN_PLAYLIST, [
            'playlists' => $playlists,
            'categories' => $categories            
        ]);
    }
    
    /**
     * Affiche les playlists dont un champ contient une valeur recherchée
     * @param string $champ
     * @param Request $request
     * @param string $table
     * @return Response
     */
    #[Route('/playlists/recherche/{champ}/{table}', name: 'playlists.findallcontain')]
    public function findAllContain($champ, Request $request, $table=""): Response{
        if($this->isCsrfTokenValid('filtre_'.$champ, $request->get('_token'))){    
            $valeur = $request->get("recherche");
            $playlists = $this->playlistRepository->findByContainValue($champ, $valeur, $table);
            $categories = $this->categorieRepository->findAll();
            return $this->render(self::CHEMIN_PLAYLIST, [
                'playlists' => $playlists,
                'categories' => $categories,            
                'valeur' => $valeur,
                'table' => $table
            ]);
        }
        return $this->redirectToRoute('playlists');
    }
    
    /**
     * Affiche le détail d'une playlist avec ses formations et ses catégories
     * @param int $id
     * @return Response
     */
    #[Route('/playlists/playlist/{id}', name: 'playlists.showone')]
    public function showOne($id): Response{
        $playlist = $this->playlistRepository->find($id);
        $playlistCategories = $this->categorieRepository->findAllForOnePlaylist($id);
        $playlistFormations = $this->formationRepository->findAllForOnePlaylist($id);
        return $this->render("pages/playlist.html.twig", [
            'playlist' => $playlist,
            'playlistcategories' => $playlistCategories,
            'playlistformations' => $playlistFormations
        ]);
    }       
}