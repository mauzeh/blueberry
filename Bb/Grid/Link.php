<?php

/**
 * Represents a link that is placed in the grid. This is currently
 * only used in the Actions column. The generated output in the grid will
 * be:
 * <code>
 * <a href="{$baseUrl}/id/{$id}" class="icon {$icon}">{$label}</a>
 * </code>
 */
class Blueberry_Grid_Link {

    /**
     * @var string The url location of the base page. Note that
     *             currently the string "/id/{$id}" is appended to this
     *             param to form the total url. {$id} refers to the "id"
     *             attribute of the object that belongs to a row.
     */
    protected $baseUrl = '';

    /**
     * @var string The css class that will load the icon. If no icon
     *             is defined, then the link will be rendered as a regular
     *             link.
     */
    protected $icon = '';

    /**
     * @var string The label of the link (eg. "Edit..." or "Delete...")
     */
    protected $label = '';

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
     *
     */
    public function __construct($label, $baseUrl, $icon = ''){

        $this->baseUrl = $baseUrl;
        $this->label = $label;
        $this->icon = $icon;

    }

    /**
     * Renders the link.
     * @param mixed $id The value of the object's "id" attribute
     */
    public function render($id) {

        // If an icon is defined, use it. Else: keep css class empty
        $class = $this->icon != '' ? 'icon '.$this->icon : '';

        return sprintf('<a href="%s/id/%s" class="%s">%s</a>',
                       $this->baseUrl, $id, $class, $this->label);

    }

}
