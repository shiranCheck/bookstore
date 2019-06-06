<?PHP
	class IceWarpAPI{
		var$base;
		private static$instance;
		function __construct(){
			$this->base=icewarp_apiobjectcall(0,'','IceWarpServer.APIObject');
		}

		
		function __destruct(){
			icewarp_apiobjectcall($this->base);
		}

		
		function instance($identity){
			
			if(!isset(self::$instance)){
				self::$instance=new IceWarpAPI();
				self::$instance->SetProperty('C_System_Logging_Maintenance_Identity',$identity);
			}

			return self::$instance;
		}

		
		function Save(){
			return icewarp_apiobjectcall($this->base,'Save');
		}

		
		function GetDomain($domain){
			return icewarp_apiobjectcall($this->base,'getdomain',$domain);
		}

		
		function GetProperty($property){
			return icewarp_apiobjectcall($this->base,'GetProperty',$property);
		}

		
		function SetProperty($property,$value){
			return icewarp_apiobjectcall($this->base,'SetProperty',$property,$value);
		}

		
		function GetDomainCount(){
			return icewarp_apiobjectcall($this->base,'GetDomainCount');
		}

		
		function GetDomainIndex($domain){
			return icewarp_apiobjectcall($this->base,'GetDomainIndex',$domain);
		}

		
		function OpenDomain($domain){
			require_once('domain.php');
			$domainobject=new MerakDomain();
			
			if($domainobject->Open($domain))return$domainobject;
			return false;
		}

		
		function NewDomain($domain){
			require_once('domain.php');
			$domainobject=new MerakDomain();
			
			if($domainobject->New_($domain))return$domainobject;
			return false;
		}

		
		function GetUserStatistics($from,$to,$filter){
			return icewarp_apiobjectcall($this->base,'GetUserStatistics',$from,$to,$filter);
		}

		
		function UpdateConfiguration(){
			return icewarp_apiobjectcall($this->base,'UpdateConfiguration');
		}

		
		function ReloadServices(){
			return icewarp_apiobjectcall($this->base,'ReloadServices');
		}

		
		function BackupConfig($path){
			return icewarp_apiobjectcall($this->base,'BackupConfig',$path);
		}

		
		function RestoreConfig($path,$password=''){
			return icewarp_apiobjectcall($this->base,'RestoreConfig',$path,$password);
		}

		
		function DeleteDomain($domain){
			return icewarp_apiobjectcall($this->base,'DeleteDomain',$domain);
		}

		
		function RenameDomain($olddomain,$newdomain){
			return icewarp_apiobjectcall($this->base,'RenameDomain',$olddomain,$newdomain);
		}

		
		function GetDomainList(){
			return icewarp_apiobjectcall($this->base,'GetDomainList');
		}

		
		function CreateTables($service,$connection){
			return icewarp_apiobjectcall($this->base,'CreateTables',$service,$connection);
		}

		
		function DropTables($service){
			return icewarp_apiobjectcall($this->base,'DropTables',$service);
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

		
		function QuarantineAuthorize($folder){
			return icewarp_apiobjectcall($this->base,'QuarantineAuthorize',$folder);
		}

		
		function QuarantineDelete($owner,$sender,$folder){
			return icewarp_apiobjectcall($this->base,'QuarantineDelete',$owner,$sender,$folder);
		}

		
		function QuarantineAdd($owner,$sender,$state){
			return icewarp_apiobjectcall($this->base,'QuarantineAdd',$owner,$sender,$state);
		}

		
		function QuarantineSet($owner,$sender,$state){
			return icewarp_apiobjectcall($this->base,'QuarantineSet',$owner,$sender,$state);
		}

		
		function QuarantineList($owner,$flags,$top,$offset='0'){
			return icewarp_apiobjectcall($this->base,'QuarantineList',$owner,$flags,$top,$offset);
		}

		
		function GLList($email,$flags,$top,$offset='0'){
			return icewarp_apiobjectcall($this->base,'GLList',$email,$flags,$top,$offset);
		}

		
		function GLAdd($value,$email,$status){
			return icewarp_apiobjectcall($this->base,'GLAdd',$value,$email,$status);
		}

		
		function GLSet($value,$email,$status){
			return icewarp_apiobjectcall($this->base,'GLSet',$value,$email,$status);
		}

		
		function GLDelete($value,$email,$status){
			return icewarp_apiobjectcall($this->base,'GLDelete',$value,$email,$status);
		}

		
		function ConnectNow(){
			return icewarp_apiobjectcall($this->base,'ConnectNow');
		}

		
		function SpamIndexNow($email=''){
			return icewarp_apiobjectcall($this->base,'SpamIndexNow',$email);
		}

		
		function SpamCompactDB(){
			return icewarp_apiobjectcall($this->base,'SpamCompactDB');
		}

		
		function AntiSpamUpdate(){
			return icewarp_apiobjectcall($this->base,'AntiSpamUpdate');
		}

		
		function AntiVirusUpdate(){
			return icewarp_apiobjectcall($this->base,'AntiVirusUpdate');
		}

		
		function ETRNNow($index){
			return icewarp_apiobjectcall($this->base,'ETRNNow',$index);
		}

		
		function FTPSyncNow($item){
			return icewarp_apiobjectcall($this->base,'FTPSyncNow',$item);
		}

		
		function RemoteAccountNow($index){
			return icewarp_apiobjectcall($this->base,'RemoteAccountNow',$index);
		}

		
		function RemoteServerWatchdogNow(){
			return icewarp_apiobjectcall($this->base,'RemoteServerWatchdogNow');
		}

		
		function TaskEventNow($index){
			return icewarp_apiobjectcall($this->base,'TaskEventNow',$index);
		}

		
		function CheckDBConnection($conn){
			return icewarp_apiobjectcall($this->base,'CheckDBConnection',$conn);
		}

		
		function CheckDNSServer($dns){
			return icewarp_apiobjectcall($this->base,'CheckDNSServer',$dns);
		}

		
		function Migration_Finish(){
			return icewarp_apiobjectcall($this->base,'Migration_Finish');
		}

		
		function Migration_MigrateMessages($single,$user,$pass,$domain,$bulkbuffer){
			return icewarp_apiobjectcall($this->base,'Migration_MigrateMessages',$single,$user,$pass,$domain,$bulkbuffer);
		}

		
		function Migration_MigrateMessagesAccounts($single,$user,$pass,$bulkbuffer){
			return icewarp_apiobjectcall($this->base,'Migration_MigrateMessagesAccounts',$single,$user,$pass,$bulkbuffer);
		}

		
		function IDNToUTF8($idn){
			return icewarp_apiobjectcall($this->base,'IDNToUTF8',$idn);
		}

		
		function UTF8ToIDN($utf8){
			return icewarp_apiobjectcall($this->base,'UTF8ToIDN',$utf8);
		}

		
		function GetLocalIPs(){
			return icewarp_apiobjectcall($this->base,'GetLocalIPs');
		}

		
		function DeleteFiles($folder,$ext,$rec,$older){
			return icewarp_apiobjectcall($this->base,'DeleteFiles',$folder,$ext,$rec,$older);
		}

		
		function GetFolderList($folder,$rec){
			return icewarp_apiobjectcall($this->base,'GetFolderList',$folder,$rec);
		}

		
		function SIPReferCall($owner,$number){
			return icewarp_apiobjectcall($this->base,'SIPReferCall',$owner,$number);
		}

		
		function PostServiceMessage($service,$msg,$wparam,$lparam){
			return icewarp_apiobjectcall($this->base,'PostServiceMessage',$service,$msg,$wparam,$lparam);
		}

		
		function MakePrimaryDomain($domain){
			return icewarp_apiobjectcall($this->base,'MakePrimaryDomain',$domain);
		}

		
		function MigrateDatabase($sourcedsn,$destdsn,$logfile,$index){
			return icewarp_apiobjectcall($this->base,'MigrateDatabase',$sourcedsn,$destdsn,$logfile,$index);
		}

		
		function ConvertStorage($todb){
			return icewarp_apiobjectcall($this->base,'ConvertStorage',$todb);
		}

		
		function GetFileList($folder,$rec){
			return icewarp_apiobjectcall($this->base,'GetFileList',$folder,$rec);
		}

		
		function GetMessageContent($file,$type='',$maxsize=0,$maxlines=0){
			return icewarp_apiobjectcall($this->base,'GetMessageContent',$file,$type,$maxsize,$maxlines);
		}

		
		function Base64FileEncode($source,$dest){
			return icewarp_apiobjectcall($this->base,'Base64FileEncode',$source,$dest);
		}

		
		function SMSHTTP($http,$bypassauth=false){
			return icewarp_apiobjectcall($this->base,'SMSHTTP',$http,$bypassauth);
		}

		
		function SyncPush($param1,$param2,$param3,$param4){
			return icewarp_apiobjectcall($this->base,'SyncPush',$param1,$param2,$param3,$param4);
		}

		
		function CryptData($alg,$key,$data,$encode){
			return icewarp_apiobjectcall($this->base,'CryptData',$alg,$key,$data,$encode);
		}

		
		function SmartAttach($filename,$account,$expiration,$anonymous,$params){
			return icewarp_apiobjectcall($this->base,'SmartAttach',$filename,$account,$expiration,$anonymous,$params);
		}

		
		function DoLog($ThreadId,$LogType,$Who,$Value,$FType=1,$Flags=0){
			return icewarp_apiobjectcall($this->base,'DoLog',$ThreadId,$LogType,$Who,$Value,$FType."|".$Flags);
		}

		
		function MoveFileWithUpdate($FileName,$DestName){
			return icewarp_apiobjectcall($this->base,'MoveFileWithUpdate',$FileName,$DestName);
		}

		
		function CopyFileWithUpdate($FileName,$DestName,$fFailIfExists=0){
			return icewarp_apiobjectcall($this->base,'COpyFileWithUpdate',$FileName,$DestName,$fFailIfExists);
		}

		
		function DeleteFileWithUpdate($FileName){
			return icewarp_apiobjectcall($this->base,'DeleteFileWithUpdate',$FileName);
		}

		
		function CacheFileWithUpdate($FileName){
			return icewarp_apiobjectcall($this->base,'CacheFileWithUpdate',$FileName);
		}

		
		function MoveDirWithUpdate($FileName,$DestName){
			return icewarp_apiobjectcall($this->base,'MoveDirWithUpdate',$FileName,$DestName);
		}

		
		function CopyDirRecWithUpdate($FileName,$DestName){
			return icewarp_apiobjectcall($this->base,'COpyDIrRecWithUpdate',$FileName,$DestName);
		}

		
		function DeleteDirRecWithUpdate($FileName){
			return icewarp_apiobjectcall($this->base,'DeleteDirRecWithUpdate',$FileName);
		}

		
		function CacheDirWithUpdate($FileName){
			return icewarp_apiobjectcall($this->base,'CacheDirWithUpdate',$FileName);
		}

		
		function SOCKSCall($command,$hash,$param1='',$param2=''){
			return icewarp_apiobjectcall($this->base,'SOCKSCall',$command,$hash,$param1,$param2);
		}

		
		function GetLogRecords($LogType,$UnixDate,$Filter='',$DeleteLog=False){
			return icewarp_apiobjectcall($this->base,'GetLogRecords',$LogType,$UnixDate,$Filter,$DeleteLog);
		}

		
		function ManageConfig($Selector,$Command,$Params=''){
			return icewarp_apiobjectcall($this->base,'ManageConfig',$Selector,$Command,$Params);
		}

	}

	
	class MerakAPI extends IceWarpAPI{
	}

	?>