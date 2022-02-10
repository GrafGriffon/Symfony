<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', TextType::class, array(
                'label' => 'Email',
                'attr' => array('class' => 'form-adding')
            ))
            ->add('password', PasswordType::class, array(
                'label' => 'Password',
                'attr' => array('class' => 'form-adding')
            ))
            ->add('roles', ChoiceType::class, array(
                'multiple' => true,
                'choices' => array(
                    'User' => 'ROLE_USER',
                    'Moderator' => 'ROLE_MODERATOR',
                    'Admin' => 'ROLE_ADMIN'
                ),
                'label' => 'Rollen',
                'required' => true,
                'attr' => array('class' => 'form-adding')
            ))
            ->add('firstName', TextType::class, array(
                'label' => 'First name',
                'attr' => array('class' => 'form-adding')
            ))
            ->add('lastName', TextType::class, array(
                'label' => 'Last name',
                'attr' => array('class' => 'form-adding')
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}