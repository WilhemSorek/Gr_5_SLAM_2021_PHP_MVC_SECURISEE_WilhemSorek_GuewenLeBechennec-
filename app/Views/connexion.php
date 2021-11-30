<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
        <title>connexion</title>
        <link rel="stylesheet" href="<?php echo base_url('/public/css/style 2.css'); ?>" /> 
       <body>
<img
    src="<?php echo base_url('/public/images/logogsb.png'); ?>"
    height="300px"
    width="500px"/>	

    <form class="div" action="index.php" method="post"> 
      <input type="text" name="identifiant" placeholder="Votre nom" id="id" />
      <input type="password" name="password" placeholder="Votre mot de passe"
      id="password" />
      <button>Connexion</button>
    </form>
  
</body>
</html>