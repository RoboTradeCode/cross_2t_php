module.exports = {
    apps : [{
        name: 'Binance Exmo. WAVES/USDT',
        namespace: 'wte',
        script: '/var/www/trade-bot/private/binance_exmo_waves_usdt.php',
        error_file: "/dev/null",
        out_file: "/dev/null",
        merge_logs: true,
        log_date_format: "YYYY-MM-DD HH:mm:ss",
        instances: 1,
        autorestart: true,
        watch: false,
        env: {
            NODE_ENV: 'development'
        }
    }]
};