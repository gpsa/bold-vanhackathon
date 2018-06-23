<?php
/**
 * Created by PhpStorm.
 * User: guilherme
 * Date: 23/06/18
 * Time: 10:50
 */

namespace Application\Entity\Shopify;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;

class AppNameRepository extends EntityRepository
{
    /**
     *
     * @param $appSlug
     * @return AppName|null
     */
    public function getAppBySlug($appSlug): ?AppName
    {
        return $this->findOneBy(['appSlug' => $appSlug]);
    }

    /**
     * @param $appSlug
     * @return AppName
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createBySlug($appSlug): AppName
    {
        $entity = new AppName();
        $entity->setAppSlug($appSlug)->setName(ucfirst(str_replace("-", " ", $appSlug)));

        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();

        return $entity;
    }

    public function getAppsListArray(Criteria $criteria = null)
    {
        $query = $this->createQueryBuilder('app')
            ->select('app.appSlug, app.name');

        if (!empty($criteria)) {
            $query->addCriteria($criteria);
        }

        $items = [];
        $result = $query->getQuery()->getArrayResult();
        foreach ($result as $item) {
            $items[$item['appSlug']] = $item['name'];
        }

        return $items;
    }
}