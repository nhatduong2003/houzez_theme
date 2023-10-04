<?php

/**
 * Realtyna Benchmarker
 * Copyright (C) 2022 Realtyna Inc.
 * All rights reserved.
 */

/**
 * Realtyna Benchmarker - client
 * @author Ashton <ashton@realtyna.com>
 */
class RealtynaBenchmarker
{
    /**
     * Client Version
     * @var string
     */
    public static $version = '1.0.1';

    /**
     * Backend API Endpoint
     * @var string
     */
    public static $api_endpoint = 'https://benchmarker.host/api';

    /**
     * Disk space required to run tests
     * @var int
     */
    public static $diskspace_required = 300 * 1024 * 1024;

    /**
     * Current Full URL
     * @var string
     */
    public $url;

    /**
     * Constructor
     * @author Ashton <ashton@realtyna.com>
     */
    public function __construct()
    {
        $protocol = ( isset( $_SERVER['HTTPS'] ) && ( $_SERVER['HTTPS'] == 'on' ) ) ? 'https' : 'http';
		$this->url = $protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];        
        ini_set('memory_limit', '128M');
    }

    /**
     * Check for update
     * @author Ashton <ashton@realtyna.com>
     */
    public function check_for_update()
    {
        $data = [
            'op' => 'check-update',
            'version' => self::$version
        ];

        $result = self::api_request($data);

        if(stripos($result, 'update-available') !== false) {
            $this->view('update-prompt', $result);
        }
    }

    /**
     * Check available disk space
     * @author Ashton <ashton@realtyna.com>
     */
    public function check_disk_space()
    {
        $free_space_bytes = diskfreespace(__DIR__);
        if(!$free_space_bytes) return;
        if(self::$diskspace_required > $free_space_bytes) {
            $this->output(
                sprintf(
                    "Error: You need %dMB of free space at least. (%dMB free now)",
                    self::$diskspace_required / pow(1024, 2),
                    $free_space_bytes / pow(1024, 2)
				)
            );
        }
    }

    /**
     * Handle request and load proper view
     * @author Ashton <ashton@realtyna.com>
     */
    public function load()
    {
        $page = 'home';

        if(isset($_GET['run_tests']) or php_sapi_name() == "cli") $page = 'run-tests';

        if(isset($_GET['update'])) $page = 'update';

        $skip_update = isset($_GET['skip_update']);

        switch ($page) {
            case 'run-tests':
                $data = array();

                // Test Network
                $data['network'] = $this->test_network();

                // Test Disk
                $data['disk'] = $this->test_disk();

                // Test CPU
                $cpu_test = new CPUTest(8);
                $data['cpu'] = $cpu_test->run();

                $this->view('run-tests', $data);
                break;

            case 'update':
                $updated = $this->update();
                $this->view('update', $updated);
                break;

            case 'home':
            default:
                // Load home
                $this->check_disk_space();
                if(!$skip_update) $this->check_for_update();
                $this->view('home');
                break;
        }
    }

    /**
     * Update to latest version
     * @author Ashton <ashton@realtyna.com>
     * @return array
     */
    public function update()
    {
        $request = [
            'op' => 'get-update-package'
        ];

        $update_package = self::api_request($request);
        if(!strlen($update_package)) return ['error' => 'Failed to download update package'];

        // Remove last byte to match MD5
        $update_package = substr($update_package, 0, -1);

        $request = [
            'op' => 'get-update-package-md5'
        ];

        $source_md5 = trim(self::api_request($request));

        if(md5($update_package) !== $source_md5) return ['error' => 'Failed to verify update package integrity'];

        file_put_contents(__FILE__, $update_package);

        return ['success' => 1];
    }


    /**
     * Get a view's content
     * @author Ashton <ashton@realtyna.com>
     * @param string $page
     * @param string $params
     */
    public function view($page = 'home', $params = '')
    {
        switch($page) {
            case 'home':
                $this->output($this->view_home());
                break;

            case 'update-prompt':
                $this->output($this->view_update_prompt($params));
                break;

            case 'update':
                $this->output($this->view_update_status($params));
                break;

            case 'run-tests':
                $this->output($this->view_test_results($params));
                break;
        }
    }


    /**
     * Run Network test
     * @author Ashton <ashton@realtyna.com>
     * @return array
     */
    public function test_network()
    {
        $request = [
            'op' => 'get-file-url'
        ];

        $url = trim(self::api_request($request));

        $file_path = __DIR__ . '/test.tar.xz';

        $start = microtime(true);

        self::download_file($url, $file_path);

        $time = microtime(true) - $start;

        if(!file_exists($file_path)) return ["error" => "Failed to download the test data."];

        $file_size = filesize($file_path);

        unlink($file_path);

        $speed = ($file_size * 8) / $time;

        return [
            'size' => $file_size,
            'time' => $time,
            'speed' => number_format($speed / pow(1024, 2)) . ' Mbps'
        ];
    }

    /**
     * Run Disk test
     * @return array
     */
    public function test_disk()
    {
        $file_path = __DIR__ . '/test.txt';
        $write_size = self::$diskspace_required * 1.00;
        $block_size = 2 * pow(1024, 2);
        $phrase = mt_rand(10000, 99999);
	$data = str_repeat($phrase, (int)($block_size / strlen($phrase)));

        $start = microtime(true);
        $written = 0;
        $written_count = 0;
        while($written < $write_size) {
            if(!file_put_contents($file_path, $data)) {
                return ['error' => 'Failed to write to temp file'];
            }
            $written += $block_size;
            $written_count++;
        }

        $time = microtime(true) - $start;
        unlink($file_path);

        $written = $written / pow(1024, 2);
        $rate = $written / $time;

        return [
            'time' => $time,
            'written' => [
                'count' => $written_count,
                'size' => $written
            ],
            'rate' => $rate
        ];
    }

    /**
     * View home page
     * @author Ashton <ashton@realtyna.com>
     * Updated on
     * @return string
     */
    protected function view_home()
    {
    	$view = sprintf('<div class="hbp-result"></div>
								<div class="hbp-row">
									<div class="hbp-col-lg-12">
										<div class="hbp-start-button">
											<a id="hbp-start-test" href="benchmarker.php">
												<span id="hbp-start-ring" class="hbp-start-ring-high"></span>
												<span id="hbp-start-border" class="hbp-start-border"></span>
												<span id="hbp-start-text" class="hbp-start-text">
						                                <span id="hbp-speed-test" class="hbp-speed-test">Testing...</span>
						                                <span id="hbp-mbps-text" class="hbp-mbps-text"></span>
						                            </span>
												<span class="hbp-progress-bar"></span>
											</a>
										</div>
										<div id="hbp-running" class="hbp-running"></div>
									</div>
								</div>
								<script>window.location = "%s";</script>'
		                        ,$this->url . (strpos($this->url, '?') === false ? '?' : '&') . 'run_tests');

	    return $this->view_template($view);
    }

    /**
     * View update prompt page
     * @author Ashton <ashton@realtyna.com>
     * @param $response
     * @return string
     */
    protected function view_update_prompt($response)
    {
        $result = '';
        $message = str_replace("update-available", "", $response);

        $result .= '<h3>An update is available!</h3><hr/>';
        if(trim($message)) $result .= $message;
        $result .= sprintf('<a href="%s">Update Now</a>', $this->url . '?update');

        return $result;
    }

    /**
     * View update status page
     * @param $result
     * @return string
     */
    protected function view_update_status($result)
    {
        if(isset($result['error'])) {
            return sprintf(
                '<h3>Update failed</h3>
					<p>
						<b>Error:</b> %s
					</p>
					<a href="#" onclick="window.location.reload()">Retry</a>
					&nbsp;&nbsp;
					<a href="?skip_update">Skip</a>
				',
                $result['error']
            );
        }

        return sprintf(
            '<h3>Update successful</h3>
			<hr/>
			<p>Please wait, Redirecting...</p>
			<script>
				setTimeout(function() {
					window.location = "%s";
				}, 2000);
			</script>
			',
            str_replace('?update', '', $this->url)
        );
    }

    /**
     * View test results page
     * Updated by Zein on 26 February 2022
     * @param $data
     * @return string
     */
    protected function view_test_results($data)
    {
    	$networkResult = $diskResult = $cpuResult = '';

	    if(isset($data['network']['error'])) {
		    $networkResult = "<span class='hbp-speed-text'>Error!</span>";
		    $networkResult .= "<h4>".$data['network']['error']."</h4>";
        } else {
		    $networkResult = "<h3>";
            $networkResult .= "<span class='hbp-speed-text'>Speed</span>";
		    $networkResult .= "<span class='hbp-measure-text'>" . $data['network']['speed'] . "</span>";
		    $networkResult .= "<span class='hbp-unit-text'>Mbps</span>";
		    $networkResult .= "</h3>";
		    $networkResult .= "<h4>";
		    $networkResult .= "(Downloaded ". round( $data['network']['size'] / pow(1024, 2),2) ."MB in ".round( $data['network']['time'],2)." seconds)";
            $networkResult .= "</h4>";
	    }


	    if(isset($data['disk']['error'])) {
		    $diskResult = "<span class='hbp-speed-text'>Error!</span>";
		    $diskResult .= "<h4>".$data['disk']['error']."</h4>";
	    } else {
		    $diskResult = "<h3>";
		    $diskResult .= "<span class='hbp-speed-text'>Speed</span>";
		    $diskResult .= "<span class='hbp-measure-text'>" . round($data['disk']['rate'] ,2) . "</span>";
		    $diskResult .= "<span class='hbp-unit-text'>MB/s</span>";
		    $diskResult .= "</h3>";
		    $diskResult .= "<h4>";
		    $diskResult .= "(Created ". $data['disk']['written']['count'] ." files, ". $data['disk']['written']['size'] ."MB in total size)";
		    $diskResult .= "</h4>";
	    }


	    if(!is_array($data['cpu'])) {
		    $cpuResult = "<h4>".$data['cpu']."</h4>";
	    } else {
		    $cpuResult = "<h3>";
		    $cpuResult .= "<span class='hbp-speed-text'>Score</span>";
		    $cpuResult .= "<span class='hbp-measure-text'>" . $data['cpu']['score'] . "</span>";
		    $cpuResult .= "<span class='hbp-unit-text'>of 10</span>";
		    $cpuResult .= "</h3>";
		    $cpuResult .= "<h4>";
		    $cpuResult .= "System Load averages";
		    $cpuResult .= "<span class='hbp-sub-text'>(Lower is better)</span>";
		    $cpuResult .= "</h4>";
		    $cpuResult .= "<table class='hbp-load-avg'><tbody>";
		    foreach ($data['cpu']['load_avg'] as $minutes => $load_avg)
		    {
			    $minutes_labeled = $minutes . ($minutes > 1 ? ' minutes' : ' minute');
			    $cpuResult .= "<tr>";
			    $cpuResult .= "<td>".$minutes_labeled."</td>";
			    $cpuResult .= "<td>".$load_avg['value']."</td>";
			    $cpuResult .= "<td>".$load_avg['label']."</td>";
			    $cpuResult .= "</tr>";
		    }
		    $cpuResult .= "</tbody></table>";
	    }


	    $result = '<div class="hbp-result">
                        <div class="hbp-row">
                            <div class="hbp-col-lg-4">
                                <div class="hbp-network-test">
                                        <span class="hbp-network-icon">
                                            <img src="img/icon/Wifi.png" alt="Network Test">
                                        </span>
                                    <h2>Network Test</h2>'
                                    .$networkResult.
                                '</div>
                            </div>
                            <div class="hbp-col-lg-4">
                                <div class="hbp-disk-test">
                                        <span class="hbp-disk-icon">
                                            <img src="img/icon/HDD.png" alt="Disk Test">
                                        </span>
                                    <h2>Disk Test</h2>'
                                    .$diskResult.
                                '</div>
                            </div>
                            <div class="hbp-col-lg-4">
                                <div class="hbp-cpu-test">
                                        <span class="hbp-cpu-icon">
                                            <img src="img/icon/CPU.png" alt="CPU Test">
                                        </span>
                                    <h2>CPU Test</h2>'
                                    .$cpuResult.
                                '</div>
                            </div>
                        </div>
                    </div>
                    <div class="hbp-row">
                        <div class="hbp-col-lg-12">
                            <div class="hbp-start-button">
                                <a id="hbp-start-test" href="benchmarker.php">
                                    <span id="hbp-start-ring" class="hbp-start-ring"></span>
                                    <span id="hbp-start-border" class="hbp-start-border"></span>
                                    <span id="hbp-start-text" class="hbp-start-text">
                                            <span id="hbp-speed-test" class="hbp-speed-test">Again!</span>
                                            <span id="hbp-mbps-text" class="hbp-mbps-text"></span>
                                        </span>
                                    <span class="hbp-progress-bar"></span>
                                </a>
                            </div>
                            <div id="hbp-running" class="hbp-running">
                            Get Ultra Fast Hosting at <a href="https://hosting.realtyna.com/" target="_blank">hosting.realtyna.com</a>
							</div>
                   
                        </div>
                    </div>';
	    $title = '<h1>Test Result</h1>';
        return $this->view_template($result,$title);
    }

	/**
	 * Create full view of content's template
	 * @author Zein <zein.z@realtyna.net>
	 * @param string $hunk
	 * @return  string
	 */
	public function view_template($hunk , $title = '<h1>Realtyna Benchmarker</h1><p>A unique tool to measure your server\'s performance</p>') {

		$page_view = '<!DOCTYPE html>
					<html lang="en-US">
					<head>
						<!-- Required meta tags -->
						<meta charset="UTF-8">
						<meta http-equiv="X-UA-Compatible" content="IE=edge">
						<meta name="viewport" content="width=device-width, initial-scale=1.0">
						<!-- CSS -->
						<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
					
						<link href="css/style.css" rel="stylesheet">
										
						<title>Benchmark product</title>
					</head>
					<body>
					<header>
						<nav class="hbp-navbar">
							<div class="hbp-container">
								<div class="hbp-row">
									<div class="hbp-navbar-header">
										<a class="hbp-navbar-brand" href="https://realtyna.com" target="_blank">
											<img src="img/logo/Realtyna-Logo.png" alt="Realtyna logo">
											<span>
					                                <span class="hbp-fw-300">Realtyna</span>
					                                <br />
					                                BENCHMARKER
					                            </span>
										</a>
									</div>
									<div class="hbp-navbar-list">
										<ul class="hbp-navbar-item">
											<li><a href="https://benchmarker.host/">Test Our Servers</a></li>
											<li><a href="https://realtyna.com/hosting/" target="_blank">Realtyna Ultra fast Hosting</a></li>
										</ul>
									</div>
								</div>
							</div>
						</nav>
					</header>
					<div class="hbp-content hbp-text-center">
						<div class="hbp-container">
							<div class="hbp-row">
								<div class="hbp-col-lg-12">
									<div class="hbp-title">'
										.$title.
									'</div>
								</div>
							</div>'
		             .$hunk.
		             '</div>
						</div>
						<div class="hbp-top-shave"></div>
						<div class="hbp-bottom-shave"></div>
						<!-- JS -->
						<script src="js/script.js"></script>
						</body>
						</html>';
		return $page_view;
	}

    /**
     * Download a file
     * @author Ashton <ashton@realtyna.com>
     * @param $url
     * @param $path
     * @return bool
     */
    public static function download_file($url, $path = '')
    {
        if($path) {
            $fp = fopen($path, 'w');
            if ($fp === false) return false;
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        if($path) curl_setopt($ch, CURLOPT_FILE, $fp);
        else curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($ch);

        if(curl_errno($ch)) return false;
        $status_code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if($path) {
            fclose($fp);
            return $status_code == 200;
        }
        else {
            return $output;
        }
    }

    public static function get_test_data()
    {
        $request = [
            'op' => 'get-testdata-url'
        ];

        $url = trim(self::api_request($request));

        $content = false;
        $file_path = __DIR__ . '/test.jpg';
        if(self::download_file($url, $file_path)) {
            $content = file_get_contents($file_path);
            unlink($file_path);
        }
        return $content;
    }

    /**
     * Send an API request and return the response
     * @author Ashton <ashton@realtyna.com>
     * @param array $data
     * @return bool|string
     */
    public static function api_request(array $data)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, self::$api_endpoint);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        return curl_exec($ch);
    }

    /**
     * Write to output
     * @author Ashton <ashton@realtyna.com>
     * @param $content
     * @param bool $exit
     */
    protected function output($content, $exit = true)
    {
        echo $content . PHP_EOL;
        if($exit) exit;
    }
}

