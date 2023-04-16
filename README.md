# Norby test task



You need to implement a service that gives the current exchange rate (EUR) and the history of exchange rates through HTTP REST API, with access only for authorized users.
Laravel framework, you can use any database and any front end.

### Additional requirement:
- The history of exchange rates should be for every day (starting from April), without gaps. 
- Explain what needs to be added or changed so that the service can handle 1500 requests per second.

Will look at the code style and approach to implementation.

Do not use other people's libs!



### Installation
```sh
make run
```



### Load test
```sh
siege http://127.0.0.1:8088/api/v1/currencies -c 100 -t 10s -b -i
```
```sh
siege http://127.0.0.1:8088/api/v1/currencies/eur/exchange-rate -c 100 -t 10s -b -i
```



### Result
![N|Solid](https://i.postimg.cc/3ryCqctG/2023-04-16-22-46-43.png)
![N|Solid](https://i.postimg.cc/jqGQY41F/2023-04-16-23-05-25.png)
