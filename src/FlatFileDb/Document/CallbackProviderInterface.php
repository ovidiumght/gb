<?php

namespace FlatFileDb\Document;

interface CallbackProviderInterface
{
    public function onPreSave();
    public function onPostSave();
    public function onPreRemove();
    public function onPostRemove();
}
