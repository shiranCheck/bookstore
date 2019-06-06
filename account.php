<?PHP
	class IceWarpAccount{
		var$base;
		var$EmailAddress;
		var$Domain;
		var$LastErr;
		function __construct(){
			$this->base=icewarp_apiobjectcall(0,'','IceWarpServer.AccountObject');
		}

		
		function __destruct(){
			icewarp_apiobjectcall($this->base);
		}

		
		function _GetData(){
			$this->EmailAddress=icewarp_apiobjectcall($this->base,'EmailAddress');
			$this->Domain=icewarp_apiobjectcall($this->base,'Domain');
		}

		
		function GetProperty($property){
			return icewarp_apiobjectcall($this->base,'GetProperty',$property);
		}

		
		function SetProperty($property,$value){
			return icewarp_apiobjectcall($this->base,'SetProperty',$property,$value);
		}

		
		function New_($account){
			$ret=icewarp_apiobjectcall($this->base,'New',$account);
			$this->_GetData();
			return$ret;
		}

		
		function Open($account){
			$ret=icewarp_apiobjectcall($this->base,'Open',$account);
			$this->LastErr=icewarp_apiobjectcall($this->base,'LastErr');
			
			if($ret)$this->_GetData();
			return$ret;
		}

		
		function Save(){
			$result=icewarp_apiobjectcall($this->base,'Save');
			$this->LastErr=icewarp_apiobjectcall($this->base,'LastErr');
			return$result;
		}

		
		function Delete(){
			return icewarp_apiobjectcall($this->base,'Delete');
		}

		
		function AuthenticateUser($username,$password,$ip){
			$ret=icewarp_apiobjectcall($this->base,'AuthenticateUser',$username,$password,$ip);
			
			if($ret)$this->_GetData();
			return$ret;
		}

		
		function AuthenticateUserHash($username,$hash,$ip,$method,$flags=0){
			$ret=icewarp_apiobjectcall($this->base,'AuthenticateUserHash',$username,$hash,$ip,$method,$flags);
			
			if($ret)$this->_GetData();
			return$ret;
		}

		
		function ValidateUser($accounttype=-1){
			return icewarp_apiobjectcall($this->base,'ValidateUser',$accounttype);
		}

		
		function FindInitQuery($domain,$query=''){
			return icewarp_apiobjectcall($this->base,'FindInitQuery',$domain,$query);
		}

		
		function FindNext(){
			$ret=icewarp_apiobjectcall($this->base,'FindNext');
			$this->_GetData();
			return$ret;
		}

		
		function FindDone(){
			return icewarp_apiobjectcall($this->base,'FindDone');
		}

		
		function CanCreateMailbox($alias,$mailbox,$password,$domain){
			return icewarp_apiobjectcall($this->base,'CanCreateMailbox',$alias,$mailbox,$password,$domain);
		}

		
		function GetUserGroups($user){
			return icewarp_apiobjectcall($this->base,'GetUserGroups',$user);
		}

		
		function ApplyTemplate($template=''){
			return icewarp_apiobjectcall($this->base,'ApplyTemplate',$template);
		}

	}

	
	class MerakAccount extends IceWarpAccount{
	}

	?>