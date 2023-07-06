<?php

namespace Emma\App\ErrorHandler;

use Emma\Common\CallBackHandler\CallBackHandler;

/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 */
interface EventHandlerInterface
{
    /**
     * @return CallBackHandler[]
     */
    public function getCallbacks(): array;

    /**
     * @param array $callbacks
     * @return $this
     */
    public function setCallbacks(array $callbacks): static;

    /**
     * @return mixed
     */
    public function getHandlerType(): mixed;

    /**
     * @param mixed $handlerType
     * @return $this
     */
    public function setHandlerType(mixed $handlerType): static;


}