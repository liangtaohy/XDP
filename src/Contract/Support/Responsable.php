<?php

namespace Xdp\Contract\Support;

interface Responsable
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Xdp\Http\Request  $request
     * @return \Xdp\Http\Response
     */
    public function toResponse($request);
}
