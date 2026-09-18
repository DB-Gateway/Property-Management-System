<?php

return [
    // The dashboard card is ready, but remains non-interactive until explicitly enabled.
    'aging_requests' => (bool) env('AGING_REQUESTS_ENABLED', false),
];
