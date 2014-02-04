<?php

/**
 * Dynamically compresses a file during a HTTP request cycle.
 *
 * Specifically aimed at improving performance.
 *
 * Added benefit: allows for PHP to be used inside the file. The file is then
 * also able to access variables at application-level, such as configuration
 * variables in /config.php. This means that you can keep the .css extension
 * which gives you code-coloring and such in popular editors.
 *
 * Usage (add the following code to your bootstrap file, likely to be
 * index.php):
 * <code>
 * <?php
 *
 * $front = Zend_Controller_Front::getInstance();
 * $front->registerPlugin(
 *         new Blueberry_Controller_Plugin_FileCompressor(array(
 *
 *             '.css' => 'text/css',
 *              '.js'  => 'application/x-javascript'
 *
 *         )));
 *
 * ?>
 * </code>
  */
class Blueberry_Controller_Plugin_FileCompressor extends
      Zend_Controller_Plugin_Abstract {

    protected $extensions = array();

    /**
     * @param array $extensions A simple array with the file extensions and
     * their content types for which to perform this action.
     */
    public function __construct($extensions) {

        $this->extensions = $extensions;

    }

    public function routeStartup(Zend_Controller_Request_Abstract $request)
    {

        // The absolute HTTP path to the file
        $path = $request->getRequestUri();

        // The absolute server path to the file (removes PATH)
        $path = str_replace(PATH, '', $path);

        // The file extension
        $extension = array_pop(explode('.', $path));

        foreach($this->extensions as $iteratedExtension => $contentType){

            if('.'.$extension != $iteratedExtension) continue;

            if(!file_exists(ABS_SERVER_PATH.$path)){

                // Needs improvement
                header("HTTP/1.0 404 Not Found");
                echo '<h1>404 not found</h1>';
                exit;

            }

            header('Content-type: '.$contentType, true);

            ob_start("ob_gzhandler");
            header("Cache-Control: must-revalidate, public");
            $offset = 60 * 60 ;
            $ExpStr = "Expires: " .
            gmdate("D, d M Y H:i:s",
            time() + $offset) . " GMT";
            header($ExpStr);

            require ABS_SERVER_PATH.$path;
            exit;


        }
    }
}
