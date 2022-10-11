<?php

namespace App\Controller\Admin;

use App\Controller\CategorieController;
use App\Entity\Article;
use App\Entity\Categorie;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\String\Slugger\SluggerInterface;
use const Sodium\CRYPTO_PWHASH_SCRYPTSALSA208SHA256_STRPREFIX;

class ArticleCrudController extends AbstractCrudController
{
    private SluggerInterface $slugger;

    //Injection du slugger au niveau du constructeur
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public static function getEntityFqcn(): string
    {
        return Article::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('titre'),
            TextEditorField::new('contenu')->setSortable(false)
            ->hideOnIndex(),
            AssociationField::new("categorie")->setRequired(false),
            DateTimeField::new("createdAt","Créé le")->hideOnForm(),
            TextField::new("slug")->hideOnForm(),
            BooleanField::new("publie")

        ];
    }

    //Redéfinir la methode persistEntity qui va être appelée lors de la création de l'article en Base de données
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        // Vérifier que la variable entityInstance est bien une instance de la classe Article
        if(!$entityInstance instanceof Article) return;
        $entityInstance->setCreatedAt(new \DateTime());
        $entityInstance->setSlug($this->slugger->slug($entityInstance->getTitre())->lower());
        // Appel à la méthode héritée afin de persister l'entité
        parent::persistEntity($entityManager, $entityInstance);
    }

    public function configureCrud(Crud $crud): Crud
    {
        $crud->setPageTitle(Crud::PAGE_INDEX,"Liste des articles")
            ->setPageTitle(Crud::PAGE_NEW  ,"Création d'un article")
            ->setPageTitle(Crud::PAGE_EDIT  ,"Modification d'un article")
            ->setPaginatorPageSize(10)
            ->setDefaultSort(["createdAt"=>"DESC"]);
        return $crud;
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions->update(Crud::PAGE_INDEX,Action::NEW,function (Action $action) {
           return $action->setLabel("Ajouter article")->setIcon("fa fa-plus");
        });
        $actions->update(Crud::PAGE_NEW, Action::SAVE_AND_RETURN, function (Action $action) {
            return $action->setLabel("Valider")->setIcon("fa fa-check");
        });
        $actions->remove(Crud::PAGE_NEW,Action::SAVE_AND_ADD_ANOTHER);
        $actions->add(Crud::PAGE_INDEX, Action::DETAIL);


        return $actions;
    }

    public function configureFilters(Filters $filters): Filters
    {
       $filters->add("titre")
       ->add("createdAt");
       return $filters;
    }


}
