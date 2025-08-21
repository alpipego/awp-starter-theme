<?php

namespace Theme\Translations;

class Translatable
{
    public readonly string $key;

    public function __construct(
        public string $string,
        ?string $key = null,
        public readonly ?string $context = null,
        public readonly ?string $plural = null,
        public readonly null|int|float $number = null,
    ) {
        if (empty($this->string)) {
            throw new \RuntimeException('Translation string cannot be empty.');
        }
        $this->key = $this->sanitizeKey($key ?? $this->string);

        if (!empty($this->plural) && empty($this->number)) {
            throw new \RuntimeException('Plural translation requires a number.');
        }
    }

    public function __toString()
    {
        return $this->string;
    }

    public function resolve(string $domain): string
    {
        if ($this->plural !== null) {
            if ($this->context !== null) {
                return "_nx('$this->string', '$this->plural', $this->number, '$this->context', '$domain')";
            }

            return "_n('$this->string', '$this->plural', '$this->number', '$domain')";
        }

        if ($this->context !== null) {
            return "_x('$this->string', '$this->context', '$domain')";
        }

        return "__('$this->string', '$domain')";
    }

    private function sanitizeKey(string $key): string
    {
        $key = preg_replace('/[^a-z0-9_-]/i', '_', trim($key));
        if (empty($key)) {
            $key = preg_replace('/[^a-z0-9_-]/i', '_', trim($this->string));
        }

        return $key;
    }
}
