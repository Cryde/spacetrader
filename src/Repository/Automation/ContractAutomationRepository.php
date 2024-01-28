<?php

namespace App\Repository\Automation;

use App\Entity\Automation\ContractAutomation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ContractAutomation>
 *
 * @method ContractAutomation|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContractAutomation|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContractAutomation[]    findAll()
 * @method ContractAutomation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContractAutomationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContractAutomation::class);
    }
}
