

docker run -tid --name selenium-standalone-chrome -h selenium-standalone-chrome --memory 1g --shm-size="1g" --memory-swap -1 -p 9515:4444 selenium/standalone-chrome

docker run -tid --name selenium-standalone-chrome1 -h selenium-standalone-chrome --memory 1g --shm-size="1g" --memory-swap -1 -p 9516:4444 selenium/standalone-chrome


docker run -tid --name selenium-standalone-chrome2 -h selenium-standalone-chrome --memory 1g --shm-size="1g" --memory-swap -1 -p 9517:4444 selenium/standalone-chrome
