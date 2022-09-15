<?php

namespace App\Form;

use App\Entity\Agents;
use App\Entity\Nomenclature;
use App\Entity\Products;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('count', IntegerType::class, [
                'data' => 1,
                'attr' => ['min' => 1]
            ])
            ->add('price')
            ->add('date_paper', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('date_end', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('nomenclature', EntityType::class, [
                'class' => Nomenclature::class
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Products::class,
        ]);
    }
}
