<?php
/**
 * Created by PhpStorm.
 * User: guilherme
 * Date: 23/06/18
 * Time: 11:34
 */

namespace Application\Console;

use Application\Entity\Shopify\AppName;
use Application\Entity\Shopify\AppNameRepository;
use Application\Entity\Shopify\AppReviews;
use Application\Entity\Shopify\AppReviewsRepository;
use Application\Service\ShopifySyncer;
use Doctrine\ORM\EntityManager;
use Zend\Cache\Storage\StorageInterface;
use Zend\Mvc\Controller\AbstractActionController;


class SyncController extends AbstractActionController
{
    /**
     * @return EntityManager
     */
    private function getEntityManager()
    {
        return $this->getEvent()->getApplication()->getServiceManager()->get(EntityManager::class);
    }

    public function loadAppsAction()
    {
        $em = $this->getEntityManager();
        $configs = $this->getEvent()->getApplication()->getServiceManager()->get('config');
        $apps = $configs['reviews_schedule']['apps'];

        /** @var AppNameRepository $rp */
        $rp = $em->getRepository(AppName::class);

        foreach ($apps as $app) {
            if ($rp->getAppBySlug($app)) {
                continue;
            }

            $rp->createBySlug($app);
        }
    }

    public function runAction()
    {
        $container = $this->getEvent()->getApplication()->getServiceManager();

        $configs = $container->get('config')['reviews_schedule'];

        /** @var ShopifySyncer $shopifySyncer */
        $shopifySyncer = $container->get(ShopifySyncer::class);

        foreach ($configs['apps'] as $appSlug) {
            try {

                /** @var StorageInterface $cache */
                $cache = $container->get('Cache\Transient');

                if ($cache->hasItem('sync_last_run') &&
                    ($cache->getItem('sync_last_run') + $configs['each']) < time()
                ) {
                    return;
                }

                $body = $shopifySyncer->getReviews($appSlug);

                $em = $this->getEntityManager();
                /** @var AppNameRepository $rpApp */
                $rpApp = $em->getRepository(AppName::class);

                // Creates AppSlug if it don't exists
                if (!($app = $rpApp->getAppBySlug($appSlug))) {
                    $app = $rpApp->createBySlug($appSlug);
                }

                /** @var AppReviewsRepository $rp */
                $rp = $em->getRepository(AppReviews::class);

                foreach ($body['reviews'] as $review) {
                    $entity = $rp->finbByDomain($appSlug, $review['shop_domain']) ?? new AppReviews();
                    $entity->setAppSlug($app)
                        ->setCreatedAt(new \DateTime($review['created_at']))
                        ->setShopifyDomain($review['shop_domain'])
                        ->setStarRating($review['star_rating']);

                    if ($entity->getId() > 0 && !$em->getUnitOfWork()->isScheduledForUpdate($entity)) {
                        continue;
                    } elseif ($entity->getId() > 0) {
                        $em->persist($entity);
                    } else {
                        $em->merge($entity);
                    }

                    $em->flush();
                }

            } catch (\Exception $e) {

            }
        }

//        $cache->setItem('sync_last_run', time());
    }

}