<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return [
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            \Zend\ServiceManager\AbstractFactory\ConfigAbstractFactory::class,

        ),
        'factories' => [
            'shipify_config' => \Application\Service\ConfigurableFactory::class
        ]
    ),
    'reviews_schedule' => [
        'each' => 3600,
        'apps' => ['product-upsell', 'product-discount', 'store-locator', 'product-options', 'quantity-breaks', 'product-bundles', 'customer-pricing', 'product-builder', 'social-triggers', 'recurring-orders', 'multi-currency', 'quickbooks-online', 'xero', 'the-bold-brain']
    ],
    'shipify_config' => [
        'url' => 'https://apps.shopify.com',
        'http_client' => [
            'adapter' => \Zend\Http\Client\Adapter\Socket::class,
            'ssltransport' => 'tls',
        ]
    ],
];
