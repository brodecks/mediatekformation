<?php

namespace App\Tests\Validations;

use App\Entity\Formation;
use App\Entity\Playlist;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Tests de validation sur l'entité Formation.
 */
class FormationValidationsTest extends KernelTestCase {

    /**
     * Crée et retourne une instance de Formation valide pour les tests.
     */
    public function getFormation(): Formation{
        return (new Formation())
                ->setPublishedAt(new DateTime('2024-04-04'))
                ->setTitle('test')
                ->setVideoId('wdkqndjkwq')
                ->setPlaylist((new Playlist())->setName('tests'));
    }

    /**
     * Utilise le validateur Symfony pour vérifier le nombre d'erreurs sur une entité.
     */
    public function assertErrors(Formation $formation, int $nbErreursAttendues){
        self::bootKernel();
        $validator = self::getContainer()->get(ValidatorInterface::class);
        $error = $validator->validate($formation);
        $this->assertCount($nbErreursAttendues, $error);
    }
    
    /**
     * Teste que la validation échoue si la date de publication est postérieure à la date actuelle.
     */
    public function testDatePosterieure(){
        $formation = $this->getFormation()->setPublishedAt(new DateTime('2027-03-23'));
        $this->assertErrors($formation, 1);
    }
}