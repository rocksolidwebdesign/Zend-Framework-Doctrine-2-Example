<?php
class BlogController extends Zend_Controller_Action
{
    protected $_doctrine;
    protected $_flash;

    public function init()
    {
        $this->_doctrine = \Zend_Registry::get('doctrineEm');
        $this->_repo = $this->_doctrine->getRepository('Entities\Blog\Entry');
        $this->_flash = $this->_helper->getHelper('FlashMessenger');
    }

    public function indexAction()
    {
        $page = $this->getRequest()->getParam('page');
        $page = $page ? $page : 0;

        $size = $this->getRequest()->getParam('size');
        $size = $size ? $size : 10;

        $this->view->pageNum  = $page;
        $this->view->pageSize = $size;
        $this->view->count    = $this->_repo->getCount();
        $this->view->numPages = ($numPages = $this->_repo->getPageCount($size));
        $this->view->rows     = $this->_repo->getPage($page, $size);

        $pagerRadius = 3;

        $lbound = $page - $pagerRadius;
        $rbound = $page + $pagerRadius;

        $this->view->lBound = $lbound  > 0 ? $lbound : 0;
        $this->view->rBound = $rbound <= $numPages ? $rbound : $numPages;

        $this->view->lNum = $page - 1 ? $page - 1 : 1;
        $this->view->rNum = $page + 1 <= $numPages ? $page + 1 : $numPages;
    }

    public function showAction()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            $this->view->row = $this->_doctrine->find('Entities\Blog\Entry', $id);
        } else {
            $this->_helper->redirector('index');
        }
    }

    public function editAction()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            $entity = $this->_doctrine->find('Entities\Blog\Entry', $id);
            $form = new Application_Form_BlogEntry(null, $entity);
            $form->setAction("/blog/edit/$id");
            $this->updateAction($form, $entity);
            $this->view->actionType = 'Update';
        } else {
            $form = new Application_Form_BlogEntry();
            $form->setAction('/blog/edit');
            $this->createAction($form);
            $this->view->actionType = 'Create';
        }

        $this->view->form = $form;
    }

    public function createAction($form = false)
    {
        $form = $form ? $form : new \Application_Form_BlogEntry;

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {
                // create the entity
                $entity = new \Entities\Blog\Entry;

                // save the entity
                $entity->setData($this->getRequest()->getPost('entity'));

                $this->_doctrine->persist($entity);
                $this->_doctrine->flush();

                // report creation to the user
                $this->_flash->addMessage("Blog entry successfully created.");

                $this->_helper->redirector('index');
            }
        }
    }

    public function updateAction($form = false, $entity = false)
    {
        $form = $form ? $form : new \Application_Form_BlogEntry;

        if ($this->getRequest()->getPost()
            && $form->isValid($this->getRequest()->getPost())
            && ($id = $this->getRequest()->getParam('id'))
        ) {
            // Find the entity
            $entity = $entity ? $entity : $this->_doctrine->find('Entries\Blog\Entry', $id);

            if (!$entity) {
                $this->_flash->addMessage("Blog entry $id not found.");
                $this->_helper->redirector('index');
            }

            // Save the entity
            $entity->setData($this->getRequest()->getPost('entity'));

            $this->_doctrine->persist($entity);
            $this->_doctrine->flush();

            // Report update to the user
            $this->_flash->addMessage('Blog entry successfully saved.');
            $this->_helper->redirector('index');
        }
    }

    public function deleteAction()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            $entity = $this->_doctrine->find('Entities\Blog\Entry', $id);
            $this->_doctrine->remove($entity);
            $this->_doctrine->flush();
        }

        $this->_helper->redirector('index');
    }
}
