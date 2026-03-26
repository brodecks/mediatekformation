<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of AccueilControllerTest
 *
 * @author rapha
 */
class AccueilControllerTest extends WebTestCase{
    /**
     * Test d'acces a la page d'accueil
     */
    public function testAccesPage(){
        $client = static::createClient();
        $client->request('GET', "/");
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}
