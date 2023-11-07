<?php

namespace touiteur\Action;

use \touiteur\Action\Action;
class ActionDefault extends Action
{

    public function execute(): string
    {
        return<<<END
<h2> Bienvenue sur Touiteur </h2>
END;

        // TODO: Implement execute() method.
    }
}