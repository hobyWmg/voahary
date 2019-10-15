<?php

namespace AppBundle\Form\Handler;

class CarouselEntityHandler extends BaseHandler {
    
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
            $data->setOrdre(-1);
            $this->onSuccess($data);

            return true;
        }

        return false;
    }
}