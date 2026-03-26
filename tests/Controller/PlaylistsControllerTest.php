<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Tests fonctionnels du contrôleur PlaylistsController.
 */
class PlaylistsControllerTest extends WebTestCase
{
    /**
     * Teste le tri alphabétique croissant sur les noms de playlists.
     */
    public function testTriPlaylistParNomASC()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/playlists');
        $link = $crawler->filter('a[href*="name"][href*="ASC"]')->link();
        $client->click($link);
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('h5', 'Bases de la programmation (C#)');
    }

    /**
     * Teste le tri alphabétique décroissant sur les noms de playlists.
     */
    public function testTriPlaylistParNomDESC()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/playlists');
        $link = $crawler->filter('a[href*="name"][href*="DESC"]')->link();
        $client->click($link);
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('h5', 'Visual Studio 2019 et C#');
    }

    /**
     * Teste le tri décroissant par nombre de formations contenues.
     */
    public function testTriPlaylistParNbFormationsDESC()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/playlists');
        $link = $crawler->filter('a[href*="nbFormations"][href*="DESC"]')->link();
        $crawler = $client->click($link);
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertEquals('74', $crawler->filter('h5')->eq(1)->text());
    }

    /**
     * Teste le tri croissant par nombre de formations contenues.
     */
    public function testTriPlaylistParNbFormationsASC()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/playlists');
        $link = $crawler->filter('a[href*="nbFormations"][href*="ASC"]')->link();
        $crawler = $client->click($link);
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertEquals('0', $crawler->filter('h5')->eq(1)->text());
    }

    /**
     * Teste le filtrage des playlists par recherche textuelle de nom.
     */
    public function testFiltrePlaylistParNom()
    {
        $client = static::createClient();
        $client->request('GET', '/playlists');
        $crawler = $client->submitForm('filtrer', [
            'recherche' => 'Eclipse'
        ]);
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertCount(1, $crawler->filter('tbody tr'));
        $this->assertSelectorTextContains('h5', 'Eclipse et Java');
    }

    /**
     * Teste le filtrage des playlists par sélection de catégorie.
     */
    public function testFiltrePlaylistParCategorie()
    {
        $client = static::createClient();
        $client->request('GET', '/playlists');
        $crawler = $client->submitForm('filtrer', [
            'recherche' => '1'
        ]);
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertNotEmpty($crawler->filter('tbody tr'));
        $this->assertSelectorTextContains('h5', 'Sujet E5 SLAM 2017 métropole : cas AHM-23');
    }

    /**
     * Teste l'accès à la page de détail d'une playlist spécifique.
     */
    public function testClickVoirDetailPlaylist()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/playlists');
        $link = $crawler->filter('a.btn-secondary')->first()->link();
        $client->click($link);
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $uri = $client->getRequest()->server->get('REQUEST_URI');
        $this->assertStringContainsString('/playlists/', $uri);
        $this->assertSelectorExists('h4');
    }
}