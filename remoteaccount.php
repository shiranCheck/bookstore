<?PHP
	class IceWarpRemoteAccount{
		var$base;
		var$LastErr;
		function __construct(){
			$this->base=icewarp_apiobjectcall(0,'','IceWarpServer.RemoteAccountObject');
		}

		
		function __destruct(){
			icewarp_apiobjectcall($this->base);
		}

		
		function New_(){
			return icewarp_apiobjectcall($this->base,'New');
		}

		
		function Open($account){
			return icewarp_apiobjectcall($this->base,'Open',$account);
		}

		
		function Save(){
			$result=icewarp_apiobjectcall($this->base,'Save');
			$this->LastErr=icewarp_apiobjectcall($this->base,'LastErr');
			return$result;
		}

		
		function Delete(){
			return icewarp_apiobjectcall($this->base,'Delete');
		}

		
		function GetProperty($property){
			return icewarp_apiobjectcall($this->base,'GetProperty',$property);
		}

		
		function SetProperty($property,$value){
			return icewarp_apiobjectcall($this->base,'SetProperty',$property,$value);
		}

		
		function DeleteIndex($idx){
			return icewarp_apiobjectcall($this->base,'DeleteIndex',$idx);
		}

		
		function Count(){
			return icewarp_apiobjectcall($this->base,'Count');
		}

		
		function GetSchedule($property){
			require_once('schedule.php');
			$base=icewarp_apiobjectcall($this->base,'GetSchedule',$property);
			$scheduleobject=new MerakSchedule($base);
			$scheduleobject->UpdateCount();
			return$scheduleobject;
		}

		
		function SetSchedule($property,$scheduleobject){
			return icewarp_apiobjectcall($this->base,'SetSchedule',$property,$scheduleobject->base);
		}

		
		function ApplyTemplate($template=''){
			return icewarp_apiobjectcall($this->base,'ApplyTemplate',$template);
		}

	}

	
	class MerakRemoteAccount extends IceWarpRemoteAccount{
	}

	?>