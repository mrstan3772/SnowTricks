<?php 

namespace App\Form\FormExtension;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RepeatedPasswordType extends AbstractType {
    public function getParent(): string
    {
        return RepeatedType
    }
}