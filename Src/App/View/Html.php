<?php
namespace Emma\App\View;

/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 * Date: 2/16/2019
 * Time: 6:26 AM
 */
interface Html
{
    /**
     * @param $attr
     * @param $value
     * @return mixed
     */
    public function addAttribute($attr, $value): static;

    /**
     * @return mixed
     */
    public function removeDefaultAttributes(): static;

    /**
     * @param $attr
     * @return mixed
     */
    public function removeAttribute($attr): static;

    /**
     * @return mixed
     */
    public function getAttributes(): string;

    /**
     * @param $attr
     * @return mixed
     */
    public function getAttributeById($attr): ?string;

    /**
     * @param array $attribs
     * @return mixed
     */
    public function getAttributesExcept(array $attribs = array()): string;

    /**
     * @return string
     */
    public function getHTML(): string;

    /**
     * @echo string
     */
    public function render(): string;

    /**
     * @return string
     */
    public function __toString(): string;

}