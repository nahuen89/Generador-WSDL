<?php
/**************************
*  Sourena Maroofi		  *
*  maroofi@gmail.com	  *
***************************/
class SSoap{
	private $data;
	private $message;
	private $binding;
	private $portType;
	private $service;
	private $soapName=null;
	private $soapLastMessage="None";
	private $soapServiceAddress='';
	private $wsdl_out=false;
	
	private function soapAddMessage(){
		
	}
	private function soapAddPortType(){
		
	}
	private function soapAddBinding(){
		
	}
	public function handle(){
		$result=$this->generateSoapXML($this->data);
		$result=new SimpleXMLElement($result);

		if($this->wsdl_out==true){
			//header("Content-Type: application/xml; charset=utf-8");
			//echo $result->asXml();
			$tmpfile= tempnam(sys_get_temp_dir(), "wsdl");
			$file = fopen($tmpfile,"w");
			fwrite($file,$result->asXml());
			fclose($file);
			$server = new SoapServer($tmpfile);
			foreach ($this->data as $value){
				$server->addFunction($value->funcName);
			}
			$server->handle();
			unlink($tmpfile);
			return;
		}else{
			echo $this->echoXML($result->asXml());
		}
	
	}
	private function calculateSoapAddress(){
		if (isset($_SERVER)) {
			$SERVER_NAME = $_SERVER['SERVER_NAME'];
			$SCRIPT_NAME = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
			$HTTPS = isset($_SERVER['HTTPS']) ? $_SERVER['HTTPS'] : (isset($HTTP_SERVER_VARS['HTTPS']) ? $HTTP_SERVER_VARS['HTTPS'] : 'off');
		} elseif (isset($HTTP_SERVER_VARS)) {
			$SERVER_NAME = $HTTP_SERVER_VARS['SERVER_NAME'];
			$SCRIPT_NAME = isset($HTTP_SERVER_VARS['PHP_SELF']) ? $HTTP_SERVER_VARS['PHP_SELF'] : $HTTP_SERVER_VARS['SCRIPT_NAME'];
			$HTTPS = isset($HTTP_SERVER_VARS['HTTPS']) ? $HTTP_SERVER_VARS['HTTPS'] : 'off';
		}
		if ($HTTPS == '1' || $HTTPS == 'on') {
			$SCHEME = 'https';
		} else {
			$SCHEME = 'http';
		}
		$address="$SCHEME://$SERVER_NAME$SCRIPT_NAME";
		return $address;
	}
	private	function echoXML($oh){
		$address=$this->calculateSoapAddress();
		$xml_file=$oh;
			$xml=simplexml_load_string($xml_file);
			if(isset($xml['targetNamespace'])){
				$tns=(string)$xml['targetNamespace'];
				$tns=explode(':',$tns);
				if(count($tns)>2){
					$tns[0]="";
					$tns=implode("",$tns);
				}else{
					$tns=$tns[count($tns)-1];
				}
			}
			$operation=array();
			if(isset($xml->portType->operation)){
				foreach ($xml->portType->operation as $value){
					$data=array();
					if (isset($value['name'])){
						$data['name']=(string)$value['name'];
					}else{
						$data['name']='NoName';
					}
					if(isset($value->documentation)){
						$data['documentation']=(string)$value->documentation;
					}else{
						$data['documentation']='No Documentation';
					}
					if(isset($value->input)){
						$inputArray=array();
						foreach ($value->input as $input){
							if(isset($input['message'])){
								$message=(string)$input['message'];
								$message=explode(":",$message);
								$message=$message[count($message)-1];
								$inputPart=array();
								if(isset($xml->message)){
									$check=false;
									foreach($xml->message as $messagePart){
										if(isset($messagePart['name'])){
											if((string)$messagePart['name']==$message){
												foreach($messagePart as $message_part){
													if(isset($message_part['name']) && isset($message_part['type'])){
														$inputPart[]=array('name'=>(string)$message_part['name'],
																			'type'=>(string)$message_part['type']
																	);
														
													}
												}
												$inputArray[]=array('name'=>$message,'part'=>$inputPart);
												$check=true;
											}
										}
									}
									if($check==false){
										$inputArray[]=array('name'=>$message,'part'=>$inputPart);
									}
								}
							}
						}
						$data['input']=$inputArray;
					}
					if(isset($value->output)){
						$outputArray=array();
						foreach ($value->output as $output){
							if(isset($output['message'])){
								$message=(string)$output['message'];
								$message=explode(":",$message);
								$message=$message[count($message)-1];
								$outputPart=array();
								if(isset($xml->message)){
									$check=false;
									foreach($xml->message as $messagePart){
										if(isset($messagePart['name'])){
											if((string)$messagePart['name']==$message){
												foreach($messagePart as $message_part){
													if(isset($message_part['name']) && isset($message_part['type'])){
														$outputPart[]=array('name'=>(string)$message_part['name'],
																			'type'=>(string)$message_part['type']
																	);
													}
												}
												$outputArray[]=array('name'=>$message,'part'=>$outputPart);
												$check=true;
											}
										}
									}
									if($check==false){
										$outputArray[]=array('name'=>$message,'part'=>$outputPart);
									}
								}
							}
						}
						$data['output']=$outputArray;
					}
					//TODO add attribs here
					$operation[]=$data;
				}
			}
			//echo json_encode($operation);
		
		$operation=json_decode(json_encode($operation));
		//echo json_encode($operation);
		echo '<html><head><title>SSoap : '.$tns.'</title>';
		echo '<style type="text/css">';
		echo ' body { font-family: arial; color: #000000; background-color: #ffffff; margin: 0px 0px 0px 0px; }';
		echo 'p { font-family: arial; color: #ffffff; margin-top: 0px; margin-bottom: 12px; }';
		echo 'pre { background-color: silver; padding: 5px; font-family: Courier New; font-size: x-small; color: #000000;}';
		echo 'ul { margin-top: 10px; margin-left: 20px; }';
		echo '.salam {float: right;font-family: tahoma;font-size: 9px; padding-right: 20px; }';
		echo ' li { list-style-type: none; margin-top: 10px; color: #000000; }';
		echo '.content{margin-left: 0px; padding-bottom: 2em; }';
		echo '.nav {padding-top: 10px; padding-bottom: 10px; padding-left: 15px; font-size: .70em;margin-top: 10px; margin-left: 0px; color: #000000;background-color: #8f1fe6; width: 20%; margin-left: 20px; margin-top: 20px; }';
		echo ' .title {font-family: arial; font-size: 26px; color: #ffffff;background-color: #5A00FF; width: 105%; margin-left: 0px;padding-top: 10px; padding-bottom: 10px; padding-left: 15px;}';
		echo '.hidden {position: absolute; visibility: hidden; z-index: 200; left: 250px; top: 100px;font-family: arial; overflow: hidden; width: 600;padding: 20px; font-size: 10px; background-color: #949C4F;layer-background-color:#FFFFFF; }';
		echo ' a,a:active { color: #FAFCAF; font-weight: bold; }';
		echo 'a:visited { color: #ffd200; font-weight: bold; }';
		echo 'a:hover { color: #ff6000; font-weight: bold; }';
		echo '</style><script type="text/javascript" language="JavaScript">';
		echo 'function lib_bwcheck(){ this.ver=navigator.appVersion;this.agent=navigator.userAgent;';
		echo 'this.dom=document.getElementById?1:0;this.opera5=this.agent.indexOf("Opera 5")>-1;';
		echo ' this.ie5=(this.ver.indexOf("MSIE 5")>-1 && this.dom && !this.opera5)?1:0;';
		echo ' this.ie6=(this.ver.indexOf("MSIE 6")>-1 && this.dom && !this.opera5)?1:0;';
		echo ' this.ie4=(document.all && !this.dom && !this.opera5)?1:0;';
		echo 'this.ie=this.ie4||this.ie5||this.ie6;this.mac=this.agent.indexOf("Mac")>-1;';
		echo ' this.ns6=(this.dom && parseInt(this.ver) >= 5) ?1:0;this.ns4=(document.layers && !this.dom)?1:0;';
		echo ' this.bw=(this.ie6 || this.ie5 || this.ie4 || this.ns4 || this.ns6 || this.opera5);';
		echo 'return this;}var bw = new lib_bwcheck();function makeObj(obj){';
		echo 'this.evnt=bw.dom? document.getElementById(obj):bw.ie4?document.all[obj]:bw.ns4?document.layers[obj]:0;';
		echo ' if(!this.evnt) return false;this.css=bw.dom||bw.ie4?this.evnt.style:bw.ns4?this.evnt:0;';
		echo 'this.wref=bw.dom||bw.ie4?this.evnt:bw.ns4?this.css.document:0;this.writeIt=b_writeIt;';
		echo ' return this;}function b_writeIt(text){';
		echo ' if (bw.ns4){this.wref.write(text);this.wref.close()} else this.wref.innerHTML = text;}';
		echo 'var oDesc;function popup(divid){';
		echo ' if(oDesc = new makeObj(divid)){oDesc.css.visibility = "visible";}}';
		echo 'function popout(){ if(oDesc) oDesc.css.visibility = "hidden";}';
		echo '</script>';
		echo '</head><body><div class="content"><br><br><div class="title">'.$tns.' ESTE ES EL TITULO linea 204 wsdl.class?</div>';
		echo '<div class="salam">SSoap by S.Maroofi ( maroofi at gmail com ) , M.Dastpak ( hdbplus.md at gmail com)</div>';
		echo '<div class="nav"><p>View the <a href="'.$address."?wsdl".'">WSDL</a>for the service. Click on an operation name to view it\'s details.';
		echo '</p>';
		//ta aval tage ul neveshtam va az inja be bad ba barname va halghe tolid  mishe.
		//var_dump($operation);
		
		if(empty($operation)==false){
			echo '<ul>';
			foreach($operation as $operate){
				//var_dump($operate);
				
				echo '<li>';
				echo '<a onclick="popout();popup('."'".(isset($operate->name)?$operate->name:'NoName')."'".')"'.' href="#">'.(isset($operate->name)?$operate->name:'NoName').'</a>';
				echo '</li>';
				echo '<div id="'.(isset($operate->name)?$operate->name:'NoName') .'"'. 'class="hidden">';
				echo '<a onclick="popout()" href="#">';
				echo '<font color="#ffffff">Close</font>';
				echo '</a><br><br>';
				echo '<font color="white">Name :&nbsp;&nbsp;&nbsp;</font>';
				echo isset($operate->name)?$operate->name:'NoName';
				echo '<br>';
				echo '<font color="white">Documentation :&nbsp;&nbsp;&nbsp;</font>';
				echo (isset($operate->documentation)?$operate->documentation:'No Documentation');
				echo '<br>';
				echo '<font color="white">Input : &nbsp;&nbsp;&nbsp;</font>';
				echo '<br>';
				foreach ($operate->input as $input){
					echo '<font color="white">&nbsp;&nbsp;Name :&nbsp;&nbsp;&nbsp;</font>';
					echo isset($input->name)?$input->name:'NoName';
					echo '<br>';
					echo '<font color="white">&nbsp;&nbsp;Parts :&nbsp;&nbsp;&nbsp;</font>';
					foreach ($input->part as $parts){
						echo '<br>';
						echo '<font color="white">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Name :&nbsp;&nbsp;&nbsp;</font>';
						echo isset($parts->name)?$parts->name:'NoName';
						echo '<br>';
						echo '<font color="white">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Type :&nbsp;&nbsp;&nbsp;</font>';
						echo isset($parts->type)?$parts->type:'UNKNOWN TYPE';
					}
				}
				echo '<br>';
				echo '<font color="white">Output : &nbsp;&nbsp;&nbsp;</font>';
				echo '<br>';
				foreach ($operate->output as $output){
					echo '<font color="white">&nbsp;&nbsp;Name :&nbsp;&nbsp;&nbsp;</font>';
					echo isset($output->name)?$output->name:'NoName';
					echo '<br>';
					echo '<font color="white">&nbsp;&nbsp;Parts :&nbsp;&nbsp;&nbsp;</font>';
					foreach ($output->part as $parts){
						echo '<br>';
						echo '<font color="white">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Name :&nbsp;&nbsp;&nbsp;</font>';
						echo isset($parts->name)?$parts->name:'NoName';
						echo '<br>';
						echo '<font color="white">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Type :&nbsp;&nbsp;&nbsp;</font>';
						echo isset($parts->type)?$parts->type:'UNKNOWN TYPE';
					}
				}
				echo '</div>';		
			}
			echo '</ul>';
		}
	}

