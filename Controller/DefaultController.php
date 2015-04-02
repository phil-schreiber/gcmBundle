<?php

namespace df\gcmBundle\Controller;

use df\gcmBundle\Entity\Device;
use df\gcmBundle\Entity\Result;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use df\gcmBundle\lib\GCM;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Response;
class DefaultController  extends Controller
{
    const GOOGLE_API_KEY= "AIzaSyA-o0AR0-pSNSLR1YH55UMTAkZ4gB-zRVE";
    public function indexAction($name)
    {
        return $this->render('dfgcmBundle:Default:index.html.twig', array('name' => "$name"));
    }
    
    public function pushAction()
    {
         $deviceRep = $this->getDoctrine()->getRepository('dfgcmBundle:Device');
         $query=$deviceRep->createQueryBuilder('d')->where("d.gcm_regid <> :nada AND d.deleted = 0 AND d.hidden = 0")->setParameter('nada','')->getQuery();
         $devices=$query->getResult();
         
        $registatoin_ids =array();
         foreach($devices as $counter => $device){         
             
         $registatoin_ids[]=$device->getGcmRegid();

             
         }
         
         $gcm = new GCM();                  
         $message = array("price" => "Eine neues Thema steht zum Download zur Verfügung!"); 
         $result = $gcm->send_notification($registatoin_ids, $message);
        

        return $this->render('dfgcmBundle:Default:index.html.twig', array('name' => "$result"));
    }
    
