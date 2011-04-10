<?php
class Application_Form_BlogEntry extends Zend_Form
{
    protected $_pop_values;

    public function __construct($options = null, $entity = false)
    {
        if ($entity) {
            $this->_pop_values = $entity;
        }

        parent::__construct($options);
    }

    public function init()
    {
        $this->setMethod('post');
        $this->setAttribs(array('id' => 'blog_entry_edit_form', 'class' => 'blog-entry-edit-form'));

        $subform = new Zend_Form_SubForm();
        $this->addSubForms(array('entity' => $subform));

        $subform->addElement(new Zend_Form_Element_Text('title', array('label' => 'Title')));
        $subform->addElement(new Zend_Form_Element_Text('permalink', array('label' => 'Permalink URL')));
        $subform->addElement(new Zend_Form_Element_DateTime('pub_date', array('label' => 'Published On')));
        $subform->addElement(new Zend_Form_Element_Textarea('content', array('label' => 'Content')));

        $hidden = $this->_getHidingDecorators();

        // CSRF
        $this->addElement(new Zend_Form_Element_Hash('csrf_hash', array(
            'ignore' => true,
            'decorators' => $hidden
        )));
        $this->addElement(new Zend_Form_Element_Submit('submit', array('label' => 'Submit')));

        if ($this->_pop_values) {
            $subform->title->setValue($this->_pop_values->title);
            $subform->permalink->setValue($this->_pop_values->permalink);
            $subform->pub_date->setValue($this->_pop_values->pub_date);
            $subform->content->setValue($this->_pop_values->content);
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
