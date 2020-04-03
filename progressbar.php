<?php

class ProgressBar {

    private $start_time;
    private $total;

    function __construct($total) {
        $this->total = $total;
        $this->start_time = time();
    }

    public function display($current) {
        $elapsed_seconds = time() - $this->start_time;
        $elapsed_minutes = leftPad(2, floor((time() - $this->start_time) / 60), "0");
        $elapsed_seconds_remainder = leftPad(2, (time() - $this->start_time) % 60, "0");

        $seconds_per_count = $elapsed_seconds / ($current + 0.01);

        $total_seconds_estimation = $seconds_per_count * $this->total;
        $total_minutes_estimation = floor($total_seconds_estimation / 60);
        $total_seconds_estimation_remainder = leftPad(2, $total_seconds_estimation % 60, "0");

        $percent = leftPad(6, number_format($current/$this->total*100, 3), "0");
        $bar = str_repeat("\u{2588}", round($percent / 100 * 50));
        $space = str_repeat(" ", 50 - round($percent / 100 * 50));
        $padded_idx = leftPad(strlen("{$this->total}"), "{$current}", "0");

        $time = "{$elapsed_minutes}:{$elapsed_seconds_remainder}";
        $time .= "/{$total_minutes_estimation}:{$total_seconds_estimation_remainder}";

        $text = "\r[{$bar}${space}] | {$padded_idx}/{$this->total} | ({$percent}%) | {$time} ";

        print($text);
    }
}
