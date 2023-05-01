## api
- client-token parametresi auth olunduğunda header dan geliyor
- mock api için başka uygulama yerine farklı auth kontrolüyle buraya yazdım
- veritabanının optimize çalışması için flag(lar) kullandım
- veritabanında gerekli kolonlara index ekledim

## worker
- artisan komutu "subs:check" olarak ilerledim
- queue olarak anlaşılır olması için database kullandım (tercihim redis)
- işlemlerin kolay takibi ve müdahele edilebilmesi için batchable yaptım
- mock api rate limite düşen istekler tetikleyici tarafından sonra tekrar denenecek<br/><br/>
  ```
  php artisan queue:retry --queue=worker
  ```

## callback
- api auth kontrolü ve queue ile bu uygulama içerisine yazdım
- event queue fail istekler tetikleyici tarafından sonra tekrar denenecek<br/><br/>
  ```
  php artisan queue:retry --queue=callback
  ```
- mock api tarafından start ve renew bilgileride response alınsaydı subscriptions tablosundaki flag ların kontrolü yapılacaktı
- gelen isteklerden appId 4 e tam bölünen olanlar 429 status dönecek (event kuyruk tekrar gönderilebilmesi için)

## raporlama
- seed ile milyon data üretmek çok uzun sürdüğü için test imkanım kısıtlı oldu
- artisan komutu "subs:report" olarak ilerledim<br/><br/>
  ```sql
  SELECT s.app_id, s.updated_at_date, u.os,
    SUM(s.is_renewed) AS renew_quantity,
    SUM(s.is_finished) AS finish_quantity
  FROM subscriptions AS s
  JOIN users AS u ON u.u_id = s.u_id
    AND u.app_id = s.app_id
  WHERE s.updated_at_date BETWEEN "2023-05-02" AND "2023-05-04"
  GROUP BY s.app_id, s.updated_at_date, u.os
  ORDER BY s.updated_at_date, s.app_id, u.os
  ```