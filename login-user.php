<?php

  require_once 'database.php';

  if ($_POST) 
  {
    if (!empty($_POST['email'])) 
    {
      $email = securisation($_POST['email']); 
      if (isEmail($email)) 
      {
        if (!empty($_POST['passwd'])) 
        {
          $password = securisation($_POST['passwd']);
          
          //Call LogUser
          LogUser();
        }
        else 
        {
          $response=array(
            'status' => 0,
            'status_message' =>'Veuillez entrer votre Mot de Passe de connexion.'
          );
        }
      }
      else 
      {
        $response=array(
          'status' => 0,
          'status_message' =>'Veuillez entrer un email valide.'
        );
      }
    }
    else 
    {
      $response=array(
        'status' => 0,
        'status_message' =>'Veuillez entrer votre Email de connexion.'
      );
    }
    header('Content-Type: application/json');
    echo json_encode($response);
  }

  //Function to Log User
  function LogUser()
  {
    //connect to bdd
    $bdd =  Database::connect();
    global $email;
    global $password;

    $check=$bdd->prepare("SELECT * FROM users WHERE email = ? AND passwd = ?");
    $check->execute(array($email, $password));
    $userexist = $check->rowCount();

    if ($userexist == 1) 
    {
      $userinfo = $check->fetch();
      if ($userinfo['confirme'] == 1) 
      {
        $response=array(
          'id' => $userinfo['id'],
          'nom' => $userinfo['nom'],
          'email' => $userinfo['email'],
          'telephone' => $userinfo['telephone'],
          'coordonnees_x' => $userinfo['coordonnees_x'],
          'coordonnees_y' => $userinfo['coordonnees_y'],
          'Logged' => true,
        );
        database::disconnect();
      }
      else 
      {
        $response=array(
          'status' => 0,
          'status_message' =>'Ce compte est en attente de confirmation! Veuillez consulter votre boite mail ou reprenez l\'inscription'
        );
      }
    }
    else
    {
      $response=array(
        'status' => 0,
        'status_message' =>'Ce compte n\existe pas!'
      );
    }
    header('Content-Type: application/json');
    echo json_encode($response);
  }


  //Data securing
  function securisation($donnee)
	{
		$donnee = trim($donnee);
		$donnee = stripslashes($donnee);
		$donnee = strip_tags($donnee);
		$donnee = htmlspecialchars($donnee);
		return $donnee;
	}

  //Vérification de l'email
  function isEmail($var)
  {
    return filter_var($var, FILTER_VALIDATE_EMAIL);
  }
?>