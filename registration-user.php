<?php
  require_once 'database.php';

  //Initializing variables
  $nom = $prenom = $email = $telephone = "";

  $sql = "INSERT INTO `users`(`nom`, `prenoms`, `email`, `telephone`, `confirmekey`) VALUES (\'deo\',\'deo\',\'deo@gmail.com\',\'61123187\',\'124896433\')";

  if ($_POST) 
  {
    if (!empty($_POST['nom'])) 
    {
      if (!empty($_POST['prenoms'])) 
      {
        if (!empty($_POST['email'])) 
        {
          if (IsEmail($_POST['email'])) 
          {
            if (!empty($_POST['telephone'])) 
            {
              if (isPhone($_POST['telephone'])) 
              {
                $nom = $_POST['nom'];
                $prenom = $_POST['prenoms'];
                $email = $_POST['email'];
                $telephone = $_POST['telephone'];

                

                //call RegistreUser
                //RegistreUser();
              }
              else 
              {
                $response=array(
                  'status' => 0,
                  'status_message' =>'Veuillez entrer un Numéro de Télephone valide. Que des chiffres et des espaces !.'
                );
              }
            }
            else 
            {
              $response=array(
                'status' => 1,
                'status_message' =>'Veuillez entrer votre Numéro de Télephone.'
              );
            }
          }
          else 
          {
            $response=array(
              'status' => 2,
              'status_message' =>'Veuillez entrer un adresse Email valide.'
            );
          }
        }
        else 
        {
          $response=array(
            'status' => 3,
            'status_message' =>'Veuillez entrer votre adresse Email.'
          );
        }
      }
      else 
      {
        $response=array(
          'status' => 4,
          'status_message' =>'Veuillez entrer votre Prénom.'
        );
      }
    }
    else 
    {
      $response=array(
        'status' => 5,
        'status_message' =>'Veuillez entrer votre Nom.'
      );
    }
    header('Content-Type: application/json');
    echo json_encode($response);
  }

  function RegistreUser()
  {
    // Connect to the database
    $bdd = Database::connect(); // Assuming the Database class and connect() method exist

    // Check if email already exists
    $reqmail = $bdd->prepare("SELECT * FROM users WHERE mail = ?");
    $reqmail->execute(array($email));
    $mail_exist = $reqmail->rowCount();

    // Insertion data
    if ($mail_exist == 0) 
    {
        // Generate a confirmation key
        $longueurkey = 15;
        $key = "";
        for ($i = 0; $i < $longueurkey; $i++) 
        {
            $key .= mt_rand(0, 9);
        }

        $passwd = sha1(12345678);
        $coordonne_x = 123;
        $coordonne_y = 123;
        $confirmed = 0;

        // Prepare and execute the insert query
        $insert_user = $bdd->prepare("INSERT INTO users(nom, prenoms, email, telephone, passwd, confirmekey, coordonne_x, coordonne_y, confirmed) VALUES(:nom, :prenoms, :email, :telephone, :passwd, :confirmekey, :coordonne_x, :coordonne_y, :confirmed)");

        $insert_user->bindParam(':nom', $nom);
        $insert_user->bindParam(':prenoms', $prenom);
        $insert_user->bindParam(':email', $email);
        $insert_user->bindParam(':telephone', $telephone);
        $insert_user->bindParam(':passwd', $passwd);
        $insert_user->bindParam(':confirmekey', $key);
        $insert_user->bindParam(':coordonne_x', $coordonne_x);
        $insert_user->bindParam(':coordonne_y', $coordonne_y);
        $insert_user->bindParam(':confirmed', $confirmed);

        $insert_user->execute();
        
        $insertion = true;
    }
    
      //Recherche de l'Id de l'inscription effectué
      if ($insertion) 
      {
        $requser = $bdd->prepare("SELECT * FROM users WHERE email = ?");
        $requser->execute(array($email));
        $userexist = $requser->rowCount();

        if ($userexist == 1) 
        {
          $userinfo = $requser->fetch();
          $id = $userinfo['id'];
          //Création de l'en-tête du mail
          $header = "MIME-Version: 1.0\r\n";
          $header .= 'From:"K-Pharma - Validation de compte"<deo@arenedeo.hecamacb.com>' ."\n";
          $header .= 'Content-Type:text/html; charset="utf-8"'."\n";
          $header .= 'Content-Transfer-Encoding: 8bit';

          // Création du corps de l'email
          $message='
          <html>
            <body>
              <div>
                <div class="divider"></div>
                <p>Bienvenu et merci de choisir K-Pharma pour vos recherches et commandes de médicamentdans une pharmacie au Bénin !</p>
                <p>Veuillez cliquer sur ce bouton pour valider la création de votre compte</p>
                <a class="btn btn-primary" href="http://arenedeo.hecamacb.com/deo/Kpharma/confirmation.php?id='.$id.'&key='.$key.'">Confirmez votre compte !</a>
              </div>
            </body>
          </html>
          ';

          //Envoi du mail
          mail($email, "Confirmation de création de compte", $message, $header);

          $response=array(
            'status' => 0,
            'status_message' =>'Votre compte K-Pharma à bien été créé ! Vérifiez votre boîte mail pour valider votre inscription.'
          );
        }
      }
      else 
      {
        $response=array(
          'status' => 1,
          'status_message' =>'L\'inscription echouée !'
        );
      }

    }
    else 
    {
      $response=array(
        'status' => 1,
        'status_message' =>'Ce email détient déja un compte. Veuillez-vous connecter avec !'
      );
    }
    header('Content-Type: application/json');
    echo json_encode($response);
  }


  function securisation($donnee)
	{
		$donnee = trim($donnee);
		$donnee = stripslashes($donnee);
		$donnee = strip_tags($donnee);
		$donnee = htmlspecialchars($donnee);
		return $donnee;
	}

  //Validité de l'email
	function isEmail($var)
	{
		return filter_var($var, FILTER_VALIDATE_EMAIL);
	}

	//Validité du phone
	function isPhone($var)
	{
		return preg_match("/^[0-9 ]*$/", $var);
	}

?>