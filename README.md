## api
- client-token parametresi auth olunduğunda header dan geliyor
- mock api için başka uygulama yerine farklı auth kontrolüyle buraya yazdım
- veritabanının optimize çalışması için flag(lar) kullandım

## worker
- artisan komutu "subs:check" olarak ilerledim
- queue olarak anlaşılır olması için database kullandım (tercihim redis)
- işlemlerin kolay takibi ve müdahele edilebilmesi için batchable yaptım
- mock api rate limite düşen istekler tetikleyici tarafından sonra tekrar denenecek (php artisan queue:retry)