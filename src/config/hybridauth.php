<?php

return array(
    "base_url" => "http://dev.local/slowloris/public/social/auth",
    "providers" => array(
        "Google" => array(
            "enabled" => true,
            "keys" => array("id" => "ID", "secret" => "SECRET"),
        ),
        "Facebook" => array(
            "enabled" => true,
            "keys" => array("id" => "605542416204530", "secret" => "b76491b3c8d9602d5572eab17c15b000"),
        ),
        "Twitter" => array(
            "enabled" => false,
            "keys" => array("key" => "ID", "secret" => "SECRET")
        )
    ),
);
