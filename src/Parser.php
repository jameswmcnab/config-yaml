<?php

namespace Jameswmcnab\ConfigYaml;

use Symfony\Component\Yaml\Parser as SymfonyParser;
use Symfony\Component\Yaml\Exception\ParseException;

class Parser
{
    /**
     * @var \Symfony\Component\Yaml\Parser
     */
    protected $symfonyParser;

    /**
     * @var \Symfony\Component\Yaml\Exception\ParseException
     */
    protected $lastParseException;

    /**
     * @param SymfonyParser $symfonyParser
     */
    public function __construct(SymfonyParser $symfonyParser)
    {
        $this->symfonyParser = $symfonyParser;
    }

    /**
     * Parse a string of YAML and return the.
     *
     * @param string $string
     *
     * @return string
     *
     * @throws \Symfony\Component\Yaml\Exception\ParseException
     */
    public function parseString($string)
    {
        try {
            return $this->symfonyParser->parse($string);
        } catch (ParseException $e) {
            $this->lastParseException = $e;
            throw $e;
        }
    }
}
