<?php
	class Connexion{
		//TODO test available values for $dbName
		public function getDB(){
			$db = null;
			try{
			    $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION; 
			    $db = new PDO('mysql:host=parionsppriv.mysql.db;dbname=parionsppriv', 'parionsppriv', 'thoke69PP',array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			}
			catch (Exception $e){
			    try{
					$db = new PDO('mysql:host=localhost;dbname=parionspotes',  'root', '', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
				}
				catch (Exception $e){
					die('Nous rencontrons un probl&egrave;me en raison d\'un trop gros trafic... Merci de revenir dans une petite minute...');
				}    
			}
			return $db;
		}
	}
?>