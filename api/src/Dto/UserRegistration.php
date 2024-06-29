<?php
namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class UserRegistration
{
    /**
     * @Assert\NotBlank
     * @Assert\Email
     */
    public $email;

    /**
     * @Assert\NotBlank
     * @Assert\Length(min=6)
     */
    public $password;
}
