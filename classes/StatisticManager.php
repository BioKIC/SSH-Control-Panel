<?php
include_once($SERVER_ROOT.'/config/dbconnection.php');

class StatisticManager{

	private $conn;
	private $activeConn;
	private $portalMeta = array();

	function __construct() {
		$this->conn = MySQLiConnectionFactory::getCon('central', 'central_db');
	}

	function __destruct(){
		if(!($this->conn === false)) $this->conn->close();
	}

	public function buildStats($postArr){
		$searchVarArr = array('portalid','category','geoscope1','geoscope2','taxonscope1','taxonscope2');
		$sqlWhere = '';
		if($searchVarArr){
			foreach($searchVarArr as $field){
				if(array_key_exists($field,$postArr)){
					$sqlWhere .= 'AND '.$field.' = "'.$this->cleanInStr($postArr[$field]).'" ';
				}
			}
		}
		$sql = 'SELECT portalID, projectName, projectDescription, projectLead, projectLeadEmail, projectUrl, tcnUrl, projectStatus, category, '.
			'geoScope1, geoScope2, taxonScope1, taxonScope2, serverName ,schemaName, lastStatTimestamp, initialTimestamp FROM portaldb ';
		if($sqlWhere) $sql .= 'WHERE '.substr($sqlWhere,4);
		$rs = $this->conn->query($sql);
		while($r = $rs->fetch_object()){
			$this->portalMeta[$r->portalID]['projectName'] = $r->projectName;
			$this->portalMeta[$r->portalID]['projectDescription'] = $r->projectDescription;
			$this->portalMeta[$r->portalID]['projectLead'] = $r->projectLead;
			$this->portalMeta[$r->portalID]['projectLeadEmail'] = $r->projectLeadEmail;
			$this->portalMeta[$r->portalID]['projectUrl'] = $r->projectUrl;
			$this->portalMeta[$r->portalID]['projectStatus'] = $r->projectStatus;
			$this->portalMeta[$r->portalID]['category'] = $r->category;
			$this->portalMeta[$r->portalID]['geoScope1'] = $r->geoScope1;
			$this->portalMeta[$r->portalID]['geoScope2'] = $r->geoScope2;
			$this->portalMeta[$r->portalID]['taxonScope1'] = $r->taxonScope1;
			$this->portalMeta[$r->portalID]['taxonScope2'] = $r->taxonScope2;
			$this->portalMeta[$r->portalID]['serverName'] = $r->serverName;
			$this->portalMeta[$r->portalID]['schemaName'] = $r->schemaName;
			$this->portalMeta[$r->portalID]['lastStatTimestamp'] = $r->lastStatTimestamp;

		}
		$rs->free();
		if($this->portalMeta){
			$this->setFrontendCounts();
			$this->setPortalStats();
		}
		else{
			echo '<div>No portal records returned</div>';
		}
	}

	//Stat functions
	private function setFrontendCounts(){
		if($this->portalMeta){
			$sql = 'SELECT portalID, count(*) as cnt FROM frontend WHERE portalID IN('.implode(',',array_keys($this->portalMeta)).') GROUP BY portalID';
			$rs = $this->conn->query($sql);
			while($r = $rs->fetch_object()){
				$this->portalMeta[$r->portalID]['frontendCnt'] = $r->cnt;
			}
			$rs->free();
		}
	}

	private function setPortalStats(){
		foreach($this->portalMeta as $portalID => $portalArr){
			$this->activeConn = MySQLiConnectionFactory::getCon($portalArr['serverName'], $portalArr['schemaName']);
			$this->portalMeta[$portalID]['collCnt'] = $this->getCollectionCount();
			$this->portalMeta[$portalID]['occurCnt'] = $this->getOccurrenceCount();
			$this->portalMeta[$portalID]['imageCnt'] = $this->getImageCount();
			$this->portalMeta[$portalID]['checklistCnt'] = $this->getChecklistCount();
			$this->portalMeta[$portalID]['userCnt'] = $this->getUserCount();
			$editCnt = $this->getEditCount();
			$this->portalMeta[$portalID]['editCnt'] = $editCnt;
			$editChange = $editCnt/$this->getEditPriorCount();
			if($editCnt){
				if($editChange > 1) $this->portalMeta[$portalID]['editChange'] = ($editCnt*100).' edit increase from last year';
				else $this->portalMeta[$portalID]['editChange'] = (100/$editCnt).' edit decrease from last year';
			}
			$this->activeConn->close();
		}
	}

	private function getCollectionCount(){
		$sql = 'SELECT collType, managementType, COUNT(*) as cnt FROM omcollections GROUP BY colltype DESC, managementtype';
		return $this->getManagementCount($sql);
	}

	private function getOccurrenceCount(){
		$sql = 'SELECT c.collType, c.managementType, COUNT(o.occid) as cnt FROM omcollections c INNER JOIN omoccurrences o ON c.collid = o.collid GROUP BY c.colltype DESC, c.managementtype';
		return $this->getManagementCount($sql);
	}

