<?php

namespace App\Controller\Backend;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UsersController
 * @package App\Controller\Backend
 *
 * @Route("/backend/users")
 */
class UsersController extends AbstractController
{
    /**
     * @Route("/", name="backend_users")
     *
     * @param UserRepository $userRepository
     * @return Response
     */
    public function index(UserRepository $userRepository)
    {
        return $this->render('backend/users/index.html.twig', [
            'users' => $userRepository->findAll()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     *
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', "Änderungen gespeichert.");
        }

        return $this->render('backend/users/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/set-key", name="user_set_reset_key", methods={"GET"})
     *
     * @param Request $request
     * @param User $user
     * @return Response
     * @throws Exception
     */
    public function setResetKey(Request $request, User $user): Response
    {

        $key = sha1(random_bytes(10));
        $user->setResetKey($key);
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash('success', "Email Token wurde erzeugt.");
        return $this->redirectToRoute('user_edit', ['id' => $user->getId()]);
    }

    /**
     * @Route("/{id}/send-key", name="user_send_reset_key", methods={"GET"})
     *
     * @param Request $request
     * @param User $user
     * @param MailerInterface $mailer
     * @return Response
     */
    public function sendResetKey(Request $request, User $user, MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from('mail@runde.tips')
            ->to($user->getEmail())
            ->subject('Tipprunde: Passwort (Re-)Set Link')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        try {
            $mailer->send($email);
            $this->addFlash('success', "Email mit Token wurde verschickt.");
        } catch (TransportExceptionInterface $e) {
            $this->addFlash('error', "Probleme beim Versenden des Tokens.");
        }

        return $this->redirectToRoute('user_edit', ['id' => $user->getId()]);
    }
}
