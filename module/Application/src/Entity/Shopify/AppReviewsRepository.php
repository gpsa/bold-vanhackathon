<?php
/**
 * Created by PhpStorm.
 * User: guilherme
 * Date: 23/06/18
 * Time: 10:50
 */

namespace Application\Entity\Shopify;

use Doctrine\ORM\EntityRepository;

class AppReviewsRepository extends EntityRepository
{
    public function finbByDomain($appSlug, $domain)
    {
        return $this->findOneBy([
            'appSlug' => $appSlug,
            'shopifyDomain' => $domain
        ]);
    }
}