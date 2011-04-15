<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Form
 * @subpackage Element
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/** Zend_Form_Element_Xhtml */
require_once 'Zend/Form/Element/Xhtml.php';

/**
 * DateTime form element
 *
 * @category   Zend
 * @package    Zend_Form
 * @subpackage Element
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Password.php 23775 2011-03-01 17:25:24Z ralph $
 */
class Zend_Form_Element_Doctrine_Abstract extends Zend_Form_Element_Xhtml
{
    protected $_doctrine;
    protected $_entityName;

    public function init()
    {
        $this->_doctrine = \Zend_Registry::get('doctrineEm');
        parent::init();
    }

    public function setEntityName($name)
    {
        $this->_entityName = $name;
        return $this;
    }

    public function getEntityName()
    {
        return $this->_entityName;
    }

    public function getRepo()
    {
        if ($this->_repo == null) {
            $this->_repo = $this->_doctrine->getRepository($this->_entityName);
        }
        return $this->_repo;
    }
}
