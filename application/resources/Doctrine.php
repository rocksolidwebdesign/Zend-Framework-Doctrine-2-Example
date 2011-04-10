<?php
class My_Resource_Doctrine extends Zend_Application_Resource_ResourceAbstract
{
    private $_doctrine;

    public function init()
    {
        return $this->getDoctrine();
    }

    public function getDoctrine()
    {
        if (null === $this->_doctrine) {

            // Get "configs/application.ini" settings
            $zfConfigArray = $this->getBootstrap()->getOptions();

            include_once(APPLICATION_PATH.'/doctrine/common.php');

            // put doctrine in the registry for more reliable and consistent access
            \Zend_Registry::set('doctrineEm', $em);

            $this->_doctrine = $em;
        }

        return $this->_doctrine;
    }
}
