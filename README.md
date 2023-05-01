## api
- client-token parametresi auth olunduğunda header dan geliyor
- mock api için başka uygulama yerine farklı auth kontrolüyle buraya yazdım
- veritabanının optimize çalışması için flag(lar) kullandım

## worker
- artisan komutu "subs:check" olarak ilerledim
- queue olarak anlaşılır olması için database kullandım (tercihim redis)
- işlemlerin kolay takibi ve müdahele edilebilmesi için batchable yaptım
- mock api rate limite düşen istekler tetikleyici tarafından sonra tekrar denenecek (php artisan queue:retry --queue=worker)

## callback
- api auth kontrolü ve queue ile bu uygulama içerisine yazdım
- event queue fail istekler tetikleyici tarafından sonra tekrar denenecek (php artisan queue:retry --queue=callback)
- mock api tarafından start ve renew bilgileride response alınsaydı subscriptions tablosundaki flag ların kontrolü yapılacaktı
- gelen isteklerden appId 4 e tam bölünen olanlar 429 status dönecek (event kuyruk tekrar gönderilebilmesi için)