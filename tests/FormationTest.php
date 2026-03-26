<?php

namespace App\Tests;

use App\Entity\Formation;
use DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Tests unitaires sur l'entité Formation.
 */
class FormationTest extends TestCase {

    /**
     * Teste la conversion du format de date de l'entité en chaîne de caractères.
     */
    public function testGetPublishedAtString() {
        $formation = new Formation();
        $formation->setPublishedAt(new DateTime("2024-04-04"));
        $this->assertEquals("04/04/2024", $formation->getPublishedAtString());
    }
}