<?php
/**
 * AweCMS
 *
 * LICENSE
 *
 * This source file is subject to the BSD license that is bundled
 * with this package in the file LICENSE.txt
 *
 * It is also available through the world-wide-web at this URL:
 * http://www.opensource.org/licenses/bsd-license.php
 *
 * @category   AweCMS
 * @package    AweCMS_Doctrine_DBAL_Logging
 * @copyright  Copyright (c) 2010 Rock Solid Web Design (http://rocksolidwebdesign.com)
 * @license    http://www.opensource.org/licenses/bsd-license.php BSD License
 */

namespace Doctrine\DBAL\Logging;

class FileSQLLogger implements SQLLogger
{
    private $_path;
    private $_name;

    public function __construct($path, $name = 'doctrine.log')
    {
        $this->_path = $path;
        $this->_name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function startQuery($sql, array $params = null, array $types = null)
    {
        $path = "$this->_path/$this->_name";
        if (is_dir($this->_path) && is_writeable($this->_path)) {
            $fh = fopen($path, "a");
            if ($fh) {
                fwrite($fh, $sql . PHP_EOL);
                fwrite($fh, print_r($params, true));
                fwrite($fh, print_r($types, true));
                fwrite($fh, PHP_EOL);
            }
            fclose($fh);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function stopQuery()
    {

    }
}
