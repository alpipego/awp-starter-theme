<?php

namespace Theme;

use Exception;

class Logger
{

    /**
     * @var string
     */
    private static $env = 'local';

    public static function console(...$messages)
    {
        array_walk($messages, static function (&$message) {
            $message = json_encode($message);
        });
        echo '<script>';
        printf('console.table(%s)', implode(',', $messages));
        echo '</script>';
    }

    public static function consoleBody(...$messages)
    {
        add_action('admin_footer', static function () use ($messages) {
            self::console(...$messages);
        });
    }

    public static function float(...$messages)
    {
        echo '<code><pre style="white-space: pre-wrap; word-wrap: break-word;position:fixed; right: 0; padding: 50px; background-color: lightpink; max-width: 80vw; overflow-y: scroll; max-height: 100vh;">';
        var_dump(...$messages);
        echo '</pre></code>';
    }

    public static function setEnv($env)
    {
        self::$env = $env;
    }

    public static function info(...$messages)
    {
        self::log(...$messages);
    }

    private static function log(...$messages)
    {
        $caller = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[1];
        error_log(
            sprintf(
                "%s %s::%d\n%s\n",
                date('d.m.Y H:i:s'),
                $caller['file'],
                $caller['line'],
                implode(
                    "\n",
                    array_map(static function ($message) {
                        return var_export($message, true);
                    }, $messages),
                ),
            ),
        );
    }

    public static function dd(...$messages)
    {
        echo '<code><pre style="white-space: pre-wrap; word-wrap: break-word;">';
        var_dump(...$messages);
        echo '</pre></code>';
        die();
    }

    public static function wplog(...$messages)
    {
        $logFile = defined('WP_DEBUG_LOG') && WP_DEBUG_LOG ? WP_DEBUG_LOG : WP_CONTENT_DIR . '/debug.log';
        if (!file_exists($logFile)) {
            touch($logFile);
        }
        if (!file_exists($logFile)) {
            if (self::$env !== 'local') {
                return;
            }
            throw new Exception('Can\'t create ' . $logFile);
        }
        $log = fopen($logFile, 'ab');
        foreach ($messages as $message) {
            fwrite($log, var_export($message, true));
        }

        fclose($log);
    }

    public static function qm(...$messages)
    {
        $log = static function () use ($messages) {
            foreach ($messages as $message) {
                \QM::debug(var_export($message, true));
            }
        };

        if (did_action('plugins_loaded')) {
            $log();
        }

        add_action('plugins_loaded', $log);
    }

    public static function admin(...$messages)
    {
        $print = static function () use ($messages) {
            echo '<div class="notice notice-info"><pre>';
            array_walk($messages, function ($message) {
                var_dump($message);
            });
            echo '</pre></div>';
        };
        if (did_action('all_admin_notices')) {
            $print();
        }
        add_action('all_admin_notices', static function () use ($print) {
            $print();
        });
    }

    public static function error($message)
    {
        if (self::$env === 'local') {
            throw new \Exception($message);
        }

        self::log($message);
    }

    public static function dump(...$messages)
    {
        $print = static function () use ($messages) {
            echo '<code><pre style="white-space: pre-wrap; word-wrap: break-word;">';
            var_dump(...$messages);
            echo '</pre></code>';
        };
        if (!did_action('wp_body_open')) {
            add_action('wp_body_open', $print);

            return;
        }

        $print();
    }
}
