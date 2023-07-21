<?php
    namespace JuegoMemoria\Extras;
    class TimeManager {
        function getCurrentTimeAsString(): string {
            return date('Y-m-d H:i:s', strtotime('now'));
        }
    }