    public function apnsPushAction(){
    $deviceRep = $this->getDoctrine()->getRepository('dfgcmBundle:Device');
         $query=$deviceRep->createQueryBuilder('d')->where("d.apns_token <> :nada AND d.deleted = 0 AND d.hidden = 0")->setParameter('nada','')->getQuery();
         $devices=$query->getResult();
         
        $registatoin_ids =array();
         foreach($devices as $counter => $device){         
             
         $registatoin_ids[]=$device->getApnsToken();

             
         }
    	
       
	// Put your private key's passphrase here:
	$passphrase = 'ka56ka';

	// Put your alert message here:
	$message = 'Eine neues Thema steht zum Download zur Verfügung!';
	
	$ctx = stream_context_create();
	stream_context_set_option($ctx, 'ssl', 'local_cert', '../src/df/gcmBundle/Controller/cert.pem');
	stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
	
	// Open a connection to the APNS server
	$fp = stream_socket_client(
		'ssl://gateway.push.apple.com:2195', $err,
		$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
	
	if (!$fp)
		exit("Failed to connect: $err $errstr" . PHP_EOL);
	
	echo 'Connected to APNS' . PHP_EOL;
	
	// Create the payload body
	$body['aps'] = array(
		'alert' => $message,
		'sound' => 'atone.caf',
		'badge' => 1
		);
	
	// Encode the payload as JSON
	$payload = json_encode($body);

	// Build the binary notification
	foreach($registatoin_ids as $key => $devicetoken){
	$msg = chr(0) . pack('n', 32) . pack('H*', $devicetoken) . pack('n', strlen($payload)) . $payload;
	
	// Send it to the server
	$result = fwrite($fp, $msg, strlen($msg));	
	}
	
	
	if (!$result)
		echo 'Message not delivered' . PHP_EOL;
	else
		echo 'Message successfully delivered' . PHP_EOL;
	
	// Close the connection to the server
	fclose($fp);
		
	return $this->render('dfgcmBundle:Default:index.html.twig', array('name' => "Mr Bensonmam"));
    }
	
    public function load(array $configs, ContainerBuilder $container){
         $container->setParameter('rms_push_notifications.android.gcm.api_key',self::GOOGLE_API_KEY);
    }
	
	public function apnsRegisterAction(){
		$returnMessage="No device registered";
        $request  = $this->getRequest();
        
        $regId = $request->request->get('regId');
        $name='';
        $email='';
        
        
        
        if($request->getMethod() == 'POST' && $regId != '')   {
           $deviceRep = $this->getDoctrine()->getRepository('dfgcmBundle:Device');
           $devices=$deviceRep->findOneBy(array('apns_token' => $regId)); 
           if(!$devices){ 
                if($request->request->get('name')!=''){
                    $name=$request->request->get('name');
                }
                if($request->request->get('email')!=''){
                    $email=$request->request->get('email');
                }
                $device=new Device();
                $device->setApnsToken($regId);
                $device->setName($name);
                $device->setEmail($email);
                $device->setCrdate(time());
		$device->setGcmRegid('');
                $device->setHidden(0);
                $device->setDeleted(0);
    		$em = $this->getDoctrine()->getManager();
                $em->persist($device);
                $em->flush();
                $returnMessage="Registered device ".$regId;
    			
               
           }
        }
        
        return $this->render('dfgcmBundle:Default:index.html.twig', array('name' => "$returnMessage"));
		
	}
    public function registerAction()
    {
        $returnMessage="No device registered";
        $request  = $this->getRequest();
        
        $regId = $request->request->get('regId');
        $name='';
        $email='';
        
        
        
            
        
        
        
        if($request->getMethod() == 'POST' && $regId != '')   {
           $deviceRep = $this->getDoctrine()->getRepository('dfgcmBundle:Device');
           $devices=$deviceRep->findOneBy(array('gcm_regid' => $regId)); 
           if(!$devices){ 
                if($request->request->get('name')!=''){
                    $name=$request->request->get('name');
                }
                if($request->request->get('email')!=''){
                    $email=$request->request->get('email');
                }
                $device=new Device();
				 $device->setApnsToken('');
                $device->setGcmRegid($regId);
                $device->setName($name);
                $device->setEmail($email);
                $device->setCrdate(time());
                $device->setHidden(0);
                $device->setDeleted(0);				
    				 $em = $this->getDoctrine()->getManager();
                $em->persist($device);
                $em->flush();
                $returnMessage="Registered device ".$regId;
    			
               
           }
        }
        
        return $this->render('dfgcmBundle:Default:index.html.twig', array('name' => "$returnMessage"));
    }
    
    public function benchAction()
     {
            $resultRep=$this->getDoctrine()->getRepository('dfgcmBundle:Result');            
            $result=$resultRep->getBenchmark();            
            
            foreach($result as $key => $value){
                
                    
                    
               /* $sumTopicParticipants[$value['topic']] += $value['dings'];
                $sumMaxle[$value['topic']]+=$value['maxle'];
                $sumTopicRestults[$value['topic']] += $result[$key]['dangs'];*/
               
               
                $absolute=round(($result[$key]['dangs']/$result[$key]['dings']),2);
                
                if($value['maxle']!=0){
                $relative=round($absolute*100/$value['maxle'],2);
                $allVals[$value['topic']]['percent']+=$relative;
                $allVals[$value['topic']]['count']++;
                $relative=$relative.'%';
                }else{
                    $relative=$absolute;
                }
            $bench[$value['topic']][$value['gameid']]=$relative;
            
            }
            foreach( $allVals as $key => $value){
                $bench[$key]['all']=round($value['percent']/$value['count'],2).' %';
            }
         
        return new JsonResponse($bench);
     }
     
     public function uploadAction(){
         $returnMessage=false;
         $request=$this->getRequest();
         $regId=$request->request->get('regId');
         $gameid=intval($request->request->get('gameId'));
         $topic=intval($request->request->get('topicId'));
         $points=intval($request->request->get('achievedPoints'));
         $maxPoints=intval($request->request->get('maxPoints'));
         
         if($request->getMethod()=='POST' && $regId != '')
             {
             $resultRep=$this->getDoctrine()->getRepository('dfgcmBundle:Result');
             $results=$resultRep->findOneBy(array('deviceid'=>$regId,'topic'=>$topic,'gameid'=>$gameid));
             
             if(!$results){
                 $result=new Result();
                 if(!$regId){
                     $regId="";
                 }
                 if(!$gameid){
                     $gameid=0;
                 }
                 if(!$topic){
                     $topic=0;
                 }
                 if(!$points){
                     $points=0;
                 }
                 if(!$maxPoints){
                     $maxPoints=0;
                 }
                 $result->setDeviceid($regId);
                 $result->setGameid($gameid);
                 $result->setTopic($topic);
                 $result->setResult($points);
                 $result->setMaxresult($maxPoints);
				 
                if($topic>0){		 
    				 $em=$this->getDoctrine()->getManager();
                 	$em->persist($result);
                 	$em->flush();
                 }
				 	$returnMessage=true;
    			
				 
                
                 
             }
             
             }
         
         return $this->render('dfgcmBundle:Default:index.html.twig', array('name' => "$returnMessage"));
     }
     
     public function updateAction(){
        $request=$this->getRequest();
        
        $regId=$request->request->get('topic');
        
        if(!$regId){
            $regId=1;
        }
                
        $finder = new Finder();
        $finder->files()->name('*.json')->in('/www/295885_50354/webseiten/apps/itfitness/gcm/updates');
        $finder->sortByModifiedTime();
        $finderArray=iterator_to_array($finder,false);
        $update=array();  
        $filecount=count($finderArray);
        if($regId>=1){
        $regId=intval($regId)-1;
        }
        
        $response=false; 
        
        if($filecount >= $regId){
            
                    $file = new File($finderArray[$regId]);

                    // in case you need the container
                    $container = $this->container;
                    $response = new StreamedResponse(function() use($container, $file) {
                        $handle = fopen($file->getRealPath(), 'r');
                        while (!feof($handle)) {
                            $buffer = fread($handle, 1024);
                            echo $buffer;
                            flush();
                        }   
                        fclose($handle);
                    }); 

                    $response->headers->set('Content-Type', $file->getMimeType());
             
            
        }
        
        
        

    return $response;
     }
     
     public function updateavailableAction(){
        $request=$this->getRequest();
        
        $regId=$request->request->get('topic');
      
        if(!$regId){
            $regId=1;
        }
                
        $finder = new Finder();
        $finder->files()->name('*.json')->in('/www/295885_50354/webseiten/apps/itfitness/gcm/updates');
        $finder->sortByModifiedTime();
        $finderArray=iterator_to_array($finder,false);
        $update=array();  
        $filecount=count($finderArray);
        
        
        $response=false; 
        
        if($filecount >= $regId){            
            $returnMessage=1;
        }else{
            $returnMessage=0;
        }
        $response = new Response();

        $response->setContent("$returnMessage");
        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'text/html');
        
        

    return $response;
     }
    
}
