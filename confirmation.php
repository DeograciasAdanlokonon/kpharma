<?php

require 'database.php';

if (isset($_GET['id'], $_GET['key']) AND !empty($_GET['id']) AND !empty($_GET['key'])) 
{
  $id = intval($_GET['id']);
  $key = htmlspecialchars($_GET['key']);
  $bdd =  Database::connect();
  $requser = $bdd->prepare("SELECT * FROM users WHERE id = ? AND confirmekey = ?");
  $requser->execute(array($id, $key));
  $userexist = $requser->rowCount();

  if ($userexist == 1) 
  {
    $userinfo = $requser->fetch();

    if ($userinfo['confirme'] == 0) 
    {
      $updateuser = $bdd->prepare("UPDATE users SET confirme = 1 WHERE id = ? AND confirmekey = ?");
      $updateuser ->execute(array($id, $key));

      $response=array(
        'id' => $userinfo['id'],
      );
      Database::disconnect();
    }
    else 
    {
      $response=array(
        'status' => 0,
        'status_message' =>'Ce compte a déja été confirmé !'
      );
    }
    header('Content-Type: application/json');
    echo json_encode($response);
  }
}

?>