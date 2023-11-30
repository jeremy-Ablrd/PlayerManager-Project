<?php

namespace App\Controller;

use App\Form\LoginType;

use App\Repository\ManagerRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomePageController extends AbstractController
{
    // private $session;

    // public function __construct(SessionInterface $session)
    // {
    //     $this->session = $session;
    // }

    #[Route('/', name: 'app_home_page')]
    public function index(): Response
    {
        return $this->render('home_page/index.html.twig', [
            'controller_name' => 'Welcome to the Home Page',
        ]);
    }

    #[Route('/login', name: 'app_home_login', methods: ['GET', 'POST'])]
    public function login_page(Request $request, ManagerRepository $managerRepository): Response
    {
        // configurer la session
        $session = array();

        // Créez le formulaire en utilisant le type de formulaire LoginType
        $form = $this->createForm(LoginType::class);

        // Traitez la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $donneesFormulaire = $form->getData();

            $data = $managerRepository->findOneBy(['email' => $donneesFormulaire['email']]);

            // validation du password form et password hashage
            

            if($data) {
                if ($donneesFormulaire['password'] == $data->getPassword()){
                    $emailUser = $data->getEmail();         // accées au getter email dans entité manager

                    // initialiser les parametres de session
                    $session = $request->getSession();

                    if ($session == null) {
                        $userManager['email'] = $emailUser;
                        $userManager['firstname'] = $data->getFirstName();
                        $userManager['lastname'] = $data->getLastName();

                        $session->set("userManager", $userManager);
                    }
                return $this->redirectToRoute('app_manager_index', [], Response::HTTP_SEE_OTHER);
            }
            return $this->render('home_page/login.html.twig', ['form' => $form,]);
        }

        return $this->render('home_page/login.html.twig', ['form' => $form,]);
    }
}

    #[Route('/access/manager', name: 'app_home_login')]
    public function pageManager(): Response
    {
        return $this->render('home_page/login.html.twig', [
            'controller_name' => 'Welcome to the log-in Page',
        ]);
    } 

}