class CPUTest
{
    public $iterations = 8;
    public static $test_data = '';
    public static $stats = [
        'Intel(R) Core(TM) i7-3720QM CPU @ 2.60GHz' => 5.97,
        'Intel(R) Xeon(R) Platinum 8275CL CPU @ 3.00GHz' => 5.73,
        'Intel(R) Xeon(R) CPU E5-2676 v3 @ 2.40GHz' => 8.25,
    ];

    public function __construct($iterations = 8)
    {
        @ini_set('memory_limit', -1);
        $this->iterations = $iterations;
        if(!self::$test_data) self::$test_data = RealtynaBenchmarker::get_test_data();
    }

    public function run()
    {
        if(!self::$test_data) return 'Error: failed to acquire test data.';
        $start = microtime(true);
        $methods = get_class_methods($this);
        for($i = 0; $i < $this->iterations; $i++) {
            foreach ($methods as $method) {
                if (!preg_match("/^test_.+/", $method)) continue;
                $result = $this->$method();
	        if ($result !== null && stripos($result, 'error') !== false) {
		        return $result;
	        }
            }
        }

        $runtime = microtime(true) - $start;
        return [
            'runtime' => $runtime,
            'score' => self::calculate_score($runtime),
            'load_avg' => self::get_load_averages()
        ];
    }

