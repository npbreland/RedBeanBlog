<?php

namespace RedBeanBlog\Controller;

use RedBeanPHP\R as R;

class Posts extends Controller
{
    protected $type;

    public function __construct()
    {
        $this->type = 'post';
    }

    public function readAll()
    {
        $order_by = isset($_GET['orderBy']) ? $_GET['orderBy'] : 'publish_date';
        $order = isset($_GET['order']) ? strtoupper($_GET['order']) : 'DESC';
        $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 20;
        $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;

        if ($order !== 'ASC' && $order !== 'DESC') {
            echo 'Order can only be ASC or DESC';
        }

        $allowed_order_bys = [ 'publish_date' ];
        if (!in_array($order_by, $allowed_order_bys)) {
            echo 'Can only order by ' . implode(', ', $allowed_order_bys);
        }
        
        $posts = R::findAll( 
            $this->type,
            " ORDER BY $order_by $order LIMIT $limit OFFSET $offset "
        );

        echo json_encode($posts);
    }

    public function read($id)
    {
        $post = $this->loadBean($id);
        echo json_encode($post);
    }

    public function create()
    {
        $post = R::dispense( $this->type );
        $content = $_POST['content'];

        $post->content = $content;
        $post->publish_date = new \DateTime();

        // TODO: tags
        R::store( $post );
    }

    public function update($id)
    {
        parse_str(file_get_contents("php://input"), $_PATCH);
        $content = $_PATCH['content'];

        $post = $this->loadBean($id);
        $post->content = $content;

        R::store( $post );
    }


    public function delete($id)
    {
        $post = $this->loadBean($id);
        R::trash( $post );
    }

}
