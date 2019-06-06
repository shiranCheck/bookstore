<?PHP
	class IceWarpDomain{
		var$base;
		var$IPAddress;
		var$Name;
		var$LastErr;
		function __construct(){
			$this->base=icewarp_apiobjectcall(0,'','IceWarpServer.DomainObject');
		}

		
		function __destruct(){
			icewarp_apiobjectcall($this->base);
		}

		
		function _GetData(){
			$this->IPAddress=icewarp_apiobjectcall($this->base,'IPAddress');
			$this->Name=icewarp_apiobjectcall($this->base,'Name');
		}

		
		function _FormatEmail($email){
			$result=$email;
			
			if($this->Name)
			if(!strpos($result,'@'))$result=$result.'@'.$this->Name;
			return$result;
		}

		
		function New_($domain){
			$ret=icewarp_apiobjectcall($this->base,'New',$domain);
			$this->_GetData();
			return$ret;
		}

		
		function Open($domain){
			$ret=icewarp_apiobjectcall($this->base,'Open',$domain);
			$this->LastErr=icewarp_apiobjectcall($this->base,'LastErr');
			
			if($ret)$this->_GetData();
			return$ret;
		}

		
		function Save(){
			icewarp_apiobjectcall($this->base,'IPAddress',$this->IPAddress);
			icewarp_apiobjectcall($this->base,'Name',$this->Name);
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

		
		function GetAccountCount(){
			return icewarp_apiobjectcall($this->base,'GetAccountCount');
		}

		
		function GetAccountList(){
			return icewarp_apiobjectcall($this->base,'GetAccountList');
		}

		
		function GetAccount($account){
			return icewarp_apiobjectcall($this->base,'GetAccount',$account);
		}

		
		function DeleteAccount($account){
			return icewarp_apiobjectcall($this->base,'DeleteAccount',$account);
		}

		
		function OpenAccount($account){
			require_once('account.php');
			$accountobject=new MerakAccount();
			
			if($accountobject->Open($this->_FormatEmail($account)))return$accountobject;
			return false;
		}

		
		function NewAccount($account){
			require_once('account.php');
			$accountobject=new MerakAccount();
			
			if($accountobject->New_($this->_FormatEmail($account)))return$accountobject;
			return false;
		}

		
		function ApplyTemplate($template=''){
			return icewarp_apiobjectcall($this->base,'ApplyTemplate',$template);
		}

	}

	
	class MerakDomain extends IceWarpDomain{
	}

	?>