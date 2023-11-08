<?php

namespace touiteur\Action;

use \touiteur\Action\Action;

class ActionDefault extends Action
{

    public function execute(): string
    {
        $action = new ActionAfficherListeTouite(ActionAfficherListeTouite::DEFAULT);
        $html = ($action->execute());
        return <<<END
<h2> Bienvenue sur Touiteur </h2> 
$html
END;

        // TODO: Implement execute() method.
    }
}