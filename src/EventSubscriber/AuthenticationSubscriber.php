<?php

namespace App\EventSubscriber;

use App\Config\EnTextConfig;
use App\Config\SiteConfig;
use App\Config\TextConfig;
use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class AuthenticationSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private RequestStack $requestStack 
    )
    {

    }

    public function onAuthentication(AuthenticationSuccessEvent $event): void
    {
        if(str_contains($this->requestStack->getCurrentRequest()->getPathInfo(), '/en/'))
        {
            $confirmedMessage = EnTextConfig::ERROR_NOT_CONFIRMED_USER;
            $restrictedMessage = EnTextConfig::ERROR_RESTRICTED_USER;
        }
        else
        {
            $confirmedMessage = TextConfig::ERROR_NOT_CONFIRMED_USER;
            $restrictedMessage = TextConfig::ERROR_RESTRICTED_USER;
        }

        /** @var User */
        $user = $event->getAuthenticationToken()->getUser();
        if(!$user->isConfirmed())
        {
            throw new AuthenticationException($confirmedMessage, 100);
        }
        if(in_array(SiteConfig::ROLE_USER_RESTRICTED, $user->getRoles()))
        {
            throw new AuthenticationException($restrictedMessage, 100);
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            AuthenticationSuccessEvent::class => 'onAuthentication',
        ];
    }
}
