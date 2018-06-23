<?php
/**
 * Created by PhpStorm.
 * User: guilherme
 * Date: 23/06/18
 * Time: 12:04
 */

namespace Application\Service;


use Psr\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class ConfigurableFactory implements FactoryInterface
{
//    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
//    {
//        $dependency = $container->get(stdClass::class);
//        return new MyObject($dependency);
//    }
    public function __invoke(\Interop\Container\ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');

        return $config[$requestedName];
    }
}
