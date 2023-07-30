<?php

namespace App\Security\Voter;

use App\Entity\Products;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use \Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class PrdoductsVoter extends Voter
{
    const EDIT = 'PRODUCT_EDIT';
    const DELETE = 'PRODUCT_DELETE';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $product): bool
    {
        if(!in_array($attribute, [self::EDIT, self::DELETE])) {
            return false;
        }
        if(!$product instanceof Products){
            return false;
        }
        return true;
    }     

    protected function voteOnAttribute($attribute, $product, TokenInterface $token): bool
    {
        // On commence par récupérer l’utilisateur à partir du token
        $user = $token->getUser();

        // On vérifie si l’utilisateur est une instance d’UserInterface
        if(!$user instanceof UserInterface) return false;

        // On vérifie si l’utilisateur est un admin
        if($this->security->isGranted('ROLE_ADMIN')) return true;

        // On vérifie les permissions
        switch($attribute) {
            case self::EDIT:
                return $this->canEdit();
                break;
            case self::DELETE:
                return $this->canDelete();
                break;
        }
    }

    private function canEdit(){
        return $this->security->isGranted('ROLE_PRODUCT_ADMIN');
    }
    private function canDelete(){
        return $this->security->isGranted('ROLE_ADMIN');
    }

}