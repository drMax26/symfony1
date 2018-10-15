<?php

namespace App\Form;

use App\Entity\Blog;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlogType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
				'article', 
				null, 
				[
					'label' => 'Article',
					'attr' => [
						'style' => 'width: 75em; height: 25em'
					]
				]
			)
            ->add(
				'title', 
				null, 
				[
					'label' => 'Article Title',
					'attr' => [
						'style' => 'width: 200px',
					]
				]
			)
            ->add(
				'createdAt', 
				null, 
				[
					'label' => 'Created At', 
					'html5' => true, 
					'widget' => 'single_text',
					'attr' => [
						'style' => 'width: 200px'
					]
				]
			)
            ->add(
				'category', 
				null, 
				[
					'label' => 'Category', 
					'attr' => [
						'style' => 'width: 200px'
					]
				]
			)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Blog::class,
        ]);
    }
}
