<?php
$token = bin2hex(mycrpt_create_iv(32, MYCRPT_DEV_URANDOM));

$_SESSION['token'] = $token;
?>

<!DOCTYPE html>

<html>
    <head>
         <meta charset="utf-8">
         <title>Acceuil</title>
         <link rel="stylesheet" href="<?php echo base_url('/public/css/rdf.css'); ?>" />
    </head>
    <body>

            <a href="index.php?action=retouracc"> <img class="im1"  src="<?php echo base_url('/public/images/fleche.png'); ?>" height="40px" width="60px"/>  </a>
            <img class="im2" src="<?php echo base_url('/public/images/logogsb.png'); ?>" height="200px" width="350px"/> 
            
        <aside class="aside1">
        <h2>Frais forfait</h2>
            <div class='Fraisforfait'>
            <form class="Form1" method="post" action="index.php">
                <label for="idetat">Renseignez le frais forfaitisé</label>
                <select name="idfrais" id="idfrais"> 
                    <option value="ETP"> Forfait étape </option>
                    <option value="KM"> Frais Kilométrique </option>
                    <option value="NUI"> Nuitée Hotel </option>
                    <option value="REP"> Repas Restaurant </option>
                </select>
                <br>
                <br>
                    <input type="number" name="quantite" min="0" value="0">
                    <br>
                    <br>
                    <input type="hidden" name="token" id="token" value="<?php echo $token ?>">
                    <input type="submit" value="Envoyer">
            </form></div>
        </aside>
        <aside class="aside2">

        <h2>Frais Hors Forfait</h2>
            <div class='Fraishforfait'>
            <form method="post" action="index.php">
                <label>Renseignez l'objet du frais à rembourser</label>
                <input type="text" name="libelle">
                <br>
                <br>
                <label>Renseignez le montant</label>
                <input type="number" name="montant" min="0">
                <br>
                <br>
                <input type="submit" value="Envoyer">
            </form></div>
        </aside>
    </body>
</html>