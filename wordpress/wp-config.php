/* Desabilita WP-Cron via HTTP */
define('DISABLE_WP_CRON', true);

/* Aumenta limites para processos do Tainacan */
define('WP_MEMORY_LIMIT', '512M');
define('WP_MAX_MEMORY_LIMIT', '1024M');

/* Timeout maior para processos longos */
@ini_set('max_execution_time', '0');
@ini_set('max_input_time', '600');