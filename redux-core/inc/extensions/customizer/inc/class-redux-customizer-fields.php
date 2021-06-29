<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Redux Customizer Fields Class
 *
 * @class   Redux_Core
 * @version 4.0.0
 * @package Redux Framework
 */

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace
// phpcs:disable Generic.Files.OneObjectStructurePerFile.MultipleFound
// phpcs:disable Generic.Files.OneClassPerFile

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Customizer_Control_Checkbox', false ) ) {
	/**
	 * Class Redux_Customizer_Control_checkbox
	 */
	class Redux_Customizer_Control_Checkbox extends Redux_Customizer_Control {
		/**
		 * Set control type.
		 *
		 * @var string
		 */
		public $type = 'redux-checkbox';
	}
}

if ( ! class_exists( 'Redux_Customizer_Control_Color_Rgba', false ) ) {
	/**
	 * Class Redux_Customizer_Control_color_rgba
	 */
	class Redux_Customizer_Control_Color_Rgba extends Redux_Customizer_Control {
		/**
		 * Set control type.
		 *
		 * @var string
		 */
		public $type = 'redux-color_rgba';
	}
}


if ( ! class_exists( 'Redux_Customizer_Control_Color', false ) ) {
	/**
	 * Class Redux_Customizer_Control_color
	 */
	class Redux_Customizer_Control_Color extends Redux_Customizer_Control {
		/**
		 * Set control type.
		 *
		 * @var string
		 */
		public $type = 'redux-color';
	}
}

if ( ! class_exists( 'Redux_Customizer_Control_Media', false ) ) {
	/**
	 * Class Redux_Customizer_Control_media
	 */
	class Redux_Customizer_Control_Media extends Redux_Customizer_Control {
 // phpcs:ignore Generic.Files.OneClassPerFile
		/**
		 * Set control type.
		 *
		 * @var string
		 */
		public $type = 'redux-media';
	}
}

if ( ! class_exists( 'Redux_Customizer_Control_Spinner', false ) ) {
	/**
	 * Class Redux_Customizer_Control_spinner
	 */
	class Redux_Customizer_Control_Spinner extends Redux_Customizer_Control {
 // phpcs:ignore Generic.Files.OneClassPerFile
		/**
		 * Set control type.
		 *
		 * @var string
		 */
		public $type = 'redux-spinner';
	}
}

if ( ! class_exists( 'Redux_Customizer_Control_Palette', false ) ) {
	/**
	 * Class Redux_Customizer_Control_palette
	 */
	class Redux_Customizer_Control_Palette extends Redux_Customizer_Control {
 // phpcs:ignore Generic.Files.OneClassPerFile
		/**
		 * Set control type.
		 *
		 * @var string
		 */
		public $type = 'redux-palette';
	}
}

if ( ! class_exists( 'Redux_Customizer_Control_Button_Set', false ) ) {
	/**
	 * Class Redux_Customizer_Control_button_set
	 */
	class Redux_Customizer_Control_Button_Set extends Redux_Customizer_Control {
 // phpcs:ignore Generic.Files.OneClassPerFile
		/**
		 * Set control type.
		 *
		 * @var string
		 */
		public $type = 'redux-button_set';
	}
}

if ( ! class_exists( 'Redux_Customizer_Control_Image_Select', false ) ) {
	/**
	 * Class Redux_Customizer_Control_image_select
	 */
	class Redux_Customizer_Control_Image_Select extends Redux_Customizer_Control {
 // phpcs:ignore Generic.Files.OneClassPerFile

		/**
		 * Set control type.
		 *
		 * @var string
		 */
		public $type = 'redux-image_select';
	}
}

if ( ! class_exists( 'Redux_Customizer_Control_Radio', false ) ) {
	/**
	 * Class Redux_Customizer_Control_radio
	 */
	class Redux_Customizer_Control_Radio extends Redux_Customizer_Control {
 // phpcs:ignore Generic.Files.OneClassPerFile
		/**
		 * Set control type.
		 *
		 * @var string
		 */
		public $type = 'redux-radio';
	}
}

if ( ! class_exists( 'Redux_Customizer_Control_Select', false ) ) {
	/**
	 * Class Redux_Customizer_Control_select
	 */
	class Redux_Customizer_Control_Select extends Redux_Customizer_Control {
 // phpcs:ignore Generic.Files.OneClassPerFile

		/**
		 * Set control type.
		 *
		 * @var string
		 */
		public $type = 'redux-select';
	}
}

if ( ! class_exists( 'Redux_Customizer_Control_Gallery', false ) ) {
	/**
	 * Class Redux_Customizer_Control_gallery
	 */
	class Redux_Customizer_Control_Gallery extends Redux_Customizer_Control {
 // phpcs:ignore Generic.Files.OneClassPerFile
		/**
		 * Set control type.
		 *
		 * @var string
		 */
		public $type = 'redux-gallery';
	}
}

if ( ! class_exists( 'Redux_Customizer_Control_Slider', false ) ) {
	/**
	 * Class Redux_Customizer_Control_slider
	 */
	class Redux_Customizer_Control_Slider extends Redux_Customizer_Control {
 // phpcs:ignore Generic.Files.OneClassPerFile
		/**
		 * Set control type.
		 *
		 * @var string
		 */
		public $type = 'redux-slider';
	}
}

if ( ! class_exists( 'Redux_Customizer_Control_Sortable', false ) ) {
	/**
	 * Class Redux_Customizer_Control_sortable
	 */
	class Redux_Customizer_Control_Sortable extends Redux_Customizer_Control {
 // phpcs:ignore Generic.Files.OneClassPerFile
		/**
		 * Set control type.
		 *
		 * @var string
		 */
		public $type = 'redux-sortable';
	}
}

if ( ! class_exists( 'Redux_Customizer_Control_Switch', false ) ) {
	/**
	 * Class Redux_Customizer_Control_switch
	 */
	class Redux_Customizer_Control_Switch extends Redux_Customizer_Control {
 // phpcs:ignore Generic.Files.OneClassPerFile
		/**
		 * Set control type.
		 *
		 * @var string
		 */
		public $type = 'redux-switch';
	}
}

if ( ! class_exists( 'Redux_Customizer_Control_Text', false ) ) {
	/**
	 * Class Redux_Customizer_Control_text
	 */
	class Redux_Customizer_Control_Text extends Redux_Customizer_Control {
 // phpcs:ignore Generic.Files.OneClassPerFile
		/**
		 * Set control type.
		 *
		 * @var string
		 */
		public $type = 'redux-text';
	}
}

if ( ! class_exists( 'Redux_Customizer_Control_Textarea', false ) ) {
	/**
	 * Class Redux_Customizer_Control_textarea
	 */
	class Redux_Customizer_Control_Textarea extends Redux_Customizer_Control {
 // phpcs:ignore Generic.Files.OneClassPerFile

		/**
		 * Set control type.
		 *
		 * @var string
		 */
		public $type = 'redux-textarea';
	}
}
