# Auto apartment tour app
An almost automatic web app to allow people to book a visit to your apartment (because you leave definitely it) without doing lot of thing.

You just have to register users, add time slot for the visits and manually approve the bookings from a mobile app (Ios/Android). Then the app will automatically send sms (with your current mobile phone number) and add the event in your calendar (which can be a sync calendar as Google calendar)

**User space**

![Alt Text](assets/user_booking.gif)

**Manual approval**

![Alt Text](assets/booking_validation.gif)

**Add user**

![Alt Text](assets/add_user.gif)

# Installation

## Mobile

```
expo install
expo build:<platform>
```

where ```<platform>``` is either ``` android ``` or ``` ios ```.

Check the *releases* folder for latest update of the compile mobile app

## Web app

If you do not have php7.4 (on ubuntu) :

```
sudo apt-get update
sudo apt -y install software-properties-common
sudo add-apt-repository ppa:ondrej/php
sudo apt-get update
sudo apt-get install php7.4 php7.4-cli php7.4-fpm php7.4-json php7.4-common php7.4-mysql php7.4-zip php7.4-gd php7.4-mbstring php7.4-curl php7.4-xml php7.4-bcmath
```

```
sudo apt-get install composer
```

In the server root folder :

```
cat env.example > .env
```

Then edit the created file (*.env*) to match your configuration (DB_CONNECTION, DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD).

```
cat init.example.json > init.json
```

And as previously edit the created file (*.env*) to match your configuration.


Then check that your system uses the correct 7.4 version of php or run composer.phar with the php7.4 binary and run :
```
composer install
php artisan key:generate 
php artisan jwt:secret
php artisan migrate:fresh --seed
```

## Production mode
To run the laravel app in production mode (with apache2) if you cannot access the api roots edit the file at */etc/apache2/apache2.conf*, find the xml tag *<Directory /var/www/>* and change
*AllowOverride None* to *AllowOverride All*, then restart apache.

# Configuration

## Translations

The app has a little bit of languages support built-in : the mobile app is entirely in English and the web app in French. To translate this app you will need to edit the file itsefl and recompile it (the .js file for the mobile app and the .html file for the webapp).
This is far from perfect but it has been developed mainly in a weekend so not all the desired feature has been implemented. Nonetheless, the messages send to users with sms and the name of the status (of actions or event) have a translation file for that.

