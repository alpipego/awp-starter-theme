<?php

declare(strict_types=1);

namespace Theme\Core;

class Cron
{
    public function strtotime(string $timestring): float
    {
        $time = strtotime($timestring);
        if (gmdate('U') === date('U')) {
            $time -= get_option('gmt_offset') * HOUR_IN_SECONDS;
        }

        return $time;
    }

    public function schedule(float $start, string $schedule, string $action, array $args = []): self
    {
        if ( ! $this->scheduled($action, $args)) {
            wp_schedule_event($start, $schedule, $action, $args);
        }

        return $this;
    }

    public function unschedule(string $action, array $args): self
    {
        while ($next = $this->scheduled($action, $args)) {
            wp_unschedule_event($next, $action, $args);
        }

        return $this;
    }

    public function scheduled(string $action, array $args = []): int
    {
        return (int)wp_next_scheduled($action, $args);
    }

    public function oneshot(float $when, string $action, array $args = [], bool $force = false): self
    {
        if ($force) {
            $this->unschedule($action, $args);
            wp_schedule_single_event((int)$when, $action, $args);

            return $this;
        }

        if ( ! $this->scheduled($action, $args)) {
            wp_schedule_single_event((int)$when, $action, $args);
        }

        return $this;
    }
}
