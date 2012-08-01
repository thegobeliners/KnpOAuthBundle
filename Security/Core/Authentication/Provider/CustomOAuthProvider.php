<?php

namespace Knp\Bundle\OAuthBundle\Security\Core\Authentication\Provider;

use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface,
    Symfony\Component\Security\Core\Authentication\Token\TokenInterface,
    Symfony\Component\Security\Core\User\UserProviderInterface;
use Knp\Bundle\OAuthBundle\Security\Core\Authentication\Token\OAuthToken,
    Knp\Bundle\OAuthBundle\Security\Http\OAuth\OAuthProviderInterface;


class CustomOAuthProvider extends OAuthProvider {

    /**
     * {@inheritDoc}
     */
    public function authenticate(TokenInterface $token) {
        $username = $this->oauthProvider->getUsername($token->getCredentials());
        $info = $this->oauthProvider->getUserInfo($token->getCredentials());
        $user = $this->userProvider->loadUser($username,$info);

        $token = new OAuthToken($token->getCredentials(), $user->getRoles());
        $token->setUser($user);
        $token->setAuthenticated(true);

        return $token;
    }

}

?>
