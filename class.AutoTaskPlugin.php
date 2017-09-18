<?php
require_once (INCLUDE_DIR . 'class.signal.php');
require_once ('config.php');

class AutoTaskPlugin extends Plugin {
    const DEBUG = FALSE;
    /**
     * Which config to use (in config.php)
     *
     * @var string
     */
    public $config_class = 'AutoTaskPluginConfig';
    
    /**
     * Run on every instantiation of osTicket..
     * needs to be concise
     *
     * {@inheritdoc}
     *
     * @see Plugin::bootstrap()
     */
    function bootstrap() {
        Signal::connect ( 'ticket.created', function (Ticket $ticket) {
            global $thisstaff;
            $config = $this->getConfig();
            if(!$thisstaff)
                $thisstaff = Staff::lookup($config->get('imitate'));
            $vars = array(
                'object_id' => $ticket->getId(),
                'object_type' => 'T',
                'description' => $config->get('message'),
                'default_formdata' => array(
                    'title' => $config->get('subject'),
                    'description' => $config->get('message'),
                ),
                'internal_formdata' => array(
                    'dept_id' => $config->get('department'),
                    'assignee' => Staff::lookup($config->get('assignee')),
                )
            );
            Task::create($vars);
        });
    }
    
    function handleVariables($content){
        $content = str_replace();
    }
    
    /**
     * Required stub.
     *
     * {@inheritdoc}
     *
     * @see Plugin::uninstall()
     */
    function uninstall() {
        $errors = array ();
        parent::uninstall ( $errors );
    }
    
    /**
     * Plugins seem to want this.
     */
    public function getForm() {
        return array ();
    }
}


