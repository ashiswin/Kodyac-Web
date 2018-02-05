<?php
	class LinkConnector {
		private $mysqli = NULL;

		public static $TABLE_NAME = "links";
		public static $COLUMN_ID = "id";
		public static $COLUMN_COMPANYID = "companyId";


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

			$this->createStatement = $mysqli->prepare("INSERT INTO " . LinkConnector::$TABLE_NAME . "(`" . LinkConnector::$COLUMN_COMPANYID . "`) VALUES(?,?)");
			$this->selectStatement = $mysqli->prepare("SELECT * FROM " . LinkConnector::$TABLE_NAME . " WHERE `" . LinkConnector::$COLUMN_ID . "` = ?");
			$this->selectAllStatement = $mysqli->prepare("SELECT * FROM " . LinkConnector::$TABLE_NAME);
			$this->deleteStatement = $mysqli->prepare("DELETE FROM " . LinkConnector::$TABLE_NAME . " WHERE `" . LinkConnector::$COLUMN_ID . "` = ?");
		}

		public function create($companyId) {
			$this->createStatement->bind_param("i", $companyId);
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