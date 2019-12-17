`sudo docker-compose up --build -d`

запустить миграции базы:
`sudo docker exec -it php-url-shortener php /var/www/app/artisan migrate`

### /make_short_url
ожидает json вида
```
{
	"full_url": "https://google.com"
}
```
ответ:
```
{
  "full_url": "https:\/\/google.com",
  "short_url": "http:\/\/localhost\/q\/8"
}
```

### /q/{encoded_url}
редирект на полный урл


почти всё тут: https://github.com/Snowfall022/lumen-url-shortener/blob/master/www/app/routes/web.php
