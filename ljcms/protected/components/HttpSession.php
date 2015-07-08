<?php

class HttpSession extends CHttpSession
{
    public function regenerateID($deleteOldSession = false) 
    {
        if(session_id() === '')
            session_regenerate_id($deleteOldSession);
    }
}

