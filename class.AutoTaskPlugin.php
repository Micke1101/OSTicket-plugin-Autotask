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
            global $thisstaff, $ost;
            $config = $this->getConfig();
			if(!array_key_exists($ticket->getTopicId(), (is_array($config->get('topics'))) ? $config->get('topics') : array($config->get('topics'))))
				return;
            if(!$thisstaff)
                $thisstaff = Staff::lookup($config->get('imitate'));
            $vars = array(
                'object_id' => $ticket->getId(),
                'object_type' => 'T',
                'description' => $ost->replaceTemplateVariables(str_replace('%{ticket.id}', $ticket->getId(), $config->get('message')), array('ticket' => $ticket)),
                'default_formdata' => array(
                    'title' => $ost->replaceTemplateVariables(str_replace('%{ticket.id}', $ticket->getId(), $config->get('subject')), array('ticket' => $ticket)),
                    'description' => $ost->replaceTemplateVariables(str_replace('%{ticket.id}', $ticket->getId(), $config->get('message')), array('ticket' => $ticket)),
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


