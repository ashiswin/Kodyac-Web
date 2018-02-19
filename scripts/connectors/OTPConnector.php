<?php
	class OTPConnector {
		private $mysqli = NULL;

		public static $TABLE_NAME = "otps";
		public static $COLUMN_ID = "id";
		public static $COLUMN_LINKID = "linkId";
		public static $COLUMN_OTP = "otp";
		public static $COLUMN_CREATEDON = "createdOn";


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

			$this->createStatement = $mysqli->prepare("INSERT INTO " . CompanyConnector::$TABLE_NAME . "(`" . CompanyConnector::$COLUMN_LINKID . "`,`" . CompanyConnector::$COLUMN_OTP . "`) VALUES(?,?)");
			$this->selectStatement = $mysqli->prepare("SELECT * FROM " . CompanyConnector::$TABLE_NAME . " WHERE `" . CompanyConnector::$COLUMN_LINKID . "` = ?");
			$this->deleteStatement = $mysqli->prepare("DELETE FROM " . CompanyConnector::$TABLE_NAME . " WHERE `" . CompanyConnector::$COLUMN_ID . "` = ?");
		}

		public function create($linkId, $otp) {
			$this->createStatement->bind_param("is", $linkId, $otp);
			return $this->createStatement->execute();
		}

		public function select($linkId) {
			$this->selectStatement->bind_param("i", $linkId);
			if(!$this->selectStatement->execute()) return false;
			
			$result = $this->selectStatement->get_result();
			if(!$result) return false;
			$otp = $result->fetch_assoc();
			
			$this->selectStatement->free_result();
			
			return $otp;
		}

		public function delete($id) {
			$this->deleteStatement->bind_param("i", $id);
			if(!$this->deleteStatement->execute()) return false;

			return true;
		}
	}
?>
