
#!/bin/bash
echo "Script started: $(/bin/date)" >> /Users/liweide/upload.log
SCP_OUTPUT=$(/usr/bin/scp -i /Users/liweide/.ssh/aws.pem /Users/liweide/laravel/pokemon/database/seeders/pokemonsTest.json ubuntu@54.238.70.16:/var/www/pokemonProjectGold/database/seeders 2>&1)

if [ $? -eq 0 ]; then
    echo "Uploading file to EC2 at $(/bin/date)" >> /Users/liweide/upload.log
else
    echo "Failed to upload file to EC2 at $(/bin/date). Error: $SCP_OUTPUT" >> /Users/liweide/upload.log
fi

