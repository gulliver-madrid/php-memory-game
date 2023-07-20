<?php
    class TimeManager {
        function getCurrentTimeAsString(){
            return date('Y-m-d H:i:s', strtotime('now'));
        }
    }
