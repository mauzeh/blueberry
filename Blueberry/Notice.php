<?php

/**
 * Handles the display of system notices and errors to the user.
 * Also contains static methods that manage the notices that need to be
 * displayed in the current page cycle.
 *
 * Saves to session to ensure notice display upon redirect.
 */
class Blueberry_Notice {

    const TYPE_ERROR   = 'error';
    const TYPE_SUCCESS = 'success';
    const TYPE_NOTICE  = 'notice';
	const TYPE_INFO    = 'info';
	const TYPE_DEBUG   = 'debug';

    /**
     * Triggers the system to display the message.
     *
     * @param string $type
     *  Any of the self::TYPE_* constants.
     * @param string $title
     *  The title of the notice.
     * @param mixed $message
     *  A longer message (optional).
     * @param array $roles
     *  An array with the user roles who may see this message. If empty, then
     *  everyone can see this message.
     */
    public static function raise(
        $type = self::TYPE_ERROR, $title, $message = '', $roles = array()
    ){
	    if($message === ''){
		    $message = $title;
		    $title = '';
	    }

        $session = self::init();
        $session->notices[] = (object) array(
            'type'    => $type,
            'title'   => $title,
            'message' => $message,
	        'roles'   => $roles
        );
    }

	/**
	 * Clears notices from the registry.
	 */
	public static function clear()
	{
		$session = new Zend_Session_Namespace('Blueberry_Notice');
		$session->notices = array();
	}

	/**
     * Initializes the session registry.
     *
     * @return array A reference to the session registry.
     */
    public static function init()
    {
        $session = new Zend_Session_Namespace('Blueberry_Notice');
        if(!isset($session->notices)){
            $session->notices = array();
        }
        return $session;
    }

    /**
     * Returns the number of notices stacked up for display.
     *
     * @param $type Only count messages of type $type.
     */
    public static function count()
    {
        $session = self::init();
        return count($session->notices);
    }

    /**
     * Detects the set notices and returns them in HTML.
     *
     * @return The HTML with all the rendered notices in the current page cycle.
     */
    public static function detect()
    {
        $session = self::init();
        $html = '';

        while(count($session->notices))
        {
            $notice = array_shift($session->notices);

	        // Skip if user is not allowed to see this notice
	        $auth = new Zend_Session_Namespace('Zend_Auth');
	        $user = $auth->user;
	        if( ! empty($notice->roles) &&
	            ! in_array($user->role_id, $notice->roles)){
		        continue;
	        }

	        $title = '';
	        if($notice->title){
	            $title = sprintf(
	                '<div class="bb-notice-title">%s</div>',
	                $notice->title
	            );
	        }
            $message = '';
            if($notice->message){
                $message = sprintf(
                    '<div class="bb-notice-message">%s</div>',
                    $notice->message
                );
            }
            $html .= sprintf(
                '<div class="bb-notice bb-notice-%s">%s%s</div>',
                $notice->type, $title, $message
            );
        }
        return $html;
    }
}