	public function register($funcname=null,$parameters=array(),$return=array(),$doc=''){
		if(!isset($funcname) || $funcname==null){
			$this->setSoapLastMessage('There is no name for function');
			return json_encode(array('success'=>false,'msg'=>$this->getSoapLastError()));
		}
		if (!is_array($parameters) || !is_array($return)){
			$this->setSoapLastMessage('Input parameters and return must be array');
			return json_encode(array('success'=>false,'msg'=>$this->getSoapLastError()));
		}
		if($doc==null || !isset($doc)){
			$doc='';
		}
		
		$temp=new stdClass();
		$temp->funcName=$funcname;
		$temp->parameters=$parameters;
		$temp->return=$return;
		$temp->doc=$doc;
		//$temp=json_decode(json_encode($temp));
		$this->data[]=$temp;
		
		//$result=$this->generateSoapXML(array($data));
		/*
		if($this->wsdl_out==true){
			header("Content-Type: application/xml; charset=utf-8");
			echo $result;
			return;
		}else{
			echo $this->echoXML($result);
		}
		*/
	}
	
	public function setSoapServiceAddress($addr=null){
		if(!isset($addr) || $addr==null){
			return ;
		}
		$this->SoapServiceAddress=$addr;
		return 0;
	}
	public function __construct($serviceName=null){
		$this->soapServiceAddress=$this->calculateSoapAddress() . '?wsdl';
		if(isset($_GET['wsdl'])){
			$this->wsdl_out=true;
		}
		if(!isset($serviceName) || $serviceName==null ){
			$this->soapName="Services";
		}else{
			$this->soapName=$serviceName;
		}
		$this->data=array();
		return 0;
	}
	public function getSoapName(){
		return ($this->soapName!=null)?$this->soapName:-1;
	}
	public function setSoapName($serviceName=null){
		if(!isset($serviceName) || $serviceName==null)
			return;
		$this->soapName=$serviceName;
		return 0;
	}
	public function setSoapFunction($funcname="",$parameters=array(),$return=array(),$doc=null){
		if($funcname=="" || $funcname==null){
			$this->setSoapLastMessage('Wrong Function name' . $funcname);
			return  json_encode(array('success'=>false,'msg'=>$this->getSoapLastError()));
		}
		if($doc==null){
			$doc="";
		}
		if(!is_array($parameters)){
			$this->setSoapLastMessage('Function arguments should be an array of "Name"=>"Type" key-value');
			return json_encode(array('success'=>false,'msg'=>$this->getSoapLastError()));
		}
		if(!is_array($return)){
			$this->setSoapLastMessage('return arguments should be an array of "Name"=>"Type" key-value');
			return json_encode(array('success'=>false,'msg'=>$this->getSoapLastError()));
		}
		
	}
	private function getSoapDataType($par=""){
		if(!isset($par) || $par==""){
			return 'xsd:goh';
		}
		if(is_object($par) || is_array($par)){
			//TODO
			return "xsd:string";
		}
		$par=strtolower(strval($par));
		if($par=='str' || $par=='string' || $par=='xsd:string' || $par=="xs:string"){
			return 'xsd:string';
		}
		if($par=='int' || $par=='integer' || $par=='xsd:integer' || $par=='xsd:int'){
			return 'xsd:integer';
		}
		if($par=='float' || $par=='double' || $par=='xsd:float' || $par=='xsd:double'){
			return 'xsd:decimal';
		}
		return 'BUGGY!!!';
	}
	private function generateSoapXML($data=array()){

	   /**
		*	$data=[  
		*	{
		*		funcName=>'name of the function goes here as a text'
		*		parameters=>array('key'=>'value',...,'key'=>'value')
		*		return=>array('key'=>'value',...,'key'=>'value')
		*		doc=>'documents goes here as a text'
		*	}
		*	....
		*	]	
		**/
		if(!is_array($data) or empty($data)){
			return json_encode(array('success'=>false,'msg'=>$this->getSoapLastError()));
		}
		$header='<?xml version="1.0" encoding="UTF-8"?>';
		$definitions='<definitions xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/" xmlns:tns="urn:'. $this->soapName .'" xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/" xmlns="http://schemas.xmlsoap.org/wsdl/" ';
		if($this->soapName==null){
			$this->setSoapLastMessage('No Service Name to generate wsdl Xml');
			return json_encode(array('success'=>false,'msg'=>$this->getSoapLastError()));
		}
		$definitions = $definitions . 'targetNamespace="urn:' . $this->soapName;
		$definitions = $definitions . '">';
		$types='<types><xsd:schema targetNamespace="urn:' . $this->soapName . '">';
		$types = $types . '<xsd:import namespace="http://schemas.xmlsoap.org/soap/encoding/" />';
		$types = $types . '<xsd:import namespace="http://schemas.xmlsoap.org/wsdl/" />';
		$types = $types . '</xsd:schema>';
		$types = $types . '</types>';
		$messages="";
		$portType="";
		$binding="";
		$binding = $binding . '<binding name="' . $this->soapName . 'Binding" type="tns:'.$this->soapName.'PortType">';
		$binding = $binding . '<soap:binding style="rpc" transport="http://schemas.xmlsoap.org/soap/http"/>';
		$portType='<portType name="' . $this->soapName . 'PortType">';
		$service='<service name="'. $this->soapName .'">';
		$service = $service . '<port name="'. $this->soapName .'Port" binding="tns:'. $this->soapName .'Binding">';
		$service = $service . '<soap:address location="'. $this->soapServiceAddress .'"/>';
		$service = $service . '</port>';
		$service = $service . '</service>';
		$service = $service . '</definitions>';
		foreach ($data as $value){
			if(isset($value->funcName)){
				if(!isset($value->doc) || $value->doc==null){
					$value->doc='No Documention!!!';
				}
				$messages = $messages . '<message name="' . $value->funcName . 'Request">';
				if(!is_array($value->parameters) && !is_object($value->parameters)){
					$messages = $messages . '</message>';
					continue;
				}
				foreach($value->parameters as $val => $par){
					$messages = $messages . '<part name="';
					$messages = $messages . $val .'"' .' ';
					$dataType=$this->getSoapDataType($par);
					$messages = $messages . 'type="' . $dataType . '"' . ' />';
				}
				$messages = $messages . '</message>';
				$messages = $messages . '<message name="';
				$messages = $messages . $value->funcName . 'Response">';
				$messages = $messages . ' <part name="return" type="xsd:string" /></message>';
				$portType = $portType . '<operation name="' . $value->funcName .'">';
				$portType = $portType . '<documentation>';
				$portType = $portType . $value->doc . '</documentation>';
				$portType = $portType . '<input message="tns:' . $value->funcName . 'Request"/>';
				$portType = $portType . '<output message="tns:' . $value->funcName .'Response"/>';
				$portType = $portType . ' </operation>';
				$binding = $binding . '<operation name="'.$value->funcName.'">';
				$binding = $binding . '<soap:operation soapAction="urn:'. $this->soapName . '.' . $value->funcName .'" style="rpc"/>';
				$binding = $binding . '<input><soap:body use="literal" namespace="urn:	"/></input>';
				$binding = $binding . '<output><soap:body use="literal" namespace="urn:	"/></output>';
				$binding = $binding . '</operation>';
				
			}
		}
		$portType = $portType . '</portType>';
		$binding = $binding . '</binding>';
		
		$xml=$definitions . $types . $messages . $portType . $binding . $service;
		return $xml;
		
	}
	public function getSoapLastError(){
		return $this->soapLastMessage;
	}
	public function setSoapLastMessage($msg){
		if($msg=="" || !isset($msg) || strtolower($msg)=='none'){
			$this->soapLastMessage="None";
		}else{
			$this->soapLastMessage=$msg;
		}
	}
}
?>