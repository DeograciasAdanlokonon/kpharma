<?php

  require_once 'database.php';

  if (!empty($_POST['nom'])) 
  {
    $pharmacy = $_POST['nom'];
    ReadPharmacy();
  }

  function ReadPharmacy()
  {
        global $mysqli;
        global $pharmacy;
        $query = "SELECT * FROM pharmacies WHERE nom = '$pharmacy'";
        $response = array();
        $result = mysqli_query($mysqli, $query);

        //Boucle to fetch voiture
        while ($row = mysqli_fetch_array($result))
        //while($row = $statement->fetch()) 
        {
            $response[] = $row;
        }
        header('Content-Type: application/json');
        echo json_encode($response, JSON_PRETTY_PRINT);
  }

?>