## Booking App API

Customers can register and login to create a booking.
A booking accepts a date and purpose of booking.

Admin level users are identified by 'is_admin' column in users table.
### Main Controllers
#### UserController
Creates user. Shows list of users to Admin. Search for users with given email. 
#### MyBookingController
stores, shows, updates, deletes booking of the logged in user.
#### BookingController
Admin can view the count of bookings in list of booked dates. 
Shows bookings with users given date



