<?php
#parse("PHP File Header.php")

#if (${NAMESPACE})namespace ${NAMESPACE};
#end

interface ${NAME}
{
    public function __invoke() : void;
}
