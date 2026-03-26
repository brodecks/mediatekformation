<?php

namespace App\Tests\Repository;

use App\Entity\Formation;
use App\Entity\Playlist;
use App\Repository\FormationRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Tests d'intégration pour le repository des formations.
 */
class FormationRepositoryTest extends KernelTestCase 
{
    /**
     * Récupère le repository Formation depuis le conteneur de services.
     */
    public function recupRepository(): FormationRepository
    {
        self::bootKernel();
        $repository = self::getContainer()->get(FormationRepository::class);
        return $repository;
    }

    /**
     * Crée une instance de Formation pré-remplie pour les tests.
     */
    public function newFormation(): Formation
    {
        return (new Formation())
                ->setPublishedAt(new DateTime('2024-04-04'))
                ->setTitle('test')
                ->setVideoId('wdkqndjkwq')
                ->setPlaylist((new Playlist())->setName('tests'));
    }

    /**
     * Vérifie que le nombre total de formations en base est exact.
     */
    public function testNbFormations()
    {
        $repository = $this->recupRepository();
        $nbFormations = $repository->count([]);
        $this->assertEquals(237, $nbFormations);
    }

    /**
     * Teste l'ajout d'une formation et vérifie l'incrémentation du compteur.
     */
    public function testAdd()
    {
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $nbFormations = $repository->count([]);
        $repository->add($formation);
        $this->assertEquals($nbFormations + 1, $repository->count([]), "erreur lors de l'ajout");
    }

    /**
     * Teste la suppression d'une formation et vérifie la décrémentation du compteur.
     */
    public function testRemove()
    {
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $repository->add($formation);
        $nbFormations = $repository->count([]);
        $repository->remove($formation);
        $this->assertEquals($nbFormations - 1, $repository->count([]), "erreur lors de la suppression");
    }

    /**
     * Teste la récupération des formations triées selon un champ et un ordre précis.
     */
    public function testFindAllOrderBy()
    {
        $repository = $this->recupRepository();
        $formations = $repository->findAllOrderBy('title', 'ASC');
        $this->assertNotEmpty($formations);
        $this->assertInstanceOf(Formation::class, $formations[0]);
    }

    /**
     * Teste la recherche de formations contenant une valeur spécifique dans un champ.
     */
    public function testFindByContainValue()
    {
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $repository->add($formation);
        $formations = $repository->findByContainValue('title', 'test');
        $nbFormations = count($formations);
        $this->assertEquals(6, $nbFormations);
        $this->assertEquals('test', $formations[0]->getTitle());
    }

    /**
     * Teste la récupération des N dernières formations publiées.
     */
    public function testFindAllLasted()
    {
        $repository = $this->recupRepository();
        $formations = $repository->findAllLasted(3);
        $this->assertLessThanOrEqual(3, count($formations));
    }

    /**
     * Teste la récupération de toutes les formations appartenant à une playlist donnée.
     */
    public function testFindAllForOnePlaylist()
    {
        $repository = $this->recupRepository();
        $formations = $repository->findAllForOnePlaylist(1);
        $this->assertNotEmpty($formations);
        $this->assertInstanceOf(Formation::class, $formations[0]);
    }
}