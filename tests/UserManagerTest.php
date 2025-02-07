<?php

namespace Fdjer\TestUnitaire;

use http\Exception\InvalidArgumentException;
use Exception;
use PDO;

use PHPUnit\Framework\TestCase;

/**
 * La Classe UserManagerTest :
 *
 * Ce fichier contient les tests unitaires pour la classe UserManager.
 * On utilise PHPUnit pour vérifier que chaque méthode se comporte
 * comme prévu, y compris en cas d'erreur (exceptions gérées).
 */
class UserManagerTest extends TestCase
{
    /**
     * la fonction "testAddUser()" correspond aux tests de l'ajout d'utilisateur :
     * - On réinitialise la table
     * - On ajoute un utilisateur
     * - On vérifie qu'on a bien 1 utilisateur stocké
     * - On vérifie que l'ID 1 correspond au nom "Zoro"
     */
    public function testAddUser()
    {
        // On instancie le UserManager:
        $userManager = new UserManager();
        // On vide la table "users" et on reset l'auto-increment (à 1):
        $userManager->resetTable();
        // Ajout d'un utilisateur:
        $userManager->addUser("Zoro", "zoro@shonen.fr");

        //Vérifications:
        // On s'attend à n'avoir qu'un seul utilisateur après l'ajout
        $this->assertCount(1, $userManager->getUsers());
        // On vérifie le contenu de l'utilisateur avec l'ID 1
        $this->assertEquals('Zoro', $userManager->getUser(1)["name"]);
    }

    /**
     * La fonction "testAddUserEmailException()" correspond aux tests d'ajout d'user avec email invalide:
     * - On s'attend à une Exception : "InvalidArgumentException"
     * - Avec le message "Email invalide."
     */
    public function testAddUserEmailException(){
        $userManager = new UserManager();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Email invalide.");

        // Ici, l'email 'zoroshonen' n'a pas de format valide => déclenchement de l'exception:
        $userManager->addUser("Zorrrrro", "zoroshonen");
        
    }

    /**
     * La fonction "testUpdateUser()" = test de la mise à jour d'un utilisateur existant:
     * - On ajoute un utilisateur "luffy"
     * - On met à jour son email
     * - On vérifie que le nouveau mail est bien celui qu'on vient de setter
     */
    public function testUpdateUser(){
        $userManager = new UserManager();
        $userManager->resetTable();

        // On ajoute un premier utilisateur:
        $userManager->addUser("luffy","luffy@shonen.fr");

        // On met à jour l'utilisateur ID 1 avec un nouvel email:
        $userManager->updateUser(1, "Luffy", "luffy@luffy.fr");

        // On vérifie la mise à jour:
        $this->assertEquals("luffy@luffy.fr", $userManager->getUser(1)["email"],
            "L'email de l'utilisateur 1 doit être mis à jour à 'luffy@luffy.fr'."
        );
    }

    /**
     * La fonction "testRemoveUser()" = test de la suppression d'un utilisateur existant.
     * - On ajoute 2 utilisateurs
     * - On supprime le premier
     * - On vérifie qu'il reste 1 seul utilisateur
     */
    public function testRemoveUser(){
            $userManager = new UserManager();
            $userManager->resetTable();
            $userManager->addUser("Sanji", "sanji@sanji.fr");
            $userManager->addUser("Zoro", "zoro@shonen.fr");

        // On supprime l'ID 1 (Sanji):
            $userManager->removeUser(1);

        // Il ne reste plus qu'un utilisateur dans la table : Zoro
        $this->assertCount(1, $userManager->getUsers(), "Il doit rester 1 utilisateur après la suppression.");
        }

    /**
     * La fonction "testGetUsers()" correspond aux Tests de la récupération de la liste complète d'utilisateurs:
     * - On en ajoute 2
     * - On vérifie qu'on a bien 2 résultats dans getUsers()
     * - On vérifie que les infos correspondent aux données insérées
     */
        public function testGetUsers()
        {
            $userManager = new UserManager();
            $userManager->resetTable();
            $userManager->addUser("Zoro", "zoro@shonen.fr");
            $userManager->addUser("Tanjiro", "tanjiro@slayer.fr");

            // On s'attend à avoir 2 utilisateurs:
            $this->assertCount(2, $userManager->getUsers());
            // On vérifie l'email du premier:
           $this->assertEquals('zoro@shonen.fr',$userManager->getUsers()[0]["email"],
               "Le premier utilisateur doit avoir l'email 'zoro@shonen.fr'.");

            // On vérifie le nom et l'email du deuxième:
            $this->assertEquals('Tanjiro',$userManager->getUsers()[1]["name"],
                "Le second utilisateur doit s'appeler 'Tanjiro'.");
            $this->assertEquals('tanjiro@slayer.fr',$userManager->getUsers()[1]["email"],
                "Le second utilisateur doit avoir l'email 'tanjiro@slayer.fr'."
            );
        }

    /**
     * La fonction "testInvalidUpdateThrowsException()" = test de la mise à jour sur un utilisateur inexistant :
     * - On réinitialise la table -> aucun utilisateur
     * - On essaie de mettre à jour l'ID 1
     * - On s'attend à une exception "Utilisateur introuvable."
     */
        public function testInvalidUpdateThrowsException(){
        $userManager = new UserManager();
        $userManager->resetTable();
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Utilisateur introuvable.");

        // Comme on n'a pas ajouté d'utilisateur, l'ID=1 n'existe pas:
            $userManager->updateUser(1, "luffy", "luffy@luffy.fr");
        $this->assertEquals(Exception::class, $userManager->getUser(1)["email"]);

        }

    /**
     * Test de la suppression d'un utilisateur inexistant :
     * - On réinitialise la table -> aucun utilisateur
     * - On essaie de supprimer l'ID 1
     * - On s'attend à une exception "Utilisateur introuvable."
     */

        public function testInvalidDeleteThrowsException(){
        $userManager = new UserManager();
        $userManager->resetTable();
       $this->expectException(\Exception::class);
       $this->expectExceptionMessage("Utilisateur introuvable.");


        // L'ID=1 n'existe pas -> removeUser doit être null ou afficher une exception (en modifiant le code source):
            //$this->assertNull($userManager->removeUser(1));

        }

}