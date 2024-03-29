<?php

namespace App\DataFixtures;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\Planning;
use App\Entity\Usager;
use App\Entity\Poste;
use App\Entity\Atelier;
use App\Entity\Animateur;
use App\Entity\Quartier;
use App\Entity\Partenaire;



class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i < 7; $i++) {
            $Faker = Factory::create("fr_FR");

            $quartier = new Quartier();
            $quartier->setNomQuartier('Quartier'.$i);

            $partenaire = new Partenaire();
            $partenaire->setNomEtablissement('Partenaire '.$i)
                       ->setTypeEtablissement($Faker->company);

             $usager = new Usager();
             $usager->setGenre('Homme','Femme')
                    ->setNom($Faker->firstName())
                    ->setPrenom($Faker->lastName());
             $usager->setEmail($Faker->email())
                    ->setTel($Faker->phoneNumber());
             $usager->setPassword($Faker->md5())
                    ->setAdresse($Faker->streetAddress())
                    ->setVille($Faker->city())
                    ->setCp($Faker->postcode())
                    ->setRoles(["ROLE_USAGER"])
                    ->setCategorie('profession '.$i)
                    // ->setDateCreation($Faker->dateTime())
                    ->setNiveau('Level',$Faker->numberBetween(1, 3))
                    ->setLoisir('loisir '. $i)
                    ->setPartenaires($partenaire)
                    ->setQuartiers($quartier);

            $animateur = new Animateur();

            $animateur->setGenre('Homme','Femme');
            $animateur->setNom($Faker->firstName());
            $animateur->setPrenom($Faker->lastName());
            $animateur->setEmail($Faker->email());
            $animateur->setTel($Faker->phoneNumber());
            $animateur->setPassword($Faker->md5());
            $animateur->setRoles(["ROLE_ANIMATEUR"]);

            $atelier = new Atelier();

            $atelier->setLibelle('Atelier'.$i);
            $atelier->setDate($Faker->dateTimeBetween('now', '+1year'));
            // $atelier->setStart($Faker->dateTime());
            // $atelier->setEnd($Faker->dateTime());
            $atelier->setHeureDebut('09:00');
            $atelier->setHeureFin('10:00');
            $atelier->setStatut('confirme');
            $atelier->setNbrPlaces($Faker->numberBetween(10, 15));
            $atelier->setImage($Faker->imageUrl(400, 300, 'cats'));
         // $atelier->setImage('https://fr.wikipedia.org/wiki/A%C3%A9rodrome_d%27Anaa#/media/Fichier:Anaa-atoll-ISS007-E-14624.png');
            $atelier->setAnimateurs($animateur);

            $poste = new Poste();
            $poste->setLibelle('Poste'.$i)
                  ->setTypePoste('Type'.$i);
            $manager->persist($partenaire);
            

            $planning = new Planning();
            $planning->setDate($Faker->dateTimeBetween('now', '+1year'));
            $planning->setUsagers($usager);
            $planning->setPostes($poste);
            $planning->setAteliers($atelier);


            $manager->persist($quartier);
            $manager->persist($partenaire);
            $manager->persist($usager);
            $manager->persist($animateur);
            $manager->persist($atelier);
            $manager->persist($poste);
            $manager->persist($planning);
            
            
            
           
        }

        $manager->flush();
    }
}
