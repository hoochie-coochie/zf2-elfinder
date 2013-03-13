<?php
/**
 * Index Controller for the entire application
 *
 * This file contains the main controller used for the application.  This
 * should extend from the base class and should need no further modification.
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   Main\Application\Controllers\Index
 * @author    Unkown <unknown@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://ci.reliv.com/confluence
 */
namespace ElFinder\Controller;

use ElFinder\Exception\RuntimeException;
use Zend\Mvc\Controller\AbstractActionController;
use elFinder;
use elFinderConnector;

/**
 * Index Controller for the entire application
 *
 * This is main controller used for the application.  This should extend from
 * the base class located in Rcm and should need no further
 * modification.
 *
 * @category  Reliv
 * @package   Main\Application\Controllers\Index
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://ci.reliv.com/confluence
 *
 */
class IndexController extends AbstractActionController
{
    /**
     * Index Action - Used when index or root document is called.
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        return $this->init();
    }

    public function ckEditorFileManagerAction()
    {
        return $this->init();
    }

    public function standAloneAction()
    {
        return $this->init();
    }

    public function init()
    {
        $config    = $this->getConfig();
        $connector = $config['connectorPath'];
        $type      = $this->params('fileType');

        if (empty($connector)) {
            throw new RuntimeException("No Connector path found in Module config");
        }

        return array(
            'type' => $type,
        );
    }

    public function connectorAction()
    {
        error_reporting(0);

        $config = $this->getConfig();

        $type = $this->getEvent()->getRouteMatch()->getParam('fileType');

        if (!empty($type) && !empty($config['mounts'][$type])) {
            $mount = $config['mounts'][$type];
        } else {
            $mount = $config['mounts']['defaults'];
        }

        foreach($mount['roots'] as $k => $v) {
            $mount['roots'][$k]['accessControl'] = array($this,'access');
        }

        $connector = new elFinderConnector(new elFinder($mount));
        $connector->run();
    }

    public function access($attr, $path, $data, $volume) {
        return strpos(basename($path), '.') === 0       // if file/folder begins with '.' (dot)
            ? !($attr == 'read' || $attr == 'write')    // set read+write to false, other (locked+hidden) set to true
            :  null;                                    // else elFinder decide it itself
    }


    public function getConfig() {
        $config = $this->getServiceLocator()->get('config');


        return $config['elfinder'];
    }
}
