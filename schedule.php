<?PHP
	class IceWarpSchedule{
		var$base,$lastcount,$Count;
		function __construct($basevalue=0){
			$this->lastcount=0;
			
			if(!$basevalue)$this->base=icewarp_apiobjectcall(0,'','IceWarpServer.ScheduleObject'); else $this->base=$basevalue;
		}

		
		function __destruct(){
			icewarp_apiobjectcall($this->base);
		}

		
		function GetProperty($property){
			$this->UpdateCount(false);
			return icewarp_apiobjectcall($this->base,'GetProperty',$property);
		}

		
		function SetProperty($property,$value){
			$this->UpdateCount(false);
			$result=icewarp_apiobjectcall($this->base,'SetProperty',$property,$value);
			
			if(strcasecmp($property,'s_backup')==0)$this->UpdateCount();
			return$result;
		}

		
		function Add(){
			$result=icewarp_apiobjectcall($this->base,'Add');
			$this->UpdateCount();
			return$result;
		}

		
		function Delete($index){
			$result=icewarp_apiobjectcall($this->base,'Delete',$index);
			$this->UpdateCount();
			return$result;
		}

		
		function Select($index){
			$result=icewarp_apiobjectcall($this->base,'Select',$index);
			$this->UpdateCount();
			return$result;
		}

		
		function UpdateCount($get=true){
			
			if($get)$this->Count=icewarp_apiobjectcall($this->base,'GetCount'); else
			if($this->lastcount<>$this->Count)icewarp_apiobjectcall($this->base,'SetCount',$this->Count);
			$this->lastcount=$this->Count;
		}

	}

	
	class MerakSchedule extends IceWarpSchedule{
	}

	?>