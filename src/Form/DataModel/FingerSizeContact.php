<?php
namespace App\Form\DataModel;

use Symfony\Component\Validator\Constraints as ASsert;

class FingerSizeContact 
{
    #[Assert\NotBlank()]
    #[Assert\Email()]
    #[Assert\Length(max: 200)]
    private ?string $email = null;

    #[Assert\NotNull()]
    #[Assert\Positive()]
    private ?int $fingerSize = null;

    /**
     * Get the value of fingerSize
     */ 
    public function getFingerSize()
    {
        return $this->fingerSize;
    }

    /**
     * Set the value of fingerSize
     *
     * @return  self
     */ 
    public function setFingerSize($fingerSize)
    {
        $this->fingerSize = $fingerSize;

        return $this;
    }

    /**
     * Get the value of email
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */ 
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }
}