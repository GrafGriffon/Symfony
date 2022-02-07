<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Products;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProductForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => 'Name new product',
                'attr' => array('class' => 'form-adding')
            ))
            ->add('currprice', IntegerType::class, array(
                'label' => 'Current price',
                'attr' => array('class' => 'form-adding')
            ))
            ->add('statuscount', NumberType::class, array(
                'label' => 'Current count',
                'attr' => array('class' => 'form-adding')
            ))
            ->add('category', EntityType::class, [
                'required' => true,
                'class' => Category::class,
                'multiple' => false,
                'label' => 'Index of category',
                'attr' => array('class' => 'form-adding')
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Products::class,
        ]);
    }
}