<?php
namespace FlatFileDb\Document;

class Document implements DocumentInterface
{
    public $_id;
    
    public function getIdentifier()
    {
        return $this->_id;
    }
    
    public function setIdentifier($id)
    {
        $this->_id = $id;
    }
}