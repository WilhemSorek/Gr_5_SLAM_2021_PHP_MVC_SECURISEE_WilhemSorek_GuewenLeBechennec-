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
	session_start();
    $token = bin2hex(random_bytes(32));

    $_SESSION['token'] = $token;
	if (isset($_GET['action']))
	{
		$action = $_GET['action'];
	}
	else
	{
		$action = 'retourConnexion';
	}
	if (isset($_POST['identifiant']) && strlen($_POST['identifiant']) <= 25 && strlen($_POST['password']) <= 25)
	{ 
		$crypt  = Chiffrement::crypt($_POST['password']);

		$Modele = new \App\Models\Modele();
		$ip = $this->recupIP();
		if ($Modele->verifNbConnexion($ip)< 10) {
			$action = 'connexion';

		sleep(1);
		}
		else {
			$Modele->ajoutTentativeConnexionEchouee($ip);
		}
	}
	elseif (isset($_POST['quantite']))
	{
		$action = 'updateFF';
	}
	elseif (isset($_POST['libelle']))
	{
		$action = 'insertFHF';
	}

	if(!isset($_SESSION['v_ti']))
	{
		$ticket = session_id().microtime().rand(0,9999999999);
		$ticket = hash('sha512', $ticket);
		setcookie('v_tc', $ticket, time() +  (60 * 20));
		$_SESSION['v_ti'] = $ticket;
	}
	else
	{
		if(isset($_COOKIE['v_tc']) == isset($_SESSION['v_ti']))
		{
			$ticket = session_id().microtime().rand(0,9999999999);
			$ticket = hash('sha512', $ticket);
			setcookie('v_tc', $ticket, time() +  (60 * 20));
			$_SESSION['v_ti'] = $ticket;
		}
		else
		{
			$action = 'retourConnexion';
			$_SESSION = array();
			$_COOKIE = array();
			session_destroy();
			session_start();
			$ticket = session_id().microtime().rand(0,9999999999);
			$ticket = hash('sha512', $ticket);
			setcookie('v_tc', $ticket, time() +  (60 * 20));
			$_SESSION['v_ti'] = $ticket;
		}
	}
	switch ($action)
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
		case "retourConnexion":
				session_unset();
				$_SESSION = array();
				$this->connexion();
		break;
		case 'updateFF':
			$this->updateFrais();
		break;
		case 'insertFHF':
			$this->insertHorsForfait();
		break;
		case 'connexion':
			$this->verif(htmlspecialchars(strtolower($_POST['identifiant'])), htmlspecialchars(strtolower(Chiffrement::decrypt($crypt))));
		break;
		default:
		$this->connexion();
		break;
	}
	
}
public function updateFrais()
{
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
		$Modele = new \App\Models\Modele();

		$Modele->insertFraisHF(htmlspecialchars($_SESSION['id']), $Modele->moisTrad(), htmlspecialchars(strtolower($_POST['libelle'])), $Modele->today(), htmlspecialchars($_POST['montant']));
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
	session_destroy();
	echo view('connexion.php');
}

public function consulter()
{
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
			$cookie_name = "v_tc";
			$ticket = session_id().microtime().rand(0,9999999999);
			$ticket = hash('sha512', $ticket);
			setcookie($cookie_name, $ticket, time() + (60 * 20));
			$_SESSION['v_ti'] = $ticket;

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
		$ip = $this->recupIP();
		$Modele->ajoutTentativeConnexionEchouee($ip);
		  echo view("connexion.php");
		}
}

public function recupIP()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) 
    {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))  
    {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    return $ip;
}



//==========================
//Fin du code du controleur simple
//===========================

//fin de la classe
}

class Chiffrement extends BaseController
{
	private static $cipher = "MYCRYPT_RIJNDAEL_128";
	private static $key = "WPA2";
	private static $mode = 'cbc';

	public static function crypt($data)
	{
		$keyHash = md5(self::$key);
		$key = substr($keyHash, 0, mycrypt_get_key_size(self::$cipher, self::$mode));
		$iv = substr($keyHash, 0, mycrypt_get_block_size(self::$cipher, self::$mode));

		$data=mcrypt_encrypt(self::$cipher, $key, $data, self::$mode, $iv);

		return base64_encode($data);
	}

	public static function decrypt($data)
	{
		$keyHash=md5(self::$key);
		$key=substr($keyHash, 0,   mcrypt_get_key_size(self::$cipher, self::$mode));
		$iv=substr($keyHash, 0, mcrypt_get_block_size(self::$cipher, self::$mode));

		$data = base64_decode($data);
		$data = mcrypt_decrypt(self::$cipher, $key, $data, self::$mode, $iv);

		return rtrim($data);
	}
}

?>