<?php

namespace App\Form;

use App\Entity\Orders;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


class OrdersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('number', IntegerType::class, [
                'disabled' => true
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'новый' => 0,
                    'оплачен' => 2,
                    'выполнен' => 4
                ]
            ])
            ->add('comment', TextType::class, [
                'required' => false
            ])
            ->add('date_order', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('date_delivery', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('track_number', TextType::class, [
                'required' => false
            ])
            ->add('summ', HiddenType::class, [
                'required' => false,
                'data' => 0
            ])
            ->add('sms_status', ChoiceType::class, [
                'choices' => [
                    'не отправлено' => 0,
                    'отправлено' => 1,
                    'ошибка' => 3
                ]
            ])
            ->add('user_id', HiddenType::class, [
                'required' => false
            ])
            ->add('editor_id', HiddenType::class, [
                'required' => false
            ])
            ->add('address')
            ->add('deleted')
            ->add('agent')
            ->add('products', CollectionType::class, [
                'entry_type' => ProductsType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'entry_options' => array('label' => false),
                'by_reference' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Orders::class,
        ]);
    }
}
