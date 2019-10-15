<?php

namespace AppBundle\Form\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Templating\EngineInterface;

class BaseHandler
{
    /**
     * @var Form
     */
    protected $form;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;
    
    protected $user;
    
    protected $container;

    /**
     * Initialize the handler with the form and the request
     *
     * @param Form                   $form
     * @param Request                $request
     * @param EntityManagerInterface $entityManager
     * @param $user
     */
    public function __construct(
        Form $form,
        Request $request,
        EntityManagerInterface $entityManager,
        $user = null,
        $container = null
    ) {
        $this->form          = $form;
        $this->request       = $request;
        $this->entityManager = $entityManager;
        $this->user          = $user;
        $this->container     = $container;
    }

    /**
     * Process form
     *
     * @return mixed
     */
    public function process()
    {
        $this->form->handleRequest($this->request);
       
        if ($this->request->isMethod('post') && $this->form->isValid()) {
            $data = $this->form->getData();
            if ($this->user) {
                $data->setAbstractUser($this->user);
            }
            
            $this->onSuccess($data);

            return true;
        }

        return false;
    }

    /**
     * Save on success
     *
     * @param array $data
     *
     */
    protected function onSuccess($data)
    {
        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }

    /**
     * getForm
     *
     * @return type
     */
    public function getForm()
    {
        return $this->form;
    }
}