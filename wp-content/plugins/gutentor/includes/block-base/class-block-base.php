<?php
if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('Gutentor_Block_Base')) {

    /**
     * Base Class For Gutentor for common functions
     * @package Gutentor
     * @since 1.0.1
     *
     */
    class Gutentor_Block_Base{

        /**
         * Prevent some functions to called many times
         * @access private
         * @since 1.0.1
         * @var integer
         */
        private static $counter = 0;

        /**
         * Gets an instance of this object.
         * Prevents duplicate instances which avoid artefacts and improves performance.
         *
         * @static
         * @access public
         * @return object
         * @since 1.0.1
         */
        public static function get_base_instance(){

            // Store the instance locally to avoid private static replication
            static $instance = null;

            // Only run these methods if they haven't been ran previously
            if (null === $instance) {
                $instance = new self();
            }

            // Always return the instance
            return $instance;

        }

        /**
         * Run Block
         *
         * @access public
         * @return void
         * @since 1.0.1
         */
        public function run(){

            if (method_exists($this, 'load_dependencies')) {
                $this->load_dependencies();
            }
            add_action('init', array($this, 'register_and_render'));

            if (self::$counter === 0) {
                add_filter('gutentor_common_attr_default_value', array($this, 'add_single_item_common_attrs_default_values'));
                self::$counter++;
            }
        }

        /**
         * Register this Block
         * Callback will aut called by this function register_block_type
         *
         * @access public
         * @return void
         * @since 1.0.1
         */
        public function register_and_render(){

            $args = array();

            if (method_exists($this, 'render_callback')) {
                $args = array(
                    'render_callback' => array($this, 'render_callback'),
                );
                if ($this->block_name === 'p1') {
                    $attributes = $this->get_attrs();
                } else {
                    if (method_exists($this, 'get_attrs')) {
                        $attributes = array_merge_recursive($this->get_attrs(), $this->get_common_attrs());
                    } else {
                        $attributes = $this->get_common_attrs();
                    }
                }

                $args['attributes'] = $attributes;
            }

            register_block_type('gutentor/' . $this->block_name, $args);

        }

        /**
         * Define Common Attributes
         * It Basically Includes Advanced Attributes
         *
         * @since      1.0.0
         * @package    Gutentor
         * @author     Gutentor <info@gutentor.com>
         */
        public function get_common_attrs(){
            $common_attrs = array(

                /*column*/
                'blockItemsColumn' => array(
                    'type' => 'object',
                    'default' => array(
                        'desktop' => 'grid-md-4',
                        'tablet' => 'grid-sm-4',
                        'mobile' => 'grid-xs-12',
                    )
                ),
                'blockSectionHtmlTag' => array(
                    'type' => 'string',
                    'default' => 'section'
                ),

                /*Advanced Attr*/
                'blockComponentAnimation' => array(
                    'type' => 'object',
                    'default' => array(
                        'Animation' => 'none',
                        'Delay' => '',
                        'Speed' => '',
                        'Iteration' => '',
                    )
                ),
                'blockComponentBGType' => array(
                    'type' => 'string',
                ),
                'blockComponentBGImage' => array(
                    'type' => 'string',
                ),
                'blockComponentBGVideo' => array(
                    'type' => 'object',
                ),
                'blockComponentBGColor' => array(
                    'type' => 'object',
                ),
                'blockComponentBGImageSize' => array(
                    'type' => 'string',
                ),
                'blockComponentBGImagePosition' => array(
                    'type' => 'string',
                ),
                'blockComponentBGImageRepeat' => array(
                    'type' => 'string',
                ),
                'blockComponentBGImageAttachment' => array(
                    'type' => 'string',
                ),
                'blockComponentBGVideoLoop' => array(
                    'type' => 'object',
                    'default' => true
                ),
                'blockComponentBGVideoMuted' => array(
                    'type' => 'boolean',
                    'default' => true
                ),
                'blockComponentEnableOverlay' => array(
                    'type' => 'boolean',
                    'default' => false
                ),
                'blockComponentOverlayColor' => array(
                    'type' => 'string',
                    'default' => ''
                ),
                'blockComponentBoxBorder' => array(
                    'type' => 'object',
                    'default' => array(
                        'borderStyle' => 'none',
                        'borderTop' => '',
                        'borderRight' => '',
                        'borderBottom' => '',
                        'borderLeft' => '',
                        'borderColorNormal' => '',
                        'borderColorHover' => '',
                        'borderRadiusType' => 'px',
                        'borderRadiusTop' => '',
                        'borderRadiusRight' => '',
                        'borderRadiusBottom' => '',
                        'borderRadiusLeft' => '',
                    )
                ),
                'blockComponentMargin' => array(
                    'type' => 'object',
                    'default' => array(
                        'type' => 'px',
                        'desktopTop' => '',
                        'desktopRight' => '',
                        'desktopBottom' => '',
                        'desktopLeft' => '',
                        'tabletTop' => '',
                        'tabletRight' => '',
                        'tabletBottom' => 'px',
                        'tabletLeft' => '',
                        'mobileTop' => '',
                        'mobileRight' => '',
                        'mobileBottom' => '',
                        'mobileLeft' => '',
                    )
                ),
                'blockComponentPadding' => array(
                    'type' => 'object',
                    'default' => array(
                        'type' => 'px',
                        'desktopTop' => '',
                        'desktopRight' => '',
                        'desktopBottom' => '',
                        'desktopLeft' => '',
                        'tabletTop' => '',
                        'tabletRight' => '',
                        'tabletBottom' => 'px',
                        'tabletLeft' => '',
                        'mobileTop' => '',
                        'mobileRight' => '',
                        'mobileBottom' => '',
                        'mobileLeft' => '',
                    )
                ),
                'blockComponentBoxShadowOptions' => array(
                    'type' => 'object',
                    'default' => array(
                        'boxShadowColor' => '',
                        'boxShadowX' => '',
                        'boxShadowY' => '',
                        'boxShadowBlur' => '',
                        'boxShadowSpread' => '',
                        'boxShadowPosition' => '',
                    )
                ),


                /*adv shape*/
                'blockShapeTopSelect' => array(
                    'type' => 'string',
                    'default' => '',
                ),
                'blockShapeTopSelectEnableColor' => array(
                    'type' => 'boolean',
                    'default' => false,
                ),
                'blockShapeTopFlipHorizontally' => array(
                    'type' => 'boolean',
                    'default' => false,
                ),
                'blockShapeTopFlipVertically' => array(
                    'type' => 'boolean',
                    'default' => false,
                ),
                'blockShapeTopSelectColor' => array(
                    'type' => 'object',
                    'default' => '',
                ),
                'blockShapeTopHeight' => array(
                    'type' => 'object',
                    'default' => array(
                        'type' => 'px',
                        'desktop' => '',
                        'tablet' => '',
                        'mobile' => '',
                    )
                ),
                'blockShapeTopWidth' => array(
                    'type' => 'object',
                    'default' => array(
                        'type' => 'px',
                        'desktop' => '',
                        'tablet' => '',
                        'mobile' => '',
                    )
                ),
                'blockShapeTopPosition' => array(
                    'type' => 'boolean',
                    'default' => '',
                ),
                'blockShapeBottomSelect' => array(
                    'type' => 'string',
                    'default' => '',
                ),
                'blockShapeBottomSelectEnableColor' => array(
                    'type' => 'boolean',
                    'default' => '',
                ),
                'blockShapeBottomSelectColor' => array(
                    'type' => 'object',
                    'default' => '',
                ),
                'blockShapeBottomHeight' => array(
                    'type' => 'object',
                    'default' => array(
                        'type' => 'px',
                        'desktop' => '',
                        'tablet' => '',
                        'mobile' => '',
                    )
                ),
                'blockShapeBottomWidth' => array(
                    'type' => 'object',
                    'default' => array(
                        'type' => 'px',
                        'desktop' => '',
                        'tablet' => '',
                        'mobile' => '',
                    )
                ),
                'blockShapeBottomPosition' => array(
                    'type' => 'boolean',
                    'default' => '',
                ),
                'blockComponentEnableHeight' => array(
                    'type' => 'boolean',
                    'default' => false,
                ),
                'blockComponentHeight' => array(
                    'type' => 'object',
                    'default' => array(
                        'type' => 'px',
                        'desktop' => '',
                        'tablet' => '',
                        'mobile' => '',
                    )
                ),
                'blockShapeBottomFlipVertically' => array(
                    'type' => 'boolean',
                    'default' => false,
                ),
                'blockShapeBottomFlipHorizontally' => array(
                    'type' => 'boolean',
                    'default' => false,
                ),
                'blockComponentEnablePosition' => array(
                    'type' => 'boolean',
                    'default' => false,
                ),
                'blockComponentPositionTypeDesktop' => array(
                    'type' => 'string',
                    'default' => 'gutentor-position-default-desktop',
                ),
                'blockComponentPositionTypeTablet' => array(
                    'type' => 'string',
                    'default' => 'gutentor-position-default-tablet',
                ),
                'blockComponentPositionTypeMobile' => array(
                    'type' => 'string',
                    'default' => 'gutentor-position-default-mobile',
                ),
                'blockComponentPositionDesktop' => array(
                    'type' => 'object',
                    'default' => array(
                        'type' => 'px',
                        'top' => '',
                        'right' => '',
                        'bottom' => '',
                        'left' => '',
                    )
                ),
                'blockComponentPositionDesktopWidth' => array(
                    'type' => 'object',
                    'default' => array(
                        'type' => 'px',
                        'width' => '',
                    )
                ),
                'blockComponentPositionTablet' => array(
                    'type' => 'object',
                    'default' => array(
                        'type' => 'px',
                        'top' => '',
                        'right' => '',
                        'bottom' => '',
                        'left' => '',
                    )
                ),
                'blockComponentPositionTabletWidth' => array(
                    'type' => 'object',
                    'default' => array(
                        'type' => 'px',
                        'width' => '',
                    )
                ),
                'blockComponentPositionMobile' => array(
                    'type' => 'object',
                    'default' => array(
                        'type' => 'px',
                        'top' => '',
                        'right' => '',
                        'bottom' => '',
                        'left' => '',
                    )
                ),
                'blockComponentPositionMobileWidth' => array(
                    'type' => 'object',
                    'default' => array(
                        'type' => 'px',
                        'width' => '',
                    )
                ),
                'blockComponentEnableZIndex' => array(
                    'type' => 'boolean',
                    'default' => false,
                ),
                'blockComponentZIndex' => array(
                    'type' => 'object',
                    'default' => array(
                        'desktop' => '',
                        'tablet' => '',
                        'mobile' => '',
                    )
                ),
                'blockComponentDesktopDisplayMode' => array(
                    'type' => 'boolean',
                    'default' => false,
                ),
                'blockComponentTabletDisplayMode' => array(
                    'type' => 'boolean',
                    'default' => false,
                ),
                'blockComponentMobileDisplayMode' => array(
                    'type' => 'boolean',
                    'default' => false,
                ),
                'blockComponentRemoveContainerSpace' => array(
                    'type' => 'object',
                    'default' => array(
                        'desktop' => '',
                        'tablet' => '',
                        'mobile' => '',
                    )
                ),
                'blockComponentRemoveRowSpace' => array(
                    'type' => 'object',
                    'default' => array(
                        'desktop' => '',
                        'tablet' => '',
                        'mobile' => '',
                    )
                ),
                'blockComponentRemoveColumnSpace' => array(
                    'type' => 'object',
                    'default' => array(
                        'desktop' => '',
                        'tablet' => '',
                        'mobile' => '',
                    )
                ),


                /* block component title*/
                'blockComponentTitleEnable' => array(
                    'type' => 'boolean',
                    'default' => true,
                ),
                'blockComponentTitle' => array(
                    'type' => 'string',
                    'default' => __('Block Title', 'gutentor'),
                ),
                'blockComponentTitleTag' => array(
                    'type' => 'string',
                    'default' => 'h2'
                ),
                'blockComponentTitleAlign' => array(
                    'type' => 'string',
                    'default' => 'text-center'
                ),
                'blockComponentTitleColorEnable' => array(
                    'type' => 'boolean',
                    'default' => true
                ),
                'blockComponentTitleColor' => array(
                    'type' => 'object',
                    'default' => array(
                        'hex' => '#111111',
                    )
                ),
                'blockComponentTitleTypography' => array(
                    'type' => 'object',
                    'default' => array(
                        'fontType' => 'default',
                        'systemFont' => '',
                        'googleFont' => '',
                        'customFont' => '',

                        'desktopFontSize' => '',
                        'tabletFontSize' => '',
                        'mobileFontSize' => '',

                        'fontWeight' => '',
                        'textTransform' => '',
                        'fontStyle' => '',
                        'textDecoration' => '',

                        'desktopLineHeight' => '',
                        'tabletLineHeight' => '',
                        'mobileLineHeight' => '',

                        'desktopLetterSpacing' => '',
                        'tabletLetterSpacing' => '',
                        'mobileLetterSpacing' => '',
                    )
                ),
                'blockComponentTitleMargin' => array(
                    'type' => 'object',
                    'default' => array(
                        'type' => 'px',
                        'desktopTop' => '',
                        'desktopRight' => '',
                        'desktopBottom' => '',
                        'desktopLeft' => '',

                        'tabletTop' => '',
                        'tabletRight' => '',
                        'tabletBottom' => '',
                        'tabletLeft' => '',

                        'mobileTop' => '',
                        'mobileRight' => '',
                        'mobileBottom' => '',
                        'mobileLeft' => '',

                    )
                ),
                'blockComponentTitlePadding' => array(
                    'type' => 'object',
                    'default' => array(
                        'type' => 'px',
                        'desktopTop' => '',
                        'desktopRight' => '',
                        'desktopBottom' => '',
                        'desktopLeft' => '',

                        'tabletTop' => '',
                        'tabletRight' => '',
                        'tabletBottom' => '',
                        'tabletLeft' => '',

                        'mobileTop' => '',
                        'mobileRight' => '',
                        'mobileBottom' => '',
                        'mobileLeft' => '',

                    )
                ),
                'blockComponentTitleAnimation' => array(
                    'type' => 'object',
                    'default' => array(
                        'Animation' => 'none',
                        'Delay' => '',
                        'Speed' => '',
                        'Iteration' => '',
                    )
                ),
                'blockComponentTitleDesignEnable' => array(
                    'type' => 'boolean',
                    'default' => true,
                ),
                'blockComponentTitleSeperatorPosition' => array(
                    'type' => 'string',
                    'default' => 'seperator-bottom',
                ),

                /* block component sub title*/
                'blockComponentSubTitleEnable' => array(
                    'type' => 'boolean',
                    'default' => false,
                ),
                'blockComponentSubTitle' => array(
                    'type' => 'string',
                    'default' => __('Block Title', 'gutentor'),
                ),
                'blockComponentSubTitleTag' => array(
                    'type' => 'string',
                    'default' => 'p'
                ),
                'blockComponentSubTitleAlign' => array(
                    'type' => 'string',
                    'default' => 'text-center'
                ),
                'blockComponentSubTitleColorEnable' => array(
                    'type' => 'boolean',
                    'default' => true
                ),
                'blockComponentSubTitleColor' => array(
                    'type' => 'object',
                    'default' => array(
                        'hex' => '#111111',
                    )
                ),
                'blockComponentSubTitleTypography' => array(
                    'type' => 'object',
                    'default' => array(
                        'fontType' => 'default',
                        'systemFont' => '',
                        'googleFont' => '',
                        'customFont' => '',

                        'desktopFontSize' => '',
                        'tabletFontSize' => '',
                        'mobileFontSize' => '',

                        'fontWeight' => '',
                        'textTransform' => '',
                        'fontStyle' => '',
                        'textDecoration' => '',

                        'desktopLineHeight' => '',
                        'tabletLineHeight' => '',
                        'mobileLineHeight' => '',

                        'desktopLetterSpacing' => '',
                        'tabletLetterSpacing' => '',
                        'mobileLetterSpacing' => '',

                    )
                ),
                'blockComponentSubTitleMargin' => array(
                    'type' => 'object',
                    'default' => array(
                        'type' => 'px',
                        'desktopTop' => '',
                        'desktopRight' => '',
                        'desktopBottom' => '',
                        'desktopLeft' => '',

                        'tabletTop' => '',
                        'tabletRight' => '',
                        'tabletBottom' => '',
                        'tabletLeft' => '',

                        'mobileTop' => '',
                        'mobileRight' => '',
                        'mobileBottom' => '',
                        'mobileLeft' => '',

                    )
                ),
                'blockComponentSubTitlePadding' => array(
                    'type' => 'object',
                    'default' => array(
                        'type' => 'px',
                        'desktopTop' => '',
                        'desktopRight' => '',
                        'desktopBottom' => '',
                        'desktopLeft' => '',

                        'tabletTop' => '',
                        'tabletRight' => '',
                        'tabletBottom' => '',
                        'tabletLeft' => '',

                        'mobileTop' => '',
                        'mobileRight' => '',
                        'mobileBottom' => '',
                        'mobileLeft' => '',

                    )
                ),
                'blockComponentSubTitleAnimation' => array(
                    'type' => 'object',
                    'default' => array(
                        'Animation' => 'px',
                        'Delay' => '',
                        'Speed' => '',
                        'Iteration' => '',
                    )
                ),
                'blockComponentSubTitleDesignEnable' => array(
                    'type' => 'boolean',
                    'default' => true,
                ),
                'blockComponentSubTitleSeperatorPosition' => array(
                    'type' => 'string',
                    'default' => 'seperator-bottom',
                ),

                /* primary button */
                'blockComponentPrimaryButtonEnable' => array(
                    'type' => 'boolean',
                    'default' => false,
                ),
                'blockComponentPrimaryButtonLinkOptions' => array(
                    'type' => 'object',
                    'default' => array(
                        'openInNewTab' => false,
                        'rel' => '',
                    ),
                ),
                'blockComponentPrimaryButtonColor' => array(
                    'type' => 'object',
                    'default' => array(
                        'enable' => true,
                        'normal' => array(
                            'hex' => '#275cf6',
                            'rgb' => array(
                                'r' => '39',
                                'g' => '92',
                                'b' => '246',
                                'a' => '1',
                            )
                        ),
                        'hover' => array(
                            'hex' => '#1949d4',
                            'rgb' => array(
                                'r' => '25',
                                'g' => '73',
                                'b' => '212',
                                'a' => '1',
                            )
                        ),
                    )
                ),
                'blockComponentPrimaryButtonTextColor' => array(
                    'type' => 'object',
                    'default' => array(
                        'enable' => true,
                        'normal' => array(
                            'hex' => '#fff',
                        ),
                        'hover' => '',
                    )
                ),
                'blockComponentPrimaryButtonMargin' => array(
                    'type' => 'object',
                    'default' => array(
                        'type' => 'px',
                        'desktopTop' => '',
                        'desktopRight' => '',
                        'desktopBottom' => '',
                        'desktopLeft' => '',
                        'tabletTop' => '',
                        'tabletRight' => '',
                        'tabletBottom' => '',
                        'tabletLeft' => '',
                        'mobileTop' => '',
                        'mobileRight' => '',
                        'mobileBottom' => '',
                        'mobileLeft' => '',
                    )
                ),
                'blockComponentPrimaryButtonPadding' => array(
                    'type' => 'object',
                    'default' => array(
                        'type' => 'px',
                        'desktopTop' => '12',
                        'desktopRight' => '25',
                        'desktopBottom' => '12',
                        'desktopLeft' => '25',
                        'tabletTop' => '12',
                        'tabletRight' => '25',
                        'tabletBottom' => '12',
                        'tabletLeft' => '25',
                        'mobileTop' => '12',
                        'mobileRight' => '25',
                        'mobileBottom' => '12',
                        'mobileLeft' => '25',
                    ),
                ),
                'blockComponentPrimaryButtonIconOptions' => array(
                    'type' => 'object',
                    'default' => array(
                        'position' => 'hide',
                        'size' => '',
                    )
                ),
                'blockComponentPrimaryButtonIconMargin' => array(
                    'type' => 'object',
                    'default' => array(
                        'type' => 'px',
                        'desktopTop' => '',
                        'desktopRight' => '',
                        'desktopBottom' => '',
                        'desktopLeft' => '',
                        'tabletTop' => '',
                        'tabletRight' => '',
                        'tabletBottom' => '',
                        'tabletLeft' => '',
                        'mobileTop' => '',
                        'mobileRight' => '',
                        'mobileBottom' => '',
                        'mobileLeft' => '',
                    )
                ),
                'blockComponentPrimaryButtonIconPadding' => array(
                    'type' => 'object',
                    'default' => array(
                        'type' => 'px',
                        'desktopTop' => '',
                        'desktopRight' => '',
                        'desktopBottom' => '',
                        'desktopLeft' => '',
                        'tabletTop' => '',
                        'tabletRight' => '',
                        'tabletBottom' => '',
                        'tabletLeft' => '',
                        'mobileTop' => '',
                        'mobileRight' => '',
                        'mobileBottom' => '',
                        'mobileLeft' => '',
                    )
                ),
                'blockComponentPrimaryButtonBorder' => array(
                    'type' => 'object',
                    'default' => array(
                        'borderStyle' => '',
                        'borderTop' => '',
                        'borderRight' => '',
                        'borderBottom' => '',
                        'borderLeft' => '',
                        'borderColorNormal' => '',
                        'borderColorHover' => '',
                        'borderRadiusType' => 'px',
                        'borderRadiusTop' => '3',
                        'borderRadiusRight' => '3',
                        'borderRadiusBottom' => '3',
                        'borderRadiusLeft' => '3',
                    )
                ),
                'blockComponentPrimaryButtonBoxShadow' => array(
                    'type' => 'object',
                    'default' => array(
                        'boxShadowColor' => '',
                        'boxShadowX' => '',
                        'boxShadowY' => '',
                        'boxShadowBlur' => '',
                        'boxShadowSpread' => '',
                        'boxShadowPosition' => '',
                    )
                ),
                'blockComponentPrimaryButtonTypography' => array(
                    'type' => 'object',
                    'default' => array(
                        'fontType' => 'default',
                        'systemFont' => '',
                        'googleFont' => '',
                        'customFont' => '',

                        'desktopFontSize' => '16',
                        'tabletFontSize' => '16',
                        'mobileFontSize' => '16',

                        'fontWeight' => '',
                        'textTransform' => 'normal',
                        'fontStyle' => '',
                        'textDecoration' => '',

                        'desktopLineHeight' => '',
                        'tabletLineHeight' => '',
                        'mobileLineHeight' => '',

                        'desktopLetterSpacing' => '',
                        'tabletLetterSpacing' => '',
                        'mobileLetterSpacing' => ''

                    )
                ),
                'blockComponentPrimaryButtonIcon' => array(
                    'type' => 'object',
                    'default' => array(
                        'label' => 'fa-book',
                        'value' => 'fas fa-book',
                        'code' => 'f108',
                    )
                ),
                'blockComponentPrimaryButtonText' => array(
                    'type' => 'string',
                    'default' => __('View More')
                ),
                'blockComponentPrimaryButtonLink' => array(
                    'type' => 'string',
                    'default' => __('#')
                ),

                /*Secondary Button*/
                'blockComponentSecondaryButtonLinkOptions' => array(
                    'type' => 'object',
                    'default' => array(
                        'openInNewTab' => false,
                        'rel' => '',
                    ),
                ),
                'blockComponentSecondaryButtonColor' => array(
                    'type' => 'object',
                    'default' => array(
                        'enable' => true,
                        'normal' => array(
                            'hex' => '#275cf6',
                            'rgb' => array(
                                'r' => '39',
                                'g' => '92',
                                'b' => '246',
                                'a' => '1',
                            )
                        ),
                        'hover' => array(
                            'hex' => '#1949d4',
                            'rgb' => array(
                                'r' => '25',
                                'g' => '73',
                                'b' => '212',
                                'a' => '1',
                            )
                        ),
                    )
                ),
                'blockComponentSecondaryButtonTextColor' => array(
                    'type' => 'object',
                    'default' => array(
                        'enable' => true,
                        'normal' => array(
                            'hex' => '#fff',
                        ),
                        'hover' => '',
                    )
                ),
                'blockComponentSecondaryButtonMargin' => array(
                    'type' => 'object',
                    'default' => array(
                        'type' => 'px',
                        'desktopTop' => '',
                        'desktopRight' => '',
                        'desktopBottom' => '',
                        'desktopLeft' => '',
                        'tabletTop' => '',
                        'tabletRight' => '',
                        'tabletBottom' => '',
                        'tabletLeft' => '',
                        'mobileTop' => '',
                        'mobileRight' => '',
                        'mobileBottom' => '',
                        'mobileLeft' => '',
                    )
                ),
                'blockComponentSecondaryButtonPadding' => array(
                    'type' => 'object',
                    'default' => array(
                        'type' => 'px',
                        'desktopTop' => '12',
                        'desktopRight' => '25',
                        'desktopBottom' => '12',
                        'desktopLeft' => '25',
                        'tabletTop' => '12',
                        'tabletRight' => '25',
                        'tabletBottom' => '12',
                        'tabletLeft' => '25',
                        'mobileTop' => '12',
                        'mobileRight' => '25',
                        'mobileBottom' => '12',
                        'mobileLeft' => '25',
                    ),
                ),
                'blockComponentSecondaryButtonIconOptions' => array(
                    'type' => 'object',
                    'default' => array(
                        'position' => 'hide',
                        'size' => '',
                    )
                ),
                'blockComponentSecondaryButtonIconMargin' => array(
                    'type' => 'object',
                    'default' => array(
                        'type' => 'px',
                        'desktopTop' => '',
                        'desktopRight' => '',
                        'desktopBottom' => '',
                        'desktopLeft' => '',
                        'tabletTop' => '',
                        'tabletRight' => '',
                        'tabletBottom' => '',
                        'tabletLeft' => '',
                        'mobileTop' => '',
                        'mobileRight' => '',
                        'mobileBottom' => '',
                        'mobileLeft' => '',
                    )
                ),
                'blockComponentSecondaryButtonIconPadding' => array(
                    'type' => 'object',
                    'default' => array(
                        'type' => 'px',
                        'desktopTop' => '',
                        'desktopRight' => '',
                        'desktopBottom' => '',
                        'desktopLeft' => '',
                        'tabletTop' => '',
                        'tabletRight' => '',
                        'tabletBottom' => '',
                        'tabletLeft' => '',
                        'mobileTop' => '',
                        'mobileRight' => '',
                        'mobileBottom' => '',
                        'mobileLeft' => '',
                    )
                ),
                'blockComponentSecondaryButtonBorder' => array(
                    'type' => 'object',
                    'default' => array(
                        'borderStyle' => '',
                        'borderTop' => '',
                        'borderRight' => '',
                        'borderBottom' => '',
                        'borderLeft' => '',
                        'borderColorNormal' => '',
                        'borderColorHover' => '',
                        'borderRadiusType' => 'px',
                        'borderRadiusTop' => '3',
                        'borderRadiusRight' => '3',
                        'borderRadiusBottom' => '3',
                        'borderRadiusLeft' => '3',
                    )
                ),
                'blockComponentSecondaryButtonBoxShadow' => array(
                    'type' => 'object',
                    'default' => array(
                        'boxShadowColor' => '',
                        'boxShadowX' => '',
                        'boxShadowY' => '',
                        'boxShadowBlur' => '',
                        'boxShadowSpread' => '',
                        'boxShadowPosition' => '',
                    )
                ),
                'blockComponentSecondaryButtonTypography' => array(
                    'type' => 'object',
                    'default' => array(
                        'fontType' => 'default',
                        'systemFont' => '',
                        'googleFont' => '',
                        'customFont' => '',

                        'desktopFontSize' => '16',
                        'tabletFontSize' => '16',
                        'mobileFontSize' => '16',

                        'fontWeight' => '',
                        'textTransform' => 'normal',
                        'fontStyle' => '',
                        'textDecoration' => '',

                        'desktopLineHeight' => '',
                        'tabletLineHeight' => '',
                        'mobileLineHeight' => '',

                        'desktopLetterSpacing' => '',
                        'tabletLetterSpacing' => '',
                        'mobileLetterSpacing' => ''

                    )
                ),
                'blockComponentSecondaryButtonIcon' => array(
                    'type' => 'object',
                    'default' => array(
                        'label' => 'fa-book',
                        'value' => 'fas fa-book',
                        'code' => 'f108',
                    )
                ),
                'blockComponentSecondaryButtonText' => array(
                    'type' => 'string',
                    'default' => __('View More')
                ),
                'blockComponentSecondaryButtonLink' => array(
                    'type' => 'string',
                    'default' => __('#')
                ),

                /*carousel attr*/
                'blockItemsCarouselEnable' => array(
                    'type' => 'boolean',
                    'default' => false
                ),
                'blockItemsCarouselDots' => array(
                    'type' => 'boolean',
                    'default' => false
                ),
                'blockItemsCarouselDotsTablet' => array(
                    'type' => 'boolean',
                    'default' => false
                ),
                'blockItemsCarouselDotsMobile' => array(
                    'type' => 'boolean',
                    'default' => false
                ),
                'blockItemsCarouselDotsColor' => array(
                    'type' => 'object',
                    'default' => array(
                        'enable' => false,
                        'normal' => '',
                        'hover' => ''
                    ),
                ),
                'blockItemsCarouselDotsButtonBorder' => array(
                    'type' => 'object',
                    'default' => array(
                        'borderStyle' => 'none',
                        'borderTop' => '',
                        'borderRight' => '',
                        'borderBottom' => '',
                        'borderLeft' => '',
                        'borderColorNormal' => '',
                        'borderColorHover' => '',
                        'borderRadiusType' => 'px',
                        'borderRadiusTop' => '',
                        'borderRadiusRight' => '',
                        'borderRadiusBottom' => '',
                        'borderRadiusLeft' => ''
                    ),
                ),
                'blockItemsCarouselDotsButtonHeight' => array(
                    'type' => 'object',
                ),
                'blockItemsCarouselDotsButtonWidth' => array(
                    'type' => 'object',
                ),
                'blockItemsCarouselArrows' => array(
                    'type' => 'boolean',
                    'default' => true
                ),
                'blockItemsCarouselArrowsTablet' => array(
                    'type' => 'boolean',
                    'default' => true
                ),
                'blockItemsCarouselArrowsMobile' => array(
                    'type' => 'boolean',
                    'default' => true
                ),
                'blockItemsCarouselArrowsBgColor' => array(
                    'type' => 'object',
                    'default' => array(
                        'enable' => false,
                        'normal' => '',
                        'hover' => ''
                    ),
                ),
                'blockItemsCarouselArrowsTextColor' => array(
                    'type' => 'object',
                    'default' => array(
                        'enable' => false,
                        'normal' => '',
                        'hover' => ''
                    ),
                ),
                'blockItemsCarouselInfinite' => array(
                    'type' => 'boolean',
                    'default' => false
                ),
                'blockItemsCarouselFade' => array(
                    'type' => 'boolean',
                    'default' => false
                ),
                'blockItemsCarouselAutoPlay' => array(
                    'type' => 'boolean',
                    'default' => false
                ),
                'blockItemsCarouselSlideSpeed' => array(
                    'type' => 'number',
                    'default' => 300
                ),
                'blockItemsCarouselCenterMode' => array(
                    'type' => 'boolean',
                    'default' => false
                ),
                'blockItemsCarouselCenterPadding' => array(
                    'type' => 'number',
                    'default' => 60
                ),
                'blockItemsCarouselAutoPlaySpeed' => array(
                    'type' => 'number',
                    'default' => 1200
                ),
                'blockItemsCarouselResponsiveSlideItem' => array(
                    'type' => 'object',
                    'default' => array(
                        'desktop' => '4',
                        'tablet' => '3',
                        'mobile' => '2',
                    ),
                ),
                'blockItemsCarouselResponsiveSlideScroll' => array(
                    'type' => 'object',
                    'default' => array(
                        'desktop' => '4',
                        'tablet' => '3',
                        'mobile' => '2',
                    ),
                ),

                'blockItemsCarouselNextArrow' => array(
                    'type' => 'object',
                    'default' => array(
                        'label' => 'fa-angle-right',
                        'value' => 'fas fa-angle-right',
                        'code' => 'f105',
                    ),
                ),
                'blockItemsCarouselPrevArrow' => array(
                    'type' => 'object',
                    'default' => array(
                        'label' => 'fa-angle-left',
                        'value' => 'fas fa-angle-left',
                        'code' => 'f104',
                    ),
                ),
                'blockItemsCarouselButtonIconSize' => array(
                    'type' => 'number',
                    'default' => 16
                ),
                'blockItemsCarouselArrowButtonHeight' => array(
                    'type' => 'object',
                    'default' => array(
                        'desktop' => '40',
                        'tablet' => '30',
                        'mobile' => '20',
                    ),
                ),
                'blockItemsCarouselArrowButtonWidth' => array(
                    'type' => 'object',
                    'default' => array(
                        'desktop' => '40',
                        'tablet' => '30',
                        'mobile' => '20',
                    ),
                ),
                'blockItemsCarouselArrowButtonBorder' => array(
                    'type' => 'object',
                    'default' => array(
                        'borderStyle' => 'none',
                        'borderTop' => '',
                        'borderRight' => '',
                        'borderBottom' => '',
                        'borderLeft' => '',
                        'borderColorNormal' => '',
                        'borderColorHover' => '',
                        'borderRadiusType' => 'px',
                        'borderRadiusTop' => '',
                        'borderRadiusRight' => '',
                        'borderRadiusBottom' => '',
                        'borderRadiusLeft' => ''
                    ),
                ),

                /*Image Options attr*/
                'blockImageBoxImageOverlayColor' => array(
                    'type' => 'object',
                    'default' => array(
                        'enable' => false,
                        'normal' => '',
                        'hover' => '',
                    ),
                ),
                'blockFullImageEnable' => array(
                    'type' => 'boolean',
                    'default' => false
                ),
                'blockEnableImageBoxWidth' => array(
                    'type' => 'boolean',
                    'default' => false
                ),
                'blockImageBoxWidth' => array(
                    'type' => 'number',
                    'default' => ''
                ),
                'blockEnableImageBoxHeight' => array(
                    'type' => 'boolean',
                    'default' => false
                ),
                'blockImageBoxHeight' => array(
                    'type' => 'number',
                    'default' => ''
                ),
                'blockEnableImageBoxDisplayOptions' => array(
                    'type' => 'boolean',
                    'default' => false
                ),
                'blockImageBoxDisplayOptions' => array(
                    'type' => 'string',
                    'default' => 'normal-image'
                ),
                'blockImageBoxBackgroundImageOptions' => array(
                    'type' => 'object',
                    'default' => array(

                        'backgroundImage' => '',
                        'desktopHeight' => '',
                        'tabletHeight' => '',
                        'mobileHeight' => '',

                        'backgroundSize' => '',
                        'backgroundPosition' => '',
                        'backgroundRepeat' => '',
                        'backgroundAttachment' => '',
                    ),
                ),
                'blockEnableImageBoxBorder' => array(
                    'type' => 'boolean',
                    'default' => false
                ),
                'blockImageBoxBorder' => array(
                    'type' => 'object',
                    'default' => array(
                        'borderStyle' => 'none',
                        'borderTop' => '',
                        'borderRight' => '',
                        'borderBottom' => '',
                        'borderLeft' => '',
                        'borderColorNormal' => '',
                        'borderColorHover' => '',
                        'borderRadiusType' => 'px',
                        'borderRadiusTop' => '',
                        'borderRadiusRight' => '',
                        'borderRadiusBottom' => '',
                        'borderRadiusLeft' => '',
                    ),
                ),

                /*item Wrap*/
                'blockItemsWrapMargin' => array(
                    'type' => 'object',
                    'default' => array(
                        'type' => 'px',
                        'desktopTop' => '',
                        'desktopRight' => '',
                        'desktopBottom' => '',
                        'desktopLeft' => '',
                        'tabletTop' => '',
                        'tabletRight' => '',
                        'tabletBottom' => '',
                        'tabletLeft' => '',
                        'mobileTop' => '',
                        'mobileRight' => '',
                        'mobileBottom' => '',
                        'mobileLeft' => '',
                    )
                ),
                'blockItemsWrapPadding' => array(
                    'type' => 'object',
                    'default' => array(
                        'type' => 'px',
                        'desktopTop' => '',
                        'desktopRight' => '',
                        'desktopBottom' => '',
                        'desktopLeft' => '',
                        'tabletTop' => '',
                        'tabletRight' => '',
                        'tabletBottom' => '',
                        'tabletLeft' => '',
                        'mobileTop' => '',
                        'mobileRight' => '',
                        'mobileBottom' => '',
                        'mobileLeft' => '',
                    )
                ),
                'blockItemsWrapAnimation' => array(
                    'type' => 'object',
                    'default' => array(
                        'Animation' => 'none',
                        'Delay' => '',
                        'Speed' => '',
                        'Iteration' => '',
                    )
                ),
            );

            return apply_filters('gutentor_get_common_attrs', $common_attrs);
        }

        /**
         * Block Single Items Common attributes
         * eg Title, Description, Button etc
         *
         * @access public
         * @return array
         * @since 1.0.1
         */
        public function get_single_item_common_attrs()
        {

            return array(

                /*single item title*/
                'blockSingleItemTitleEnable' => array(
                    'type' => 'boolean',
                    'default' => true,
                ),
                'blockSingleItemTitleTag' => array(
                    'type' => 'string',
                    'default' => 'h3'
                ),
                'blockSingleItemTitleColor' => array(
                    'type' => 'object',
                    'default' => array(
                        'enable' => 'false',
                        'normal' => array(
                            'hex' => '#111111',
                        ),
                        'hover' => '',
                    )
                ),
                'blockSingleItemTitleTypography' => array(
                    'type' => 'object',
                    'default' => array(
                        'fontType' => 'default',
                        'systemFont' => '',
                        'googleFont' => '',
                        'customFont' => '',

                        'desktopFontSize' => '',
                        'tabletFontSize' => '',
                        'mobileFontSize' => '',

                        'fontWeight' => '',
                        'textTransform' => '',
                        'fontStyle' => '',
                        'textDecoration' => '',

                        'desktopLineHeight' => '',
                        'tabletLineHeight' => '',
                        'mobileLineHeight' => '',

                        'desktopLetterSpacing' => '',
                        'tabletLetterSpacing' => '',
                        'mobileLetterSpacing' => '',
                    )
                ),
                'blockSingleItemTitleMargin' => array(
                    'type' => 'object',
                    'default' => array(
                        'type' => 'px',
                        'desktopTop' => '',
                        'desktopRight' => '',
                        'desktopBottom' => '',
                        'desktopLeft' => '',

                        'tabletTop' => '',
                        'tabletRight' => '',
                        'tabletBottom' => '',
                        'tabletLeft' => '',

                        'mobileTop' => '',
                        'mobileRight' => '',
                        'mobileBottom' => '',
                        'mobileLeft' => '',

                    )
                ),
                'blockSingleItemTitlePadding' => array(
                    'type' => 'object',
                    'default' => array(
                        'type' => 'px',
                        'desktopTop' => '',
                        'desktopRight' => '',
                        'desktopBottom' => '',
                        'desktopLeft' => '',

                        'tabletTop' => '',
                        'tabletRight' => '',
                        'tabletBottom' => '',
                        'tabletLeft' => '',

                        'mobileTop' => '',
                        'mobileRight' => '',
                        'mobileBottom' => '',
                        'mobileLeft' => '',

                    )
                ),

                /* single item description*/
                'blockSingleItemDescriptionEnable' => array(
                    'type' => 'boolean',
                    'default' => true,
                ),
                'blockSingleItemDescriptionTag' => array(
                    'type' => 'string',
                    'default' => 'p'
                ),
                'blockSingleItemDescriptionColor' => array(
                    'type' => 'object',
                    'default' => array(
                        'enable' => 'false',
                        'normal' => '',
                        'hover' => '',
                    )
                ),
                'blockSingleItemDescriptionTypography' => array(
                    'type' => 'object',
                    'default' => array(
                        'fontType' => 'default',
                        'systemFont' => '',
                        'googleFont' => '',
                        'customFont' => '',

                        'desktopFontSize' => '',
                        'tabletFontSize' => '',
                        'mobileFontSize' => '',

                        'fontWeight' => '',
                        'textTransform' => '',
                        'fontStyle' => '',
                        'textDecoration' => '',

                        'desktopLineHeight' => '',
                        'tabletLineHeight' => '',
                        'mobileLineHeight' => '',

                        'desktopLetterSpacing' => '',
                        'tabletLetterSpacing' => '',
                        'mobileLetterSpacing' => '',
                    )
                ),
                'blockSingleItemDescriptionMargin' => array(
                    'type' => 'object',
                    'default' => array(
                        'type' => 'px',
                        'desktopTop' => '',
                        'desktopRight' => '',
                        'desktopBottom' => '',
                        'desktopLeft' => '',

                        'tabletTop' => '',
                        'tabletRight' => '',
                        'tabletBottom' => '',
                        'tabletLeft' => '',

                        'mobileTop' => '',
                        'mobileRight' => '',
                        'mobileBottom' => '',
                        'mobileLeft' => '',

                    )
                ),
                'blockSingleItemDescriptionPadding' => array(
                    'type' => 'object',
                    'default' => array(
                        'type' => 'px',
                        'desktopTop' => '',
                        'desktopRight' => '',
                        'desktopBottom' => '',
                        'desktopLeft' => '',

                        'tabletTop' => '',
                        'tabletRight' => '',
                        'tabletBottom' => '',
                        'tabletLeft' => '',

                        'mobileTop' => '',
                        'mobileRight' => '',
                        'mobileBottom' => '',
                        'mobileLeft' => '',

                    )
                ),

                /*single item button*/
                'blockSingleItemButtonEnable' => array(
                    'type' => 'boolean',
                    'default' => false,
                ),
                'blockSingleItemButtonLinkOptions' => array(
                    'type' => 'object',
                    'default' => array(
                        'openInNewTab' => false,
                        'rel' => '',
                    ),
                ),
                'blockSingleItemButtonColor' => array(
                    'type' => 'object',
                    'default' => array(
                        'enable' => true,
                        'normal' => array(
                            'hex' => '#275cf6',
                            'rgb' => array(
                                'r' => '39',
                                'g' => '92',
                                'b' => '246',
                                'a' => '1',
                            )
                        ),
                        'hover' => array(
                            'hex' => '#1949d4',
                            'rgb' => array(
                                'r' => '25',
                                'g' => '73',
                                'b' => '212',
                                'a' => '1',
                            )
                        ),
                    )
                ),
                'blockSingleItemButtonTextColor' => array(
                    'type' => 'object',
                    'default' => array(
                        'enable' => true,
                        'normal' => array(
                            'hex' => '#fff',
                        ),
                        'hover' => '',
                    )
                ),
                'blockSingleItemButtonMargin' => array(
                    'type' => 'object',
                    'default' => array(
                        'type' => 'px',
                        'desktopTop' => '',
                        'desktopRight' => '',
                        'desktopBottom' => '',
                        'desktopLeft' => '',
                        'tabletTop' => '',
                        'tabletRight' => '',
                        'tabletBottom' => '',
                        'tabletLeft' => '',
                        'mobileTop' => '',
                        'mobileRight' => '',
                        'mobileBottom' => '',
                        'mobileLeft' => '',
                    )
                ),
                'blockSingleItemButtonPadding' => array(
                    'type' => 'object',
                    'default' => array(
                        'type' => 'px',
                        'desktopTop' => '10',
                        'desktopRight' => '15',
                        'desktopBottom' => '10',
                        'desktopLeft' => '15',
                        'tabletTop' => '10',
                        'tabletRight' => '15',
                        'tabletBottom' => '10',
                        'tabletLeft' => '15',
                        'mobileTop' => '10',
                        'mobileRight' => '15',
                        'mobileBottom' => '10',
                        'mobileLeft' => '15',
                    ),
                ),
                'blockSingleItemButtonIconOptions' => array(
                    'type' => 'object',
                    'default' => array(
                        'position' => 'hide',
                        'size' => '',
                    )
                ),
                'blockSingleItemButtonIconMargin' => array(
                    'type' => 'object',
                    'default' => array(
                        'type' => 'px',
                        'desktopTop' => '',
                        'desktopRight' => '',
                        'desktopBottom' => '',
                        'desktopLeft' => '',
                        'tabletTop' => '',
                        'tabletRight' => '',
                        'tabletBottom' => '',
                        'tabletLeft' => '',
                        'mobileTop' => '',
                        'mobileRight' => '',
                        'mobileBottom' => '',
                        'mobileLeft' => '',
                    )
                ),
                'blockSingleItemButtonIconPadding' => array(
                    'type' => 'object',
                    'default' => array(
                        'type' => 'px',
                        'desktopTop' => '',
                        'desktopRight' => '',
                        'desktopBottom' => '',
                        'desktopLeft' => '',
                        'tabletTop' => '',
                        'tabletRight' => '',
                        'tabletBottom' => '',
                        'tabletLeft' => '',
                        'mobileTop' => '',
                        'mobileRight' => '',
                        'mobileBottom' => '',
                        'mobileLeft' => '',
                    )
                ),
                'blockSingleItemButtonBorder' => array(
                    'type' => 'object',
                    'default' => array(
                        'borderStyle' => '',
                        'borderTop' => '',
                        'borderRight' => '',
                        'borderBottom' => '',
                        'borderLeft' => '',
                        'borderColorNormal' => '',
                        'borderColorHover' => '',
                        'borderRadiusType' => 'px',
                        'borderRadiusTop' => '3',
                        'borderRadiusRight' => '3',
                        'borderRadiusBottom' => '3',
                        'borderRadiusLeft' => '3',
                    )
                ),
                'blockSingleItemButtonBoxShadow' => array(
                    'type' => 'object',
                    'default' => array(
                        'boxShadowColor' => '',
                        'boxShadowX' => '',
                        'boxShadowY' => '',
                        'boxShadowBlur' => '',
                        'boxShadowSpread' => '',
                        'boxShadowPosition' => '',
                    )
                ),
                'blockSingleItemButtonTypography' => array(
                    'type' => 'object',
                    'default' => array(
                        'fontType' => 'system',
                        'systemFont' => '',
                        'googleFont' => '',
                        'customFont' => '',

                        'desktopFontSize' => '14',
                        'tabletFontSize' => '14',
                        'mobileFontSize' => '14',

                        'fontWeight' => '',
                        'textTransform' => 'normal',
                        'fontStyle' => '',
                        'textDecoration' => '',

                        'desktopLineHeight' => '',
                        'tabletLineHeight' => '',
                        'mobileLineHeight' => '',

                        'desktopLetterSpacing' => '',
                        'tabletLetterSpacing' => '',
                        'mobileLetterSpacing' => ''
                    )
                ),

                /* single item box title*/
                'blockSingleItemBoxColor' => array(
                    'type' => 'object',
                    'default' => array(
                        'enable' => true,
                        'normal' => '',
                        'hover' => '',
                    )
                ),
                'blockSingleItemBoxBorder' => array(
                    'type' => 'object',
                    'default' => array(
                        'borderStyle' => '',
                        'borderTop' => '',
                        'borderRight' => '',
                        'borderBottom' => '',
                        'borderLeft' => '',
                        'borderColorNormal' => '',
                        'borderColorHover' => '',
                        'borderRadiusType' => 'px',
                        'borderRadiusTop' => '3',
                        'borderRadiusRight' => '3',
                        'borderRadiusBottom' => '3',
                        'borderRadiusLeft' => '3',
                    )
                ),
                'blockSingleItemBoxShadowOptions' => array(
                    'type' => 'object',
                    'default' => array(
                        'boxShadowColor' => '',
                        'boxShadowX' => '',
                        'boxShadowY' => '',
                        'boxShadowBlur' => '',
                        'boxShadowSpread' => '',
                        'boxShadowPosition' => '',
                    )
                ),
                'blockSingleItemBoxMargin' => array(
                    'type' => 'object',
                    'default' => array(
                        'type' => 'px',
                        'desktopTop' => '',
                        'desktopRight' => '',
                        'desktopBottom' => '',
                        'desktopLeft' => '',

                        'tabletTop' => '',
                        'tabletRight' => '',
                        'tabletBottom' => '',
                        'tabletLeft' => '',

                        'mobileTop' => '',
                        'mobileRight' => '',
                        'mobileBottom' => '',
                        'mobileLeft' => '',

                    )
                ),
                'blockSingleItemBoxPadding' => array(
                    'type' => 'object',
                    'default' => array(
                        'type' => 'px',
                        'desktopTop' => '',
                        'desktopRight' => '',
                        'desktopBottom' => '',
                        'desktopLeft' => '',

                        'tabletTop' => '',
                        'tabletRight' => '',
                        'tabletBottom' => '',
                        'tabletLeft' => '',

                        'mobileTop' => '',
                        'mobileRight' => '',
                        'mobileBottom' => '',
                        'mobileLeft' => '',
                    )
                ),
            );
        }


        /**
         * Element Common attributes
         * eg Title, Description, Button etc
         *
         * @access public
         * @return array
         * @since 1.0.1
         */
        public function get_element_common_attrs()
        {

            return array(

                /*single item title*/
                'eAnimation' => array(
                    'type' => 'object',
                ),
                'eOnPos' => array(
                    'type' => 'boolean',
                    'default' => false,
                ),
                'ePosTypeD' => array(
                    'type' => 'string',
                    'default' => 'g-pos-d'
                ),
                'ePosTypeM' => array(
                    'type' => 'string',
                    'default' => 'g-pos-d'
                ),
                'ePosTypeT' => array(
                    'type' => 'string',
                    'default' => 'g-pos-d'
                ),
                'ePosD' => array(
                    'type' => 'object',
                ),
                'ePosDWidth' => array(
                    'type' => 'object',
                    'default' => array(
                        'type' => 'px',
                        'width' => '',
                    )
                ),
                'ePosT' => array(
                    'type' => 'object',
                ),
                'ePosTWidth' => array(
                    'type' => 'object',
                    'default' => array(
                        'type' => 'px',
                        'width' => '',
                    )
                ),
                'ePosM' => array(
                    'type' => 'object',
                ),
                'ePosMWidth' => array(
                    'type' => 'object',
                    'default' => array(
                        'type' => 'px',
                        'width' => '',
                    )
                ),
                'eZIndex' => array(
                    'type' => 'object',
                ),
                'eHideMode' => array(
                    'type' => 'object',
                )
            );
        }


        /**
         * Module Common attributes
         * eg Title, Description, Button etc
         *
         * @access public
         * @return array
         * @since 1.0.1
         */
        public function get_module_common_attrs()
        {

            return array(
                'mTag' => array(
                    'type' => 'string',
                    'default' => 'section'
                ),
                /*single item title*/
                'mAnimation' => array(
                    'type' => 'object',
                ),
                'pID' => array(
                    'type' => 'string',
                ),
                'mOnPos' => array(
                    'type' => 'boolean',
                    'default' => false,
                ),
                'mPosTypeD' => array(
                    'type' => 'string',
                    'default' => 'g-pos-d'
                ),
                'mPosOptD' => array(
                    'type' => 'string',
                    'default' => 'g-pos-custom'
                ),
                'mPosTypeM' => array(
                    'type' => 'string',
                    'default' => 'g-pos-d'
                ),
                'mPosOptM' => array(
                    'type' => 'string',
                    'default' => 'g-pos-custom'
                ),
                'mPosTypeT' => array(
                    'type' => 'string',
                    'default' => 'g-pos-d'
                ),
                'mPosOptT' => array(
                    'type' => 'string',
                    'default' => 'g-pos-custom'
                ),
                'mPosD' => array(
                    'type' => 'object',
                ),
                'mPosDWidth' => array(
                    'type' => 'object',
                    'default' => array(
                        'type' => 'px',
                        'width' => '',
                    )
                ),
                'mPosT' => array(
                    'type' => 'object',
                ),
                'mPosTWidth' => array(
                    'type' => 'object',
                    'default' => array(
                        'type' => 'px',
                        'width' => '',
                    )
                ),
                'mPosM' => array(
                    'type' => 'object',
                ),
                'mPosMWidth' => array(
                    'type' => 'object',
                    'default' => array(
                        'type' => 'px',
                        'width' => '',
                    )
                ),
                'mZIndex' => array(
                    'type' => 'object',
                ),
                'mHideMode' => array(
                    'type' => 'object',
                ),
                'mOnOverlay' => array(
                    'type' => 'boolean',
                    'default' => false,
                ),
                'mOverlayColor' => array(
                    'type' => 'string',
                    'default' => 'normal-image'
                ),
                /*top shape*/
                'mTShape' => array(
                    'type' => 'string',
                ),
                'mTShapeOnColor' => array(
                    'type' => 'boolean',
                    'default' => false,
                ),
                'mTShapeVFlip' => array(
                    'type' => 'boolean',
                    'default' => false,
                ),
                'mTShapeHFlip' => array(
                    'type' => 'boolean',
                    'default' => false,
                ),
                'mTShapeColor' => array(
                    'type' => 'string',
                ),
                'mTShapeHeight' => array(
                    'type' => 'object',
                ),
                'mTShapeWidth' => array(
                    'type' => 'object',
                ),
                'mTShapePos' => array(
                    'type' => 'boolean',
                    'default' => false,
                ),
                /*Bottom shape*/
                'mBShape' => array(
                    'type' => 'string',
                ),
                'mBShapeOnColor' => array(
                    'type' => 'boolean',
                    'default' => false,
                ),
                'mBShapeVFlip' => array(
                    'type' => 'boolean',
                    'default' => false,
                ),
                'mBShapeHFlip' => array(
                    'type' => 'boolean',
                    'default' => false,
                ),
                'mBShapeColor' => array(
                    'type' => 'string',
                ),
                'mBShapeHeight' => array(
                    'type' => 'object',
                ),
                'mBShapeWidth' => array(
                    'type' => 'object',
                ),
                'mBShapePos' => array(
                    'type' => 'boolean',
                    'default' => false,
                ),
            );
        }

        /**
         * Module Common attributes
         * eg Title, Description, Button etc
         *
         * @access public
         * @return array
         * @since 1.0.1
         */
        public function get_module_query_elements_common_attrs()
        {
            return array(
                'blockSortableItems' => array(
                    'type' => "object",
                    'default' => array(
                        array(
                            'itemValue' => 'featured-image',
                            'itemLabel' => __('Featured Image'),
                        ),
                        array(
                            'itemValue' => 'title',
                            'itemLabel' => __('Title'),
                        ),
                        array(
                            'itemValue' => 'primary-entry-meta',
                            'itemLabel' => __('Primary Entry Meta'),
                        ),
                        array(
                            'itemValue' => 'description',
                            'itemLabel' => __('Description/Excerpt'),
                        ),
                        array(
                            'itemValue' => 'button',
                            'itemLabel' => __('Button'),
                        ),
                        array(
                            'itemValue' => 'secondary-entry-meta',
                            'itemLabel' => __('Secondary Entry Meta'),
                        ),
                    )

                ),
                'pMeta1Sorting' => array(
                    'type' => "object",
                    'default' => array(
                        array(
                            'itemValue' => 'meta-author',
                            'itemLabel' => __('Author'),
                        ),
                        array(
                            'itemValue' => 'meta-date',
                            'itemLabel' => __('Date'),
                        ),
                        array(
                            'itemValue' => 'meta-category',
                            'itemLabel' => __('Category'),
                        ),
                        array(
                            'itemValue' => 'meta-comment',
                            'itemLabel' => __('Comments'),
                        ),
                    )

                ),
                'pMeta2Sorting' => array(
                    'type' => "object",
                    'default' => array(
                        array(
                            'itemValue' => 'meta-author',
                            'itemLabel' => __('Author'),
                        ),
                        array(
                            'itemValue' => 'meta-date',
                            'itemLabel' => __('Date'),
                        ),
                        array(
                            'itemValue' => 'meta-category',
                            'itemLabel' => __('Category'),
                        ),
                        array(
                            'itemValue' => 'meta-comment',
                            'itemLabel' => __('Comments'),
                        ),
                    )
                ),
                'pOnTitle' => array(
                    'type' => 'boolean',
                    'default' => true,
                ),
                'pTitleTag' => array(
                    'type' => 'string',
                    'default' => 'h3'
                ),
                'pOnDesc' => array(
                    'type' => 'boolean',
                    'default' => true,
                ),
                'PExcerptLen' => array(
                    'type' => 'number',
                    'default' => 100
                ),
                'pOnMeta1' => array(
                    'type' => 'boolean',
                    'default' => true,
                ),
                'pOnMeta2' => array(
                    'type' => 'boolean',
                    'default' => false,
                ),
                'pOnAuthorMeta1' => array(
                    'type' => 'boolean',
                    'default' => true,
                ),
                'pOnAuthorMeta2' => array(
                    'type' => 'boolean',
                    'default' => true,
                ),
                'pOnDateMeta1' => array(
                    'type' => 'boolean',
                    'default' => true,
                ),
                'pOnDateMeta2' => array(
                    'type' => 'boolean',
                    'default' => true,
                ),
                'pOnCatMeta1' => array(
                    'type' => 'boolean',
                    'default' => true,
                ),
                'pOnCatMeta2' => array(
                    'type' => 'boolean',
                    'default' => true,
                ),
                'pOnCommentMeta1' => array(
                    'type' => 'boolean',
                    'default' => true,
                ),
                'pOnCommentMeta2' => array(
                    'type' => 'boolean',
                    'default' => true,
                ),
                'categories' => array(
                    'type' => 'string',
                    'default' => '',
                ),
                'pOnFImg' => array(
                    'type' => 'boolean',
                    'default' => true,
                ),
                'pOnBtn' => array(
                    'type' => 'boolean',
                    'default' => true,
                ),
                'pBtnIconOpt' => array(
                    'type' => "object",
                    'default' => array(
                        'position' => '',
                        'size' => '',
                    )
                ),
                'pBtnIcon' => array(
                    'type' => "object",
                    'default' => array(
                        'label' => 'fa-book',
                        'value' => '',
                        'code' => '',
                    )
                ),
                'pBtnLink' => array(
                    'type' => "object",
                    'default' => array(
                        'openInNewTab' => '',
                        'rel' => '',
                    )
                ),
                'pBtnText' => array(
                    'type' => 'string',
                    'default' => __('Read More')
                ),
                'pImgOnLink' => array(
                    'type' => 'boolean',
                    'default' => false,
                ),
                'pImgOpenNewTab' => array(
                    'type' => 'boolean',
                    'default' => false,
                ),
                'pImgLinkRel' => array(
                    'type' => 'string',
                    'default' => 'noopener noreferrer',
                ),
                'pFImgOColor' => array(
                    'type' => "object",
                    'default' => array(
                        'enable' => false,
                        'normal' => '',
                        'hover' => '',
                    )
                ),
                'pImgBgOpt' => array(
                    'type' => "object",
                    'default' => array(
                        'backgroundImage' => '',
                        'desktopHeight' => '',
                        'tabletHeight' => '',
                        'mobileHeight' => '',
                        'type' => 'px',
                        'backgroundSize' => '',
                        'backgroundPosition' => '',
                        'backgroundRepeat' => '',
                        'backgroundAttachment' => '',
                    )
                ),
                'pOnImgDisplayOpt' => array(
                    'type' => 'boolean',
                    'default' => false,
                ),
                'pImgDisplayOpt' => array(
                    'type' => 'string',
                    'default' => 'normal-image'
                ),
                'pOnFeaturedCat' => array(
                    'type' => 'boolean',
                    'default' => false,
                ),
                'pPostCatPos' => array(
                    'type' => 'string',
                    'default' => 'gutentor-cat-pos-img-top-left'
                ),
                'pOnPostFormatOpt' => array(
                    'type' => 'boolean',
                    'default' => false,
                ),
                'pPostFormatPos' => array(
                    'type' => 'string',
                    'default' => 'gutentor-pf-pos-img-top-right'
                )
            );
        }

        /**
         * Gutentor Common Attr Default Value
         * Default Values
         *
         * @since      1.0.0
         * @package    Gutentor
         * @author     Gutentor <info@gutentor.com>
         */
        public function get_common_attrs_default_values()
        {
            $default_attr = [
                /*column*/
                'blockItemsColumn' => [
                    'desktop' => 'grid-md-4',
                    'tablet' => 'grid-sm-4',
                    'mobile' => 'grid-xs-12',
                ],
                'blockSectionHtmlTag' => 'section',
                'gutentorBlockName' => '',
                'blockID' => '',

                /*Advanced attr*/
                'blockComponentAnimation' => [
                    'Animation' => 'none',
                    'Delay' => '',
                    'Speed' => '',
                    'Iteration' => '',
                ],
                'blockComponentBGType' => '',
                'blockComponentBGImage' => '',
                'blockComponentBGVideo' => '',
                'blockComponentBGColor' => '',
                'blockComponentBGImageSize' => '',
                'blockComponentBGImagePosition' => '',
                'blockComponentBGImageRepeat' => '',
                'blockComponentBGImageAttachment' => '',
                'blockComponentBGVideoLoop' => true,
                'blockComponentBGVideoMuted' => true,
                'blockComponentEnableOverlay' => false,
                'blockComponentOverlayColor' => '',
                'blockComponentBoxBorder' => [
                    'borderStyle' => 'none',
                    'borderTop' => '',
                    'borderRight' => '',
                    'borderBottom' => '',
                    'borderLeft' => '',
                    'borderColorNormal' => '',
                    'borderColorHover' => '',
                    'borderRadiusType' => 'px',
                    'borderRadiusTop' => '',
                    'borderRadiusRight' => '',
                    'borderRadiusBottom' => '',
                    'borderRadiusLeft' => '',
                ],
                'blockComponentMargin' => [
                    'type' => 'px',
                    'desktopTop' => '',
                    'desktopRight' => '',
                    'desktopBottom' => '',
                    'desktopLeft' => '',
                    'tabletTop' => '',
                    'tabletRight' => '',
                    'tabletBottom' => 'px',
                    'tabletLeft' => '',
                    'mobileTop' => '',
                    'mobileRight' => '',
                    'mobileBottom' => '',
                    'mobileLeft' => '',
                ],
                'blockComponentPadding' => [
                    'type' => 'px',
                    'desktopTop' => '',
                    'desktopRight' => '',
                    'desktopBottom' => '',
                    'desktopLeft' => '',
                    'tabletTop' => '',
                    'tabletRight' => '',
                    'tabletBottom' => 'px',
                    'tabletLeft' => '',
                    'mobileTop' => '',
                    'mobileRight' => '',
                    'mobileBottom' => '',
                    'mobileLeft' => '',
                ],
                'blockComponentBoxShadowOptions' => [
                    'boxShadowColor' => '',
                    'boxShadowX' => '',
                    'boxShadowY' => '',
                    'boxShadowBlur' => '',
                    'boxShadowSpread' => '',
                    'boxShadowPosition' => '',
                ],

                /*adv shape*/
                'blockShapeTopSelect' => '',
                'blockShapeTopSelectEnableColor' => '',
                'blockShapeTopSelectColor' => '',
                'blockShapeTopHeight' => [

                    'type' => 'px',
                    'desktop' => '',
                    'tablet' => '',
                    'mobile' => '',
                ],
                'blockShapeTopWidth' => [

                    'type' => 'px',
                    'desktop' => '',
                    'tablet' => '',
                    'mobile' => '',
                ],
                'blockShapeTopPosition' => false,
                'blockShapeBottomSelect' => '',
                'blockShapeBottomSelectEnableColor' => '',
                'blockShapeBottomSelectColor' => '',
                'blockShapeBottomHeight' => [
                    'type' => 'px',
                    'desktop' => '',
                    'tablet' => '',
                    'mobile' => '',
                ],
                'blockShapeBottomWidth' => [
                    'type' => 'px',
                    'desktop' => '',
                    'tablet' => '',
                    'mobile' => '',
                ],
                'blockShapeBottomPosition' => '',
                'blockComponentEnableHeight' => false,
                'blockComponentHeight' => [
                    'type' => 'px',
                    'desktop' => '',
                    'tablet' => '',
                    'mobile' => '',
                ],
                'blockComponentEnablePosition' => false,
                'blockComponentPositionTypeDesktop' => 'gutentor-position-default-desktop',
                'blockComponentPositionTypeTablet' => 'gutentor-position-default-tablet',
                'blockComponentPositionTypeMobile' => 'gutentor-position-default-mobile',
                'blockComponentPositionDesktop' => [
                    'type' => 'px',
                    'top' => '',
                    'right' => '',
                    'bottom' => '',
                    'left' => '',
                ],
                'blockComponentPositionDesktopWidth' => [
                    'type' => 'px',
                    'width' => '',
                ],
                'blockComponentPositionTablet' => [
                    'type' => 'px',
                    'top' => '',
                    'right' => '',
                    'bottom' => '',
                    'left' => '',
                ],
                'blockComponentPositionTabletWidth' => [
                    'type' => 'px',
                    'width' => '',
                ],
                'blockComponentPositionMobile' => [
                    'type' => 'px',
                    'top' => '',
                    'right' => '',
                    'bottom' => '',
                    'left' => '',
                ],
                'blockComponentPositionMobileWidth' => [
                    'type' => 'px',
                    'width' => '',
                ],
                'blockComponentEnableZIndex' => false,
                'blockComponentZIndex' => [
                    'desktop' => '',
                    'tablet' => '',
                    'mobile' => '',
                ],
                'blockComponentDesktopDisplayMode' => false,
                'blockComponentTabletDisplayMode' => false,
                'blockComponentMobileDisplayMode' => false,
                'blockComponentRemoveContainerSpace' => [
                    'desktop' => '',
                    'tablet' => '',
                    'mobile' => '',
                ],
                'blockComponentRemoveRowSpace' => [
                    'desktop' => '',
                    'tablet' => '',
                    'mobile' => '',
                ],
                'blockComponentRemoveColumnSpace' => [
                    'desktop' => '',
                    'tablet' => '',
                    'mobile' => '',
                ],

                /*Block Title*/
                'blockComponentTitleEnable' => true,
                'blockComponentTitle' => __('Block Title', 'gutentor'),
                'blockComponentTitleTag' => 'h2',
                'blockComponentTitleAlign' => 'text-center',
                'blockComponentTitleColorEnable' => true,
                'blockComponentTitleColor' => [
                    'hex' => '#111111',
                ],
                'blockComponentTitleTypography' => [

                    'fontType' => '',
                    'systemFont' => '',
                    'googleFont' => '',
                    'customFont' => '',

                    'desktopFontSize' => '',
                    'tabletFontSize' => '',
                    'mobileFontSize' => '',

                    'fontWeight' => '',
                    'textTransform' => '',
                    'fontStyle' => '',
                    'textDecoration' => '',
                    'desktopLineHeight' => '',
                    'tabletLineHeight' => '',
                    'mobileLineHeight' => '',

                    'desktopLetterSpacing' => '',
                    'tabletLetterSpacing' => '',
                    'mobileLetterSpacing' => '',

                ],
                'blockComponentTitleMargin' => [

                    'type' => 'px',
                    'desktopTop' => '',
                    'desktopRight' => '',
                    'desktopBottom' => '',
                    'desktopLeft' => '',

                    'tabletTop' => '',
                    'tabletRight' => '',
                    'tabletBottom' => '',
                    'tabletLeft' => '',

                    'mobileTop' => '',
                    'mobileRight' => '',
                    'mobileBottom' => '',
                    'mobileLeft' => '',

                ],
                'blockComponentTitlePadding' => [

                    'type' => 'px',
                    'desktopTop' => '',
                    'desktopRight' => '',
                    'desktopBottom' => '',
                    'desktopLeft' => '',

                    'tabletTop' => '',
                    'tabletRight' => '',
                    'tabletBottom' => '',
                    'tabletLeft' => '',

                    'mobileTop' => '',
                    'mobileRight' => '',
                    'mobileBottom' => '',
                    'mobileLeft' => '',
                ],

                'blockComponentTitleAnimation' => [
                    'Animation' => 'px',
                    'Delay' => '',
                    'Speed' => '',
                    'Iteration' => '',
                ],
                'blockComponentTitleDesignEnable' => true,
                'blockComponentTitleSeperatorPosition' => 'seperator-bottom',

                /*Block Sub title*/
                'blockComponentSubTitleEnable' => false,
                'blockComponentSubTitle' => __('Block Sub Title', 'gutentor'),
                'blockComponentSubTitleTag' => 'p',
                'blockComponentSubTitleAlign' => 'text-center',
                'blockComponentSubTitleColorEnable' => true,
                'blockComponentSubTitleColor' => [
                    'hex' => '#111111',
                ],
                'blockComponentSubTitleTypography' => [

                    'fontType' => '',
                    'systemFont' => '',
                    'googleFont' => '',
                    'customFont' => '',

                    'desktopFontSize' => '',
                    'tabletFontSize' => '',
                    'mobileFontSize' => '',

                    'fontWeight' => '',
                    'textTransform' => '',
                    'fontStyle' => '',
                    'textDecoration' => '',
                    'desktopLineHeight' => '',
                    'tabletLineHeight' => '',
                    'mobileLineHeight' => '',

                    'desktopLetterSpacing' => '',
                    'tabletLetterSpacing' => '',
                    'mobileLetterSpacing' => '',

                ],
                'blockComponentSubTitleMargin' => [

                    'type' => 'px',
                    'desktopTop' => '',
                    'desktopRight' => '',
                    'desktopBottom' => '',
                    'desktopLeft' => '',

                    'tabletTop' => '',
                    'tabletRight' => '',
                    'tabletBottom' => '',
                    'tabletLeft' => '',

                    'mobileTop' => '',
                    'mobileRight' => '',
                    'mobileBottom' => '',
                    'mobileLeft' => '',

                ],
                'blockComponentSubTitlePadding' => [

                    'type' => 'px',
                    'desktopTop' => '',
                    'desktopRight' => '',
                    'desktopBottom' => '',
                    'desktopLeft' => '',

                    'tabletTop' => '',
                    'tabletRight' => '',
                    'tabletBottom' => '',
                    'tabletLeft' => '',

                    'mobileTop' => '',
                    'mobileRight' => '',
                    'mobileBottom' => '',
                    'mobileLeft' => '',

                ],
                'blockComponentSubTitleAnimation' => [

                    'Animation' => 'px',
                    'Delay' => '',
                    'Speed' => '',
                    'Iteration' => '',

                ],
                'blockComponentSubTitleDesignEnable' => false,
                'blockComponentSubTitleSeperatorPosition' => 'seperator-bottom',

                /*primary button*/
                'blockComponentPrimaryButtonEnable' => false,
                'blockComponentPrimaryButtonLinkOptions' => [
                    'openInNewTab' => false,
                    'rel' => '',
                ],
                'blockComponentPrimaryButtonColor' => [
                    'enable' => true,
                    'normal' => [
                        'hex' => '#275cf6',
                        'rgb' => array(
                            'r' => '39',
                            'g' => '92',
                            'b' => '246',
                            'a' => '1',
                        )
                    ],
                    'hover' => [
                        'hex' => '#1949d4',
                        'rgb' => [
                            'r' => '25',
                            'g' => '73',
                            'b' => '212',
                            'a' => '1',
                        ]
                    ],
                ],
                'blockComponentPrimaryButtonTextColor' => [
                    'enable' => true,
                    'normal' => [
                        'hex' => '#fff',
                    ],
                    'hover' => '',
                ],
                'blockComponentPrimaryButtonMargin' => [
                    'type' => 'px',
                    'desktopTop' => '',
                    'desktopRight' => '',
                    'desktopBottom' => '',
                    'desktopLeft' => '',
                    'tabletTop' => '',
                    'tabletRight' => '',
                    'tabletBottom' => '',
                    'tabletLeft' => '',
                    'mobileTop' => '',
                    'mobileRight' => '',
                    'mobileBottom' => '',
                    'mobileLeft' => '',
                ],
                'blockComponentPrimaryButtonPadding' => [
                    'type' => 'px',
                    'desktopTop' => '12',
                    'desktopRight' => '25',
                    'desktopBottom' => '12',
                    'desktopLeft' => '25',
                    'tabletTop' => '12',
                    'tabletRight' => '25',
                    'tabletBottom' => '12',
                    'tabletLeft' => '25',
                    'mobileTop' => '12',
                    'mobileRight' => '25',
                    'mobileBottom' => '12',
                    'mobileLeft' => '25',
                ],
                'blockComponentPrimaryButtonIconOptions' => [

                    'position' => 'hide',
                    'size' => '',
                ],
                'blockComponentPrimaryButtonIconMargin' => [
                    'type' => 'px',
                    'desktopTop' => '',
                    'desktopRight' => '',
                    'desktopBottom' => '',
                    'desktopLeft' => '',
                    'tabletTop' => '',
                    'tabletRight' => '',
                    'tabletBottom' => '',
                    'tabletLeft' => '',
                    'mobileTop' => '',
                    'mobileRight' => '',
                    'mobileBottom' => '',
                    'mobileLeft' => '',
                ],
                'blockComponentPrimaryButtonIconPadding' => [
                    'type' => 'px',
                    'desktopTop' => '',
                    'desktopRight' => '',
                    'desktopBottom' => '',
                    'desktopLeft' => '',
                    'tabletTop' => '',
                    'tabletRight' => '',
                    'tabletBottom' => '',
                    'tabletLeft' => '',
                    'mobileTop' => '',
                    'mobileRight' => '',
                    'mobileBottom' => '',
                    'mobileLeft' => '',
                ],
                'blockComponentPrimaryButtonBorder' => [
                    'borderStyle' => '',
                    'borderTop' => '',
                    'borderRight' => '',
                    'borderBottom' => '',
                    'borderLeft' => '',
                    'borderColorNormal' => '',
                    'borderColorHover' => '',
                    'borderRadiusType' => 'px',
                    'borderRadiusTop' => '3',
                    'borderRadiusRight' => '3',
                    'borderRadiusBottom' => '3',
                    'borderRadiusLeft' => '3',

                ],
                'blockComponentPrimaryButtonBoxShadow' => [
                    'boxShadowColor' => '',
                    'boxShadowX' => '',
                    'boxShadowY' => '',
                    'boxShadowBlur' => '',
                    'boxShadowSpread' => '',
                    'boxShadowPosition' => '',
                ],
                'blockComponentPrimaryButtonTypography' => [
                    'fontType' => 'system',
                    'systemFont' => '',
                    'googleFont' => '',
                    'customFont' => '',

                    'desktopFontSize' => '16',
                    'tabletFontSize' => '16',
                    'mobileFontSize' => '16',

                    'fontWeight' => '',
                    'textTransform' => 'normal',
                    'fontStyle' => '',
                    'textDecoration' => '',

                    'desktopLineHeight' => '',
                    'tabletLineHeight' => '',
                    'mobileLineHeight' => '',

                    'desktopLetterSpacing' => '',
                    'tabletLetterSpacing' => '',
                    'mobileLetterSpacing' => ''
                ],
                'blockComponentPrimaryButtonIcon' => [

                    'label' => 'fa-book',
                    'value' => 'fas fa-book',
                    'code' => 'f108',
                ],
                'blockComponentPrimaryButtonText' => __('View More'),
                'blockComponentPrimaryButtonLink' => __('#'),

                /*Secondary Button*/
                'blockComponentSecondaryButtonEnable' => false,
                'blockComponentSecondaryButtonLinkOptions' => [
                    'openInNewTab' => false,
                    'rel' => '',
                ],
                'blockComponentSecondaryButtonColor' => [
                    'enable' => true,
                    'normal' => [
                        'hex' => '#275cf6',
                        'rgb' => array(
                            'r' => '39',
                            'g' => '92',
                            'b' => '246',
                            'a' => '1',
                        )
                    ],
                    'hover' => [
                        'hex' => '#1949d4',
                        'rgb' => [
                            'r' => '25',
                            'g' => '73',
                            'b' => '212',
                            'a' => '1',
                        ]
                    ],
                ],
                'blockComponentSecondaryButtonTextColor' => [
                    'enable' => true,
                    'normal' => [
                        'hex' => '#fff',
                    ],
                    'hover' => '',
                ],
                'blockComponentSecondaryButtonMargin' => [
                    'type' => 'px',
                    'desktopTop' => '',
                    'desktopRight' => '',
                    'desktopBottom' => '',
                    'desktopLeft' => '',
                    'tabletTop' => '',
                    'tabletRight' => '',
                    'tabletBottom' => '',
                    'tabletLeft' => '',
                    'mobileTop' => '',
                    'mobileRight' => '',
                    'mobileBottom' => '',
                    'mobileLeft' => '',
                ],
                'blockComponentSecondaryButtonPadding' => [
                    'type' => 'px',
                    'desktopTop' => '12',
                    'desktopRight' => '25',
                    'desktopBottom' => '12',
                    'desktopLeft' => '25',
                    'tabletTop' => '12',
                    'tabletRight' => '25',
                    'tabletBottom' => '12',
                    'tabletLeft' => '25',
                    'mobileTop' => '12',
                    'mobileRight' => '25',
                    'mobileBottom' => '12',
                    'mobileLeft' => '25',
                ],
                'blockComponentSecondaryButtonIconOptions' => [
                    'position' => 'hide',
                    'size' => '',
                ],
                'blockComponentSecondaryButtonIconMargin' => [
                    'type' => 'px',
                    'desktopTop' => '',
                    'desktopRight' => '',
                    'desktopBottom' => '',
                    'desktopLeft' => '',
                    'tabletTop' => '',
                    'tabletRight' => '',
                    'tabletBottom' => '',
                    'tabletLeft' => '',
                    'mobileTop' => '',
                    'mobileRight' => '',
                    'mobileBottom' => '',
                    'mobileLeft' => '',
                ],
                'blockComponentSecondaryButtonIconPadding' => [
                    'type' => 'px',
                    'desktopTop' => '',
                    'desktopRight' => '',
                    'desktopBottom' => '',
                    'desktopLeft' => '',
                    'tabletTop' => '',
                    'tabletRight' => '',
                    'tabletBottom' => '',
                    'tabletLeft' => '',
                    'mobileTop' => '',
                    'mobileRight' => '',
                    'mobileBottom' => '',
                    'mobileLeft' => '',
                ],
                'blockComponentSecondaryButtonBorder' => [
                    'borderStyle' => '',
                    'borderTop' => '',
                    'borderRight' => '',
                    'borderBottom' => '',
                    'borderLeft' => '',
                    'borderColorNormal' => '',
                    'borderColorHover' => '',
                    'borderRadiusType' => 'px',
                    'borderRadiusTop' => '3',
                    'borderRadiusRight' => '3',
                    'borderRadiusBottom' => '3',
                    'borderRadiusLeft' => '3',
                ],
                'blockComponentSecondaryButtonBoxShadow' => [
                    'boxShadowColor' => '',
                    'boxShadowX' => '',
                    'boxShadowY' => '',
                    'boxShadowBlur' => '',
                    'boxShadowSpread' => '',
                    'boxShadowPosition' => '',
                ],
                'blockComponentSecondaryButtonTypography' => [
                    'fontType' => 'system',
                    'systemFont' => '',
                    'googleFont' => '',
                    'customFont' => '',

                    'desktopFontSize' => '16',
                    'tabletFontSize' => '16',
                    'mobileFontSize' => '16',

                    'fontWeight' => '',
                    'textTransform' => 'normal',
                    'fontStyle' => '',
                    'textDecoration' => '',

                    'desktopLineHeight' => '',
                    'tabletLineHeight' => '',
                    'mobileLineHeight' => '',

                    'desktopLetterSpacing' => '',
                    'tabletLetterSpacing' => '',
                    'mobileLetterSpacing' => ''
                ],
                'blockComponentSecondaryButtonIcon' => [
                    'label' => 'fa-book',
                    'value' => 'fas fa-book',
                    'code' => 'f108',
                ],
                'blockComponentSecondaryButtonText' => __('View More', 'gutentor'),
                'blockComponentSecondaryButtonLink' => __('#'),

                /*carousel attr*/
                'blockItemsCarouselEnable' => false,
                'blockItemsCarouselDots' => false,
                'blockItemsCarouselDotsTablet' => false,
                'blockItemsCarouselDotsMobile' => false,
                'blockItemsCarouselDotsColor' => [
                    'enable' => false,
                    'normal' => '',
                    'hover' => ''
                ],
                'blockItemsCarouselDotsButtonBorder' => [
                    'borderStyle' => 'none',
                    'borderTop' => '',
                    'borderRight' => '',
                    'borderBottom' => '',
                    'borderLeft' => '',
                    'borderColorNormal' => '',
                    'borderColorHover' => '',
                    'borderRadiusType' => 'px',
                    'borderRadiusTop' => '',
                    'borderRadiusRight' => '',
                    'borderRadiusBottom' => '',
                    'borderRadiusLeft' => ''
                ],
                'blockItemsCarouselDotsButtonHeight' => [],
                'blockItemsCarouselDotsButtonWidth' => [],
                'blockItemsCarouselArrows' => true,
                'blockItemsCarouselArrowsTablet' => true,
                'blockItemsCarouselArrowsMobile' => true,
                'blockItemsCarouselArrowsBgColor' => [
                    'enable' => false,
                    'normal' => '',
                    'hover' => ''
                ],
                'blockItemsCarouselArrowsTextColor' => [
                    'enable' => false,
                    'normal' => '',
                    'hover' => ''
                ],
                'blockItemsCarouselInfinite' => false,
                'blockItemsCarouselFade' => false,
                'blockItemsCarouselAutoPlay' => false,
                'blockItemsCarouselSlideSpeed' => 300,
                'blockItemsCarouselCenterMode' => false,
                'blockItemsCarouselCenterPadding' => 60,
                'blockItemsCarouselAutoPlaySpeed' => 1200,
                'blockItemsCarouselResponsiveSlideItem' => [
                    'desktop' => '4',
                    'tablet' => '3',
                    'mobile' => '2',
                ],
                'blockItemsCarouselResponsiveSlideScroll' => [
                    'desktop' => '4',
                    'tablet' => '3',
                    'mobile' => '2',
                ],

                'blockItemsCarouselNextArrow' => [
                    'label' => 'fa-angle-right',
                    'value' => 'fas fa-angle-right',
                    'code' => 'f105',
                ],
                'blockItemsCarouselPrevArrow' => [
                    'label' => 'fa-angle-left',
                    'value' => 'fas fa-angle-left',
                    'code' => 'f104',
                ],
                'blockItemsCarouselButtonIconSize' => 16,
                'blockItemsCarouselArrowButtonHeight' => [
                    'desktop' => '40',
                    'tablet' => '30',
                    'mobile' => '20',
                ],
                'blockItemsCarouselArrowButtonWidth' => [
                    'desktop' => '40',
                    'tablet' => '30',
                    'mobile' => '20',
                ],
                'blockItemsCarouselArrowButtonBorder' => [
                    'borderStyle' => 'none',
                    'borderTop' => '',
                    'borderRight' => '',
                    'borderBottom' => '',
                    'borderLeft' => '',
                    'borderColorNormal' => '',
                    'borderColorHover' => '',
                    'borderRadiusType' => 'px',
                    'borderRadiusTop' => '',
                    'borderRadiusRight' => '',
                    'borderRadiusBottom' => '',
                    'borderRadiusLeft' => ''
                ],

                /*Image option attr*/
                'blockImageBoxImageOverlayColor' => [
                    'enable' => false,
                    'normal' => '',
                    'hover' => '',
                ],
                'blockImageBoxMargin' => [
                    'type' => 'px',
                    'desktopTop' => '',
                    'desktopRight' => '',
                    'desktopBottom' => '',
                    'desktopLeft' => '',
                    'tabletTop' => '',
                    'tabletRight' => '',
                    'tabletBottom' => '',
                    'tabletLeft' => '',
                    'mobileTop' => '',
                    'mobileRight' => '',
                    'mobileBottom' => '',
                    'mobileLeft' => '',
                ],
                'blockImageBoxPadding' => [
                    'type' => 'px',
                    'desktopTop' => '',
                    'desktopRight' => '',
                    'desktopBottom' => '',
                    'desktopLeft' => '',
                    'tabletTop' => '',
                    'tabletRight' => '',
                    'tabletBottom' => '',
                    'tabletLeft' => '',
                    'mobileTop' => '',
                    'mobileRight' => '',
                    'mobileBottom' => '',
                    'mobileLeft' => '',
                ],
                'blockFullImageEnable' => false,
                'blockEnableImageBoxWidth' => false,
                'blockImageBoxWidth' => '',
                'blockEnableImageBoxHeight' => false,
                'blockImageBoxHeight' => '',
                'blockEnableImageBoxDisplayOptions' => false,
                'blockImageBoxDisplayOptions' => 'normal-image',
                'blockImageBoxBackgroundImageOptions' => [

                    'backgroundImage' => '',

                    'desktopHeight' => '',
                    'tabletHeight' => '',
                    'mobileHeight' => '',

                    'backgroundSize' => '',
                    'backgroundPosition' => '',
                    'backgroundRepeat' => '',
                    'backgroundAttachment' => '',
                ],
                'blockEnableImageBoxBorder' => false,
                'blockImageBoxBorder' => [
                    'borderStyle' => 'none',
                    'borderTop' => '',
                    'borderRight' => '',
                    'borderBottom' => '',
                    'borderLeft' => '',
                    'borderColorNormal' => '',
                    'borderColorHover' => '',
                    'borderRadiusType' => 'px',
                    'borderRadiusTop' => '',
                    'borderRadiusRight' => '',
                    'borderRadiusBottom' => '',
                    'borderRadiusLeft' => '',
                ],

                /*Item Wrap*/
                'blockItemsWrapMargin' => [
                    'type' => 'px',
                    'desktopTop' => '',
                    'desktopRight' => '',
                    'desktopBottom' => '',
                    'desktopLeft' => '',
                    'tabletTop' => '',
                    'tabletRight' => '',
                    'tabletBottom' => '',
                    'tabletLeft' => '',
                    'mobileTop' => '',
                    'mobileRight' => '',
                    'mobileBottom' => '',
                    'mobileLeft' => '',
                ],
                'blockItemsWrapPadding' => [
                    'type' => 'px',
                    'desktopTop' => '',
                    'desktopRight' => '',
                    'desktopBottom' => '',
                    'desktopLeft' => '',
                    'tabletTop' => '',
                    'tabletRight' => '',
                    'tabletBottom' => '',
                    'tabletLeft' => '',
                    'mobileTop' => '',
                    'mobileRight' => '',
                    'mobileBottom' => '',
                    'mobileLeft' => '',
                ],
                'blockItemsWrapAnimation' => [
                    'Animation' => 'px',
                    'Delay' => '',
                    'Speed' => '',
                    'Iteration' => '',
                ],
            ];
            $default_attr = apply_filters('gutentor_common_attr_default_value', $default_attr);
            return $default_attr;
        }

        /**
         * Repeater Common Attr Default Values
         * Default Values
         * @access public
         * @return array
         * @since 1.0.1
         */
        public function get_single_item_common_attrs_default_values()
        {

            $gutentor_repeater_attr_default_val = [

                /*single item title*/
                'blockSingleItemTitleEnable' => true,
                'blockSingleItemTitleTag' => 'h3',
                'blockSingleItemTitleColor' => [
                    'enable' => false,
                    'normal' => [
                        'hex' => '#111111',
                    ],
                    'hover' => '',
                ],
                'blockSingleItemTitleTypography' => [
                    'fontType' => '',
                    'systemFont' => '',
                    'googleFont' => '',
                    'customFont' => '',

                    'desktopFontSize' => '',
                    'tabletFontSize' => '',
                    'mobileFontSize' => '',

                    'fontWeight' => '',
                    'textTransform' => '',
                    'fontStyle' => '',
                    'textDecoration' => '',
                    'desktopLineHeight' => '',
                    'tabletLineHeight' => '',
                    'mobileLineHeight' => '',

                    'desktopLetterSpacing' => '',
                    'tabletLetterSpacing' => '',
                    'mobileLetterSpacing' => '',

                ],
                'blockSingleItemTitleMargin' => [

                    'type' => 'px',
                    'desktopTop' => '',
                    'desktopRight' => '',
                    'desktopBottom' => '',
                    'desktopLeft' => '',

                    'tabletTop' => '',
                    'tabletRight' => '',
                    'tabletBottom' => '',
                    'tabletLeft' => '',

                    'mobileTop' => '',
                    'mobileRight' => '',
                    'mobileBottom' => '',
                    'mobileLeft' => '',

                ],
                'blockSingleItemTitlePadding' => [

                    'type' => 'px',
                    'desktopTop' => '',
                    'desktopRight' => '',
                    'desktopBottom' => '',
                    'desktopLeft' => '',

                    'tabletTop' => '',
                    'tabletRight' => '',
                    'tabletBottom' => '',
                    'tabletLeft' => '',

                    'mobileTop' => '',
                    'mobileRight' => '',
                    'mobileBottom' => '',
                    'mobileLeft' => '',

                ],

                /*single item description*/
                'blockSingleItemDescriptionEnable' => true,
                'blockSingleItemDescriptionTag' => 'p',
                'blockSingleItemDescriptionColor' => [
                    'enable' => false,
                    'normal' => '',
                    'hover' => '',
                ],
                'blockSingleItemDescriptionTypography' => [

                    'fontType' => '',
                    'systemFont' => '',
                    'googleFont' => '',
                    'customFont' => '',

                    'desktopFontSize' => '',
                    'tabletFontSize' => '',
                    'mobileFontSize' => '',

                    'fontWeight' => '',
                    'textTransform' => '',
                    'fontStyle' => '',
                    'textDecoration' => '',
                    'desktopLineHeight' => '',
                    'tabletLineHeight' => '',
                    'mobileLineHeight' => '',

                    'desktopLetterSpacing' => '',
                    'tabletLetterSpacing' => '',
                    'mobileLetterSpacing' => '',

                ],
                'blockSingleItemDescriptionMargin' => [

                    'type' => 'px',
                    'desktopTop' => '',
                    'desktopRight' => '',
                    'desktopBottom' => '',
                    'desktopLeft' => '',

                    'tabletTop' => '',
                    'tabletRight' => '',
                    'tabletBottom' => '',
                    'tabletLeft' => '',

                    'mobileTop' => '',
                    'mobileRight' => '',
                    'mobileBottom' => '',
                    'mobileLeft' => '',
                ],
                'blockSingleItemDescriptionPadding' => [

                    'type' => 'px',
                    'desktopTop' => '',
                    'desktopRight' => '',
                    'desktopBottom' => '',
                    'desktopLeft' => '',

                    'tabletTop' => '',
                    'tabletRight' => '',
                    'tabletBottom' => '',
                    'tabletLeft' => '',

                    'mobileTop' => '',
                    'mobileRight' => '',
                    'mobileBottom' => '',
                    'mobileLeft' => '',
                ],

                /*single item button*/
                'blockSingleItemButtonEnable' => false,
                'blockSingleItemButtonLinkOptions' => [
                    'openInNewTab' => false,
                    'rel' => '',
                ],
                'blockSingleItemButtonColor' => [
                    'enable' => true,
                    'normal' => [
                        'hex' => '#275cf6',
                        'rgb' => array(
                            'r' => '39',
                            'g' => '92',
                            'b' => '246',
                            'a' => '1',
                        )
                    ],
                    'hover' => [
                        'hex' => '#1949d4',
                        'rgb' => [
                            'r' => '25',
                            'g' => '73',
                            'b' => '212',
                            'a' => '1',
                        ]
                    ],
                ],
                'blockSingleItemButtonTextColor' => [
                    'enable' => true,
                    'normal' => [
                        'hex' => '#fff',
                    ],
                    'hover' => '',
                ],
                'blockSingleItemButtonMargin' => [
                    'type' => 'px',
                    'desktopTop' => '',
                    'desktopRight' => '',
                    'desktopBottom' => '',
                    'desktopLeft' => '',
                    'tabletTop' => '',
                    'tabletRight' => '',
                    'tabletBottom' => '',
                    'tabletLeft' => '',
                    'mobileTop' => '',
                    'mobileRight' => '',
                    'mobileBottom' => '',
                    'mobileLeft' => '',
                ],
                'blockSingleItemButtonPadding' => [
                    'type' => 'px',
                    'desktopTop' => '10',
                    'desktopRight' => '15',
                    'desktopBottom' => '10',
                    'desktopLeft' => '15',
                    'tabletTop' => '10',
                    'tabletRight' => '15',
                    'tabletBottom' => '10',
                    'tabletLeft' => '15',
                    'mobileTop' => '10',
                    'mobileRight' => '15',
                    'mobileBottom' => '10',
                    'mobileLeft' => '15',
                ],
                'blockSingleItemButtonIconOptions' => [

                    'position' => 'hide',
                    'size' => '',
                ],
                'blockSingleItemButtonIconMargin' => [
                    'type' => 'px',
                    'desktopTop' => '',
                    'desktopRight' => '',
                    'desktopBottom' => '',
                    'desktopLeft' => '',
                    'tabletTop' => '',
                    'tabletRight' => '',
                    'tabletBottom' => '',
                    'tabletLeft' => '',
                    'mobileTop' => '',
                    'mobileRight' => '',
                    'mobileBottom' => '',
                    'mobileLeft' => '',
                ],
                'blockSingleItemButtonIconPadding' => [
                    'type' => 'px',
                    'desktopTop' => '',
                    'desktopRight' => '',
                    'desktopBottom' => '',
                    'desktopLeft' => '',
                    'tabletTop' => '',
                    'tabletRight' => '',
                    'tabletBottom' => '',
                    'tabletLeft' => '',
                    'mobileTop' => '',
                    'mobileRight' => '',
                    'mobileBottom' => '',
                    'mobileLeft' => '',
                ],
                'blockSingleItemButtonBorder' => [
                    'borderStyle' => '',
                    'borderTop' => '',
                    'borderRight' => '',
                    'borderBottom' => '',
                    'borderLeft' => '',
                    'borderColorNormal' => '',
                    'borderColorHover' => '',
                    'borderRadiusType' => 'px',
                    'borderRadiusTop' => '3',
                    'borderRadiusRight' => '3',
                    'borderRadiusBottom' => '3',
                    'borderRadiusLeft' => '3',
                ],
                'blockSingleItemButtonBoxShadow' => [
                    'boxShadowColor' => '',
                    'boxShadowX' => '',
                    'boxShadowY' => '',
                    'boxShadowBlur' => '',
                    'boxShadowSpread' => '',
                    'boxShadowPosition' => '',
                ],
                'blockSingleItemButtonTypography' => [
                    'fontType' => 'system',
                    'systemFont' => '',
                    'googleFont' => '',
                    'customFont' => '',

                    'desktopFontSize' => '14',
                    'tabletFontSize' => '14',
                    'mobileFontSize' => '14',

                    'fontWeight' => '',
                    'textTransform' => 'normal',
                    'fontStyle' => '',
                    'textDecoration' => '',

                    'desktopLineHeight' => '',
                    'tabletLineHeight' => '',
                    'mobileLineHeight' => '',

                    'desktopLetterSpacing' => '',
                    'tabletLetterSpacing' => '',
                    'mobileLetterSpacing' => ''
                ],

                /*single item box*/
                'blockSingleItemBoxColor' => [
                    'enable' => true,
                    'normal' => '',
                    'hover' => '',
                ],
                'blockSingleItemBoxBorder' => [
                    'borderStyle' => 'none',
                    'borderTop' => '',
                    'borderRight' => '',
                    'borderBottom' => '',
                    'borderLeft' => '',
                    'borderColorNormal' => '',
                    'borderColorHover' => '',
                    'borderRadiusType' => 'px',
                    'borderRadiusTop' => '',
                    'borderRadiusRight' => '',
                    'borderRadiusBottom' => '',
                    'borderRadiusLeft' => '',
                ],
                'blockSingleItemBoxShadowOptions' => [
                    'boxShadowColor' => '',
                    'boxShadowX' => '',
                    'boxShadowY' => '',
                    'boxShadowBlur' => '',
                    'boxShadowSpread' => '',
                    'boxShadowPosition' => '',
                ],
                'blockSingleItemBoxMargin' => [
                    'type' => 'px',
                    'desktopTop' => '',
                    'desktopRight' => '',
                    'desktopBottom' => '',
                    'desktopLeft' => '',
                    'tabletTop' => '',
                    'tabletRight' => '',
                    'tabletBottom' => '',
                    'tabletLeft' => '',
                    'mobileTop' => '',
                    'mobileRight' => '',
                    'mobileBottom' => '',
                    'mobileLeft' => '',
                ],
                'blockSingleItemBoxPadding' => [
                    'type' => 'px',
                    'desktopTop' => '',
                    'desktopRight' => '',
                    'desktopBottom' => '',
                    'desktopLeft' => '',
                    'tabletTop' => '',
                    'tabletRight' => '',
                    'tabletBottom' => '',
                    'tabletLeft' => '',
                    'mobileTop' => '',
                    'mobileRight' => '',
                    'mobileBottom' => '',
                    'mobileLeft' => '',
                ],
            ];

            return $gutentor_repeater_attr_default_val;
        }

        /**
         * Repeater Attributes
         *
         * @access public
         * @return array
         * @since 1.0.1
         */

        public function add_single_item_common_attrs_default_values($attr){

            return array_merge_recursive($attr, $this->get_single_item_common_attrs_default_values());
        }
    }
}

/**
 * Return instance of  Gutentor_Block_Base class
 *
 * @since    1.0.0
 */
if (!function_exists('gutentor_block_base')) {

    function gutentor_block_base(){

        return Gutentor_Block_Base::get_base_instance();
    }
}