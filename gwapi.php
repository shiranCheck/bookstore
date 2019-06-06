<?php 
	
	class MerakGWAPI{
		public$user;
		public$pass;
		public$sessid;
		public$groupsessid;
		public$grouplist;
		public$sException;
		public
		function __construct(){
		}

		public
		function FunctionCall($funcname,$param1="",$param2="",$param3="",$param4=""){
			@$result=icewarp_calendarfunctioncall($funcname,$param1,$param2,$param3,$param4);
			return$result;
		}

		public
		function CreateParamLine($array){
			
			if(!is_array($array))return'';
			$result='';
			foreach($array as$k=>$v)$result.=$k."=".rawurlencode($v)."&";
			return$result;
		}

		public
		function ParseParamLine($line){
			$result=array();
			
			if(!$line)return array();
			$lines=explode("\r\n",$line);
			$fields=explode("&",strtoupper(trim($lines[0])));
			unset($lines[0]);
			foreach($lines as$row){
				$row=trim($row);
				
				if(!$row)continue;
				$arow=explode("&",$row);
				foreach($fields as$k=>$field){
					
					if($field)$item[$field]=rawurldecode($arow[$k]);
				}

				$result[]=$item;
			}

			return$result;
		}

		public
		function Login(){
			global$SERVER_NAME;
			
			if(!$this->user){
				$this->sException='No user';
				return false;
			}

			
			if(!$this->pass){
				$this->sException='No pass';
				return false;
			}

			
			if($this->IsConnected()&&$this->sessid)return$this->sessid;
			return$this->sessid=$this->FunctionCall("loginuser",$this->user,$this->pass,$SERVER_NAME);
		}

		public
		function Logout(){
			$this->FunctionCall("logoutuser",$this->sessid);
			$this->sessid=null;
			$this->grouplist=null;
			$this->ownerlist=null;
		}

		public
		function IsConnected(){
			
			if($this->sessid&&$this->FunctionCall("userloggedon",$this->sessid)){
				return true;
			}

			return false;
		}

		public
		function OpenGroup($groupid="*"){
			
			if(!$groupid)$groupid=$this->grouplist[0]["GRP_ID"];
			return$this->FunctionCall("opengroup",$this->sessid,$groupid);
		}

		public
		function CloseGroup($groupsessid){
			return$this->FunctionCall("closegroup",$groupsessid);
		}

		public
		function OpenFolder($groupsessid,$folder){
			return$this->FunctionCall("openfolder",$groupsessid,$folder);
		}

		public
		function CloseFolder($groupsessid){
			return$this->FunctionCall('closefolder',$groupsessid);
		}

		public
		function GetFolders($groupsessid){
			return$this->ParseParamLine($this->FunctionCall("getfolderlist",$groupsessid));
		}

		public
		function GetGroups($groupview=""){
			
			if(!$this->sessid){
				$this->sException='Call login first';
				return false;
			}

			return$this->grouplist=$this->ParseParamLine($this->FunctionCall("getgrouplist",$this->sessid,$groupview));
		}

		public
		function GetOwners(){
			
			if(!$this->sessid){
				$this->sException='Call login first';
				return false;
			}

			return$this->ownerlist=$this->ParseParamLine($this->FunctionCall("getownerlist",$this->sessid));
		}

		public
		function GetGroupSessid($groupid=""){
			return$this->OpenGroup($groupid);
		}

		public
		function GetGroupRights($groupsessid){
			return$this->FunctionCall("GetGroupAccessRights",$groupsessid);
		}

		public static
		function unix2calendarDate($unixDate){
			$arr=getdate(intval($unixDate));
			return GregorianToJD($arr["mon"],$arr["mday"],$arr["year"]);
		}

		public static
		function unix2calendarTime($unixDate){
			$arr=getdate($unixDate);
			return$arr["hours"]*60+$arr["minutes"];
		}

		public static
		function calendar2unixTime($calendarDate,$calendarTime){
			$date=JDToGregorian(intval($calendarDate));
			ereg("([0-9]{1,2})/([0-9]{1,2})/([0-9]{4})",$date,$regs);
			$month=$regs[1];
			$day=$regs[2];
			$year=$regs[3];
			
			if(intval($calendarTime)===-1){
				$hour=0;
				$min=0;
				$noTime=true;
			} else {
				$hour=floor($calendarTime/60);
				$min=$calendarTime % 60;
				$noTime=false;
			}

			$result=mktime($hour,$min,0,$month,$day,$year);
			
			if($result===false||$result===-1)$result='';
			return$result;
		}

		public
		function __destruct(){
		}

	}

	?>