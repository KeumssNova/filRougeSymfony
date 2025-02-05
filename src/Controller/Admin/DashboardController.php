<?php

namespace App\Controller\Admin;
use App\Controller\Admin\UserCrudController;
use App\Entity\Address;
use App\Entity\Projets;
use App\Entity\Taches;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractDashboardController
{
    private $adminUrlGenerator;
    public function __construct(AdminUrlGenerator $adminUrlGenerator)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
    }
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());    
        $url = $this->adminUrlGenerator
                    ->setController(UserCrudController::class)
                    ->generateUrl();
        return $this->redirect($url);



        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }



    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('FilRougeSymfony');
    }

    //faire le menu
    public function configureMenuItems(): iterable
    {
        yield MenuItem::section('Gestion Projets');
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Utilisateur', 'fas fa-user', User::class);
        yield MenuItem::linkToCrud('Adresses', 'fas fa-elementor', Address::class);
        yield MenuItem::linkToCrud('Projets', 'fas fa-user-tie', Projets::class);

        yield MenuItem::subMenu('Taches', 'fas fa-bars')
            ->setSubItems([
                MenuItem::linkToCrud('Ajouter','fas fa-plus', Taches::class)
                    ->setAction(Crud::PAGE_NEW),
                MenuItem::linkToCrud('Visualiser','fas fa-eye',Taches::class)
            ]);
    }
}
