<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Tests fonctionnels du contrôleur des formations
 *
 * @author rapha
 */
class FormationsControllerTest extends WebTestCase{

    /**
     * Vérifie que le filtre par nom de formation retourne les bons résultats
     */
    public function testFiltreFormationParNom(): void{
        $client = static::createClient();
        $client->request('GET', '/formations');
        $crawler = $client->submitForm('filtrer', [
            'recherche' => 'Eclipse'
        ]);
        $this->assertCount(9, $crawler->filter('h5'));
        $this->assertSelectorTextContains('h5', 'Eclipse');
    }

    /**
     * Vérifie que le filtre par nom de playlist retourne les bons résultats
     */
    public function testFiltrePlaylistParNom(): void{
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations');
        $form = $crawler->filter('input[name="recherche"]')->eq(1)->closest('form')->form();
        $form['recherche'] = 'Eclipse et Java';
        $crawler = $client->submit($form);
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertNotEmpty($crawler->filter('tbody tr'));
        $this->assertSelectorTextContains('h5', 'Eclipse n°8 : Déploiement');
    }

    /**
     * Vérifie que le clic sur une miniature redirige vers la bonne page de formation
     */
    public function testClickMiniatureFormation(): void{
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations');
        $link = $crawler->filter('a img[alt="image miniature"]')->first()->closest('a')->link();
        $client->click($link);
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $uri = $client->getRequest()->server->get('REQUEST_URI');
        $this->assertStringContainsString('/formations/', $uri);
    }

    /**
     * Vérifie que le tri par date ascendant retourne la formation la plus ancienne en premier
     */
    public function testTriFormationDateASC(): void{
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations');
        $link = $crawler->filter('a[href*="publishedAt"][href*="ASC"]')->link();
        $client->click($link);
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('h5', "Cours UML (1 à 7 / 33) : introduction et cas d'utilisation");
    }

    /**
     * Vérifie que le tri par date descendant retourne la formation la plus récente en premier
     */
    public function testTriFormationDateDESC(): void{
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations');
        $link = $crawler->filter('a[href*="publishedAt"][href*="DESC"]')->link();
        $client->click($link);
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('h5', 'Eclipse n°8 : Déploiement');
    }

    /**
     * Vérifie que le tri par nom ascendant retourne la première formation dans l'ordre alphabétique
     */
    public function testTriFormationParNomAsc(): void{
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations');
        $link = $crawler->filter('a[href*="title"][href*="ASC"]')->link();
        $client->click($link);
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('h5', 'Android Studio (complément n°1) : Navigation Drawer et Fragment');
    }

    /**
     * Vérifie que le tri par nom descendant retourne la dernière formation dans l'ordre alphabétique
     */
    public function testTriFormationParNomDesc(): void{
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations');
        $link = $crawler->filter('a[href*="title"][href*="DESC"]')->link();
        $client->click($link);
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('h5', 'UML : Diagramme de paquetages');
    }

    /**
     * Vérifie que le tri par nom de playlist ascendant retourne la bonne formation en premier
     */
    public function testTriPlaylistParNomDesc(): void{
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations');
        $link = $crawler->filter('a[href*="playlist"][href*="name"][href*="ASC"]')->link();
        $client->click($link);
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('h5', 'Bases de la programmation n°74 - POO : collections');
    }

    /**
     * Vérifie que le filtre par catégorie retourne les formations correspondantes
     */
    public function testFiltreFormationParCategorie(): void{
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations');
        $form = $crawler->filter('select[name="recherche"]')->closest('form')->form();
        $form['recherche'] = '1';
        $crawler = $client->submit($form);
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertNotEmpty($crawler->filter('tbody tr'));
        $this->assertSelectorTextContains('h5', "Eclipse n°8 : Déploiement");
    }
}