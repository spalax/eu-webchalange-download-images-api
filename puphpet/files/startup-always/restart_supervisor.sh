echo "Chmod uploads";
mkdir -p /var/www/wc-api-server/public/uploads && chmod -R a+rwx /var/www/wc-api-server/public/uploads;

echo "Restart supervisor";
sudo /etc/init.d/supervisord restart;
