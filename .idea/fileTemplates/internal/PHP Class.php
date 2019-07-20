<?php
#parse("PHP File Header.php")

#if (${NAMESPACE})namespace ${NAMESPACE};
#end

class ${NAME}
{
    public function __invoke() : void
    {
        // TODO: Implement method
        dd('TODO: Implement ' . __METHOD__ . '()');
    }
}
