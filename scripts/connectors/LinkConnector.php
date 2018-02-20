<?php
	class LinkConnector {
		private $mysqli = NULL;

		public static $TABLE_NAME = "links";
		public static $COLUMN_ID = "id";
		public static $COLUMN_COMPANYID = "companyId";
		public static $COLUMN_STATUS = "status";
		public static $COLUMN_APIKEY = "apiKey";

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

			$this->createStatement = $mysqli->prepare("INSERT INTO " . LinkConnector::$TABLE_NAME . "(`" . LinkConnector::$COLUMN_COMPANYID . "`,`" . LinkConnector::$COLUMN_STATUS . "`,`" . LinkConnector::$COLUMN_APIKEY . "`) VALUES(?,?,?)");
			$this->selectStatement = $mysqli->prepare("SELECT * FROM " . LinkConnector::$TABLE_NAME . " INNER JOIN profiles ON links.id=profiles.linkId WHERE " . LinkConnector::$TABLE_NAME . "." . LinkConnector::$COLUMN_ID . " = ?");
			$this->selectByCompanyStatement = $mysqli->prepare("SELECT * FROM " . LinkConnector::$TABLE_NAME . " INNER JOIN profiles ON links.id=profiles.linkId WHERE `" . LinkConnector::$COLUMN_COMPANYID . "` = ?");
			$this->selectByStatusStatement = $mysqli->prepare("SELECT * FROM " . LinkConnector::$TABLE_NAME . " INNER JOIN profiles ON links.id=profiles.linkId WHERE `" . LinkConnector::$COLUMN_STATUS . "` = ?");
			$this->selectAllStatement = $mysqli->prepare("SELECT * FROM " . LinkConnector::$TABLE_NAME . " INNER JOIN profiles ON links.id=profiles.linkId");
			$this->setStatusStatement = $mysqli->prepare("UPDATE " . LinkConnector::$TABLE_NAME . " SET `" . LinkConnector::$COLUMN_STATUS . "` = ? WHERE `" . LinkConnector::$COLUMN_ID . "` = ?");
			$this->deleteStatement = $mysqli->prepare("DELETE FROM " . LinkConnector::$TABLE_NAME . " WHERE `" . LinkConnector::$COLUMN_ID . "` = ?");
		}

		public function create($companyId, $apiKey) {
			$status = "requested";
			$this->createStatement->bind_param("iss", $companyId, $status, $apiKey);
			return $this->createStatement->execute();
		}

		public function select($id) {
			$this->selectStatement->bind_param("i", $id);
			if(!$this->selectStatement->execute()) return false;

			$result = $this->selectStatement->get_result();
			if(!$result) return false;
			$link = $result->fetch_assoc();
			
			$this->selectStatement->free_result();
			
			return $link;
		}
		
		public function selectByCompany($companyId) {
			$this->selectByCompanyStatement->bind_param("i", $companyId);
			if(!$this->selectByCompanyStatement->execute()) return false;
			$result = $this->selectByCompanyStatement->get_result();
			$resultArray = $result->fetch_all(MYSQLI_ASSOC);
			return $resultArray;
		}
		
		public function selectByStatus($status) {
			$this->selectByStatusStatement->bind_param("s", $status);
			if(!$this->selectByStatusStatement->execute()) return false;
			$result = $this->selectByStatusStatement->get_result();
			$resultArray = $result->fetch_all(MYSQLI_ASSOC);
			return $resultArray;
		}
		
		public function selectAll() {
			if(!$this->selectAllStatement->execute()) return false;
			$result = $this->selectAllStatement->get_result();
			$resultArray = $result->fetch_all(MYSQLI_ASSOC);
			return $resultArray;
		}
		
		public function setStatus($id, $status) {
			$this->setStatusStatement->bind_param("si", $status, $id);
			return $this->setStatusStatement->execute();
		}
		
		public function delete($id) {
			$this->deleteStatement->bind_param("i", $id);
			if(!$this->deleteStatement->execute()) return false;

			return true;
		}
	}
?>
