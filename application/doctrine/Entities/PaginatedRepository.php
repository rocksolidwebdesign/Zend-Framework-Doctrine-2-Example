<?php

namespace Entities;

use Doctrine\ORM\EntityRepository;

class PaginatedRepository extends EntityRepository
{
    protected $_entityClassName;
    protected $_recordCount;
    protected $_pageCount;

    public function getAll()
    {
        $q = $this->_em->createQuery("SELECT e FROM $this->_entityClassName e");
        $q->setMaxResults(100);
        return $q->getResult();
    }

    public function getPage($page_num = 0, $per_page = 10)
    {
        $limit = $per_page;
        $offset = $per_page * $page_num;

        $q = $this->_em->createQuery("SELECT e FROM $this->_entityClassName e");
        $q->setMaxResults($limit);
        $q->setFirstResult($offset);
        return $q->getResult();
    }

    public function getPageCount($size)
    {
        if ($this->_pageCount == null) {
            $this->_pageCount = ceil($this->getCount() / $size);
        }
        return $this->_pageCount;
    }

    public function getCount()
    {
        if ($this->_recordCount == null) {
            $q = $this->_em->createQuery("SELECT COUNT(e.id) FROM $this->_entityClassName e");
            $this->_recordCount = $q->getSingleScalarResult();
        }
        return $this->_recordCount;
    }

}
