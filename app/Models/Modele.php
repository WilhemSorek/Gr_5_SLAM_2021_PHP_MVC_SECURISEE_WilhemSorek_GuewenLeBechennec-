<?php
//acces au Modele parent pour l heritage
namespace App\Models;
use CodeIgniter\Model;

//=========================================================================================
//définition d'une classe Modele (meme nom que votre fichier Modele.php) 
//héritée de Modele et permettant d'utiliser les raccoucis et fonctions de CodeIgniter
//  Attention vos Fichiers et Classes Controleur et Modele doit commencer par une Majuscule 
//  et suivre par des minuscules
//=========================================================================================
class Modele extends Model {

//==========================
// Code du modele
//==========================

//=========================================================================
// Fonction 1
// récupère les données BDD dans une fonction login
// Retourne l'ID du visiteur en fonction de ses identifiants (si il existe)
//=========================================================================
public function login($id, $mdp) { 

	$db = db_connect();

    $sql =$db->prepare('SELECT id from Visiteur WHERE login= :login AND mdp= :mdp');
    $sql->bindparam(':login', $id);
    $sql->bindparam(':mdp', $mdp);
	
    $sql->execute();

    return $sql;
   
}


//=========================================================================
// Fonction 2 
// récupère les données BDD dans une fonction verifFicheFrais
// Vérifie si une fiche frais est existe
//=========================================================================
public function verifFicheFrais($id, $datefr) {
	
//==========================================================================================
// Connexion à la BDD en utilisant les données féninies dans le fichier app/Config/Database.php
//==========================================================================================
    $db = db_connect();	
	
//=====================================
// rédaction de la requete sql préparée
//=====================================
	$sql = $db->prepare('SELECT * from FicheFrais WHERE idVisiteur = :idVisiteur AND mois = :mois ');
	$sql->bindparam(':idVisiteur', $id);
    $sql->bindparm(':mois', $datefr);
    $resultat = $sql->execute();

//=============================
// renvoi du résultat au Controleur
//=============================		
    return $resultat;
  
}

//=========================================================================
// Fonction 3
// récupère les données BDD dans une fonction creationFicheFrais
// créer une fichefrais et les lignes correspondantes au mois actuelle
//=========================================================================

public function creationFicheFrais($id, $datefr, $today) 
{
$db = db_connect();
$sql = $db->prepare('INSERT INTO FicheFrais values (:id, :mois, :nbJustification, :montant, :date, :etat)');
$sql->bindparam(':id', $id);
$sql->bindparam(':mois', $datefr);
$sql->bindparam(':nbJustification', 0);
$sql->bindparam(':montant', 0);
$sql->bindparam(':date', $today);
$sql->bindparam(':etat', "CR");
$sql->execute();

return $sql;
}

//=========================================================================
// Fonction 4
// récupère les données BDD dans une fonction creationLigneETP
// créer une ligne de frais concernant les Etapes
//=========================================================================

public function creationLigneETP($id, $datefr) 
{
    $db = db_connect();
    $sql = $db->prepare('INSERT INTO LigneFraisForfait values (:id, :mois, :type, :montant)');
    $sql->bindparam(':id', $id);
    $sql->bindparam(':mois', $datefr);
    $sql->bindparam(':type', "ETP");
    $sql->bindparam('montant', 0);
    $sql->execute();

    return $sql;
}

//=========================================================================
// Fonction 5
// récupère les données BDD dans une fonction creationLigneKM
// créer une ligne de frais concernant les Kilometres
//=========================================================================

public function creationLigneKM($id, $datefr) {
    $db = db_connect();
    $sql = $db->prepare('INSERT INTO LigneFraisForfait values (:id, :mois, :type, :montant)');
    $sql->bindparam(':id', $id);
    $sql->bindparam(':mois', $datefr);
    $sql->bindparam(':type', "KM");
    $sql->bindparam('montant', 0);
    $sql->execute();

    return $sql;
}

//=========================================================================
// Fonction 6
// récupère les données BDD dans une fonction creationLigneNUI
// créer une ligne de frais concernant les nuits à l'hotel
//=========================================================================

public function creationLigneNUI($id, $datefr)
{
    $db = db_connect();
    $sql = $db->prepare('INSERT INTO LigneFraisForfait values (:id, :mois, :type, :montant)');
    $sql->bindparam(':id', $id);
    $sql->bindparam(':mois', $datefr);
    $sql->bindparam(':type', "NUI");
    $sql->bindparam('montant', 0);
    $sql->execute();

    return $sql;
}

//=========================================================================
// Fonction 7
// récupère les données BDD dans une fonction creationLigneREP
// créer une ligne de frais concernant les repas
//=========================================================================

public function creationLigneREP($id, $datefr)
{
    $db = db_connect();
    $sql = $db->prepare('INSERT INTO LigneFraisForfait values (:id, :mois, :type, :montant)');
    $sql->bindparam(':id', $id);
    $sql->bindparam(':mois', $datefr);
    $sql->bindparam(':type', "REP");
    $sql->bindparam('montant', 0);
    $sql->execute();

    return $sql;
}

//=========================================================================
// Fonction 8
// récupère les données BDD dans une fonction updateFrais
// modifie une ligne de frais en fonction des informations rentrées
//=========================================================================

public function updateFrais($nbJusti, $frais, $mois)
{
    $db = db_connect();
    $sql = $db->prepare('UPDATE LigneFraisForfait SET quantite = :qte WHERE idFraisForfait = :idfrais and mois = :mois');
    $sql->bindparam(':qte', $nbJusti);
    $sql->bindparam('idfrais', $frais);
    $sql->bindparam(':mois', $mois);
    $sql->execute();

    return $sql;
}

//=========================================================================
// Fonction 9
// récupère les données BDD dans une fonction insertFraisHF
// créer une ligne de frais Hors Forfait en fonction des informations entrées
//=========================================================================

public function insertFraisHF($id, $datefr, $libelle, $today, $montant)
{
    $db = db_connect();
    $sql = $db->prepare('INSERT into LigneFraisHorsForfait (idVisiteur, mois, libelle, date, montant)
    values (:id, :mois, :libelle, :date, :montant)');
    $sql->bindparam(':id', $id); 
    $sql->bindparam(':mois', $datefr);
    $sql->bindparam('libelle', $libelle);
    $sql->bindparam(':date', $today);
    $sql->bindparam(':montant', $montant);
    $sql->execute();

    return $sql;
}

//=========================================================================
// Fonction 10
// récupère les données BDD dans une fonction selectVDF
// selectionne les lignes de frais à afficher
//=========================================================================

public function selectVDHF($id, $mois)
{
    $db = db_connect();
    $sql = $db->prepare('SELECT * from LigneFraisHorsForfait WHERE idVisiteur = :id AND mois = :mois');
    $sql->bindparam(':id', $id);
    $sql->bidnparam(':mois', $mois);
    $sql->execute();

    return $sql;
}
public function selectVDF($id, $mois)
{
    $db = db_connect();
    $sql = $db->prepare('SELECT * from LigneFraisForfait WHERE idVisiteur = :id AND mois = :mois');
    $sql->bindparam(':id', $id);
    $sql->bindparam(':mois', $mois)

    return $sql;
}

public function modifDateFicheFrais($today, $id, $datefr)
{
    $db = db_connect();
    $sql = $db->prepare('UPDATE FicheFrais set dateModif = :dateModif where idVisiteur = :id and mois = :mois');
    $sql->bindparam('dateModif', $today);
    $sql->bindparam('id', $id);
    $sql->bindparam('mois', $datefr);
    $sql->execute();

    return $sql;
}

public function moisTrad()
{
    $datefr = date("F");

    switch ($datefr) {
        case 'January':
            $datefr = "Janvier";
            break;
        case 'Februar':
        $datefr = "Fevrier";
        break;
         case 'March':
         $datefr = 'Mars';
        break;
         case 'April':
         $datefr = "Avril";
        break;
         case 'May':
         $datefr = "Mai";
        break;
         case 'June':
         $datefr = 'Juin';
        break;
         case 'July':
         $datefr = 'Juillet';
        break;
         case 'August':
         $datefr = "Aout";
        break;
         case 'September':
         $datefr = 'Septembre';
        break;
         case 'October':
         $datefr = 'Octobre';
        break;
         case 'November':
         $datefr = 'Novembre';
        break;
         case 'December':
         $datefr = 'Decembre';
        break;
    }

    return $datefr;
}

public function today()
{
    return date('Y-m-d');
}
//fin de la classe
}


?>