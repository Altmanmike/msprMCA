<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewKeyFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            //->add('email')
            ->add('cryptedKey', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrer clé cryptée'
                ],
                'constraints' => [
                    new NotBlank(),                    
                ],
                'label' => 'Clé d\'authentification',
                'label_attr' => ['class' => 'my-2']
            ])  
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
