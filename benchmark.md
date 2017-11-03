## OURS

```
ab -n 1000 -c 10 http://localhost:9000/
This is ApacheBench, Version 2.3 <$Revision: 1796539 $>
Copyright 1996 Adam Twiss, Zeus Technology Ltd, http://www.zeustech.net/
Licensed to The Apache Software Foundation, http://www.apache.org/

Benchmarking localhost (be patient)
Completed 100 requests
Completed 200 requests
Completed 300 requests
Completed 400 requests
Completed 500 requests
Completed 600 requests
Completed 700 requests
Completed 800 requests
Completed 900 requests
Completed 1000 requests
Finished 1000 requests


Server Software:
Server Hostname:        localhost
Server Port:            9000

Document Path:          /
Document Length:        259 bytes

Concurrency Level:      10
Time taken for tests:   2.881 seconds
Complete requests:      1000
Failed requests:        0
Total transferred:      503000 bytes
HTML transferred:       259000 bytes
Requests per second:    347.12 [#/sec] (mean)
Time per request:       28.809 [ms] (mean)
Time per request:       2.881 [ms] (mean, across all concurrent requests)
Transfer rate:          170.51 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    0   0.0      0       0
Processing:     3   29   3.1     28      38
Waiting:        2   28   3.1     28      37
Total:          3   29   3.1     28      38

Percentage of the requests served within a certain time (ms)
  50%     28
  66%     30
  75%     30
  80%     31
  90%     33
  95%     34
  98%     35
  99%     36
 100%     38 (longest request)
```

## LARAVEL

```
ab -n 1000 -c 10 http://localhost:9000/
This is ApacheBench, Version 2.3 <$Revision: 1796539 $>
Copyright 1996 Adam Twiss, Zeus Technology Ltd, http://www.zeustech.net/
Licensed to The Apache Software Foundation, http://www.apache.org/

Benchmarking localhost (be patient)
Completed 100 requests
Completed 200 requests
Completed 300 requests
Completed 400 requests
Completed 500 requests
Completed 600 requests
Completed 700 requests
Completed 800 requests
Completed 900 requests
Completed 1000 requests
Finished 1000 requests


Server Software:
Server Hostname:        localhost
Server Port:            9000

Document Path:          /
Document Length:        2321 bytes

Concurrency Level:      10
Time taken for tests:   10.277 seconds
Complete requests:      1000
Failed requests:        0
Total transferred:      3347922 bytes
HTML transferred:       2321000 bytes
Requests per second:    97.30 [#/sec] (mean)
Time per request:       102.774 [ms] (mean)
Time per request:       10.277 [ms] (mean, across all concurrent requests)
Transfer rate:          318.12 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    0   0.0      0       0
Processing:    98  102   8.6     99     207
Waiting:       97  101   8.6     98     206
Total:         98  102   8.6     99     207

Percentage of the requests served within a certain time (ms)
  50%     99
  66%    100
  75%    102
  80%    104
  90%    112
  95%    115
  98%    117
  99%    130
 100%    207 (longest request)
```