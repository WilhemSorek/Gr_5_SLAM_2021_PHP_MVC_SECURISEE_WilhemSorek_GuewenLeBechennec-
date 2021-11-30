<!DOCTYPE html>

<html>
    <head>
         <meta charset="utf-8">
         <title>Acceuil</title>
         <link rel="stylesheet" href="<?php echo base_url('/public/css/vdf.css'); ?>" />
    </head>
       <body>
        <a href="index.php?action=retouracc">
        <img class="im1" 
    src="<?php echo base_url('/public/images/fleche.png'); ?>"
    height="40px"
    width="60px"/>  </a>
         <img class="logo" 
    src="<?php echo base_url('/public/images/logogsb.png'); ?>"
    height="200px"
    width="350px"/>
 
        <h1 class="FF">Frais Forfait</h1>
      <div class="liste1">
<?php
        foreach($resultat as $donnees)
        {

          ?>
        <ul>
          <li><STRONG> ID VISITEUR </STRONG> <?php echo $donnees->idVisiteur; ?> </li>
          <li><strong> Mois </strong> <?php echo $donnees->mois; ?> </li>
          <li><strong> ID Frais Forfait </strong> <?php echo $donnees->idFraisForfait; ?> </li>
          <li><STRONG> Quantité </STRONG> <?php echo $donnees->quantite; ?></li>
        </ul>
        <?php
        }
        ?>
      </div>
  <h1 class="FHF">Frais Hors Forfait</h1><aside>
  <?php
  foreach($resultatHF as $donneesHF)
  {
    ?>
   <div class="liste2">
    <ul>
       <li><strong> Id Frais </strong> <?php echo $donneesHF->id; ?> </li>
       <li><strong> ID VISITEUR </strong> <?php echo $donneesHF->idVisiteur; ?> </li>
       <li><strong> Mois </strong> <?php echo $donneesHF->mois; ?> </li>
       <li><strong> Libelle </strong> <?php echo $donneesHF->libelle; ?> </li>
       <li><strong> Date </strong><?php echo $donneesHF->date; ?> </li>
       <li><strong> montant </strong><?php echo $donneesHF->montant; ?> </li>
       
    </ul>
      </div>
    
    <?php
  }
  ?></aside>
  
  </body>
</html>