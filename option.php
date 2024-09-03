<?php
	$command = isset($argv[1]) ? $argv[1] : '';

	// Liste des commandes dispo
	$lbcCommands = [
		'a or all' => 'Execute tous les scripts',
		'h or help' => 'Aide commande',
	];

	
	require 'vendor/autoload.php'; 

	// Utilisation du biliotheque Faker pour generer des fausses donnees
	$fake = Faker\Factory::create();

	// Générer des donnees
	$fakeCompanyName = $fake->company;
	$fakeFirstName = $fake->firstName;
	$fakeLastName = $fake->lastName;

	// Liste de noms de villes
	$villes = array("Paris", "Londres", "New York", "Tokyo", "Sydney", "Berlin", "Rome", "Rio de Janeiro");

	// Générer une ville aléatoire
	$villeAleatoire = $villes[array_rand($villes)];

	// Fonction pour afficher le menu
	function afficherMenu() {
		echo "\n1. Creation des candidats\n";
		echo "2. Creation de compte candidat\n";
		echo "3. Creation des clients\n";
		echo "4. Creation des offres\n";
		echo "5. Creation de vivier\n";
		echo "6. Ajout de candidat au vivier\n";
		echo "0. Quitter\n";
	}

	// Exécuter l'option spécifiée
	// Check the user's input
	switch ($command) {
		case 'h':
		case 'help':
			// Display the list of Git commands with descriptions
			echo "Liste des commandes:\n";
			foreach ($lbcCommands as $cmd => $description) {
			echo "$cmd || $description\n";
			}
			break;
		case 'a':
		case 'all':
			echo "Vous allez exécutez tous les scripts. \n";
			echo "Vous avez 3 secondes pour annuler en utilisant ctrl + c. \n\n";
			sleep(3);

			echo "Vous allez exécuter le script creation_candidat.side\n";
			// Chemin vers le fichier erreur_candidat.log
			$erreurLogPath = __DIR__.'/erreur_candidat.log';
			// Vérifier si le fichier erreur_candidat.log existe
			if (file_exists($erreurLogPath)) {
				// Supprimer le fichier erreur_candidat.log
				unlink($erreurLogPath);
				echo "Le fichier erreur_candidat.log existant a été supprimé. \n";
			}
			
			// Incrémentation avec id unique
			$newIncrement = strtoupper(uniqid());

			// Chemin du fichier .side
			$sideFilePath = __DIR__.'/script/creation_candidat.side';

			// Lire le contenu du fichier .side
			$fileContent = file_get_contents($sideFilePath);

			// Nouveau coordonné du candidat
			$newLastName = "JEAN" . $newIncrement;
			$newFirstName = "Rousseaux";
			$newMail = strtolower("jean" . $newIncrement . "@leboncandidat.fr");
			$newCity = "Paris";
			$newZipcode = "75013";
			$newCandidatePhone = "0716123456";
			$newBirthday = "19/12/1997";
			$newAddress = "33 Avenue Henri Luisette, 94800 VILLEJUIF";

			// Remplacer un string dans le fichier designé et stocker dans la variable
			$tempContent = str_replace('"value": "NOM"', '"value": "' . $newLastName . '"', $fileContent);
			$tempContent = str_replace('"value": "Prenom"', '"value": "' . $newFirstName . '"', $tempContent);
			$tempContent = str_replace('"value": "Mail"', '"value": "' . $newMail . '"', $tempContent);
			$tempContent = str_replace('"value": "City"', '"value": "' . $newCity . '"', $tempContent);
			$tempContent = str_replace('"value": "Zipcode"', '"value": "' . $newZipcode . '"', $tempContent);
			$tempContent = str_replace('"value": "CandidatePhone"', '"value": "' . $newCandidatePhone . '"', $tempContent);
			$tempContent = str_replace('"value": "Birthday"', '"value": "' . $newBirthday . '"', $tempContent);
			$tempContent = str_replace('"value": "Address"', '"value": "' . $newAddress . '"', $tempContent);

			// Temporaire du fichier .side
			$candidate_Tmp_Path = __DIR__.'/script/creation_candidat_tmp.side';
			// Enregistrer les modifications dans un fichier temporaire .side
			file_put_contents($candidate_Tmp_Path, $tempContent);

			// Execution du script selenium avec redirection de la sortie d’erreur standard (2) vers la sortie standard (1)
			$output_candidate = shell_exec("selenium-side-runner -c \"goog:chromeOptions.args=[headless,no-sandbox] browserName=chrome\" ".$candidate_Tmp_Path." 2>&1");
			//shell_exec('selenium-side-runner -c \"goog:chromeOptions.args=[headless,no-sandbox] browserName=chrome\" --base-url=https://preprod.leboncandidat.fr script/creation_candidat.side');

			if (strpos($output_candidate, 'passed') !== false) {
				echo "creation_candidat.side: Le script a été exécuté avec succès! \n\n";
				file_put_contents('success_candidat.log', '[' . date('Y-m-d H:i:s') . '] ' . $output_candidate);
			} else {
				echo "creation_candidat.side: Erreur lors de l'exécution du script . \n\n";
				// Enregistrez les informations dans un fichier
				file_put_contents('erreur_candidat.log', '[' . date('Y-m-d H:i:s') . '] ' . $output_candidate);
			}

			// Execution du 2eme script
			echo "Vous allez exécuter le script creation_client.side.\n";
			sleep(2);

			// Chemin vers le fichier erreur_client.log
			$erreurLogPath = __DIR__.'/erreur_client.log';
			// Vérifier si le fichier erreur_client.log existe
			if (file_exists($erreurLogPath)) {
				// Supprimer le fichier erreur_client.log
				unlink($erreurLogPath);
				echo "Le fichier erreur_client.log existant a été supprimé. \n";
			}

			// Lire le contenu
			$sideFilePath = __DIR__.'/script/creation_client.side';
			$fileContent = file_get_contents($sideFilePath);

			// Incrémentation avec ID unique
			$newIncrement = strtoupper(uniqid());

			// Nouveau coordonné du client
			$newCompanyName = $fakeCompanyName . $newIncrement;
			$newCity = $villeAleatoire;
			$newZipcode = rand(10000, 99999);
			$newAddress = "56 Avenue Chicago";
			$newSiret = "11111111111111";
			$newAPE = rand(1000, 9999).chr(rand(65, 90));
			$newCampanyPhone = rand(1000000000, 9999999999);
			$newLastName = $fakeLastName . $newIncrement;
			$newFirstName = $fakeFirstName;
			$newContactMail = strtolower($newLastName) . "@leboncandidat.fr";
			$newPhoneContact = rand(1000000000, 9999999999);
			$newJob = "PDG";

			// Remplacer un string dans le fichier designé et stocker dans la variable
			$tempContent = str_replace('"value": "CompanyName"', '"value": "' . $newCompanyName . '"', $fileContent);
			$tempContent = str_replace('"value": "City"', '"value": "' . $newCity . '"', $tempContent);
			$tempContent = str_replace('"value": "Zipcode"', '"value": "' . $newZipcode . '"', $tempContent);
			$tempContent = str_replace('"value": "Address"', '"value": "' . $newAddress . '"', $tempContent);
			$tempContent = str_replace('"value": "Siret"', '"value": "' . $newSiret . '"', $tempContent);
			$tempContent = str_replace('"value": "APE"', '"value": "' . $newAPE . '"', $tempContent);
			$tempContent = str_replace('"value": "CampanyPhone"', '"value": "' . $newCampanyPhone . '"', $tempContent);
			$tempContent = str_replace('"value": "NOM"', '"value": "' . $newLastName . '"', $tempContent);
			$tempContent = str_replace('"value": "Prenom"', '"value": "' . $newFirstName . '"', $tempContent);
			$tempContent = str_replace('"value": "ContactMail"', '"value": "' . $newContactMail . '"', $tempContent);
			$tempContent = str_replace('"value": "PhoneContact"', '"value": "' . $newPhoneContact . '"', $tempContent);
			$tempContent = str_replace('"value": "Job"', '"value": "' . $newJob . '"', $tempContent);

			// Chemin du fichier .side
			$client_Tmp_Path = __DIR__.'/script/creation_client_tmp.side';
			// Enregistrer les modifications dans un fichier temporaire .side
			file_put_contents($client_Tmp_Path, $tempContent);

			// Execution du script creation des clients
			$output_client = shell_exec("selenium-side-runner -c \"goog:chromeOptions.args=[headless,no-sandbox] browserName=chrome\" ".$client_Tmp_Path." 2>&1 ");
			//shell_exec('selenium-side-runner -c \"goog:chromeOptions.args=[headless,no-sandbox] browserName=chrome\" --base-url=https://preprod.leboncandidat.fr script/creation_candidat.side');

			if (strpos($output_client, 'passed') !== false) {
				echo "creation_client.side : Le script a été exécuté avec succès! \n\n";
				file_put_contents('success_client.log', '[' . date('Y-m-d H:i:s') . '] ' . $output_client);
			} else {
				echo "creation_client.side: Erreur lors de l'exécution du script. \n\n";
				// Enregistrez les informations dans un fichier
				file_put_contents('erreur_client.log', '[' . date('Y-m-d H:i:s') . '] ' . $output_client);
			}

			// Execution du 3eme script
			echo "Vous allez executer le script creation_offre_interim.side.\n";
			sleep(2);

			// Chemin vers le fichier erreur_offre_interim.log
			$erreurLogPath = __DIR__.'/erreur_offre_interim.log';
			// Vérifier si le fichier erreur_offre_interim.log existe
			if (file_exists($erreurLogPath)) {
				// Supprimer le fichier erreur_offre_interim.log
				unlink($erreurLogPath);
				echo "Le fichier erreur_offre_interim.log existant a été supprimé. \n";
			}

			// Lire le contenu
			$sideFilePath = __DIR__.'/script/creation_offre_interim.side';
			$fileContent = file_get_contents($sideFilePath);

			$success_client_Path = __DIR__.'/success_client.log';
			$success_client_Content = file_get_contents($success_client_Path);

			// Rechercher la ligne qui contient '"value": "XXX"'
			if (preg_match('/.*L\'ID du client est: (\d+)/', $success_client_Content, $matches)) {
				$ID_Client = intval($matches[1]);
			} else {
				echo "Aucun ID trouvé dans le fichier success_client_Content.log.\n";
			}

			// Lire les info clients
			$client_Info_Path = __DIR__.'/script/creation_client_tmp.side';
			$client_Info_Content = file_get_contents($client_Info_Path);
			$client_Info_lines = explode("\n", $client_Info_Content);

			// Accéder à l'élément du tableau qui correspond à la ligne 81
			$client_Info_line81 = $client_Info_lines[80];

			// Rechercher la ligne qui contient '"value": "XXX"'
			if (preg_match('/"value":\s*"([^"]+)"/', $client_Info_line81, $matches)) {
				$CompanyName = $matches[1];
			} else {
				echo "Le nom d'entreprise n'est pas trouvé dans le fichier creation_client_tmp.side. \n";
			}

			// Nouveau coordonné du client
			$newOffer = "Acheteur";
			$newClient = $CompanyName;
			$newJob = "ACHETEUR";
			$newUser = "ADMIN TECH";
			$newApec_Job = "Apec_Job";
			$newRecommend_RH = "1";
			$newOfferZipcode = "75013";
			$newMin_salary = "22000";
			$newMax_salary = "35000";
			$newJob_count = "1";
			$newStart_date = date('d/m/Y');

			// Remplacer un string dans le fichier designé et stocker dans la variable
			$tempContent = str_replace('"target": "css=div.option[data-val=\"ID_Client\"]",', '"target": "css=div.option[data-val=\"'.$ID_Client.'\"]",', $fileContent);
			$tempContent = str_replace('"value": "Offer"', '"value": "' . $newOffer . '"', $tempContent);
			$tempContent = str_replace('"value": "Client"', '"value": "' . $newClient . '"', $tempContent);
			$tempContent = str_replace('"value": "Job"', '"value": "' . $newJob . '"', $tempContent);
			$tempContent = str_replace('"value": "User"', '"value": "' . $newUser . '"', $tempContent);
			$tempContent = str_replace('"value": "Apec_Job"', '"value": "' . $newApec_Job . '"', $tempContent);
			$tempContent = str_replace('"value": "Recommend_RH"', '"value": "' . $newRecommend_RH . '"', $tempContent);
			$tempContent = str_replace('"value": "OfferZipcode"', '"value": "' . $newOfferZipcode . '"', $tempContent);
			$tempContent = str_replace('"value": "Min_salary"', '"value": "' . $newMin_salary . '"', $tempContent);
			$tempContent = str_replace('"value": "Max_salary"', '"value": "' . $newMax_salary . '"', $tempContent);
			$tempContent = str_replace('"value": "Job_count"', '"value": "' . $newJob_count . '"', $tempContent);
			$tempContent = str_replace('"value": "Start_date"', '"value": "' . $newStart_date . '"', $tempContent);

			// Temporaire du fichier .side
			$offer_Tmp_Path = __DIR__.'/script/creation_offre_interim_tmp.side';
			// Enregistrer les modifications dans un fichier temporaire .side
			file_put_contents($offer_Tmp_Path, $tempContent);

			// Execution du script creation des clients
			$output_offer = shell_exec("selenium-side-runner -c \"goog:chromeOptions.args=[headless,no-sandbox] browserName=chrome\" ".$offer_Tmp_Path." 2>&1");
			//shell_exec('selenium-side-runner -c \"goog:chromeOptions.args=[headless,no-sandbox] browserName=chrome\" --base-url=https://preprod.leboncandidat.fr script/creation_candidat.side');

			if (strpos($output_offer, 'passed') !== false) {
				echo "creation_offre_interim.side: Le script a été exécuté avec succès! \n\n";
				file_put_contents('success_offre_interim.log', '[' . date('Y-m-d H:i:s') . '] ' . $output_offer);
			} else {
				echo "creation_offre_interim.side: Erreur lors de l'exécution du script. \n\n";
				// Enregistrez les informations dans un fichier
				file_put_contents('erreur_offre_interim.log', '[' . date('Y-m-d H:i:s') . '] ' . $output_offer);
			}

			// Execution du 4eme script
			echo "Vous allez executer le script creation_interim.side. \n";
			sleep(2);

			// Chemin vers le fichier erreur_interim.log
			$erreurLogPath = __DIR__.'/erreur_interim.log';
			// Vérifier si le fichier erreur_interim.log existe
			if (file_exists($erreurLogPath)) {
				// Supprimer le fichier erreur_interim.log
				unlink($erreurLogPath);
				echo "Le fichier erreur_interim.log existant a été supprimé. \n";
			}

			// Lire le contenu
			$sideFilePath = __DIR__.'/script/creation_interim.side';
			$fileContent = file_get_contents($sideFilePath);

			// Lire le contenu success_candidat.log
			$success_candidat_Path = __DIR__.'/success_candidat.log';
			$success_candidat_Content = file_get_contents($success_candidat_Path);

			// Rechercher la ligne qui contient L'ID du client est: 
			if (preg_match('/.*L\'ID du candidat est: (\d+)/', $success_candidat_Content, $matches)) {
				$ID_Candidat = intval($matches[1]);
			} else {
				echo "Aucun ID candidat trouvé dans le fichier.";
			}

			// Lire le contenu success_client.log
			$success_client_Path = __DIR__.'/success_client.log';
			$success_client_Content = file_get_contents($success_client_Path);

			// Rechercher la ligne qui contient L'ID du client est: 
			if (preg_match('/.*L\'ID du client est: (\d+)/', $success_client_Content, $matches)) {
				$ID_Client = intval($matches[1]);
			} else {
				echo "Aucun ID client trouvé dans le fichier.";
			}

			// Lire le contenu success_offre_interim.log
			$success_offre_interim_Path = __DIR__.'/success_offre_interim.log';
			$success_offre_interim_Content = file_get_contents($success_offre_interim_Path);

			// Rechercher la ligne qui contient L'ID de l'offre est:
			if (preg_match('/.*L\'ID de l\'offre est: (\d+)/', $success_offre_interim_Content, $matches)) {
				$ID_Offre = intval($matches[1]);
			} else {
				echo "Aucun ID offre interim trouvé dans le fichier.";
			}

			// Lien des info candidat
			$candidat_Info_Path = __DIR__.'/script/creation_candidat_tmp.side';
			$candidat_Info_Content = file_get_contents($candidat_Info_Path);
			$candidat_Info_Lines = explode("\n", $candidat_Info_Content);

			// Accéder à l'élément du tableau qui correspond à la ligne 95
			$candidat_Info_Lines95 = $candidat_Info_Lines[94];

			// Rechercher la ligne qui contient '"value": "XXX"'
			if (preg_match('/"value":\s*"([^"]+)"/', $candidat_Info_Lines95, $matches)) {
				$LastName = $matches[1];
			} else {
				echo "Le nom du candidat n'est pas trouvé dans le fichier";
			}

			// Lire les info clients
			$client_Info_Path = __DIR__.'/script/creation_client_tmp.side';
			$client_Info_Content = file_get_contents($client_Info_Path);
			$client_Info_Lines = explode("\n", $client_Info_Content);

			// Accéder à l'élément du tableau qui correspond à la ligne 81 et 257
			$client_Info_Lines81 = $client_Info_Lines[80];
			$client_Info_Lines257 = $client_Info_Lines[256];

			// Rechercher la ligne qui contient '"value": "XXX"'
			if (preg_match('/"value":\s*"([^"]+)"/', $client_Info_Lines81, $matches)) {
				$CompanyName = $matches[1];
			} else {
				echo "Le nom d'entreprise n'est pas trouvé dans le fichier";
			}

			// Rechercher la ligne qui contient '"value": "XXX"'
			if (preg_match('/"value":\s*"([^"]+)"/', $client_Info_Lines257, $matches)) {
				$ContactMail = $matches[1];
			} else {
				echo "Le mail de contact n'est pas trouvé dans le fichier";
			}

			$newBirthday = "19/12/1997";
			$newBirthCountry = "FRANCE";
			$newBirthCity = "Paris";
			$newBirthZipcode = "75013";
			$newNationality = "FRANCAISE";
			$newIdentity_French = "value=1";
			$newIDCardFront = "/home/anthony/lbc/Tests-Selenium/cv.pdf";
			$newIDCardBack = "/home/anthony/lbc/Tests-Selenium/cv.pdf";
			$newIDCardNumber = "9734988453";
			$newIDCardStart = "09/10/2023";
			$newIDCardEnd = "09/10/2024";
			$newRIB = "/home/anthony/lbc/Tests-Selenium/cv.pdf";
			$newIBAN = "FR5224525452565898658448B46";
			$newBankBIC = "LFGLGU5556";
			$newVitalCard = "/home/anthony/lbc/Tests-Selenium/cv.pdf";
			$newSecurityNumber = "848946462484845";
			$newAddressProof_File = "/home/anthony/lbc/Tests-Selenium/cv.pdf";
			$newAddressProof_Address = "33 Avenue Henri Luisette";
			$newAddressProof_City = "Paris";
			$newAddressProof_Zipcode = "75013";
			$newContrat_Date = date('m/d/Y'); //mm/dd/yyyy
			$newConsultant_Signataire = "ADMIN TECH";
			$newConsultant_CoSignataire = "RH LEA";
			$newCoSignataire_Commande = "RH LEO";
			$newOffer = "Acheteur";
			$newMission_Title = "Acheteur";
			$newMission_Address = "114 Av. des Champs-Elysees";
			$newMission_Zipcode = "75008";
			$newMission_Contact = "Tim COOK";
			$newReplaced_Person_Name = "LOUIS";
			$newReplaced_Person_Job = "Acheteur";
			$newReplaced_Reason = "Malade";
			$newMission_Description = "Acheter des marchandises";
			$newMission_Start_Date = "01/12/2023"; //date('d/m/Y', strtotime($newContrat_Date . ' -10 day'));
			$newMission_End_Date = "31/12/2023";
			$newMission_Hours_Per_Week = "35";
			$newMission_Hours_Per_Week_Before_Overtime = "35";
			$newMission_Work_Time = "35 heures par semaine";
			$newMission_Trial_Period = "30";
			$newMission_Annual_Salary = "30000";
			$newMission_Freight_Charges = "20";
			$newMission_Notes = "conges payer";
			$newMission_Rate = "10";
			$newMission_Other_Notes = "Autre";

			// Remplacer un string dans le fichier designé et stocker dans la variable
			$tempContent = str_replace('"value": "Nom"', '"value": "' . $LastName . '"', $fileContent);
			$tempContent = str_replace('"target": "css=div.option[data-val=\"ID_Candidat\"]",', '"target": "css=div.option[data-val=\"'.$ID_Candidat.'\"]",', $tempContent);
			$tempContent = str_replace('"value": "Birthday"', '"value": "' . $newBirthday . '"', $tempContent);
			$tempContent = str_replace('"value": "BirthCountry"', '"value": "' . $newBirthCountry . '"', $tempContent);
			$tempContent = str_replace('"value": "BirthCity"', '"value": "' . $newBirthCity . '"', $tempContent);
			$tempContent = str_replace('"value": "BirthZipcode"', '"value": "' . $newBirthZipcode . '"', $tempContent);
			$tempContent = str_replace('"value": "Nationality"', '"value": "' . $newNationality . '"', $tempContent);
			$tempContent = str_replace('"value": "IDCardFront"', '"value": "' . $newIDCardFront . '"', $tempContent);
			$tempContent = str_replace('"value": "IDCardBack"', '"value": "' . $newIDCardBack . '"', $tempContent);
			$tempContent = str_replace('"value": "IDCardNumber"', '"value": "' . $newIDCardNumber . '"', $tempContent);
			$tempContent = str_replace('"value": "IDCardStart"', '"value": "' . $newIDCardStart . '"', $tempContent);
			$tempContent = str_replace('"value": "IDCardEnd"', '"value": "' . $newIDCardEnd . '"', $tempContent);
			$tempContent = str_replace('"value": "RIB"', '"value": "' . $newRIB . '"', $tempContent);
			$tempContent = str_replace('"value": "IBAN"', '"value": "' . $newIBAN . '"', $tempContent);
			$tempContent = str_replace('"value": "BankBIC"', '"value": "' . $newBankBIC . '"', $tempContent);
			$tempContent = str_replace('"value": "VitalCard"', '"value": "' . $newVitalCard . '"', $tempContent);
			$tempContent = str_replace('"value": "SecurityNumber"', '"value": "' . $newSecurityNumber . '"', $tempContent);
			$tempContent = str_replace('"value": "AddressProof_File"', '"value": "' . $newAddressProof_File . '"', $tempContent);
			$tempContent = str_replace('"value": "AddressProof_Address"', '"value": "' . $newAddressProof_Address . '"', $tempContent);
			$tempContent = str_replace('"value": "AddressProof_City"', '"value": "' . $newAddressProof_City . '"', $tempContent);
			$tempContent = str_replace('"value": "AddressProof_Zipcode"', '"value": "' . $newAddressProof_Zipcode . '"', $tempContent);
			$tempContent = str_replace('"target": "http://local.leboncandidat.fr/app_dev.php/admin/interimmission/new?candidate_id=ID_Candidat",', '"target": "http://local.leboncandidat.fr/app_dev.php/admin/interimmission/new?candidate_id=' . $ID_Candidat . '",', $tempContent);
			$tempContent = str_replace('"value": "Contrat_Date"', '"value": "' . $newContrat_Date . '"', $tempContent);
			$tempContent = str_replace('"value": "Consultant_Signataire"', '"value": "' . $newConsultant_Signataire . '"', $tempContent);
			$tempContent = str_replace('"value": "Consultant_CoSignataire"', '"value": "' . $newConsultant_CoSignataire . '"', $tempContent);
			$tempContent = str_replace('"value": "CoSignataire_Commande"', '"value": "' . $newCoSignataire_Commande . '"', $tempContent);
			$tempContent = str_replace('"value": "CompanyName"', '"value": "' . $CompanyName . '"', $tempContent);
			$tempContent = str_replace('"target": "css=div.option[data-val=\"ID_Client\"]",', '"target": "css=div.option[data-val=\"'.$ID_Client.'\"]",', $tempContent);
			$tempContent = str_replace('"value": "Offer"', '"value": "' . $newOffer . '"', $tempContent);
			$tempContent = str_replace('"target": "css=.offer-field-container div.option[data-val=\"ID_Offre\"]",', '"target": "css=.offer-field-container div.option[data-val=\"'.$ID_Offre.'\"]",', $tempContent);
			$tempContent = str_replace('"value": "Mission_Title"', '"value": "' . $newMission_Title . '"', $tempContent);
			$tempContent = str_replace('"value": "Mission_Address"', '"value": "' . $newMission_Address . '"', $tempContent);
			$tempContent = str_replace('"value": "Mission_Zipcode"', '"value": "' . $newMission_Zipcode . '"', $tempContent);
			$tempContent = str_replace('"value": "Mission_Contact"', '"value": "' . $newMission_Contact . '"', $tempContent);
			$tempContent = str_replace('"value": "Replaced_Person_Name"', '"value": "' . $newReplaced_Person_Name . '"', $tempContent);
			$tempContent = str_replace('"value": "Replaced_Person_Job"', '"value": "' . $newReplaced_Person_Job . '"', $tempContent);
			$tempContent = str_replace('"value": "Replaced_Reason"', '"value": "' . $newReplaced_Reason . '"', $tempContent);
			$tempContent = str_replace('"value": "Mission_Description"', '"value": "' . $newMission_Description . '"', $tempContent);
			$tempContent = str_replace('"value": "Mission_Start_Date"', '"value": "' . $newMission_Start_Date . '"', $tempContent);
			$tempContent = str_replace('"value": "Mission_End_Date"', '"value": "' . $newMission_End_Date . '"', $tempContent);
			$tempContent = str_replace('"value": "Mission_Hours_Per_Week"', '"value": "' . $newMission_Hours_Per_Week . '"', $tempContent);
			$tempContent = str_replace('"value": "Mission_Hours_Per_Week_Before_Overtime"', '"value": "' . $newMission_Hours_Per_Week_Before_Overtime . '"', $tempContent);
			$tempContent = str_replace('"value": "Mission_Work_Time"', '"value": "' . $newMission_Work_Time . '"', $tempContent);
			$tempContent = str_replace('"value": "Mission_Trial_Period"', '"value": "' . $newMission_Trial_Period . '"', $tempContent);
			$tempContent = str_replace('"value": "Mission_Annual_Salary"', '"value": "' . $newMission_Annual_Salary . '"', $tempContent);
			$tempContent = str_replace('"value": "Mission_Freight_Charges"', '"value": "' . $newMission_Freight_Charges . '"', $tempContent);
			$tempContent = str_replace('"value": "Mission_Notes"', '"value": "' . $newMission_Notes . '"', $tempContent);
			$tempContent = str_replace('"value": "Mission_Rate"', '"value": "' . $newMission_Rate . '"', $tempContent);
			$tempContent = str_replace('"value": "Mission_Other_Notes"', '"value": "' . $newMission_Other_Notes . '"', $tempContent);
			$tempContent = str_replace('"target": "return document.body.innerText.includes(\'ContactMail\');",', '"target": "return document.body.innerText.includes(\'' . $ContactMail . '\');",', $tempContent);

			// Temporaire du fichier .side
			$interim_Tmp_Path = __DIR__.'/script/creation_interim_tmp.side';
			// Enregistrer les modifications dans un fichier temporaire .side
			file_put_contents($interim_Tmp_Path, $tempContent);

			// Execution du script creation des interim
			$output_interim = shell_exec("selenium-side-runner -c \"goog:chromeOptions.args=[headless,no-sandbox] browserName=chrome\" " . $interim_Tmp_Path . " 2>&1");
			//shell_exec('selenium-side-runner -c \"goog:chromeOptions.args=[headless,no-sandbox] browserName=chrome\" --base-url=https://preprod.leboncandidat.fr script/creation_candidat.side');

			if (strpos($output_interim, 'passed') !== false) {
				echo "creation_interim_tmp.side: Le script a été exécuté avec succès! \n\n";
			} else {
				echo "creation_interim_tmp.side: Erreur lors de l'exécution du script.\n\n";
				// Enregistrez les informations dans un fichier
				file_put_contents('erreur_interim.log', '[' . date('Y-m-d H:i:s') . '] ' . $output_interim);
			}
			
			// Execution du 5eme script
			echo "Vous allez executer le script creation_compte_candidat.side. \n";
			sleep(2);

			// Chemin vers le fichier erreur_candidate_account.log
			$erreurLogPath = __DIR__.'/erreur_candidate_account.log';
			// Vérifier si le fichier erreur_candidate_account.log existe
			if (file_exists($erreurLogPath)) {
				// Supprimer le fichier erreur_candidate_account.log
				unlink($erreurLogPath);
				echo "Le fichier erreur_candidate_account.log existant a été supprimé. \n";
			}

			// Lire le contenu de creation compte candidat
			$sideFilePath = __DIR__.'/script/creation_compte_candidat.side';
			$fileContent = file_get_contents($sideFilePath);

			// Lire le contenu success_candidat.log
			$success_candidat_Path = __DIR__.'/success_candidat.log';
			$success_candidat_Content = file_get_contents($success_candidat_Path);

			// Rechercher la ligne qui contient L'ID du client est: 
			if (preg_match('/.*L\'ID du candidat est: (\d+)/', $success_candidat_Content, $matches)) {
				$ID_Candidat = intval($matches[1]);
			} else {
				echo "Aucun ID candidat trouvé dans le fichier.";
			}

			// Remplacer un string dans le fichier designé et stocker dans la variable
			$tempContent = str_replace('"target": "http://local.leboncandidat.fr/app_dev.php/admin/candidate/ID_Candidat",', '"target": "http://local.leboncandidat.fr/app_dev.php/admin/candidate/'. $ID_Candidat .'",', $fileContent);
			$tempContent = str_replace('"target": "return document.querySelector(\'a[href=\"/app_dev.php/admin/candidate/ID_Candidat/edit/frontuser\"]\').innerText.includes(\"Modifier l\'email\");"', '"target": "return document.querySelector(\'a[href=\"/app_dev.php/admin/candidate/' . $ID_Candidat . '/edit/frontuser\"]\').innerText.includes(\"Modifier l\'email\");"', $tempContent);
			
			// Chemin du fichier temporaire .side
			$candidate_account_Tmp_Path = __DIR__.'/script/creation_compte_candidat_tmp.side';
			// Enregistrer les modifications dans un fichier temporaire .side
			file_put_contents($candidate_account_Tmp_Path, $tempContent);

			// Execution du script creation des comptes candidats
			$output_candidate_account = shell_exec("selenium-side-runner -c \"goog:chromeOptions.args=[headless,no-sandbox] browserName=chrome\" ".$candidate_account_Tmp_Path." 2>&1");

			if (strpos($output_candidate_account, 'passed') !== false) {
				echo "creation_compte_candidat.side: Le script a été exécuté avec succès! \n\n";
			} else {
				echo "creation_compte_candidat.side: Erreur lors de l'exécution du script.\n\n";
				// Enregistrez les informations dans un fichier
				file_put_contents('erreur_candidate_account.log', '[' . date('Y-m-d H:i:s') . '] ' . $output_candidate_account);
			}

			// Execution du 6eme script
			echo "Vous allez executer le script creation_compte_candidat.side. \n";
			sleep(2);

			// Chemin vers le fichier erreur_candidate_slips.log
			$erreurLogPath = __DIR__.'/erreur_candidate_slips.log';
			// Vérifier si le fichier erreur_candidate_slips.log existe
			if (file_exists($erreurLogPath)) {
				// Supprimer le fichier erreur_candidate_slips.log
				unlink($erreurLogPath);
				echo "Le fichier erreur_candidate_slips.log existant a été supprimé. \n";
			}

			// Lire le contenu
			$sideFilePath = __DIR__.'/script/creation_bordereaux_candidat.side';
			$fileContent = file_get_contents($sideFilePath);

			// Repertoire du fichier creation candidat
			$candidat_Info_Path = __DIR__.'/script/creation_candidat_tmp.side';
			$candidat_Info_Content = file_get_contents($candidat_Info_Path);

			// Extraire les adresses e-mail
			$varMail = '/[a-zA-Z0-9._%+-]+@leboncandidat.fr/';
			preg_match_all($varMail, $candidat_Info_Content, $email_candidat);

			// Afficher les adresses e-mail
			echo "Adresses e-mail trouvées : ";
			foreach ($email_candidat[0] as $mail_candidat) {
				echo $mail_candidat . "\n";
			}
			$MDP = '1234';
			$command = 'expect -c \'
				spawn php /home/anthony/lbc/bin/console frontuser:updatePassword "' . $mail_candidat . '"
				expect -re "Please enter a password for user ' . $mail_candidat . '"
				send "' . $MDP . '\r"
				expect -re "Please CONFIRM password for user ' . $mail_candidat . '"
				send "' . $MDP . '\r"
				expect eof
				\'';
			$result_account = shell_exec($command);
			echo $result_account;
			sleep(2);

			// Nouveau coordonné du candidat
			$newMail = $mail_candidat;
			$newMDP = $MDP;
			$newMonday_Time = 7; //nombre d'heure sur 24h
			$newTuesday_Time = 7;
			$newWednesday_Time = 7;
			$newThursday_Time = 7;
			$newFriday_Time = 7;
			$newSaturday_Time = 7;
			$newSunday_Time = 7;
			$newMonday_Time_Modify = 6; //nombre d'heure sur 24h
			$newTuesday_Time_Modify = 6;
			$newWednesday_Time_Modify = 6;
			$newThursday_Time_Modify = 6;
			$newFriday_Time_Modify = 6;
			$newSaturday_Time_Modify = 6;
			$newSunday_Time_Modify = 6;
			$newPayment_Request = 200;
			$newPayment_Request_Modify = 250;

			// Remplacer un string dans le fichier designé et stocker dans la variable
			$tempContent = str_replace('"value": "Mail"', '"value": "' . $newMail . '"', $fileContent);
			$tempContent = str_replace('"value": "MDP"', '"value": "' . $newMDP . '"', $tempContent);
			$tempContent = str_replace('"value": "value=Monday_Time"', '"value": "value=' . $newMonday_Time . '"', $tempContent);
			$tempContent = str_replace('"value": "value=Tuesday_Time"', '"value": "value=' . $newTuesday_Time . '"', $tempContent);
			$tempContent = str_replace('"value": "value=Wednesday_Time"', '"value": "value=' . $newWednesday_Time . '"', $tempContent);
			$tempContent = str_replace('"value": "value=Thursday_Time"', '"value": "value=' . $newThursday_Time . '"', $tempContent);
			$tempContent = str_replace('"value": "value=Friday_Time"', '"value": "value=' . $newFriday_Time . '"', $tempContent);
			$tempContent = str_replace('"value": "value=Saturday_Time"', '"value": "value=' . $newSaturday_Time . '"', $tempContent);
			$tempContent = str_replace('"value": "value=Sunday_Time"', '"value": "value=' . $newSunday_Time . '"', $tempContent);
			$tempContent = str_replace('"value": "value=Monday_Time_Modify"', '"value": "value=' . $newMonday_Time_Modify . '"', $tempContent);
			$tempContent = str_replace('"value": "value=Tuesday_Time_Modify"', '"value": "value=' . $newTuesday_Time_Modify . '"', $tempContent);
			$tempContent = str_replace('"value": "value=Wednesday_Time_Modify"', '"value": "value=' . $newWednesday_Time_Modify . '"', $tempContent);
			$tempContent = str_replace('"value": "value=Thursday_Time_Modify"', '"value": "value=' . $newThursday_Time_Modify . '"', $tempContent);
			$tempContent = str_replace('"value": "value=Friday_Time_Modify"', '"value": "value=' . $newFriday_Time_Modify . '"', $tempContent);
			$tempContent = str_replace('"value": "value=Saturday_Time_Modify"', '"value": "value=' . $newSaturday_Time_Modify . '"', $tempContent);
			$tempContent = str_replace('"value": "value=Sunday_Time_Modify"', '"value": "value=' . $newSunday_Time_Modify . '"', $tempContent);
			$tempContent = str_replace('"value": "Payment_Request"', '"value": "' . $newPayment_Request . '"', $tempContent);
			$tempContent = str_replace('"value": "Payment_Request_Modify"', '"value": "' . $newPayment_Request_Modify . '"', $tempContent);
			
			// Chemin du fichier temporaire .side
			$candidate_slips_Tmp_Path = __DIR__.'/script/creation_bordereaux_candidat_tmp.side';
			// Enregistrer les modifications dans un fichier temporaire .side
			file_put_contents($candidate_slips_Tmp_Path, $tempContent);
			
			// Execution du script creation des bordereaux candidat
			$output_candidate_slips = shell_exec("selenium-side-runner -c \"goog:chromeOptions.args=[headless,no-sandbox] browserName=chrome\" ".$candidate_slips_Tmp_Path." 2>&1");
				
			if (strpos($output_candidate_slips, 'passed') !== false) {
				echo "creation_bordereaux_candidat.side: Le script a été exécuté avec succès! \n\n";
			} else {
				echo "creation_bordereaux_candidat.side: Erreur lors de l'exécution du script.\n\n";
				// Enregistrez les informations dans un fichier
				file_put_contents('erreur_candidate_slips.log', '[' . date('Y-m-d H:i:s') . '] ' . $output_candidate_slips);
			}

			// Execution du 7eme script
			echo "Vous allez executer le script creation_client.side.\n";
			sleep(2);

			// Chemin vers le fichier erreur_client_account.log
			$erreurLogPath = __DIR__.'/erreur_client_account.log';
			// Vérifier si le fichier erreur_client_account.log existe
			if (file_exists($erreurLogPath)) {
				// Supprimer le fichier erreur_client_account.log
				unlink($erreurLogPath);
				echo "Le fichier erreur_client_account.log existant a été supprimé. \n";
			}

			// Lire le contenu
			$sideFilePath = __DIR__.'/script/creation_compte_client.side';
			$fileContent = file_get_contents($sideFilePath);

			// Lire le contenu success_client.log
			$success_client_Path = __DIR__.'/success_client.log';
			$success_client_Content = file_get_contents($success_client_Path);

			// Rechercher la ligne qui contient L'ID du client est: 
			if (preg_match('/.*L\'ID du client est: (\d+)/', $success_client_Content, $matches)) {
				$ID_Client = intval($matches[1]);
			} else {
				echo "Aucun ID client trouvé dans le fichier.";
			}

			// Remplacer un string dans le fichier designé et stocker dans la variable
			$tempContent = str_replace('"target": "http://local.leboncandidat.fr/app_dev.php/admin/company/ID_Client",', '"target": "http://local.leboncandidat.fr/app_dev.php/admin/company/' . $ID_Client . '",', $fileContent);
			$tempContent = str_replace('"target": "return document.querySelector(\'a[href=\"/app_dev.php/admin/companycontact/update-active/ID_Client\"]\').innerText.includes(\"Désactiver\");"', '"target": "return document.querySelector(\'a[href=\"/app_dev.php/admin/companycontact/update-active/' . $ID_Client . '\"]\').innerText.includes(\"Désactiver\");"', $tempContent);

			// Chemin du fichier temporaire .side
			$client_account_Tmp_Path = __DIR__.'/script/creation_compte_client_tmp.side';
			// Enregistrer les modifications dans un fichier temporaire .side
			file_put_contents($client_account_Tmp_Path, $tempContent);

			// Execution du script creation des comptes clients
			$output_client_account = shell_exec("selenium-side-runner ".$client_account_Tmp_Path." 2>&1");

			if (strpos($output_client_account, 'passed') !== false) {
				echo "creation_compte_client.side: Le script a été exécuté avec succès! \n\n";
			} else {
				echo "creation_compte_client.side: Erreur lors de l'exécution du script.\n\n";
				// Enregistrez les informations dans un fichier
				file_put_contents('erreur_client_account.log', '[' . date('Y-m-d H:i:s') . '] ' . $output_client_account);
			}

			// Execution du 8eme script
			echo "\nVous allez maintenant vous connectez au compte client pour valider les bordereaux candidats. \n";
			sleep(2);

			// Chemin vers le fichier erreur_validation_candidate_slips.log
			$erreurLogPath = __DIR__.'/erreur_validation_candidate_slips.log';
			// Vérifier si le fichier erreur_validation_candidate_slips.log existe
			if (file_exists($erreurLogPath)) {
				// Supprimer le fichier erreur_validation_candidate_slips.log
				unlink($erreurLogPath);
				echo "Le fichier erreur_validation_candidate_slips.log existant a été supprimé. \n";
			}

			// Lire le contenu
			$sideFilePath = __DIR__.'/script/validation_bordereaux_candidat.side';
			$fileContent = file_get_contents($sideFilePath);

			// Lire les info clients
			$client_Info_Path = __DIR__.'/script/creation_client_tmp.side';
			$client_Info_Content = file_get_contents($client_Info_Path);
			$client_Info_Lines = explode("\n", $client_Info_Content);

			// Accéder à l'élément du tableau qui correspond à la ligne 257
			$client_Info_Lines257 = $client_Info_Lines[256];

			// Rechercher la ligne qui contient '"value": "XXX"'
			if (preg_match('/"value":\s*"([^"]+)"/', $client_Info_Lines257, $matches)) {
				$mail_client = $matches[1];
			} else {
				echo "Le mail client n'est pas trouvé dans le fichier";
			}

			$MDP = '1234';
			$command = 'expect -c \'
				spawn php /home/anthony/lbc/bin/console frontuser:updatePassword "' . $mail_client . '"
				expect -re "Please enter a password for user ' . $mail_client . '"
				send "' . $MDP . '\r"
				expect -re "Please CONFIRM password for user ' . $mail_client . '"
				send "' . $MDP . '\r"
				expect eof
			\'';
			$result_account = shell_exec($command);
			echo $result_account;
			sleep(2);

			$tempContent = str_replace('"value": "Mail"', '"value": "' . $mail_client . '"', $fileContent);
			$tempContent = str_replace('"value": "MDP"', '"value": "' . $MDP . '"', $tempContent);

			// Temporaire du fichier .side
			$validation_candidate_slips_Tmp_Path = __DIR__.'/script/validation_bordereaux_candidat_tmp.side';
			// Enregistrer les modifications dans le fichier .side
			file_put_contents($validation_candidate_slips_Tmp_Path, $tempContent);

			// Execution du script validation bordereaux candidat
			$output_validation_candidate_slips = shell_exec("selenium-side-runner -c \"goog:chromeOptions.args=[headless,no-sandbox] browserName=chrome\" ".$validation_candidate_slips_Tmp_Path." 2>&1");
			//shell_exec('selenium-side-runner -c \"goog:chromeOptions.args=[headless,no-sandbox] browserName=chrome\" --base-url=https://preprod.leboncandidat.fr script/creation_candidat.side');

			if (strpos($output_validation_candidate_slips, 'passed') !== false) {
				echo "validation_bordereaux_candidat.side: Le script a été exécuté avec succès! \n\n";
			} else {
				echo "validation_bordereaux_candidat.side: Erreur lors de l'exécution du script.\n\n";
				// Enregistrez les informations dans un fichier
				file_put_contents('erreur_validation_candidate_slips.log', '[' . date('Y-m-d H:i:s') . '] ' . $output_validation_candidate_slips);
			}
			
			$all_Tmp_Files = __DIR__.'/script/*tmp.side';
			shell_exec('rm ' . $all_Tmp_Files . '');
			break;

		case '':
			// Boucle d'interaction avec l'utilisateur
			while (true) {
				// Afficher les options
				afficherMenu();
				// Demander à l'utilisateur de choisir une option
				echo "Choisissez une option (0 pour quitter) : ";

				// Lire l'entrée de l'utilisateur
				$choix = trim(fgets(STDIN));
				// Exécuter l'option choisie
				executerOption($choix);
			}
			break;
		
		default:
			// Erreur de commande
			echo "Commande invalide. Saisissez 'php script.php help' pour afficher la liste des commandes disponibles.\n";
			break;
	}
?>