	private function getImageCount(){
		$sql = 'SELECT collType, managementType, count(i.occid) as cnt
			FROM omcollections c INNER JOIN omoccurrences o ON c.collid = o.collid
			INNER JOIN images i ON o.occid = i.occid
			GROUP BY c.colltype, c.managementtype';
		return $this->getManagementCount($sql);
	}

	private function getManagementCount($sql){
		$retArr = array();
		if($this->activeConn){
			$rs = $this->activeConn->query($sql);
			$total = 0;
			while($r = $rs->fetch_object()){
				$retArr[$r->collType][$r->managementType] = $r->cnt;
				$total += $r->cnt;
			}
			$retArr['total'] = $total;
			$rs->free();
		}
		return $retArr;
	}

	private function getChecklistCount(){
		$sql = 'SELECT count(DISTINCT c.clid) as cnt FROM fmchecklists c INNER JOIN fmchklsttaxalink l ON c.clid = l.clid';
		return $this->getCount($sql);
	}

	private function getUserCount(){
		$sql = 'SELECT COUNT(DISTINCT uid) as cnt FROM userroles';
		return $this->getCount($sql);
	}

	private function getEditCount(){
		$sql = 'SELECT avg(intab.cnt) as cnt FROM (SELECT month(initialtimestamp) as mo, COUNT(DISTINCT occid) as cnt
			FROM omoccuredits WHERE initialtimestamp BETWEEN (CURDATE() - interval 1 year) AND CURDATE() GROUP BY mo) intab';
		return $this->getCount($sql);
	}

	private function getEditPriorCount(){
		$sql = 'SELECT avg(intab.cnt) as cnt FROM (SELECT month(initialtimestamp) as mo, COUNT(DISTINCT occid) as cnt
			FROM omoccuredits WHERE initialtimestamp BETWEEN (CURDATE() - interval 2 year) AND (CURDATE() - interval 1 year) GROUP BY mo) intab';
		return $this->getCount($sql);
	}

	private function getCount($sql){
		$cnt = 0;
		$rs = $this->activeConn->query($sql);
		while($r = $rs->fetch_object()){
			$cnt = $r->cnt;
		}
		$rs->free();
		return $cnt;
	}

	//Metadata return functions
	public function getFrontendMetaByID($frontendID){
		$retArr = false;
		if(preg_match('/^[\d,]+$/', $frontendID)) $retArr = $this->getFrontendMeta('portalID IN('.$frontendID.')');
		return $retArr;
	}

	public function getFrontendMetaByPortal($portalID){
		$retArr = false;
		if(is_numeric($portalID)) $retArr = $this->getFrontendMeta('portalID = '.$portalID);
		return $retArr;
	}

	private function getFrontendMeta($condStr){
		$retArr = array();
		$sql = 'SELECT frontendID, portalID, acronym, title, frontendStatus, category, scope, manager, managerEmail, primaryLead, primaryLeadEmail, portalUrl, notes '.
			'FROM frontend WHERE '.$condStr;
		$rs = $this->conn->query($sql);
		while($r = $rs->fetch_object()){
			$retArr[$r->frontendID]['acronym'] = $r->acronym;
			$this->portalMeta[$r->portalID]['frontend'][$r->frontendID]['title'] = $r->title;
			$this->portalMeta[$r->portalID]['frontend'][$r->frontendID]['status'] = $r->frontendStatus;
			$this->portalMeta[$r->portalID]['frontend'][$r->frontendID]['category'] = $r->category;
			$this->portalMeta[$r->portalID]['frontend'][$r->frontendID]['scope'] = $r->scope;
			$this->portalMeta[$r->portalID]['frontend'][$r->frontendID]['manager'] = $r->manager;
			$this->portalMeta[$r->portalID]['frontend'][$r->frontendID]['managerEmail'] = $r->managerEmail;
			$this->portalMeta[$r->portalID]['frontend'][$r->frontendID]['primaryLead'] = $r->primaryLead;
			$this->portalMeta[$r->portalID]['frontend'][$r->frontendID]['primaryLeadEmail'] = $r->primaryLeadEmail;
			$this->portalMeta[$r->portalID]['frontend'][$r->frontendID]['portalUrl'] = $r->portalUrl;
			$this->portalMeta[$r->portalID]['frontend'][$r->frontendID]['notes'] = $r->notes;
		}
		$rs->free();
		return $retArr;
	}

	//Setters and getter
	public function getPortalMeta(){
		return $this->portalMeta;
	}


	//Misc functions

	private function cleanInStr($str){
		$newStr = trim($str);
		$newStr = preg_replace('/\s\s+/', ' ',$newStr);
		$newStr = $this->conn->real_escape_string($newStr);
		return $newStr;
	}
}
?>