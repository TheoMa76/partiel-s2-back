<?php

namespace App\Form;

use App\Entity\CommandeMateriel;
use App\Entity\Materiel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;

class CommandeMaterielType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
{
    $builder
        ->add('materiel', EntityType::class, [
            'class' => Materiel::class,
            'choice_label' => 'name',
            'label' => 'Matériel',
            'attr' => ['class' => 'w-full py-2 px-3 border rounded-lg']
        ])
        ->add('quantite', IntegerType::class, [
            'label' => 'Quantité',
            'attr' => ['class' => 'w-full py-2 px-3 border rounded-lg']
        ]);
}

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CommandeMateriel::class,
        ]);
    }
}
