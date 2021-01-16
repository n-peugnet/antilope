<?php

/** 
 * This file is part of Antilope 
 * 
 * Antilope is free software: you can redistribute it and/or modify 
 * it under the terms of the GNU Affero General Public License as 
 * published by the Free Software Foundation, either version 3 of the 
 * License, or (at your option) any later version. 
 * 
 * This program is distributed in the hope that it will be useful, 
 * but WITHOUT ANY WARRANTY; without even the implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the 
 * GNU Affero General Public License for more details. 
 * 
 * You should have received a copy of the GNU Affero General Public License 
 * along with this program.  If not, see <https://www.gnu.org/licenses/>. 
 * 
 * PHP version 7.2 
 * 
 * @package Antilope 
 * @author Vincent Peugnet <vincent-peugnet@riseup.net> 
 * @copyright 2020-2021 Vincent Peugnet 
 * @license https://www.gnu.org/licenses/agpl-3.0.txt AGPL-3.0-or-later 
 */ 

namespace App\Repository;

use App\Entity\UserContact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserContact|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserContact|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserContact[]    findAll()
 * @method UserContact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserContact::class);
    }

    // /**
    //  * @return UserContact[] Returns an array of UserContact objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserContact
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
