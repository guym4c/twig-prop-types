<?php /** @noinspection PhpMissingParentCallCommonInspection */

namespace Guym4c\TwigProps;

use Guym4c\PropTypes\PropTypes;
use Twig;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use voku\helper\UTF8;

class PropTypesExtension extends AbstractExtension {

    private array $options;

    private array $exclude;

    /**
     * PropTypesExtension constructor.
     * @param Twig\Environment $view
     * @param string           $typesGlobal The global variable you use to access PropTypes
     * @param array            $exclude Variable names in the twig context you would like to exclude
     * @param bool             $allowExtraProperties Or, you may ignore all extra props
     */
    public function __construct(
        Twig\Environment $view,
        string $typesGlobal = 'PropTypes',
        array $exclude = [],
        bool $allowExtraProperties = false
    ) {
        $view->addGlobal($typesGlobal, new PropTypes());
        $this->options = ['allow_extra_properties' => $allowExtraProperties];
        $this->exclude = array_merge($exclude, [$typesGlobal]);
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array {
        return [new TwigFunction(
            'props',
            function (array $context, array $props): void {
                $this->check($context, $props);
            },
            ['needs_context' => true]
        )];
    }

    private function check(array $context, array $props): void {
        PropTypes::check($props, array_filter(
            $context,
            fn (string $prop): bool => (
                UTF8::substr($prop, 0, 1) !== '_'
                && !in_array($prop, $this->exclude, true)
            ),
            ARRAY_FILTER_USE_KEY
        ), $this->options);
    }
}