<?php
namespace Knp\Bundle\OAuthBundle\Security\Core\UserProvider;

use Symfony\Component\Security\Core\Exception\UsernameNotFoundException,
    Symfony\Bridge\Doctrine\Security\User\EntityUserProvider as BaseEntityUserProvider,
    Doctrine\Common\Persistence\ManagerRegistry;


class GoogleEntityUserProvider extends BaseEntityUserProvider
{
    private $class;
    private $property;
    private $em;

    public function __construct(ManagerRegistry $registry, $class, $property = null, $managerName = null)
    {
        $this->property = $property;
        $this->class = $class;

        $this->em = $registry->getManager($managerName);

        if (false !== strpos($this->class, ':')) {
            $metadata = $this->em->getClassMetadata($this->class);
            $this->class = $metadata->getName();
        }

        parent::__construct($registry, $class, $property, $managerName);
    }

    /**
     * @var string $username
     * @return mixed An user entity
     */
    public function createEntity($username,$info)
    {
        $setter = 'set'.ucfirst($this->property);
        $user   = new $this->class;

        call_user_func(array($user, $setter), $username);
        call_user_func(array($user, 'set'.ucfirst('firstName')), $info['given_name']);
        call_user_func(array($user, 'set'.ucfirst('familyName')), $info['family_name']);
        
        return $user;
    }

    public function loadUser($username,$info)
    {
        try {
            $user = $this->loadUserByUsername($username);
            
        } catch (UsernameNotFoundException $e) {
            $user = $this->createEntity($username,$info);
            $this->em->persist($user);
            $this->em->flush();
        }

        return $user;
    }
}

?>
