<?php

namespace RedBeanBlog\Controller;

use RedBeanPHP\R as R;
use Phroute\Phroute\Exception\HttpException;

class Controller
{
    protected $type;

    public function loadBean(int $id)
    {
        $bean = R::load( $this->type, $id );
        if ($bean->id === 0) {
            $msg = ucfirst($this->type) . " $id not found.";
            throw new HttpException($msg, 404);
        }
        return $bean;
    }
}
