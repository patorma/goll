<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class StandarController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(){
        return new JsonResponse(['success' => 'Inicio sesion con exito']);
    }
    #[Route('/register', name: 'register')]
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $em = $this->getDoctrine()->getManager();
        $user = new User();
        $register_form = $this->createForm(UserType::class,$user);
        $register_form->handleRequest($request);
        if($register_form->isSubmitted() &&  $register_form->isValid()){
            $password_raw  = $register_form->get('password')->getData();
            $user->setPassword($passwordEncoder->encodePassword($user, $password_raw ));
            $em->persist($user);
            $em->flush();
        }
        return $this->render('standar/index.html.twig', [
            'register_form' => $register_form->createView()
        ]);
    }
}
