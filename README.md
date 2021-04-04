# AutoApartmentTour
An almost automatic web app to allow people to book a visit to your apartment (because you leave definitely it) without doing lot of thing.

You just have to register users, add time slot for the visits and manually approve the bookings from a mobile app (Ios/Android). Then the app will automatically send sms (with your current mobile phone number) and add the event in your calendar (which can be a sync calendar as Google calendar)

**User space**
![Alt Text](assets/user_booking.gif)

**Manual approval**
![Alt Text](assets/booking_validation.gif)

**Add user**

![Alt Text](assets/add_user.gif)

/etc/apache2/apache2.conf
<Directory /var/www/>
AllowOverride None -> AllowOverride All

![Alt Text](assets/add_user.gif)
