<?php

if ( ! class_exists('Daemon' ) ) {
	require_once( OWA_INCLUDE_DIR.'Daemon.class.php' );
}

class owa_daemon extends Daemon {
	
	var $pids = array();
	var $params = array();
	var $max_workers = 5;
	var $job_scheduling_interval = 30;
	var $eq;
	var $workerCountByJob = array();
	var $lastExecutionTimeByJob = array();
	var $jobsByPid = array();
	
	function __construct() {
		
		$this->params = $this->getArgs();
		
		if (isset($this->params['interval'])) {
			$this->job_scheduling_interval = $this->params['interval'];
		}
		
		if (isset($this->params['max_workers'])) {
			$this->max_workers = $this->params['max_workers'];
		}
		
		if (isset($this->params['pid_file_location'])) {
			$this->pidFileLocation = $this->params['pid_file_location'];
		}
		
		if (isset($this->params['uid'])) {
			$this->userID = $this->params['uid'];
		}
		
		if (isset($this->params['gid'])) {
			$this->groupID = $this->params['gid'];
		}


		if (isset($this->params['pid_file_location'])) {
			$this->pidFileLocation = $this->params['pid_file_location'];
		}

		
		$this->eq = owa_coreAPI::getEventDispatch();
		
		return parent::__construct();
	}
	
	function getArgs() {
		
		$params = array();
		// get params from the command line args
		// $argv is a php super global variable
		for ( $i=1; $i < count( $argv ); $i++ ) {
			$it = split("=",$argv[$i]);
			$params[$it[0]] = $it[1];
		}
		
		return $params;
	}

	function _logMessage($msg, $status = DLOG_NOTICE) {
		
		if ($status & DLOG_TO_CONSOLE) {
        	echo $msg."\n";
        }
        
		owa_coreAPI::notice("Daemon: $msg");
	}
	
	/**
	 * This function is happening in a while loop
	 */
	function _doTask() {
		$active_workers = count( $this->pids );
		$available_workers = $this->max_workers - $active_workers;
		
		if ( $available_workers >= 1 ) {
			
			$jobs = $this->eq->filter('daemon_jobs', $job_list);
			
			if ( $jobs ) {
				$i = 0;
				//for ($i = 0; $i < $available_workers; $i++) {
				foreach ($jobs as $k => $job) {
					$pid = pcntl_fork();
 						
					if ( ! $pid ) {
						// this part is executed in the child
		 				owa_coreAPI::debug( 'New child process executing command ' . print_r( $job[$i], true ) );
		 				pcntl_exec( OWA_DIR.'cli.php', $job['cmd'] ); // takes an array of arguments
		 				exit();
		 			} elseif ($pid == -1) {
		 				// happens when something goes wrong and fork fails (handle errors here)
		 			} else {
		 				// this part is executed in the parent
						// We add pids to a global array, so that when we get a kill signal
						// we tell the kids to flush and exit.
						if ( array_key_exists( $k, $this->workerCountByJob ) ) {
							$this->workerCountByJob[$k]++
						} else {
							$this->workerCountByJob[$k] = 1;
							$this->lastExecutionTimeByJob[$k] = time();
							$this->jobsByPid[$pid] = $k;
						}
						
						$this->pids[] = $pid;	
					}									
				}
			}
		}

		// Collect any children which have exited on their own. pcntl_waitpid will
		// return the PID that exited or 0 or ERROR
		// WNOHANG means we won't sit here waiting if there's not a child ready
		// for us to reap immediately
		// -1 means any child
		$dead_and_gone = pcntl_waitpid( -1, $status, WNOHANG );
		
		while( $dead_and_gone > 0 ) {
			// Remove the gone pid from the array
			unset( $this->pids[array_search( $dead_and_gone, $this->pids )] );
			$past_job = $this->jobsByPid[$dead_and_gone];
			// decrement worker count
			--$this->workerCountByJob[$past_job];
			unset($this->jobsByPid[$dead_and_gone]);
		
			// Look for another one
			$dead_and_gone = pcntl_waitpid( -1, $status, WNOHANG);
		}
		
		owa_coreAPI::debug(sprintf(
				"Daemon Statistics -- pidsByJob: %s, workerCountByJob: %s, lastExecutionTimeByJob: %s",
				print_r( $this->pidsByJob, true),
				print_r( $this->workerCountByJob, true),
				print_r( $this->lastExecutiontimeByJob, true)
		));
		
		// Sleep for some interval
		sleep($this->job_scheduling_interval);
	}
}

?>