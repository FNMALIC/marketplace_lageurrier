<?php
namespace App\Form\EventListener;

use Symfony\Component\Form\FormEvent;

class FormSubmitListener
{
    public function onFormSubmit(FormEvent $event)
    {
        $data = $event->getData();

        // Modify the data as needed
        $data['product']['store'] = $_GET['id'];

        // Update the data in the event
        $event->setData($data);
    }
}
