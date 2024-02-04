<?php

namespace App\Repository\User;

use App\Entity\User\UserToken;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserToken>
 *
 * @method UserToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserToken|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserToken[]    findAll()
 * @method UserToken[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserTokenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserToken::class);
    }
}
