<?php

namespace App\Controller;

use App\Entity\User;
use App\Manager\UserManager;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

/**
 * @Route("/users")
 */
class UserController extends AbstractController
{
    /**
     * @var UserManager
     */
    private $manager;

    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;


    public function __construct(
        UserManager $manager, 
        EncoderFactoryInterface $encoderFactory
    ) {
        $this->manager = $manager;
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * @Route(name="users_create", methods={"POST"})
     */
    public function create(): JsonResponse
    {
        if ($this->getParameter('app.user.secret') != $this->getField('secret')) {
            return $this->responseErrorString('Código não secreto informado.');
        }

        $user = User::create()
            ->setEmail($this->getField('email'));

        $encoder = $this->encoderFactory->getEncoder($user);

        $user->setPassword(
            $encoder->encodePassword($this->getField('password'), $user->getSalt())
        );

        try {
            $user = $this->manager->create($user);
        } catch (Exception $exception) {
            return $this->responseErrorException($exception);
        }

        return $this->json($user, 201);
    }

    /**
     * @Route(name="cashiers_remove", methods={"DELETE"})
     */
    public function remove(): JsonResponse
    {
        if ($this->getParameter('app.user.secret') != $this->getField('secret')) {
            return $this->responseErrorString('Código não secreto informado.');
        }

        $user = $this->manager->find($this->getField('email'));

        if (!$user) {
            return $this->responseErrorString('Usuario não encontrado!', 404);
        }

        try {
            $this->manager->remove($user);
        } catch (ForeignKeyConstraintViolationException $exception) {
            return $this->responseErrorString('Não é possível remover este usuário, existem itens vinculados a ele.');
        } catch (Exception $exception) {
            return $this->responseErrorException($exception);
        }

        return $this->json(true);
    }
}
