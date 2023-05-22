<?php

namespace App\Controller\Admin\Question;

use App\Entity\Question;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class QuestionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Question::class;
    }


    public function configureFields(string $pageName): iterable
    {
            yield TextField::new('title');
            yield TextEditorField::new('text');
            yield TextField::new('link');
            yield IntegerField::new('score');
            yield IntegerField::new('answer_count');
            yield IntegerField::new('view_count');
            yield BooleanField::new('is_answered');
            yield IntegerField::new('owner_id');

            $createdAt = DateTimeField::new('createdAt')->setFormTypeOptions([
                'years' => range(date('Y'), date('Y') + 5),
                'widget' => 'single_text',
            ]);
            if (Crud::PAGE_EDIT === $pageName) {
                yield $createdAt->setFormTypeOption('disabled', true);
            } else {
                yield $createdAt;
            }
    }

}
