<?php
    class TimeManager {
        function getCurrentTimeAsString(): string {
            return date('Y-m-d H:i:s', strtotime('now'));
        }
    }
