<?php

namespace App\Tests\Repository;

use App\Entity\Playlist;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Tests d'intégration pour le repository des playlists.
 */
class PlaylistRepositoryTest extends KernelTestCase {

    /**
     * Récupère le repository Playlist depuis le conteneur de services.
     */
    public function recupRepository(): PlaylistRepository{
        self::bootKernel();
        $repository = self::getContainer()->get(PlaylistRepository::class);
        return $repository;
    }

    /**
     * Crée une instance de Playlist vierge pour les besoins des tests.
     */
    public function newPlaylist(): Playlist{
        return (new Playlist())
                ->setName('tests');
    }

    /**
     * Vérifie que le nombre total de playlists en base de données est exact.
     */
    public function testNbPlaylists(){
        $repository = $this->recupRepository();
        $nbPlaylists = $repository->count([]);
        $this->assertEquals(41, $nbPlaylists);
    }

    /**
     * Teste l'ajout d'une playlist et vérifie l'augmentation du compteur.
     */
    public function testAdd(){
        $repository = $this->recupRepository();
        $playlist = $this->newPlaylist();
        $nbPlaylists = $repository->count([]);
        $repository->add($playlist);
        $this->assertEquals($nbPlaylists + 1, $repository->count([]), "erreur lors de l'ajout");
    }

    /**
     * Teste la suppression d'une playlist et vérifie la diminution du compteur.
     */
    public function testRemove(){
        $repository = $this->recupRepository();
        $playlist = $this->newPlaylist();
        $repository->add($playlist);
        $nbPlaylists = $repository->count([]);
        $repository->remove($playlist);
        $this->assertEquals($nbPlaylists - 1, $repository->count([]), "erreur lors de la suppression");
    }

    /**
     * Teste la récupération de toutes les playlists triées par nom.
     */
    public function testFindAllOrderByName(){
        $repository = $this->recupRepository();
        $playlists = $repository->findAllOrderByName('ASC');
        $this->assertNotEmpty($playlists);
        $this->assertInstanceOf(Playlist::class, $playlists[0]);
    }

    /**
     * Vérifie qu'une recherche avec une valeur vide retourne la totalité des playlists.
     */
    public function testFindByContainValueValeurVide(){
        $repository = $this->recupRepository();
        $playlists = $repository->findByContainValue('name', '');
        $toutesPlaylists = $repository->findAllOrderByName('ASC');
        $this->assertEquals(count($toutesPlaylists), count($playlists));
    }

    /**
     * Teste la recherche de playlists contenant une valeur spécifique dans leur nom.
     */
    public function testFindByContainValue(){
        $repository = $this->recupRepository();
        $playlists = $repository->findByContainValue('name', 'Eclipse et Java');
        $this->assertNotEmpty($playlists);
        $this->assertInstanceOf(Playlist::class, $playlists[0]);
    }

    /**
     * Teste la recherche de playlists par valeur en incluant une jointure (ex: catégories).
     */
    public function testFindByContainValueTable(){
        $repository = $this->recupRepository();
        $playlists = $repository->findByContainValue('name', 'Java', 'categories');
        $this->assertInstanceOf(Playlist::class, $playlists[0]); 
    }

    /**
     * Teste le tri des playlists basé sur le nombre de formations qu'elles contiennent.
     */
    public function testFindAllOrderByNbFormations(){
        $repository = $this->recupRepository();
        $playlists = $repository->findAllOrderByNbFormations('ASC');
        $this->assertNotEmpty($playlists);
        $this->assertInstanceOf(Playlist::class, $playlists[0]);
    }
}