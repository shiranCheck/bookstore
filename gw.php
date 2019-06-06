<?php
	
	class MerakGWAPI{
		var$user,$pass,$authchallenge,$authscheme;
		var$sessid;
		var$groupsessid;
		var$grouplist;
		var$sException;
		function FunctionCall($funcname,$param1="",$param2="",$param3="",$param4=""){
			@$result=icewarp_calendarfunctioncall($funcname,$param1,$param2,$param3,$param4);
			return$result;
		}

		
		function CreateParamLine($array){
			
			if(!is_array($array))return'';
			foreach($array as$k=>$v)$result.=$k."=".rawurlencode($v)."&";
			return$result;
		}

		
		function ParseParamLine($line){
			
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

		
		function Login(){
			global$SERVER_NAME;
			
			if($this->IsConnected())return true;
			
			if(!$this->user){
				$this->sException='No user';
				return false;
			}

			
			if(!$this->pass){
				$this->sException='No pass';
				return false;
			}

			
			if(!$this->authscheme){
				$this->authscheme='PLAIN';
				$this->authchallenge='none';
			}

			return$this->sessid=$this->FunctionCall("authenticate",$this->user,$this->pass,$SERVER_NAME,$this->authscheme."|".$this->authchallenge);
		}

		
		function Logout(){
			
			if($this->sessid)$this->FunctionCall("logoutuser",$this->sessid);
			$this->sessid=null;
			$this->grouplist=null;
		}

		
		function IsConnected(){
			
			if($this->sessid&&$this->FunctionCall("userloggedon",$this->sessid))return true;
			return false;
		}

		
		function OpenGroup($groupid="*"){
			
			if(!$groupid)$groupid=$this->grouplist[0]["GRP_ID"];
			return$this->FunctionCall("opengroup",$this->sessid,$groupid);
		}

		
		function CloseGroup($groupsessid){
			return$this->FunctionCall("closegroup",$groupsessid);
		}

		
		function OpenFolder($groupsessid,$folder){
			return$this->FunctionCall("openfolder",$groupsessid,$folder);
		}

		
		function CloseFolder($groupsessid){
			return$this->CloseGroup($groupsessid);
		}

		
		function GetFolders($groupsessid){
			return$this->ParseParamLine($this->FunctionCall("getfolderlist",$groupsessid));
		}

		
		function GetGroups($groupview=""){
			
			if(!$this->sessid){
				$this->sException='Call login first';
				return false;
			}

			
			if(is_array($this->grouplist))return$this->grouplist;
			return$this->grouplist=$this->ParseParamLine($this->FunctionCall("getgrouplist",$this->sessid,$groupview));
		}

		
		function GetGroupSessid($groupid=""){
			return$this->OpenGroup($groupid);
		}

		
		function GetGroupRights($groupsessid){
			return$this->FunctionCall("GetGroupAccessRights",$groupsessid);
		}

		
		function Unixdate2Str($date){
			$arr=getdate($date);
			return$this->Date2Str($arr["year"],$arr["mon"],$arr["mday"]);
		}

		
		function Date2Str($year,$month,$day){
			return gregoriantojd($month,$day,$year);
		}

		
		function Datestr2Date($date,&$year,&$month,&$day){
			$result=jdtogregorian(intval($date));
			ereg("([0-9]{1,2})/([0-9]{1,2})/([0-9]{4})",$result,$regs);
			$month=$regs[1];
			$day=$regs[2];
			$year=$regs[3];
		}

		
		function Time2Str($hour,$min){
			return$hour*60+$min;
		}

		
		function Timestr2Time($time,&$hour,&$min){
			$hour=floor($time/60);
			$min=$time % 60;
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

	}

	?>