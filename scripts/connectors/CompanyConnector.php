<?php
	class CompanyConnector {
		private $mysqli = NULL;

		public static $TABLE_NAME = "companies";
		public static $COLUMN_ID = "id";
		public static $COLUMN_NAME = "name";
		public static $COLUMN_USERNAME = "username";
		public static $COLUMN_PASSWORDHASH = "passwordHash";
		public static $COLUMN_SALT = "salt";
		public static $COLUMN_POCNAME = "pocName";
		public static $COLUMN_POCEMAIL = "pocEmail";
		public static $COLUMN_POCCONTACTNUMBER = "pocContactNumber";
		public static $COLUMN_METHODS = "methods";


		private $createStatement = NULL;
		private $selectStatement = NULL;
		private $selectAllStatement = NULL;
		private $updateStatement = NULL;
		private $deleteStatement = NULL;
		function __construct($mysqli) {
			if($mysqli->connect_errno > 0){
				die('Unable to connect to database [' . $mysqli->connect_error . ']');
			}

			$this->mysqli = $mysqli;

			$this->createStatement = $mysqli->prepare("INSERT INTO " . CompanyConnector::$TABLE_NAME . "(`" . CompanyConnector::$COLUMN_NAME . "`,`" . CompanyConnector::$COLUMN_USERNAME . "`,`" . CompanyConnector::$COLUMN_PASSWORDHASH . "`,`" . CompanyConnector::$COLUMN_SALT . "`,`" . CompanyConnector::$COLUMN_POCNAME . "`,`" . CompanyConnector::$COLUMN_POCEMAIL . "`,`" . CompanyConnector::$COLUMN_POCCONTACTNUMBER . "`,`" . CompanyConnector::$COLUMN_METHODS . "`) VALUES(?,?,?,?,?,?,?,?,?)");
			$this->selectStatement = $mysqli->prepare("SELECT * FROM " . CompanyConnector::$TABLE_NAME . " WHERE `" . CompanyConnector::$COLUMN_ID . "` = ?");
			$this->selectAllStatement = $mysqli->prepare("SELECT * FROM " . CompanyConnector::$TABLE_NAME);
			$this->deleteStatement = $mysqli->prepare("DELETE FROM " . CompanyConnector::$TABLE_NAME . " WHERE `" . CompanyConnector::$COLUMN_ID . "` = ?");
		}

		public function create($name, $username, $passwordHash, $salt, $pocName, $pocEmail, $pocContactNumber, $methods) {
			$this->createStatement->bind_param("ssssssss", $name, $username, $passwordHash, $salt, $pocName, $pocEmail, $pocContactNumber, $methods);
			return $this->createStatement->execute();
		}

		public function select($id) {
			$this->selectStatement->bind_param("i", $id);
			if(!$this->selectStatement->execute()) return false;

			return true;
		}
		public function selectAll() {
			if(!$this->selectAllStatement->execute()) return false;
			$result = $this->selectAllStatement->get_result();
			$resultArray = $result->fetch_all(MYSQLI_ASSOC);
			return $resultArray;
		}

		public function delete($id) {
			$this->deleteStatement->bind_param("i", $id);
			if(!$this->deleteStatement->execute()) return false;

			return true;
		}
	}
?>