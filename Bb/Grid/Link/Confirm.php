<?php

/**
 * Adds Javascript confirmation box to link
 */
class Blueberry_Grid_Link_Confirm extends Blueberry_Grid_Link {

    /**
     * @param string $label The label of the link (eg. "Edit..." or "Delete...")
     * @param string $baseUrl The url location of the base page. Note that
     *               currently the string "/id/{$id}" is appended to this
     *               param to form the total url. {$id} refers to the "id"
     *               attribute of the object that belongs to a row. Example:
     *               <code>
     *               $baseUrl = PATH.'/backend/user/edit/';
     *               </code>
     * @param string $icon The css class that will load the icon. If no icon
     *               is defined, then the link will be rendered as a regular
     *               link.
     * @param string $message The message to be displayed when the user clicks
     *               the link.
     *
     */
    public function __construct($label, $baseUrl, $message, $icon = ''){

        $this->baseUrl = $baseUrl;
        $this->label = $label;
        $this->icon = $icon;
        $this->confirmMessage = $message;

    }

    /**
     * Renders the link.
     * @param mixed $id The value of the object's "id" attribute
     */
    public function render($id) {

        // If an icon is defined, use it. Else: keep css class empty
        $class = $this->icon != '' ? 'icon '.$this->icon : '';

        $confirm = "return confirm('".$this->confirmMessage."')";

        return sprintf('<a href="%s/id/%s" class="%s" onclick="%s">%s</a>',
                       $this->baseUrl, $id, $class, $confirm, $this->label);

    }
}
