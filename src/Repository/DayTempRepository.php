<?php

namespace App\Repository;

use App\Entity\DayTemp;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DateTime;

/**
 * @method DayTemp|null find($id, $lockMode = null, $lockVersion = null)
 * @method DayTemp|null findOneBy(array $criteria, array $orderBy = null)
 * @method DayTemp[]    findAll()
 * @method DayTemp[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DayTempRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DayTemp::class);
    }

    public function getDayTempByCity(string $city, DateTime $date)
    {
        $date->setTime(0,0,0);

        $date_to = clone $date;
        $date_to->setTime(23,59,59);

        return $this->createQueryBuilder('dt')
            ->andWhere('dt.createdAt BETWEEN :date_from AND :date_to')
            ->setParameters(['date_from' => $date, 'date_to' => $date_to])
            ->andWhere('dt.city = :city')
            ->setParameter('city', $city)
            ->orderBy('dt.createdAt', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function addCityTemp(string $city, float $temp)
    {
        $dayTemp = new DayTemp();
        $dayTemp->setCity($city);
        $dayTemp->setTemp($temp);
        $this->getEntityManager()->persist($dayTemp);
        $this->getEntityManager()->flush();
    }
}
