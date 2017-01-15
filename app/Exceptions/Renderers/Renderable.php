<?php

namespace App\Exceptions\Renderers;

interface Renderable
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \Exception $exception
     * @return mixed
     */
    public function render($request, \Exception $exception);
}