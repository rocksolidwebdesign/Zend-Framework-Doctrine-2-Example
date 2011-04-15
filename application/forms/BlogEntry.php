<?php
class Application_Form_BlogEntry extends Zend_Form
{
    protected $_popValues;

    public function __construct($options = null, $entity = false)
    {
        if ($entity) {
            $this->_popValues = $entity;
        }

        parent::__construct($options);
    }

    public function init()
    {
        $this->setMethod('post');
        $this->setAttribs(array('id' => 'blog_entry_edit_form', 'class' => 'blog-entry-edit-form'));

        $this->addElement(new Zend_Form_Element_Text('title', array('belongsTo' => 'entity', 'label' => 'Title')));
        $this->addElement(new Zend_Form_Element_Text('permalink', array('belongsTo' => 'entity', 'label' => 'Permalink URL')));
        $this->addElement(new Zend_Form_Element_Doctrine_DateTime('pub_date', array('belongsTo' => 'entity', 'label' => 'Published On')));
        $this->addElement(new Zend_Form_Element_Textarea('content', array('belongsTo' => 'entity', 'label' => 'Content')));

        $hidden = $this->_getHidingDecorators();
        $this->addElement(new Zend_Form_Element_Hash('csrf_hash', array('ignore' => true, 'decorators' => $hidden)));
        $this->addElement(new Zend_Form_Element_Submit('submit', array('label' => 'Submit')));

        if ($this->_popValues) {
            $this->title->setValue($this->_popValues->title);
            $this->permalink->setValue($this->_popValues->permalink);
            $this->pub_date->setValue($this->_popValues->pub_date);
            $this->content->setValue($this->_popValues->content);
        }
    }

    protected function _getHidingDecorators() {
        return array(
            'ViewHelper', array(
                array('input_wrapper' => 'HtmlTag'),
                array('tag' => 'div', 'style' => 'display: none;'),
            )
        );
    }
}
