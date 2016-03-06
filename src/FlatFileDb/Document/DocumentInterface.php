<?php
namespace FlatFileDb\Document;

interface DocumentInterface
{
    public function getIdentifier();
    public function setIdentifier($id);
}