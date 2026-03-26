<?php

namespace App\Tests\Repository;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Tests d'intégration pour le repository des catégories.
 */
class CategorieRepositoryTest extends KernelTestCase 
{
    /**
     * Récupère le repository Categorie depuis le conteneur de services.
     */
    public function recupRepository(): CategorieRepository
    {
        self::bootKernel();
        $repository = self::getContainer()->get(CategorieRepository::class);
        return $repository;
    }

    /**
     * Crée une nouvelle instance de l'entité Categorie pour les tests.
     */
    public function newCategorie(): Categorie
    {
        return (new Categorie())
                ->setName('test');
    }

    /**
     * Vérifie que le nombre total de catégories en base est correct.
     */
    public function testNbCategories()
    {
        $repository = $this->recupRepository();
        $nbCategories = $repository->count([]);
        $this->assertEquals(9, $nbCategories);
    }

    /**
     * Teste l'ajout d'une nouvelle catégorie dans la base de données.
     */
    public function testAdd()
    {
        $repository = $this->recupRepository();
        $categorie = $this->newCategorie();
        $nbCategories = $repository->count([]);
        $repository->add($categorie);
        $this->assertEquals($nbCategories + 1, $repository->count([]), "erreur lors de l'ajout");
    }

    /**
     * Teste la suppression d'une catégorie de la base de données.
     */
    public function testRemove()
    {
        $repository = $this->recupRepository();
        $categorie = $this->newCategorie();
        $repository->add($categorie);
        $nbCategories = $repository->count([]);
        $repository->remove($categorie);
        $this->assertEquals($nbCategories - 1, $repository->count([]), "erreur lors de la suppression");
    }

    /**
     * Teste la récupération des catégories liées à une playlist spécifique.
     */
    public function testFindAllForOnePlaylist()
    {
        $repository = $this->recupRepository();
        $categories = $repository->findAllForOnePlaylist(1);
        $this->assertNotEmpty($categories);
        $this->assertInstanceOf(Categorie::class, $categories[0]);
    }
}