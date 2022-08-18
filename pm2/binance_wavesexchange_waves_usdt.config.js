module.exports = {
    apps : [{
        name: 'Binance Wavesexchange. WAVES/USDT',
        namespace: 'wte',
        script: '/home/ubuntu/cross_2t_php/private/binance_wavesexchange_waves_usdt.php',
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