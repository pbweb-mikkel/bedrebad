<?php

class PB_Log{

	private $type;
	private $input;
	private $response;
	private $time;

	/**
	 * PB_Log constructor.
	 *
	 * @param $type
	 * @param $input
	 * @param $response
	 * @param $time
	 */
	public function __construct( $type, $input = null, $response = null, $time = false ) {
        date_default_timezone_set('Europe/Copenhagen');
		$this->setType($type);
		$this->setInput($input);
		$this->setResponse($response);
		$this->setTime($time);
		$this->save();
	}

	public function save(){
		global $wpdb;

		$sql = "INSERT INTO ".$wpdb->prefix."pb_log (type, input, response, time) VALUES ('".$this->getType()."', '".$this->getInput()."', '".$this->getResponse()."', '".$this->getTime()."')";
		$query = $wpdb->query($sql);
		return $query;
	}

    public static function clean_up(){
        global $wpdb;
        $sql = "DELETE FROM ".$wpdb->prefix."pb_log WHERE time <= (now() - interval 3 month)";
        $query = $wpdb->query($sql);
    }

	/**
	 * @return mixed
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @param mixed $type
	 */
	public function setType( $type ) {
		$this->type = filter_var($type, FILTER_SANITIZE_STRING);
	}

	/**
	 * @return mixed
	 */
	public function getInput() {
		return $this->input;
	}

	/**
	 * @param mixed $input
	 */
	public function setInput( $input ) {
		if($input && is_array($input)){
			$this->input = json_encode($input);
		}else{
			$this->input = $input;
		}
	}

	/**
	 * @return mixed
	 */
	public function getResponse() {
		return $this->response;
	}

	/**
	 * @param mixed $response
	 */
	public function setResponse( $response ) {
		if($response && is_array($response)){
			$this->response = json_encode($response);
		}else{
			$this->response = $response;
		}
	}

	/**
	 * @return bool|int
	 */
	public function getTime() {
		return $this->time;
	}

	/**
	 * @param bool|int $time
	 */
	public function setTime( $time ) {

		$time = ($time) ?: time();
		$this->time = date('Y-m-d H:i:s', $time);

	}

}

add_action('after_switch_theme', 'pb_add_log_table');

function pb_add_log_table() {
    global $table_prefix, $wpdb;
    $tblname        = 'pb_log';
    $wp_track_table = $table_prefix . "$tblname";
    #Check to see if the table exists already, if not, then create it
    if ( $wpdb->get_var( "show tables like '$wp_track_table'" ) != $wp_track_table ) {
        $sql = "CREATE TABLE " . $wp_track_table . " ( ";
        $sql .= "  id  int(11)   NOT NULL auto_increment, ";
        $sql .= "  type VARCHAR(55) NOT NULL, ";
        $sql .= "  input TEXT NULL, ";
        $sql .= "  response TEXT NULL, ";
        $sql .= "  time DATETIME NOT NULL, ";
        $sql .= "  PRIMARY KEY log_id (id) ";
        $sql .= ");";
        require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }
}


function pb_log_admin_menu() {
    add_menu_page(
        __( 'Log', 'pb' ),
        __( 'Log', 'pb' ),
        'manage_options',
        'pb-log',
        'pb_log_page_contents',
        'dashicons-schedule',
        3
    );
}

add_action( 'admin_menu', 'pb_log_admin_menu' );


function pb_log_page_contents() {
    global $wpdb;
    $logs = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}pb_log ORDER BY id DESC");
    //var_dump($logs);
    ?>
    <h1>
        <?php esc_html_e( 'Log', 'pb' ); ?>

    </h1>
    <table id="log-table">
        <thead>
        <tr>
            <th align="left">ID</th>
            <th align="left">Type</th>
            <th align="left">Input</th>
            <th align="left">Output</th>
            <th align="rigth">Time</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($logs as $log){
            ?>
            <tr>
                <td align="left"><?= $log->id ?></td>
                <td align="left"><?= $log->type ?></td>
                <td align="left"><?= $log->input ?></td>
                <td align="left"><?= $log->response ?></td>
                <td align="right"><?= $log->time ?></td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.23/datatables.min.css"/>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.23/datatables.min.js"></script>
    <script>
        jQuery(document).ready(function($) {
            $('#log-table').DataTable({
                "order": [0, 'desc'],
                "pageLength": 100,
            });
        } );
    </script>
    <style>
        tr.even{
            background:#f1f1f1 !important;
        }
    </style>
    <?php
}

function pb_log_cleanup(){
    new PB_Log('running_log_cleanup');
    PB_Log::clean_up();
}
add_action('wp_scheduled_auto_draft_delete', 'pb_log_cleanup');