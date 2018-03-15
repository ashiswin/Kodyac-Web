<?php
	class ProfileConnector {
		private $mysqli = NULL;

		public static $TABLE_NAME = "profiles";
		public static $COLUMN_ID = "id";
		public static $COLUMN_LINKID = "linkId";
		public static $COLUMN_NAME = "name";
		public static $COLUMN_ADDRESS = "address";
		public static $COLUMN_NRIC = "nric";
		public static $COLUMN_CONTACT = "contact";
		public static $COLUMN_NATIONALITY = "nationality";
		public static $COLUMN_DOB = "dob";
		public static $COLUMN_SEX = "sex";
		public static $COLUMN_RACE = "race";

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

			$this->createStatement = $mysqli->prepare("INSERT INTO " . ProfileConnector::$TABLE_NAME . "(`" . ProfileConnector::$COLUMN_LINKID . "`,`" . ProfileConnector::$COLUMN_NAME . "`,`" . ProfileConnector::$COLUMN_ADDRESS . "`,`" . ProfileConnector::$COLUMN_NRIC . "`,`" . ProfileConnector::$COLUMN_CONTACT . "`,`" . ProfileConnector::$COLUMN_NATIONALITY . "`,`" . ProfileConnector::$COLUMN_DOB . "`, `" . ProfileConnector::$COLUMN_SEX . "`, `" . ProfileConnector::$COLUMN_RACE . "`) VALUES(?,?,?,?,?,?,?,?,?)");
			$this->selectStatement = $mysqli->prepare("SELECT * FROM " . ProfileConnector::$TABLE_NAME . " WHERE `" . ProfileConnector::$COLUMN_ID . "` = ?");
			$this->selectByLinkStatement = $mysqli->prepare("SELECT * FROM " . ProfileConnector::$TABLE_NAME . " WHERE `" . ProfileConnector::$COLUMN_LINKID . "` = ?");
			$this->selectAllStatement = $mysqli->prepare("SELECT * FROM " . ProfileConnector::$TABLE_NAME);
			$this->updateContactStatement = $mysqli->prepare("UPDATE " . ProfileConnector::$TABLE_NAME . " SET `" . ProfileConnector::$COLUMN_CONTACT . "` = ? WHERE `" . ProfileConnector::$COLUMN_LINKID . "` = ?");
			$this->updateOtherInfoStatement = $mysqli->prepare("UPDATE " . ProfileConnector::$TABLE_NAME . " SET `" . ProfileConnector::$COLUMN_NAME . "`=?, `" . ProfileConnector::$COLUMN_ADDRESS . "`=?, `" . ProfileConnector::$COLUMN_NRIC . "`=?, `" . ProfileConnector::$COLUMN_NATIONALITY . "`=?, `" . ProfileConnector::$COLUMN_DOB . "`=?, `" . ProfileConnector::$COLUMN_SEX . "`=?, `" . ProfileConnector::$COLUMN_RACE . "`=? WHERE `" . ProfileConnector::$COLUMN_LINKID . "` = ?");
			$this->deleteStatement = $mysqli->prepare("DELETE FROM " . ProfileConnector::$TABLE_NAME . " WHERE `" . ProfileConnector::$COLUMN_ID . "` = ?");
		}

		public function create($linkId, $name, $address, $nric, $contact, $nationality, $dob) {
			$this->createStatement->bind_param("issssss", $linkId, $name, $address, $nric, $contact, $nationality, $dob);
			return $this->createStatement->execute();
		}

		public function select($id) {
			$this->selectStatement->bind_param("i", $id);
			if(!$this->selectStatement->execute()) return false;

			$result = $this->selectStatement->get_result();
			if(!$result) return false;
			$profile = $result->fetch_assoc();
			
			$this->selectStatement->free_result();
			
			return $profile;
		}
		
		public function selectByLink($linkId) {
			$this->selectByLinkStatement->bind_param("i", $linkId);
			if(!$this->selectByLinkStatement->execute()) return false;

			$result = $this->selectByLinkStatement->get_result();
			if(!$result) return false;
			$profile = $result->fetch_assoc();
			
			$this->selectByLinkStatement->free_result();
			
			return $profile;
		}
		public function selectAll() {
			if(!$this->selectAllStatement->execute()) return false;
			$result = $this->selectAllStatement->get_result();
			$resultArray = $result->fetch_all(MYSQLI_ASSOC);
			return $resultArray;
		}
		
		public function updateContact($linkId, $number) {
			$this->updateContactStatement->bind_param("si", $number, $linkId);
			return $this->updateContactStatement->execute();
		}
		
		public function updateOtherInfo($linkId, $name, $address, $nric, $nationality, $dob) {
			$this->updateOtherInfoStatement->bind_param("sssssi", $name, $address, $nric, $nationality, $dob, $linkId);
			return $this->updateOtherInfoStatement->execute();
		}
		
		public function delete($id) {
			$this->deleteStatement->bind_param("i", $id);
			if(!$this->deleteStatement->execute()) return false;

			return true;
		}
	}
?>
