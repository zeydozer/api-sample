## api
- client-token parametresi auth olunduğunda header dan geliyor
- mock api için başka uygulama yerine farklı auth kontrolüyle buraya yazdım
- veritabanının optimize çalışması için flag(lar) kullandım

## worker
- queue olarak anlaşılır olması için database kullandım (tercihim redis)
- işlemlerin kolay takibi ve müdahele edilebilmesi için batchable yaptım