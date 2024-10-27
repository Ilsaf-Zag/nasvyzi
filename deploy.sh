#!/bin/bash

cd frontend
npm run build
echo j4qafg4ixq0i7PVh
rsync -avz dist/* root@89.111.170.205:/var/www/app/backend/public
