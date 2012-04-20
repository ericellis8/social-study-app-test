 This is the mia configuration file
; Comments start with ';', as in php.ini

[database_info]
host = "localhost"
vendor = "mysql"
user = "mia"
password = "soulskater"
database = "mia"

[global_info]
admin_email = "mia@brilaps.com"
secret = "asdFWEFCXCas123" ; Make this string unique for your installation
enable_captcha = 1 ; Requires the GD library

; The live site url is the actual web path to your site (ex) http://brilaps.com/mia
live_site_url = "htp://localhost/svn/root/chat"

;display the emails of the users in the manage buddy/search features
show_user_emails = "false"

;server time offset
server_time_offset = "-8"

;welcome tab custom message
custom_welcome_message = "Welcome to Mia-Chat :)<br /><br />Use Manage Buddies to add a new buddy or 
double-click a buddy to start chatting.<br /><br />We hope you enjoy this application.  Please spread the word."
