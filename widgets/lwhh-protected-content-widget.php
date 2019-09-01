<?php


class LWHH_Protected_Content_Widget extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve Protected Content name.
	 *
	 * @return string Widget name.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_name() {
		return 'lwhhpc_widget';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve Protected Content title.
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_title() {
		return __( 'Protected Content', 'lwhhpc' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve Protected Content icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'fa fa-lock';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the Protected Content belongs to.
	 *
	 * @return array Widget categories.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_categories() {
		return [ 'general' ];
	}

	/**
	 * Register Protected Content controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {

		$this->register_content_controls();
		$this->register_style_controls();

	}

	/**
	 * Register Protected Content content ontrols.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	function register_content_controls() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content', 'lwhhpc' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'password',
			[
				'label'       => __( 'Password', 'lwhhpc' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'input_type'  => 'text',
				'placeholder' => __( 'Password', 'lwhhpc' ),
				'label_block' => true
			]
		);

		$this->add_control(
			'message',
			[
				'label'       => __( 'Message', 'lwhhpc' ),
				'type'        => \Elementor\Controls_Manager::TEXTAREA,
				'placeholder' => __( 'Non Protected Message', 'lwhhpc' ),
			]
		);

		$this->add_control(
			'protected_message',
			[
				'label'       => __( 'Protected Message', 'lwhhpc' ),
				'type'        => \Elementor\Controls_Manager::TEXTAREA,
				'placeholder' => __( 'Protected Message', 'lwhhpc' ),
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register Protected Content style ontrols.
	 *
	 * Adds different input fields in the style tab to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_style_controls() {

		$this->start_controls_section(
			'style_section',
			[
				'label' => __( 'Text Style', 'lwhhpc' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'color',
			[
				'label'     => __( 'Color', 'lwhhpc' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#ff0000',
				'selectors' => [
					'{{WRAPPER}} .message' => 'color: {{VALUE}}'
				]
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'content_typography',
				'label'    => __( 'Typography', 'lwhhpc' ),
				'scheme'   => \Elementor\Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .message',
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render Protected Content output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		$settings          = $this->get_settings_for_display(); //and echo $settings['dummy_text']
		$message           = $this->get_settings( 'message' );
		$password          = $this->get_settings( 'password' );
		$protected_message = $this->get_settings( 'protected_message' );
		$this->add_render_attribute( 'message', 'class', 'message' );
		$this->add_inline_editing_attributes( 'message' );

		if ( ! isset( $_POST['submit'] ) ) {
			?>
            <p <?php echo $this->get_render_attribute_string( 'message' ) ?>> <?php echo esc_html( $message ); ?></p>
            <div>
                <form action="<?php the_permalink(); ?>" method="POST">
                    <input type="hidden" name="ph" value="<?php echo md5( $password ); ?>">
                    <label>Input Password </label><br/>
                    <input type="password" name="passwrd"><br/>
                    <button type="submit" name="submit">Submit</button>
                </form>
            </div>
			<?php
		} else {
			if ( isset( $_POST['passwrd'] ) && $_POST['passwrd'] != '' ) {
				$hash    = $_POST['ph'];
				$passwrd = $_POST['passwrd'];
				if ( $hash == md5( $passwrd ) ) {
					?>
                    <p><?php echo $protected_message; ?></p>
					<?php
				} else {
					?>
                    <p>Password Didn't Match</p>
					<?php
				}
			}
		}

	}

	/**
	 * Render Protected Content output on the frontend.
	 *
	 * Written in JS and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _content_template_() {
		$this->add_render_attribute( 'dummy_text', 'class', 'dummy_text' );
		$this->add_inline_editing_attributes( 'dummy_text', 'none' );
		?>
        <div <?php echo $this->get_render_attribute_string( 'dummy_text' ) ?>> {{ settings.dummy_text }}</div>
		<?php
	}

}