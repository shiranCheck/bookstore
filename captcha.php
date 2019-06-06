<?php
	class Captcha{
		public
		function challengeresponsefunction($funcname,$param1="",$param2="",$param3=""){
			$result=@icewarp_challengeresponsefunctioncall($funcname,trim($param1),trim($param2),trim($param3));
			return$result;
		}

		public
		function challengeresponseimage($word){
			$result=$this->challengeresponsefunction("GetWordImage",$word);
			header("Content-Type: image/jpeg");
			$image=base64_decode($result);
			echo$image;
		}

		public
		function getchallengeresponseimage($word){
			$result=$this->challengeresponsefunction("GetWordImage",$word);
			return$result;
		}

		public
		function getrandomword(){
			return$this->challengeresponsefunction("GetRandomWord");
		}

		public
		function showrandomimage(&$word=false){
			
			if(!$word)$word=$this->getrandomword();
			return$this->challengeresponseimage($word);
		}

		public static
		function alnum($str){
			$str=preg_replace('#[^a-z0-9]#i','',$str);
			return$str;
		}

		public
		function check($sUID,$sMatchWord){
			session_id($sUID);
			session_start();
			
			if(!$_SESSION['captcha'])throw new Exc('confirmation_uid',$sUID);
			$sMatchWord=self::alnum($sMatchWord);
			$sWord=self::alnum($_SESSION['captcha']['word']);
			
			if(strcasecmp($sWord,$sMatchWord))throw new Exc('confirmation_word_mismatch',$sMatchWord);
		}

	}

	?>