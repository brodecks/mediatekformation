<?php
namespace App\Controller\admin;

use App\Entity\Formation;
use App\Entity\Playlist;
use App\Form\PlaylistType;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur de gestion des playlists dans la partie admin
 *
 * @author rapha
 */
class AdminPlaylistsController extends AbstractController{
    
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
    private const CHEMIN_PLAYLIST = "admin/admin.playlists.html.twig";
    
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
    #[Route('/admin/playlists', name: 'admin.playlists')]
    public function index(): Response{
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::CHEMIN_PLAYLIST, [
            'playlists' => $playlists,
            'categories' => $categories            
        ]);
    }
    
    /**
     * Supprime une playlist à partir de son identifiant
     * @param int $id
     * @return Response
     */
    #[Route('/admin/playlist/suppr/{id}', name: 'admin.playlist.suppr')]
    public function suppr(int $id): Response{
        $playlist = $this->playlistRepository->find($id);
        $this->playlistRepository->remove($playlist);
        return $this->redirectToRoute('admin.playlists');
    }
    
    /**
     * Affiche le formulaire d'édition d'une playlist et traite sa soumission
     * @param int $id
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/playlist/edit/{id}', name: 'admin.playlist.edit')]
    public function edit(int $id, Request $request): Response{
        $playlist = $this->playlistRepository->find($id);
        $formPlaylist = $this->createForm(PlaylistType::class, $playlist);

        $formPlaylist->handleRequest($request);
        if($formPlaylist->isSubmitted() && $formPlaylist->isValid()){
            $this->playlistRepository->add($playlist);
            return $this->redirectToRoute('admin.playlists');
        }

        return $this->render("admin/admin.playlist.edit.html.twig", [
            'playlist' => $playlist,
            'formplaylist' => $formPlaylist->createView()
        ]);
    }
    
    /**
     * Affiche le formulaire d'ajout d'une playlist et traite sa soumission
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/playlist/ajout', name: 'admin.playlist.ajout')]
    public function ajout(Request $request): Response{
        $playlist = new Playlist();
        $formPlaylist = $this->createForm(PlaylistType::class, $playlist);

        $formPlaylist->handleRequest($request);
        if($formPlaylist->isSubmitted() && $formPlaylist->isValid()){
            $this->playlistRepository->add($playlist);
            return $this->redirectToRoute('admin.playlists');
        }

        return $this->render("admin/admin.playlist.ajout.html.twig", [
            'playlist' => $playlist,
            'formplaylist' => $formPlaylist->createView()
        ]);
    }
    
    /**
     * Affiche la liste des playlists triées sur un champ donné
     * @param string $champ
     * @param string $ordre
     * @return Response
     */
    #[Route('/admin/playlists/tri/{champ}/{ordre}', name: 'admin.playlists.sort')]
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
    #[Route('/admin/playlists/recherche/{champ}/{table}', name: 'admin.playlists.findallcontain')]
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
        return $this->redirectToRoute('admin.playlists');
    }  

    /**
     * Affiche le détail d'une playlist avec ses formations et ses catégories
     * @param int $id
     * @return Response
     */
    #[Route('/admin/playlist/{id}', name: 'admin.playlists.showone')]
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