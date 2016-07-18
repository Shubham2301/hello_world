<?php 

namespace myocuhub\Events;

trait Auditable
{
	private $action;
    private $description;
    private $result;
    private $ip;

    function __construct($attr)
	{
        if (array_key_exists('ip', $attr)) {
            $this->setIp($attr['ip']);
        }
        if (array_key_exists('description', $attr)) {
            $this->setDescription($attr['description']);
        }
        if (array_key_exists('result', $attr)) {
            $this->setResult($attr['result']);
        }
        if (array_key_exists('action', $attr)) {
            $this->setAction($attr['action']);
        }
	}

    /**
     * Gets the value of action.
     *
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Sets the value of action.
     *
     * @param mixed $action the action
     *
     * @return self
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Gets the value of description.
     *
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets the value of description.
     *
     * @param mixed $description the description
     *
     * @return self
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Gets the value of result.
     *
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Sets the value of result.
     *
     * @param mixed $result the result
     *
     * @return self
     */
    public function setResult($result)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * Gets the value of ip.
     *
     * @return mixed
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Sets the value of ip.
     *
     * @param mixed $ip the ip
     *
     * @return self
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

}
