<?php
	require_once('../inc/conversion/defines.php');
	require_once('../inc/conversion/tools.php');
	require_once('../inc/conversion/item.php');
	class ConversionTool{
		private$aUsers;
		private$sPath;
		private$sInstallPath;
		public
		function __construct(){
			global$oAPI;
			$this->superLogin();
			$this->sInstallPath=$oAPI->getProperty("C_InstallPath");
			
			if($_REQUEST['webmailroot']!=''){
				$this->sPath=$_REQUEST['webmailroot'];
				$end=substr($this->sPath,-1,1);
				
				if($end!='/'&&$end!='\\'){
					$this->sPath.=FDR_DELIMITTER;
				}

			} else {
				$this->sPath=$this->sInstallPath.OLD_WM_USERPATH;
			}

		}

		public
		function __destruct(){
			$this->superLogout();
			dmp("<br/><br/>Processing completed .. terminating script");
		}

		public
		function getUsers(){
			global$oAPI;
			$aUsers=array();
			$sFolders=icewarp_get_folder_list($this->sPath,0);
			$aFolders=Tools::explode_j("\r\n",$sFolders);
			$bSubFolders=false;
			foreach($aFolders as$sFolder){
				$aDomains[$sFolder]=str_replace(array($this->sPath,FDR_DELIMITTER),"",$sFolder);
				$sSubFolders=icewarp_get_folder_list($sFolder,0);
				$aSubFolders=Tools::explode_j("\r\n",$sSubFolders);
				
				if(strpos($sFolder,"@")===false)foreach($aSubFolders as$sSubFolder){
					
					if(strpos($sSubFolder,"@")===false){
						$aUsers[str_replace(array($sFolder,FDR_DELIMITTER),"",$sSubFolder)."@".$aDomains[$sFolder]]=$sSubFolder;
					}

				}

			}

			return$aUsers;
		}

		public
		function getOldMailboxes_r(&$aOldUsers,$path=false){
			global$oAPI,$oAccount;
			$sMailboxPath=$oAPI->getProperty("C_System_Storage_Dir_MailPath");
			$aOldMailboxes=array();
			
			if($path===false){
				$aOldUsers=array();
				$path=$this->sPath;
			}

			$sFolders=icewarp_get_folder_list($path,0);
			$aFolders=Tools::explode_j("\r\n",$sFolders);
			foreach($aFolders as$sFolder){
				$sUser=str_replace(array($path,FDR_DELIMITTER),"",$sFolder);
				$process=false;
				
				if(eregi("(.*)@(.*)",$sUser,$aMatches)){
					$sUser=$aMatches[1].'@'.$aMatches[2];
					
					if(file_exists($sFolder.ADDRESS_FILE)||file_exists($sFolder.GROUPS_FILE))$aOldUsers[$sUser]=$sFolder;
					$process=true;
				} else {
					$info=explode(FDR_DELIMITTER,str_replace($this->sPath,'',$sFolder));
					$sUser=$info[1].'@'.$info[0];
					
					if($info[0]&&$info[1]&&!$info[2])$process=true;
				}

				
				if($process){
					
					if($oAccount->Open($sUser))$userExists=true; else $userExists=false;
					
					if(ACCOUNT_AUTO_CREATE){
						
						if(!isset($oAccount->EmailAddress)){
							$oAccount->New_($sUser);
							$oAccount->SetProperty("u_type",0);
							$oAccount->Save();
						}

					}

					
					if($userExists){
						$sTargetPath=$oAccount->getProperty("u_fullmailboxpath");
						
						if($sTargetPath!=$sMailboxPath){
							$aOldMailboxes[$sFolder]['folder']=$sTargetPath;
							$aOldMailboxes[$sFolder]['configpath']=$sFolder;
						}

					}

				}

				
				if(is_dir($sFolder))$aOldMailboxes=array_merge($aOldMailboxes,$this->getOldMailboxes_r($aOldUsers,$sFolder));
			}

			return$aOldMailboxes;
		}

		private
		function getUserGroupIDs(){
			global$oGWAPI;
			$sid=$oGWAPI->sessid;
			$users=$oGWAPI->GetOwners();
			foreach($users as$user){
				$aUsers[$user['OWN_EMAIL']]['DATA']=$user;
				$groups=$oGWAPI->FunctionCall("GetGroupList",$sid,$user['OWN_EMAIL']);
				$groups=$oGWAPI->ParseParamLine($groups);
				$aUsers[$user['OWN_EMAIL']]['GROUP']=$groups[0];
			}

			return$aUsers;
		}

		private
		function loadCalendarCongif($sPath){
			$content=explode("\r\n",@file_get_contents($sPath.CALENDAR_CFG_FILE));
			foreach($content as$line){
				$line=explode("=",$line,2);
				$result[$line[0]]=$line[1];
			}

			return$result;
		}

		private
		function getOwnerFromFile($sUser,$sPath){
			$aCalendarCfg=$this->loadCalendarCongif($sPath);
			$owner['OWN_EMAIL']=$sUser;
			$owner['OWNDAYSTART']=$aCalendarCfg['OWNDAYSTART']?$aCalendarCfg['OWNDAYSTART']:
			GW_DAY_START;
			$owner['OWNDAYEND']=$aCalendarCfg['OWNDAYEND']?$aCalendarCfg['OWNDAYEND']:
			GW_DAY_END;
			
			if($aCalendarCfg['OWNDATEFORMAT'])$owner['OWNDATEFORMAT']=$aCalendarCfg['OWNDATEFORMAT'];
			return$owner;
		}

		private
		function getGroupFromFile($aOwner,$sPath){
			$aCalendarCfg=$this->loadCalendarCongif($sPath);
			$group['GRPSHAREMODE']=$aCalendarCfg['GRPSHAREMODE'];
			$group['GRPDAILYEVENTSEMAIL']=$group['GRPREMINDEREMAIL']=$aOwner['OWN_EMAIL'];
			return$group;
		}

		private
		function createUserOwner($sUsername,$aOwner){
			global$oGWAPI;
			return$oGWAPI->FunctionCall("CreateUser",$oGWAPI->sessid,$sUsername);
		}

		private
		function updateUserGroup($sUsername,$aGroup){
			global$oGWAPI;
			$sGroups=$oGWAPI->FunctionCall("GetGroupList",$oGWAPI->sessid,$sUsername);
			$aGroups=$oGWAPI->ParseParamLine($sGroups);
			$aExistingGroup=$aGroups[0];
			$sGroupID=$aExistingGroup['GRP_ID'];
			foreach($aExistingGroup as$sGroupKey=>$sGroupField)$aGroup[$sGroupKey]=$sGroupField;
			$sGroup=$oGWAPI->CreateParamLine($aGroup);
			
			if($oGWAPI->FunctionCall("AddGroup",$oGWAPI->sessid,$sGroup,$sGroupID))return$aGroup;
		}

		private
		function autoCreateGroups($aOld,&$aNew,$sGlobalAddress=false){
			global$oAccount;
			
			if($sGlobalAddress)$aOld[$sGlobalAddress]='';
			foreach($aOld as$sUsername=>$sPath){
				$aNewUser=&$aNew[$sUsername];
				
				if(!$aNewUser){
					$aNewOwner=$this->getOwnerFromFile($sUsername,$sPath);
					$aNewGroup=$this->getGroupFromFile($aNewOwner,$sPath);
					
					if($oAccount->Open($sUsername)){
						dmp("Auto creating data for user $sUsername in GW database");
						
						if(!$sNewOwnerID=$this->createUserOwner($sUsername,$aNewOwner))$this->errors["OWNER_CREATE"]=$aNewOwner; else $aNewOwner['OWN_ID']=$sNewOwnerID;
						dmp(" >>> oConversionTool->createUserOwner($sUsername)");
						dmp(" <<< ".($sNewOwnerID?"1":
						"0")." ($sNewOwnerID)");
						
						if(!$aNewGroup=$this->updateUserGroup($sUsername,$aNewGroup))$this->errror["GROUP_EDIT"]=$aNewGroup; else $aNewGroup['GRPOWN_ID']=$sNewOwnerID;
						dmp(" >>> oConversionTool->updateUserGroup($sUsername)");
						dmp(" <<< ".($aNewGroup?"1":
						"0")." ()");
						$aNewUser['DATA']=$aNewOwner;
						$aNewUser['GROUP']=$aNewGroup;
					}

				}

			}

		}

		public
		function processFolder($aOldUsers,$aNewUsers,$sGlobalAddress){
			global$oGWAPI;
			foreach($aOldUsers as$sUsername=>$sOldWMDir){
				dmp("USER[".(++$i)."](<b>$sUsername</b>)");
				$oConversionItem=new ConversionItem($sUsername,$sOldWMDir);
				$aContacts=$oConversionItem->loadContactsFromFile($iSkipped);
				$aGroups=$oConversionItem->loadGroupsFromFile($iSkipped);
				dmp(" >>>> oConversionItem->loadContactsFromFile($sOldWMDir);");
				
				if(!$aNewUsers[$sUsername]['GROUP']){
					$iSkipped=$iSkipped+count($aContacts);
					unset($aContacts);
				}

				dmp(" <<<< 1 count[".(count($aContacts)+$iSkipped)."] process(".count($aContacts).") ToBeSkipped($iSkipped)");
				
				if(($aContacts||$aGroups)&&$aNewUsers[$sUsername]['GROUP']){
					$sGID=$oGWAPI->OpenGroup($aNewUsers[$sUsername]['GROUP']['GRP_ID']);
					$aFolders=$oGWAPI->getFolders($sGID);
					
					if($aFolders){
						foreach($aFolders as$folder){
							
							if($folder['FDRTYPE']=='C'){
								
								if($folder['FDR_DEFAULT']){
									$defaultContacts=$folder['FDR_ID'];
								} else {
									
									if(!$defaultCandidate){
										$defaultCandidate=$folder['FDR_ID'];
									}

								}

							}

						}

					}

					$defaultContacts=$defaultContacts?$defaultContacts:
					$defaultCandidate;
					$sFID=$oGWAPI->OpenFolder($sGID,$defaultContacts);
					
					if(!$sFID){
						dmp(" <<<< folder does not exist: ".$defaultContacts);
					}

					$oConversionItem->convert($sFID,$aContacts,$aGroups);
					$oGWAPI->CloseFolder($sFID);
					$oGWAPI->CloseGroup($sGID);
					unset($aContacts);
				}

				unset($oConversionItem);
			}

		}

		public
		function convert($sGlobalAddress=false){
			global$oGWAPI;
			dmp("<b>Contacts conversion</b><br/>===================");
			$aOldUsers=$this->getUsers();
			$aNewUsers=$this->getUserGroupIDs();
			dmp(" >>>> this->getOldUsers();");
			dmp(" <<<< 1 count[".count($aOldUsers)."]");
			$aOldMailboxes=$this->getOldMailboxes_r($aVeryOldUsers);
			dmp(" >>>> this->getVeryOldUsers();");
			dmp(" <<<< 1 count[".count($aVeryOldUsers)."]");
			$this->autoCreateGroups($aOldUsers,$aNewUsers,$sGlobalAddress);
			$this->autoCreateGroups($aVeryOldUsers,$aNewUsers,$sGlobalAddress);
			$this->processFolder($aOldUsers,$aNewUsers,$sGlobalAddress);
			$this->processFolder($aVeryOldUsers,$aNewUsers,$sGlobalAddress);
			
			if($sGlobalAddress&&file_exists($this->sInstallPath.OLD_WM_CONFIGPATH.ADDRESS_FILE)){
				$oConversionItem=new ConversionItem($sGlobalAddress,$this->sInstallPath.OLD_WM_CONFIGPATH);
				$aGlobalContacts=$oConversionItem->loadContactsFromFile($iSkipped);
				$sGID=$oGWAPI->OpenGroup($aNewUsers[$sGlobalAddress]['GROUP']['GRP_ID']);
				$aFolders=$oGWAPI->getFolders($sGID);
				
				if($aFolders){
					foreach($aFolders as$folder){
						
						if($folder['FDRTYPE']=='C'){
							
							if($folder['FDR_DEFAULT']){
								$defaultContacts=$folder['FDR_ID'];
							} else {
								
								if(!$defaultCandidate){
									$defaultCandidate=$folder['FDR_ID'];
								}

							}

						}

					}

				}

				$defaultContacts=$defaultContacts?$defaultContacts:
				$defaultCandidate;
				$sFID=$oGWAPI->OpenFolder($sGID,$defaultContacts);
				
				if(!$sFID){
					dmp(" <<<< folder does not exist: ".$defaultContacts);
				}

				$oConversionItem->convert($sFID,$aGlobalContacts,$aNewUsers[$sGlobalAddress]['GROUP']['GRPSHAREMODE']);
				$oGWAPI->CloseFolder($sFID);
				$oGWAPI->CloseGroup($sGID);
				unset($aContacts);
				unset($oConversionItem);
			}

			dmp("<br/><br/><b>Mailbox conversion</b><br/>===================");
			
			if($aOldMailboxes)foreach($aOldMailboxes as$from=>$to){
				dmp("USER[".(++$i)."](<b>$sUsername</b>)");
				
				if(($from!=$to['folder'])&&!file_exists($to['configpath'].DONE_FILE2)){
					dmp("Copying mailboxpath <i>$from</i> => <i>".$to['folder']."</i>");
					Tools::dircopy($from,$to['folder']);
					file_put_contents($to['configpath'].DONE_FILE2,"");
				} else dmp("Mailbox path already copied, or same as old");
			}

		}

		private
		function superLogin(){
			global$oAPI,$oGWAPI;
			$oGWAPI->user=$oAPI->getProperty("C_GW_SuperUser");
			$oGWAPI->pass=$oAPI->getProperty("C_GW_SuperPass");
			$oGWAPI->Login();
		}

		private
		function superLogout(){
			global$oGWAPI;
			
			if($oGWAPI){
				$oGWAPI->Logout();
			}

		}

	}