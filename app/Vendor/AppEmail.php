<?php

App::uses('CakeEmail', 'Network/Email');

class AppEmail extends CakeEmail
{

    public function compile($template = false, $viewVars = false): array
    {
        $this->template($template);
        $this->viewVars($viewVars);
        $this->_layout = false;

        $content = $this->_renderTemplates($this->_wrap(null));

        return $content;
    }

}