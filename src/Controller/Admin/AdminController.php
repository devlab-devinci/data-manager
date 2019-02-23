<?php

namespace App\Controller\Admin;

use App\Entity\Box;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController as BaseAdminController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends BaseAdminController
{
    /**
     * @Route("/dashboard", name="admin_dashboard")
     */
    public function dashboard()
    {
        $em = $this->getDoctrine()->getManager();
        $boxRepository = $em->getRepository(Box::class);
        $userRepository = $em->getRepository(User::class);
        return $this->render('admin/dashboard.html.twig', [
            'boxCount' => count($boxRepository->findAll()),
            'userCount' => count($userRepository->findAll())
        ]);
    }
}