<?php
// 47:18 mettre à jours l'estétique Form / insérer tout les attributs User dans le Form
// SOUCIS Date de Naissance
namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username')
           
            ->add('plainPassword', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'type' => PasswordType::class,
                'invalid_message' => "Veuillez rentrer des mots de passe identique.",
                'options' =>[
                    'attr' => ['class' => 'password-field']
                ],
                'required' => true,
                'first_options' => ['attr' => array('placeholder' => 'Mot de passe'),
                                        'label' => '=> '],
                'second_options' => ['attr' => array('placeholder' => 'Confirmez mot de passe'),
                                        'label' => '=> '],               
            ])
            ->add('firstname')
            ->add('lastname')
            ->add('birthday', BirthdayType::class, [ 
                'format' => 'dd-MM-yyyy',
                'placeholder' => [
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour']])
            ->add('email')
            ->add('isMrs', ChoiceType::class, array(
                'choices' => array(
                    'Madame' => '1',
                    'Monsieur' => '0'
                )
            ));
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
