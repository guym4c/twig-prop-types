<?php /** @noinspection PhpMissingParentCallCommonInspection */

namespace Guym4c\TwigProps;

use Guym4c\PropTypes\PropTypes;
use Twig;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use voku\helper\UTF8;

class PropTypesExtension extends AbstractExtension {

    private const ASSERT_PROP_TYPES_FUNCTION_NAME = 'props';
    private const SET_DEFAULT_PROPS_FUNCTION_NAME = 'defaults';

    private array $options;

    private array $exclude;

    private bool $bypass;

    /**
     * PropTypesExtension constructor.
     * @param Twig\Environment $view
     * @param bool $bypass
     * @param string $typesGlobal The global variable you use to access PropTypes
     * @param array $exclude Variable names in the twig context you would like to exclude
     * @param bool $allowExtraProperties Or, you may ignore all extra props
     */
    public function __construct(
        Twig\Environment $view,
        bool $bypass,
        string $typesGlobal = 'PropTypes',
        array $exclude = [],
        bool $allowExtraProperties = false
    ) {
        $view->addGlobal($typesGlobal, new TwigPropTypes());
        $this->options = ['allow_extra_properties' => $allowExtraProperties];
        $this->exclude = array_merge($exclude, [$typesGlobal]);
        $this->bypass = $bypass;
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array {
        return array_merge(
            $this->bypass
                ? [
                    new TwigFunction(self::ASSERT_PROP_TYPES_FUNCTION_NAME, function (array $props): void {})
                ]
                : [new TwigFunction(
                    self::ASSERT_PROP_TYPES_FUNCTION_NAME,
                    function (array $context, array $props): void {
                        $this->check($context, $props);
                    },
                    ['needs_context' => true],
                )],
            [new TwigFunction(
                self::SET_DEFAULT_PROPS_FUNCTION_NAME,
                [$this, 'defaults'],
                ['needs_context' => true],
            )]
        );
    }

    public function defaults(array &$context, array $defaults): void {
        foreach ($defaults as $key => $value) {
            if (!isset($context[$key])) {
                $context[$key] = $value;
            }
        }
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