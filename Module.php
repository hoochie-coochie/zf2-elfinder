<?php

/**
* Module Config For ZF2
*
* PHP version 5.3
*
* LICENSE: No License yet
*
* @category  Reliv
* @package   ContentManager\ZF2
* @author    Westin Shafer <wshafer@relivinc.com>
* @copyright 2012 Reliv International
* @license   License.txt New BSD License
* @version   GIT: <git_id>
* @link      http://ci.reliv.com/confluence
*/

namespace ElFinder;

/**
 * ZF2 Module Config.  Required by ZF2
 *
 * ZF2 reqires a Module.php file to load up all the Module Dependencies.  This
 * file has been included as part of the ZF2 standards.
 *
 * @category  Reliv
 * @package   ContentManager\ZF2
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://ci.reliv.com/confluence
 */
use ElFinder\View\Helper\ElFinder;
use Zend\ServiceManager\ServiceManager;

class Module
{
    /**
     * getAutoloaderConfig() is a requirement for all Modules in ZF2.  This
     * function is included as part of that standard.  See Docs on ZF2 for more
     * information.
     *
     * @return array Returns array to be used by the ZF2 Module Manager
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /**
     * getConfig() is a requirement for all Modules in ZF2.  This
     * function is included as part of that standard.  See Docs on ZF2 for more
     * information.
     *
     * @return array Returns array to be used by the ZF2 Module Manager
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    /**
     * getServiceConfiguration is used by the ZF2 service manager in order
     * to create new objects.
     *
     * @return object Returns an object.
     */
    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'elfinder' => function (ServiceManager $sm) {
                    $sl     = $sm->getServiceLocator();
                    $config = $sl->get('Config');

                    $headScript = $sm->get('headScript');
                    foreach ($config['elfinder']['scripts'] as $script) {
                        $headScript->appendFile($script);
                    }

                    $headLink = $sm->get('headLink');
                    foreach ($config['elfinder']['styles'] as $style) {
                        $headLink->appendStylesheet($style);
                    }

                    $helper = new ElFinder();
                    $helper->setConnectorURL($config['elfinder']['connectorPath']);

                    return $helper;
                }
            ),
        );
    }
}
