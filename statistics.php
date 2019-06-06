<?PHP
	class IceWarpStatistics{
		var$base;
		function __construct(){
			$this->base=icewarp_apiobjectcall(0,'','IceWarpServer.ServiceObject');
		}

		
		function __destruct(){
			icewarp_apiobjectcall($this->base);
		}

		
		function GetProperty($property){
			return icewarp_apiobjectcall($this->base,'GetProperty',$property);
		}

		
		function IsRunning($service){
			return icewarp_apiobjectcall($this->base,'IsRunning',$service);
		}

		
		function Poll($service){
			return icewarp_apiobjectcall($this->base,'Poll',$service);
		}

		
		function Reset($service){
			return icewarp_apiobjectcall($this->base,'Reset',$service);
		}

		
		function Start($service){
			return icewarp_apiobjectcall($this->base,'Start',$service);
		}

		
		function Stop($service){
			return icewarp_apiobjectcall($this->base,'Stop',$service);
		}

		
		function GetSessions($service,$history){
			return icewarp_apiobjectcall($this->base,'GetSessions',$service,$history);
		}

	}

	
	class MerakStatistics extends IceWarpStatistics{
	}

	?>