<?php

namespace App\Repository;

use App\Entity\Skill;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use App\Entity\Task;
use App\Service\RoleService;
use Symfony\Component\Security\Core\Security;
use App\Enum\PermissionEnum;
use Doctrine\Common\Collections\Collection;
use App\Service\PermissionService;
use App\Entity\Role;
use App\Enum\SkillEnum;

/**
 * @method Skill|null find($id, $lockMode = null, $lockVersion = null)
 * @method Skill|null findOneBy(array $criteria, array $orderBy = null)
 * @method Skill[]    findAll()
 * @method Skill[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SkillRepository extends ServiceEntityRepository
{
    private $roleService;
    
    private $securityService;
    
    private $permissionService;
    
    public function __construct(RegistryInterface $registry, RoleService $roleService, Security $security, PermissionService $permissionService)
    {
        parent::__construct($registry, Skill::class);
        
        $this->roleService = $roleService;
        $this->securityService = $security;
        $this->permissionService = $permissionService;
    }

    public function byId(int $id) : Skill
    {
        return $this->find($id);
    }
    
    public function softByRole(Role $role) : ?array
    {
        return $this->findBy([
            'role' => $role,
            'type' => SkillEnum::TYPE_SOFT,
        ]);
    }

    public function technicalByRole(Role $role) : ?array
    {
        return $this->findBy([
            'role' => $role,
            'type' => SkillEnum::TYPE_TECHNICAL,
        ]);
    }

    public function allByRole(Role $role) : ?array
    {
        return $this->findBy([
            'role' => $role,
        ]);
    }


    // /**
    //  * @return Skill[] Returns an array of Skill objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Skill
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
