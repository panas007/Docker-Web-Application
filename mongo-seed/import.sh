#! /bin/bash

#Import the concerts into MonfoDB
mongoimport --host mongo_data_con --db condata_db --collection concerts --type json --file /mongo-seed/data/concerts/concerts_data.json --jsonArray

mongoimport --host mongo_data_con --db condata_db --collection favorites 

mongoimport --host mongo_data_con --db condata_db --collection feeds

mongoimport --host mongo_data_con --db condata_db --collection subs

mongoimport --host orion_mongo --db orion --collection entities --type json --file /mongo-seed/data/entities/entities_data.json --jsonArray

# --type json --file /mongo-seed/data/favorites/favorites_data.json --jsonArray