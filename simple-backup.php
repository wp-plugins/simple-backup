<?php

class Simple_Backup {
	/**
	 * Plugin version
	 *
	 * @var string
	 */
	public $version                 = '2.5.1';
	
	/**
	 * Array with default options
	 *
	 * @var array
	 */
	protected $_options             = array(
		'enabled' => true,
		'db_backup' => true,
		'db_compression' => '.sql',
		'file_backup' => true,
		'file_compression' => ".tar.gz",
		'debug_enabled' => false,
		'optimize_db_enabled' => true,
		'check_db_enabled' => true,
		'repair_db_enabled' => false,
		'wp_optimization_methods'=>array(
			'delete_transient_options' => false,
			'delete_auto_drafts' => false,
			'delete_revisions' => false,
			'delete_unapproved_comments' => false,
			'delete_spam_comments' => false
		)
	);
	
	/**
	 * Plugin work path
	 *
	 * @var string
	 */
	protected $_plugin_dir          = null;
	
	/**
	 * Settings url
	 *
	 * @var string
	 */
	protected $_settings_url        = null;


	
	/**
	 * Get option by setting name with default value if option is unexistent
	 *
	 * @param string $setting
	 * @return mixed
	 */
	protected function get_option($setting) {
	    if(is_array($this->_options[$setting])) {
	        $options = array_merge($this->_options[$setting], get_option($setting));
	    } else {
	        $options = get_option($setting, $this->_options[$setting]);
	    }

	    return $options;
	}
	
	/**
	 * Get array with options
	 *
	 * @return array
	 */
	private function get_options() {
		$options = array();
		
		// loop through default options and get user defined options
		foreach($this->_options as $option => $value) {
			$options[$option] = $this->get_option($option);
		}
		
		return $options;
	}
	
	/**
	 * Merge configuration array with the default one
	 *
	 * @param array $default
	 * @param array $opt
	 * @return array
	 */
	private function mergeConfArray($default, $opt) {
		foreach($default as $option => $values)	{
			if(!empty($opt[$option])) {
				$default[$option] = is_array($values) ? array_merge($values, $opt[$option]) : $opt[$option];
				$default[$option] = is_array($values) ? array_intersect_key($default[$option], $values) : $opt[$option];
			}
		}

		return $default;
    }
	
	/**
	 * Plugin installation method
	 */
	public function activate_simple_backup() {
		// record install time
		add_option('simple_backup_installed', time(), null, 'no');
				
		// loop through default options and add them into DB
		foreach($this->_options as $option => $value) {
			add_option($option, $value, null, 'no');	
		}
		
		//7 day initial nag
		$expiration = 60 * 60 * 24 * 7;
		$simple_backup_nag = "true";
		set_transient( 'simple_backup_nag', $simple_backup_nag, $expiration );
	}
	
	

	
	
}

?>