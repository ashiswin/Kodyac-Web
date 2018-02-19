<?php
	class OTPConnector {
		private $mysqli = NULL;

		public static $TABLE_NAME = "otps";
		public static $COLUMN_ID = "id";
		public static $COLUMN_LINKID = "linkId";
		public static $COLUMN_OTP = "otp";
		public static $COLUMN_CREATEDON = "createdOn";
		public static $COLUMN_USED = "used";

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

			$this->createStatement = $mysqli->prepare("INSERT INTO " . OTPConnector::$TABLE_NAME . "(`" . OTPConnector::$COLUMN_LINKID . "`,`" . OTPConnector::$COLUMN_OTP . "`) VALUES(?,?)");
			$this->selectStatement = $mysqli->prepare("SELECT * FROM " . OTPConnector::$TABLE_NAME . " WHERE `" . OTPConnector::$COLUMN_OTP . "` = ?");
			$this->updateStatement = $mysqli->prepare("UPDATE " . OTPConnector::$TABLE_NAME . " SET `" . OTPConnector::$COLUMN_USED . "` = \"1\" WHERE `" . OTPConnector::$COLUMN_OTP . "` = ?");
			$this->deleteStatement = $mysqli->prepare("DELETE FROM " . OTPConnector::$TABLE_NAME . " WHERE `" . OTPConnector::$COLUMN_ID . "` = ?");
		}

		public function create($linkId, $otp) {
			$this->createStatement->bind_param("is", $linkId, $otp);
			return $this->createStatement->execute();
		}

		public function select($otp) {
			$this->selectStatement->bind_param("s", $otp);
			if(!$this->selectStatement->execute()) return false;
			
			$result = $this->selectStatement->get_result();
			if(!$result) return false;
			$otp = $result->fetch_assoc();
			
			$this->selectStatement->free_result();
			
			return $otp;
		}
		
		public function setUsed($otp) {
			$this->updateStatement->bind_param("s", $otp);
			if(!$this->selectStatement->execute()) return false;
			
			return true;
		}
		
		public function delete($id) {
			$this->deleteStatement->bind_param("i", $id);
			if(!$this->deleteStatement->execute()) return false;

			return true;
		}
	}
?>
