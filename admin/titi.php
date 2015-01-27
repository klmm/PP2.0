<?php
	class Connexion{
		//TODO test available values for $dbName
		public function getDB(){
			$db = null;
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION; 
				$db = new PDO('mysql:host=mysql51-113.bdb;dbname=parionspotes', 'parionspotes', 'thoke69pp',array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			}
			catch (Exception $e){
				try{
					$db = new PDO('mysql:host=mysql51-113.bdb;dbname=parionspotes', 'parionspotes', 'thoke69pp',array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
				}
				catch (Exception $e){
					die('Erreur : ' . $e->getMessage());
				}
			}
			return $db;
			break;			
		}
	}
?>