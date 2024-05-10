<?php

namespace App\Controller\Admin;

use App\Crud\MessageCrud;
use App\Crud\Manager\AfterCrudTrait;
use App\Entity\Message;
use App\Repository\MessageRepository;
use App\Service\VueDataFormatter;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use ReflectionException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/admin/message', name: 'app_admin_message_')]
class AdminMessageController extends AbstractController
{
    use AfterCrudTrait;

    public function __construct(
        private readonly MessageRepository      $messageRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly MessageCrud            $messageCrud
    )
    {
    }

    /**
     * @throws Exception
     */
    #[Route(path: '/{id}/show', name: 'show', methods: ['GET', 'POST'])]
    public function show(
        Message $message,
        Request $request
    ): Response
    {
        $messageForm = $this->messageCrud->save($request, $message);

        if ($messageForm === true) return $this->redirectTo('referer', $request);

        return $this->render('admin/show/message-details.html.twig', [
            'messageForm' => $messageForm,
            'message' => $message
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route(path: '/{id}/delete', name: 'delete', methods: ['GET'])]
    public function delete(
        Message $message,
        Request $request
    ): Response
    {
        $this->entityManager->remove($message);
        $this->entityManager->flush();
        return $this->redirectTo('referer', $request, 'messages');
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * @throws ReflectionException
     */
    public function getMessageData(): array
    {
        $allMessages = $this->messageRepository->findAll();

        $users = VueDataFormatter::makeVueObjectOf(
            $allMessages, ['user']
        )->regroup('user')->get();

        $subjects = VueDataFormatter::makeVueObjectOf(
            $allMessages, ['subject']
        )->regroup('subject')->get();

        $messages = VueDataFormatter::makeVueObjectOf(
            $this->messageRepository->findAll(),
            [
                'id',
                'user',
                'subject',
                'content',
                'lodging',
                'reservation',
                'createdOn'
            ])->get();

        return [
            'settings' => [
                'user' => ['name' => 'clients', 'default' => '', 'values' => $users, 'codeName' => 'user'],
                'subject' => ['name' => 'subjects', 'default' => '', 'values' => $subjects, 'codeName' => 'subject'],
            ],
            'items' => $messages
        ];
    }
}