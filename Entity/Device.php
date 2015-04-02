<?php
// src/df/gcmBundle/Entity/Device.php
namespace df\gcmBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="device")
 */
class Device {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", length=256)
     */
    protected $gcm_regid;
	
	/**
	 * @ORM\Column(type="string", length=256)
	 */
	protected $apns_token;
    
    /**
     * @ORM\Column(type="string", length=256)
     */
    protected $name;
    
    /**
     * @ORM\Column(type="string", length=256)
     */
    protected $email;
    
    /**
     * @ORM\Column(type="integer")
     */
    protected $crdate;

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
     * Set gcm_regid
     *
     * @param string $gcmRegid
     * @return Device
     */
    public function setGcmRegid($gcmRegid)
    {
        $this->gcm_regid = $gcmRegid;
    
        return $this;
    }

    /**
     * Get gcm_regid
     *
     * @return string 
     */
    public function getGcmRegid()
    {
        return $this->gcm_regid;
    }

    /**
	 * Set apns_token
	 * 
	 * @param string $apnsToken
	 * @return Device 
	 */
	 public function setApnsToken($apnsToken){
	 	$this->apns_token=$apnsToken;
		
		return $this;
	 }
	 
	 /**
	  * Get apns_token
	  * @return string
	  */
	  public function getApnsToken(){
	  	return $this->apns_token;
	  }
	 
    /**
     * Set name
     *
     * @param string $name
     * @return Device
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Device
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set crdate
     *
     * @param integer $crdate
     * @return Device
     */
    public function setCrdate($crdate)
    {
        $this->crdate = $crdate;
    
        return $this;
    }

    /**
     * Get crdate
     *
     * @return integer 
     */
    public function getCrdate()
    {
        return $this->crdate;
    }
}