<?php

namespace Qirolab\Laravel\Reactions\Contracts;

interface ReactsInterface
{
    /**
     * Reaction on reactable model.
     *
     * @param  ReactableInterface  $reactable
     * @param  mixed  $type
     * @return void
     */
    public function reactTo(ReactableInterface $reactable, $type);
}
