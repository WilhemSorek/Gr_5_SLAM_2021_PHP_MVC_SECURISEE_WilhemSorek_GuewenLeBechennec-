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

    $sql = 'SELECT id from Visiteur WHERE login = ? AND mdp = ?';
	
    $resultat = $db->query($sql, [$id, $mdp]);

	$resultat = $resultat->getResult();

    return $resultat;
   
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
	$sql = 'SELECT * from FicheFrais WHERE idVisiteur = ? AND mois = ?';
	
//=====================================================
// execution de la requete sql en passant un parametre id
//=====================================================	
    $resultat = $db->query($sql, [$id, $datefr]);
	
//=============================
// récupération des données de la requete sql
//=============================
	$resultat = $resultat->getResult();

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
$sql = 'INSERT INTO FicheFrais values (?, ?, ?, ?, ?, ?)';
$db->query($sql, [$id,$datefr, 0, 0, $today, "CR"]);
}

//=========================================================================
// Fonction 4
// récupère les données BDD dans une fonction creationLigneETP
// créer une ligne de frais concernant les Etapes
//=========================================================================

public function creationLigneETP($id, $datefr) 
{
    $db = db_connect();
    $sql = 'INSERT INTO LigneFraisForfait values (?, ?, ?, ?)';
    $db->query($sql, [$id, $datefr, "ETP", 0]);
}

//=========================================================================
// Fonction 5
// récupère les données BDD dans une fonction creationLigneKM
// créer une ligne de frais concernant les Kilometres
//=========================================================================

public function creationLigneKM($id, $datefr) {
    $db = db_connect();
    $sql = 'INSERT INTO LigneFraisForfait values (?, ?, ?, ?)';
    $db->query($sql, [$id,$datefr, "KM", 0]);
}

//=========================================================================
// Fonction 6
// récupère les données BDD dans une fonction creationLigneNUI
// créer une ligne de frais concernant les nuits à l'hotel
//=========================================================================

public function creationLigneNUI($id, $datefr)
{
    $db = db_connect();
    $sql = 'INSERT INTO LigneFraisForfait values (?, ?, ?, ?)';
    $db->query($sql, [$id,$datefr, "NUI", 0]);
}

//=========================================================================
// Fonction 7
// récupère les données BDD dans une fonction creationLigneREP
// créer une ligne de frais concernant les repas
//=========================================================================

public function creationLigneREP($id, $datefr)
{
    $db = db_connect();
    $sql = 'INSERT INTO LigneFraisForfait values (?, ?, ?, ?)';
    $db->query($sql, [$id,$datefr, "REP", 0]);
}

//=========================================================================
// Fonction 8
// récupère les données BDD dans une fonction updateFrais
// modifie une ligne de frais en fonction des informations rentrées
//=========================================================================

public function updateFrais($nbJusti, $frais, $mois)
{
    $db = db_connect();
    $sql = 'UPDATE LigneFraisForfait SET quantite = ? WHERE idFraisForfait = ? and mois = ?';
    $db->query($sql, [$nbJusti, $frais, $mois]);
}

//=========================================================================
// Fonction 9
// récupère les données BDD dans une fonction insertFraisHF
// créer une ligne de frais Hors Forfait en fonction des informations entrées
//=========================================================================

public function insertFraisHF($id, $datefr, $libelle, $today, $montant)
{
    $db = db_connect();
    $sql = 'INSERT into LigneFraisHorsForfait (idVisiteur, mois, libelle, date, montant)
    values (?, ?, ?, ?, ?)';
    $db->query($sql, [$id, $datefr, $libelle, $today, $montant]); 
}

//=========================================================================
// Fonction 10
// récupère les données BDD dans une fonction selectVDF
// selectionne les lignes de frais à afficher
//=========================================================================

public function selectVDHF($id, $mois)
{
    $db = db_connect();
    $sql = 'SELECT * from LigneFraisHorsForfait WHERE idVisiteur = ? AND mois = ?';
    $trad = $this->moisTrad();
    $resultat = $db->query($sql, [$id, $trad]);
    $resultat = $resultat->getResult();

    return $resultat;
}
public function selectVDF($id, $mois)
{
    $db = db_connect();
    $sql = 'SELECT * from LigneFraisForfait WHERE idVisiteur = ? AND mois = ?';
    $trad = $this->moisTrad();
    $resultat = $db->query($sql, [$id, $trad]);
    $resultat = $resultat->getResult();

    return $resultat;
}

public function modifDateFicheFrais($today, $id, $datefr)
{
    $db = db_connect();
    $sql = 'UPDATE FicheFrais set dateModif = ? where idVisiteur = ? and mois = ?';
    $db->query($sql, [$today, $id, $datefr]);
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