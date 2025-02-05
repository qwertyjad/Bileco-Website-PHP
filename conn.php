<?php
define('BASE_URL', 'http://localhost/crud/');

class conn{
	private $hostdb = "localhost";
	private $userdb = "root";
	private $passdb = "";
	private $namedb = "users_database";
	public $conn;

	public function __construct(){
		if (!isset($this->pdo)){
			try {
				$link = new PDO("mysql:host=".$this->hostdb.";dbname=".$this->namedb, $this->userdb, $this->passdb);
				$link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
				$link->exec("SET CHARACTER SET utf8");
				$this->conn = $link;
 
			} catch (PDOException $e){
				die("Failed to connect with Database".$e->getMessage());	
			}
		}
	}
}
?> 
<link rel="icon" href="./assets/favicon.ico" type="image/x-icon">