    protected function test_compression()
    {
        if(!self::$test_data) return false;
        if(!function_exists('gzcompress')) return "Error: PHP Zlib extension is not installed.";

        $compressed = gzcompress(self::$test_data, 9);
        gzuncompress($compressed);
        unset($compressed);

        $deflated = gzdeflate(self::$test_data, 9);
        gzinflate($deflated);
        unset($deflated);
    }

    protected function test_cryptography()
    {
        if(!function_exists('openssl_encrypt')) return "Error: PHP OpenSSL extension is not installed.";

        $ciphering = "aes256";
        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = 0;

        $iv = '';
        for($i = 0; $i < $iv_length; $i++) $iv .= mt_rand(0,9);
        $encryption_key = "RealtynaBenchmarkerCPUTest";

        $encrypted = openssl_encrypt(self::$test_data, $ciphering, $encryption_key, $options, $iv);
        openssl_decrypt($encrypted, $ciphering, $encryption_key, $options, $iv);

        password_hash(self::$test_data, PASSWORD_DEFAULT);
    }

    public static function calculate_score($time, $round_precision = 1)
    {
        // Score Range
        $a = 1;
        $b = 10;

        // Data Range
        $cpu_low = self::get_strongest_cpu();
        $cpu_high = self::get_weakest_cpu();

        $x = $time;
        $min = $cpu_low['time'];
        $max = $cpu_high['time'];

        if($time < $min) return $b;
        if($time > $max) return 0;

        // Map data to score range
        $score = ((($b - $a) * ($x - $min)) / ($max - $min)) + $a;
        $score = ($b + $a) - $score;

        return round($score, $round_precision, PHP_ROUND_HALF_DOWN);
    }

    public static function get_weakest_cpu()
    {
        $max = max(self::$stats);
        $max_model = array_search($max, self::$stats);
        return [
            'model' => $max_model,
            'time' => $max
        ];
    }

    public static function get_strongest_cpu()
    {
        $min = min(self::$stats);
        $min_model = array_search($min, self::$stats);
        return [
            'model' => $min_model,
            'time' => $min
        ];
    }

    public static function get_load_averages()
    {
        $load_avg = sys_getloadavg();
        $load_minutes = [1, 5, 15];
        $result = [];
        foreach ($load_avg as $i => $load)
        {
            $label = 'OK';
            if($load <= 2.5) $label = 'Low';
            if($load > 5) $label = 'High';
            if($load > 8) $label = 'Very High';

            $result[$load_minutes[$i]] = [
                'value' => $load,
                'label' => $label
            ];
        }
        return $result;
    }
}

$checker = new RealtynaBenchmarker();
$checker->load();
