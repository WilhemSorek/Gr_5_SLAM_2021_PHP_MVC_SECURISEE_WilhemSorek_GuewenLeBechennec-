<?php
//acces au controller parent pour l heritage
namespace App\Controllers;
use CodeIgniter\Controller;

//=========================================================================================
//définition d'une classe Controleur (meme nom que votre fichier Controleur.php) 
//héritée de Controller et permettant d'utiliser les raccoucis et fonctions de CodeIgniter
//  Attention vos Fichiers et Classes Controleur et Modele doit commencer par une Majuscule 
//  et suivre par des minuscules
//=========================================================================================

class Controleur extends Controller {

//=====================================================================
//Fonction index correspondant au Controleur frontal (ou index.php) en MVC libre
//=====================================================================
public function index()
{

	if(isset($_GET['action']))
	{
		switch ($_GET['action'])
		{
			case "consulter":
				$this->consulter();
			break;

			case "renseigner":
				$this->renseigner();
			break;

			case "deco":
				$this->deconnexion();
			break;
			
			case 'retouracc':
				$this->retourAcc();
			break;
		}

	}

	elseif (isset($_POST['identifiant']))
	{
		$this->verif(htmlspecialchars($_POST['identifiant']), htmlspecialchars($_POST['password']));

		sleep(1);
	}
	elseif (isset($_POST['quantite']))
	{
		$this->updateFrais();
	}
	elseif (isset($_POST['libelle']))
	{
		$this->insertHorsForfait();
	}
	else
	{
	$this->connexion();
	}


}
public function updateFrais()
{
	session_start();
		$Modele = new \App\Models\Modele();

		if (isset($_SESSION['token']) AND isset($_POST['token']) AND !empty($_SESSION['token']) AND !empty($_POST['token'])) {
			if ($_SESSION['token'] == $_POST['token']){	
				if($_SERVER['HTTP_REFERER'] == 'http://localhost/Gr_5_SLAM_2021_PHP_MVC_SECURISEE_WilhemSorek_GuewenLeBechennec/Gr_5_SLAM_2021_PHP_MVC_SECURISEE_WilhemSorek_GuewenLeBechennec-/public/index.php?action=renseigner'){
					$Modele->updateFrais(htmlspecialchars($_POST['quantite']), htmlspecialchars($_POST['idfrais']), $Modele->moisTrad());
					$Modele->modifDateFicheFrais($Modele->today(), htmlspecialchars($_SESSION['id']), $Modele->moisTrad());
				
					echo view('acceuil.php');
				}
			}
		}
		else {
			echo view('rdf.php');
		}
		
}
public function insertHorsForfait()
{
	session_start();
		$Modele = new \App\Models\Modele();

		$Modele->insertFraisHF(htmlspecialchars($_SESSION['id']), $Modele->moisTrad(), htmlspecialchars($_POST['libelle']), $Modele->today(), htmlspecialchars($_POST['montant']));
		$Modele->modifDateFicheFrais($Modele->today(), htmlspecialchars($_SESSION['id']), $Modele->moisTrad());
		echo view('acceuil.php');
}

public function retourAcc()
{
	echo view('acceuil.php');
}

public function connexion()
{
	echo view('connexion.php');
}

public function deconnexion()
{
	session_start();
	session_destroy();
	echo view('connexion.php');
}

public function consulter()
{
	session_start();
	$Modele = new \App\Models\Modele();

	$donneesHF = $Modele->selectVDHF($_SESSION['id'], $Modele->moisTrad());
	$donnees = $Modele->selectVDF($_SESSION['id'], $Modele->moisTrad());
	
	$data['resultat'] = $donnees;
	$data['resultatHF'] = $donneesHF;
	
	echo view('vdf.php', $data);
}

public function renseigner()
{
	echo view('rdf.php');
}

public function verif($id, $mdp)
{
	
	$Modele = new \App\Models\Modele();
		
		$donnees = $Modele->login($id, $mdp);
		
		$data['resultat']=$donnees;
  		
		
		if (!empty($data['resultat'][0]->id))
		{
			session_start();
			$_SESSION['id']=$data['resultat'][0]->id;
			if (empty($Modele->verifFicheFrais($_SESSION['id'], $Modele->moisTrad())))
			{
				$Modele->creationFicheFrais($_SESSION['id'], $Modele->moisTrad(), $Modele->today());
				$Modele->creationLigneETP($_SESSION['id'], $Modele->moisTrad());
				$Modele->creationLigneKM($_SESSION['id'], $Modele->moisTrad());
				$Modele->creationLigneREP($_SESSION['id'], $Modele->moisTrad());
				$Modele->creationLigneNUI($_SESSION['id'], $Modele->moisTrad());
				echo view ("acceuil.php");
			}
			else
			{
				echo view("acceuil.php");
			}
		}
  		else
		{
		  echo view("connexion.php");
		}
}


//==========================
//Fin du code du controleur simple
//===========================

//fin de la classe
}



?>