<?php

namespace App\EventSubscriber;

use App\Config\TextConfig;
use App\Config\EnTextConfig;
use App\Service\CartService;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LoginSuccessSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private RequestStack $requestStack,
        private CartService $cartService
    )
    {

    }


    public function onLoginSuccess(LoginSuccessEvent $event): void
    {
        if(str_contains($this->requestStack->getCurrentRequest()->getPathInfo(), '/en/'))
        {
            $message = EnTextConfig::ALERT_LOGIN_SUCCESS;
        }
        else
        {
            $message = TextConfig::ALERT_LOGIN_SUCCESS;
        }

        /** @var FlashBag */
        $flashBag = $this->requestStack->getSession()->getBag('flashes');
        $flashBag->add('success', $message);
        //suppression des données du formulaire de checkout dans le sessionStorage
        $flashBag->add('sessionStorage_remove', json_encode(['checkout', 'check_st']));

        $this->cartService->onLoginUpdate($event->getUser());
    }

    public static function getSubscribedEvents(): array
    {
        return [
            LoginSuccessEvent::class => 'onLoginSuccess',
        ];
    }
}
