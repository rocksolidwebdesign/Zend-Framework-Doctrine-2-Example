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
require_once 'Zend/Form/Element/Doctrine/Abstract.php';

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
class Zend_Form_Element_Doctrine_DateTime extends Zend_Form_Element_Doctrine_Abstract
{
    public $format = 'Y-m-d H:i:s';

    /**
     * Use doctrineDateTime view helper by default
     * @var string
     */
    public $helper = 'doctrineDateTime';

    /**
     * Set element value
     *
     * @param  mixed $value
     * @return Zend_Form_Element
     */
    public function setValue($value)
    {
        if (is_string($value)) {
            $this->_value = new \DateTime($value);
        } else {
            $this->_value = $value;
        }
        return $this;
    }

    /**
     * Get element value
     *
     * @param  mixed $value
     * @return string
     */
    public function getValue()
    {
        if (is_object($this->_value)) {
            return $this->_value->format($this->format);
        }

        return $this->_value;
    }

    /**
     * Get actual value object
     *
     * @param  mixed $value
     * @return DateTime
     */
    public function getValueObject()
    {
        return $this->_value;
    }
}
