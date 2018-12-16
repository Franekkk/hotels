# Dokumentacja

## Pierwsze uruchomienie
1. `docker-compose up -d` i poczekać aż zbuduje się wszystko.
2. `./first-run.sh` zainicjuje zależności na wszystkich serwisach dockerowych które tego potrzebują.
3. wewnątrz kontenerów `hotel_krakow`, `hotel_wroclaw` i `hotel_gdansk` nalezy odpalic migracje. 
Przykład: `docker-compose up -d hotel_krakow`, a następnie `./yii migrate` wewnątrz kontenera.

## Testy
`docker-compose run hotel_krakow bash run_tests.sh`
