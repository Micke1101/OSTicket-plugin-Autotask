<?php
require_once INCLUDE_DIR . 'class.plugin.php';

class AutoTaskPluginConfig extends PluginConfig
{

    // Provide compatibility function for versions of osTicket prior to
    // translation support (v1.9.4)
    function translate()
    {
        if (! method_exists('Plugin', 'translate')) {
            return array(
                function ($x) {
                    return $x;
                },
                function ($x, $y, $n) {
                    return $n != 1 ? $y : $x;
                }
            );
        }
        return Plugin::translate('autotask');
    }

    /**
     * Build an Admin settings page.
     *
     * {@inheritdoc}
     *
     * @see PluginConfig::getOptions()
     */
    function getOptions()
    {
        list ($__, $_N) = self::translate();
        $departments = Dept::getDepartments();
        $agents = Staff::getStaffMembers();
        return array(
            'assignee' => new ChoiceField([
                'label' => $__('Assigned agent'),
                'required' => false,
                'hint' => $__('What agent will have the tasks assigned to them.'),
                'default' => '',
                'choices' => $agents
            ]),
            'imitate' => new ChoiceField([
                'label' => $__('Imitated agent'),
                'required' => true,
                'hint' => $__('If a end user is creating a ticket we need to imitate the staff member, suggested is to create a system account for this.'),
                'default' => '',
                'choices' => $agents
            ]),
            'department' => new ChoiceField([
                'label' => $__('Department'),
                'required' => true,
                'hint' => $__('What department to assign the task to.'),
                'default' => '',
                'choices' => $departments
            ]),
            'subject' => new TextboxField([
                'label' => $__('Subject of task'),
                'hint' => $__('Subject of task'),
                'required' => true,
                'default' => $__('Task for #%{ticket.number}'),
                'configuration' => array(
                    'size' => 40,
                    'length' => 256
                )
            ]),
            'message' => new TextareaField([
                'label' => $__('Message shown in task'),
                'hint' => $__('Use variables the same as Internal Note alert'),
                'required' => true,
                'default' => 'My ticket is <a href="tickets.php?id=%{ticket.id}">here</a>'
            ]),
        );
    }
}
