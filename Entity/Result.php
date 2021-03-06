<?php
// src/df/gcmBundle/Entity/Result.php
namespace df\gcmBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="df\gcmBundle\Entity\ResultRepository")
 * @ORM\Table(name="result")
 */
class Result{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="integer")
     */
    protected $topic;
    
    /**
     * @ORM\Column(type="integer") 
     */    
    protected $gameid;
    
    /**
     * @ORM\Column(type="integer") 
     */
    protected $result;
    
    /**
     * @ORM\Column(type="string", length=256) 
     */
    protected $deviceid;
    
    /**
     * @ORM\Column(type="integer"); 
     */
    protected $maxresult;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set topic
     *
     * @param integer $topic
     * @return Result
     */
    public function setTopic($topic)
    {
        $this->topic = $topic;
    
        return $this;
    }

    /**
     * Get topic
     *
     * @return integer 
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * Set gameid
     *
     * @param integer $gameid
     * @return Result
     */
    public function setGameid($gameid)
    {
        $this->gameid = $gameid;
    
        return $this;
    }

    /**
     * Get gameid
     *
     * @return integer 
     */
    public function getGameid()
    {
        return $this->gameid;
    }

    /**
     * Set result
     *
     * @param integer $result
     * @return Result
     */
    public function setResult($result)
    {
        $this->result = $result;
    
        return $this;
    }

    /**
     * Get result
     *
     * @return integer 
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Set deviceid
     *
     * @param string $deviceid
     * @return Result
     */
    public function setDeviceid($deviceid)
    {
        $this->deviceid = $deviceid;
    
        return $this;
    }

    /**
     * Get deviceid
     *
     * @return string 
     */
    public function getDeviceid()
    {
        return $this->deviceid;
    }
    
    /**
     * Set maxresult
     * 
     * @param integer $maxresult
     * @return Result
     */
     public function setMaxresult($maxresult){
         $this->maxresult=$maxresult;
     }
     
     /**
      * Get maxresult
      * 
      * @return integer 
      */
     public function getMaxresult(){
         return $this->maxresult;
     }
    
}