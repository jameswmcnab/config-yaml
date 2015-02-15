<?php

namespace spec\Jameswmcnab\ConfigYaml;

use Illuminate\Filesystem\Filesystem;
use Jameswmcnab\ConfigYaml\Parser;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class YamlFileLoaderSpec extends ObjectBehavior
{

    /**
     * @type string
     */
    private $defaultPath;

    function let(Filesystem $files, Parser $parser)
    {
        $this->defaultPath = '/config_path';

        $this->beConstructedWith($files, $parser, $this->defaultPath);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Jameswmcnab\ConfigYaml\YamlFileLoader');
    }

    function it_can_load_non_namespaced_config(Filesystem $files, Parser $parser)
    {
        $yaml = 'foo: bar';
        $parsed = ['foo' => 'bar'];

        $path = $this->defaultPath.'/config.yaml';

        $files->exists($path)->willReturn(true);

        $files->get($path)->willReturn($yaml);

        $parser->parseString($yaml)->willReturn($parsed);

        $this->load('config', null)->shouldReturn($parsed);
    }

    function it_can_load_namespaced_config(Filesystem $files, Parser $parser)
    {
        $yaml = 'foo: bar';
        $parsed = ['foo' => 'bar'];

        $namespacePath = $this->defaultPath.'/namespace';
        $path = $namespacePath.'/config.yaml';

        $files->exists($path)->willReturn(true);

        $files->get($path)->willReturn($yaml);

        $this->addNamespace('namespace', $namespacePath);

        $parser->parseString($yaml)->willReturn($parsed);

        $this->load('config', 'namespace')->shouldReturn($parsed);
    }

}
