<?php

/**
 * Generate custom response types
 *
 */


class Response {

    /**
     * Redirect to the given URL, either by headers or by javascript/html
     *
     */
    public function redirect($url) {
        if(!headers_sent()) {
            header('Location: '.$url);
            die();
        } else {
            echo '<script type="text/javascript">window.location.href="'.$url.'";</script>';
            echo '<noscript><meta http-equiv="refresh" content="0;url='.$url.'" /></noscript>';
            die();
        }
    }

    /**
     * JSON response to the browser, helpful for APIs
     */
    public function json($data) {
        echo json_encode($data);
    }

    /**
     * Shorthand response function to generate a view.
     *
     */
    public function view($path, $data) {
        $view = new View;
        $view->set('data', $data);
        $view->make($path);
    }
}