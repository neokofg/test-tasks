<?php

function get_params(): array
{
    return app('router')->current()->parameters();
}
