-- Run this SQL to add the Google Maps embed URL setting
-- You can import this via phpMyAdmin or run it directly

INSERT INTO settings (setting_key, setting_value, setting_type, setting_group, description) 
VALUES ('contact_map_embed', 'https://maps.google.com/maps?q=Dhaka,Bangladesh&t=k&z=13&ie=UTF8&iwloc=&output=embed', 'textarea', 'contact', 'Google Maps Embed URL for contact page');
