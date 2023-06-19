<?php

  require_once 'database.php';

  function PharmacyName()
  {
    global $mysqli;
    $query = "SELECT pharmacies.nom FROM pharmacies";
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
  PharmacyName();

?>