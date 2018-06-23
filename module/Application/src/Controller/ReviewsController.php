<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Application\Entity\Shopify\AppName;
use Application\Entity\Shopify\AppNameRepository;
use Application\Entity\Shopify\AppReviews;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Doctrine\Common\Collections\Criteria as DoctrineCriteria;
use DoctrineModule\Paginator\Adapter\Selectable as SelectableAdapter;
use Zend\Paginator\Paginator;

class ReviewsController extends AbstractActionController
{
    /**
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->getEvent()->getApplication()->getServiceManager()->get(EntityManager::class);
    }

    public function indexAction()
    {
        /** @var EntityManager $em */
        $em = $this->getEntityManager();

        /** @var AppNameRepository $rp */
        $rp = $em->getRepository(AppName::class);

        return new ViewModel(['apps' => $rp->getAppsListArray()]);
    }

    public function listAction()
    {
        /** @var EntityManager $em */
        $em = $this->getEntityManager();

        $rp = $em->getRepository(AppReviews::class);

        // Create the criteria
        //        $expr     = DoctrineCriteria::expr()->eq('foo', 'bar');
        $expr = null;

        $search = $this->params()->fromPost('searchPhrase');

        $criteria = DoctrineCriteria::create();

        if (is_array($search)) {
            foreach ($search as $col => $crit) {
                $criteria->andWhere(call_user_func_array(array(DoctrineCriteria::expr(), $crit['cond']), array($col, $crit['text'])));
            }
        }

        $sort = $this->params()->fromPost('sort');

        // Sortable
        if (!empty($sort)) {
            $criteria->orderBy($sort);
        }
        // Create the adapter
        $adapter = new SelectableAdapter($rp, $criteria); // An object repository implements Selectable

        // Create the paginator itself
        $paginator = new Paginator($adapter);
        // Set page number and page size.

        $paginator->setDefaultItemCountPerPage($this->params()->fromPost('rowCount', 20));
        $paginator->setCurrentPageNumber($this->params()->fromPost('current', 1));


        return new JsonModel([
            'current' => $paginator->getCurrentPageNumber(),
            'total' => $paginator->getTotalItemCount(),
            'rows' => $paginator->getIterator()->getArrayCopy(),
            'rowCount' => $paginator->getItemCountPerPage()
        ]);
    }
}
