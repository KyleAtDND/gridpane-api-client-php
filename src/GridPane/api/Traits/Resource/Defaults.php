<?php

namespace GridPane\Api\Traits\Resource;

/**
 * This trait gives resources access to the default CRUD methods.
 */
trait Defaults
{
    use Find;
    use FindAll;
    use Delete;
    use Create;
    use Update;
}
