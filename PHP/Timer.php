<?php
/**
 * PHP_Timer
 *
 * Copyright (c) 2010-2013, Sebastian Bergmann <sebastian@phpunit.de>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of Sebastian Bergmann nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package    PHP
 * @subpackage Timer
 * @author     Sebastian Bergmann <sebastian@phpunit.de>
 * @copyright  2010-2013 Sebastian Bergmann <sebastian@phpunit.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       http://github.com/sebastianbergmann/php-timer
 * @since      File available since Release 1.0.0
 */

/**
 * Utility class for timing.
 *
 * @package    PHP
 * @subpackage Timer
 * @author     Sebastian Bergmann <sebastian@phpunit.de>
 * @copyright  2010-2013 Sebastian Bergmann <sebastian@phpunit.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @version    Release: @package_version@
 * @link       http://github.com/sebastianbergmann/php-timer
 * @since      Class available since Release 1.0.0
 */
class PHP_Timer
{
    /**
     * @var array
     */
    private $startTimes = array();

    /**
     * @var float
     */
    private $requestTime;

    /**
     * Constructor
     */
    public function __construct()
    {
        if (isset($_SERVER['REQUEST_TIME_FLOAT'])) {
            $this->requestTime = $_SERVER['REQUEST_TIME_FLOAT'];
        }

        else {
            $this->requestTime = microtime(TRUE);
        }
    }

    /**
     * Starts the timer.
     */
    public function start()
    {
        array_push($this->startTimes, microtime(TRUE));
    }

    /**
     * Stops the timer and returns the elapsed time.
     *
     * @return float
     */
    public function stop()
    {
        return microtime(TRUE) - array_pop($this->startTimes);
    }

    /**
     * Formats the elapsed time as a string.
     *
     * @param  float $time
     * @return string
     */
    public function secondsToTimeString($time)
    {
        $buffer = '';

        $hours   = sprintf('%02d', ($time >= 3600) ? floor($time / 3600) : 0);
        $minutes = sprintf(
                     '%02d',
                     ($time >= 60)   ? floor($time /   60) - 60 * $hours : 0
                   );
        $seconds = sprintf('%02d', $time - 60 * 60 * $hours - 60 * $minutes);

        if ($hours == 0 && $minutes == 0) {
            $seconds = sprintf('%1d', $seconds);

            $buffer .= $seconds . ' second';

            if ($seconds != '1') {
                $buffer .= 's';
            }
        } else {
            if ($hours > 0) {
                $buffer = $hours . ':';
            }

            $buffer .= $minutes . ':' . $seconds;
        }

        return $buffer;
    }

    /**
     * Formats the elapsed time since the start of the request as a string.
     *
     * @return string
     */
    public function timeSinceStartOfRequest()
    {
        return $this->secondsToTimeString(microtime(TRUE) - $this->requestTime);
    }

    /**
     * Returns the resources (time, memory) of the request as a string.
     *
     * @return string
     */
    public function resourceUsage()
    {
        return sprintf(
          'Time: %s, Memory: %4.2fMb',
          $this->timeSinceStartOfRequest(),
          memory_get_peak_usage(TRUE) / 1048576
        );
    }
}
