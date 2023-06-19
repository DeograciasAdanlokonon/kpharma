<?php

require 'database.php';

//Recuperer un seul produit
parse_str(file_get_contents('php://input'),$_DELETE);
$id = intval($_POST["id"]);
SetPassword();

function SetPassword()
{
  //connect to bdd
  $bdd =  Database::connect();
  global $id;

  if (!empty($_POST['passwd'])) 
  {
    if (!empty($_POST['confirme-passwd'])) 
    {
      if ($_POST['passwd'] == $_POST['confirme-passwd']) 
      {
        $passwd = sha1($_POST['passwd']);
        $reqmail = $bdd->prepare("UPDATE users SET passwd = ? WHERE id = ?");
        $reqmail->execute(array($passwd, $id));

        $response=array(
          'status' => 0,
          'status_message' =>'Mot de passe crée avec succcès !'
        );
        
      }
    }
    else 
    {
      $response=array(
        'status' => 1,
        'status_message' =>'Veuillez confirmer votre Mot de passe !'
      );
    }
  }
  else 
  {
    $response=array(
      'status' => 2,
      'status_message' =>'Veuillez entrer un Mot de passe !'
    );
  }
  header('Content-Type: application/json');
  echo json_encode($response);

}

?>