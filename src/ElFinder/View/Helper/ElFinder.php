<?php

namespace ElFinder\View\Helper;

use Zend\View\Helper\AbstractHelper;

class ElFinder extends AbstractHelper
{

    /**
     * Connector URL
     *
     * @var string
     */
    private $connectorURL;

    public function __invoke()
    {
        return $this;
    }

    public function renderContainer($id)
    {
        return sprintf('<div id="%s"></div>', $id);
    }

    public function renderElFinderScript($container = 'elfinder')
    {
        $connectorUrl = $this->getConnectorURL();
        $script = <<<SCRIPT
    $(function(){
        $('#{$container}').elfinder({
            'url'             : '{$connectorUrl}',
            'getFileCallback' : function(file) {
                window.opener.elFinderFileSelected(file);
                window.close();
            },
            resizable: false
        });
    });
SCRIPT;

        $this->getView()->headScript()->appendScript($script);

        return $this;
    }

    public function renderStandAloneScript($container = 'elfinder')
    {
        $connectorUrl = $this->getConnectorURL();
        $script = <<<SCRIPT
    $(function(){
        $('#{$container}').elfinder({url : '{$connectorUrl}'});
    });
SCRIPT;
        $this->getView()->headScript()->appendScript($script);

        return $this;
    }
    public function renderCkEditorScript($container = 'elfinder')
    {
        $connectorUrl = $this->getConnectorURL();
        $script = <<<SCRIPT
    function getUrlParam(paramName) {
        var reParam = new RegExp('(?:[\?&]|&amp;)' + paramName + '=([^&]+)', 'i') ;
        var match = window.location.search.match(reParam) ;

        return (match && match.length > 1) ? match[1] : '' ;
    }

    $(function(){
        var funcNum = getUrlParam('CKEditorFuncNum');

        $('#{$container}').elfinder({
            url : '{$connectorUrl}',
            getFileCallback : function(file) {
                window.opener.CKEDITOR.tools.callFunction(funcNum, file.url);
                window.close();
            },
            resizable: false
        });
    });
SCRIPT;

        $this->getView()->headScript()->appendScript($script);

        return $this;
    }

    public function setConnectorURL($url = '/elfinder/connector')
    {
        $this->connectorURL = $url;

        return $this;
    }

    public function getConnectorURL()
    {
        return $this->connectorURL;
    }
}