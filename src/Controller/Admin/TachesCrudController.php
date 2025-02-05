<?php

namespace App\Controller\Admin;

use App\Entity\Taches;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TachesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Taches::class;
    }
        
    //retirer une action
    public function configureActions(Actions $action): Actions
    {
        return $action
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            // ->disable(Crud::PAGE_DETAIL, Action::DELETE)
            ;//ici on enleve l'aciton "NEW" soit ajouter
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    
}
