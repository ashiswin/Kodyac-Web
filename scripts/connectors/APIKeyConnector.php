<?php
	class APIKeyConnector {
		private $mysqli = NULL;

		public static $TABLE_NAME = "apikeys";
		public static $COLUMN_ID = "id";
		public static $COLUMN_COMPANYID = "companyId";
		public static $COLUMN_NAME = "name";
		public static $COLUMN_APIKEY = "apiKey";
		public static $COLUMN_REQUESTCOUNT = "requestCount";
		public static $COLUMN_CREATEDON = "createdOn";
		public static $COLUMN_ISDELETED = "isDeleted";

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

			$this->createStatement = $mysqli->prepare("INSERT INTO " . APIKeyConnector::$TABLE_NAME . "(`" . APIKeyConnector::$COLUMN_COMPANYID . "`,`" . APIKeyConnector::$COLUMN_NAME . "`,`" . APIKeyConnector::$COLUMN_APIKEY . "`) VALUES(?,?,?)");
			$this->selectStatement = $mysqli->prepare("SELECT * FROM " . APIKeyConnector::$TABLE_NAME . " WHERE `" . APIKeyConnector::$COLUMN_ID . "` = ?");
			$this->selectAllStatement = $mysqli->prepare("SELECT * FROM " . APIKeyConnector::$TABLE_NAME);
			$this->selectByKeyStatement = $mysqli->prepare("SELECT * FROM " . APIKeyConnector::$TABLE_NAME . " WHERE `" . APIKeyConnector::$COLUMN_APIKEY . "` = ?");
			$this->addRequestStatement = $mysqli->prepare("UPDATE " . APIKeyConnector::$TABLE_NAME . " SET `" . APIKeyConnector::$COLUMN_REQUESTCOUNT . "` = `" . APIKeyConnector::$COLUMN_REQUESTCOUNT . "` + 1 WHERE `" . APIKeyConnector::$COLUMN_ID . "` = ?");
			$this->deleteStatement = $mysqli->prepare("DELETE FROM " . APIKeyConnector::$TABLE_NAME . " WHERE `" . APIKeyConnector::$COLUMN_ID . "` = ?");
		}

		public function create($companyId, $name, $apiKey) {
			$this->createStatement->bind_param("sss", $companyId, $name, $apiKey);
			return $this->createStatement->execute();
		}

		public function select($id) {
			$this->selectStatement->bind_param("i", $id);
			if(!$this->selectStatement->execute()) return false;
			
			$result = $this->selectStatement->get_result();
			if(!$result) return false;
			$apiKey = $result->fetch_assoc();
			
			$this->selectStatement->free_result();
			
			return $apiKey;
		}
		
		public function selectByKey($key) {
			$this->selectByKeyStatement->bind_param("s", $key);
			if(!$this->selectByKeyStatement->execute()) return false;
			
			$result = $this->selectByKeyStatement->get_result();
			if(!$result) return false;
			$apiKey = $result->fetch_assoc();
			
			$this->selectByKeyStatement->free_result();
			
			return $apiKey;
		}
		
		public function selectAll() {
			if(!$this->selectAllStatement->execute()) return false;
			$result = $this->selectAllStatement->get_result();
			$resultArray = $result->fetch_all(MYSQLI_ASSOC);
			return $resultArray;
		}
		
		public function addRequest($id) {
			$this->addRequestStatement->bind_param("i", $id);
			return $this->addRequestStatement->execute();
		}
		
		public function delete($id) {
			$this->deleteStatement->bind_param("i", $id);
			if(!$this->deleteStatement->execute()) return false;

			return true;
		}
	}
?>
