<?php
namespace App\Form\Error;


class SlugError 
{
    private ?string $field;

    private string $message = 'Ce slug est invalide';

    public function __construct(string $field, string $message = null)
    {
        $this->field = $field;
        if($message)
        {
            $this->message = $message;
        }
    }

    /**
     * Get the value of message
     */ 
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set the value of message
     *
     * @return  self
     */ 
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get the value of field
     */ 
    public function getField()
    {
        return $this->field;
    }

    /**
     * Set the value of field
     *
     * @return  self
     */ 
    public function setField($field)
    {
        $this->field = $field;

        return $this;
    }
}