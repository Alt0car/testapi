<?php

namespace App\Form\Type;

use App\Entity\Movie;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo')
            ->add('email', EmailType::class)
            ->add('birthDate', DateType::class, array('format' => 'yyyy-MM-dd', 'widget' => 'single_text'))
            ->add(
                'movies',
                CollectionType::class,
                [
                    'entry_type'    => MovieType::class,
                    'entry_options' => ['label' => false],
                    'allow_add'     => true,
                    'allow_delete'  => true,
                    'by_reference'  => false,
                ]
            )
            ->add('send', SubmitType::class)
            ->addEventListener(
                FormEvents::SUBMIT,
                function (FormEvent $event): void {
                    $form = $event->getForm();
                    $tmpObjectArray = new ArrayCollection();

                    /** @var Movie $movie */
                    foreach ($form->get('movies')->getViewData() as $movie) {
                        $imdbId = $movie->getImdbId();

                        $exist = $this->entityManager->getRepository(Movie::class)->findBy(['imdbId' => $imdbId]);

                        if ($exist) {
                            $tmpMovie = $exist[0];
                            $tmpObjectArray->add($tmpMovie);
                        } else {
                            $tmpMovie1 = new Movie();
                            $tmpMovie1->setImdbId($imdbId);
                            $tmpMovie1->setName($movie->getName());
                            $tmpMovie1->setThumb($movie->getThumb());
                            $tmpObjectArray->add($tmpMovie1);
                        }
                    }

                    $form->getData()->setMovies($tmpObjectArray);

                }
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class'         => User::class,
                'csrf_protection'    => false,
                "allow_extra_fields" => true,
            )
        );
    }
}