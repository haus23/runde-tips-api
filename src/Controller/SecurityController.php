<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserPasswordType;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     *
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     *
     * @throws Exception
     */
    public function logout()
    {
        throw new Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }

    /**
     * @Route("set-password/{id}/{token}", name="app_set_password")
     *
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param User $user
     * @param string $token
     * @return Response
     */
    public function setPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder, User $user, string $token)
    {
        $error = '';
        if ($user->getResetToken() !== $token) {
            $error = 'Token ungültig oder abgelaufen.';
        }

        $form = $this->createForm(UserPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            $this->addFlash('success', 'Passwort wurde (neu) gesetzt.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/set-password.html.twig', [
            'error' => $error,
            'user' => $user,
            'form' => $form->createView()
        ]);
    }
}
