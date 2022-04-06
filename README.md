# Docker-Web-Application
Docker web applications in GCP 

Web application for concerts in your area , users can see the ticket availability and dates of their favorites concert. 
If a concert is added to favorites users can see live new feeds for the concert (e.g new dates or ticket supply)

To run the web application you need to install Docker.

In the terminal (or powershell on windows) run

- Docker-compose up 

You can access the web-app in :

http://localhost:80

You can manage users in the keyrock dashboard in 

http://localhost:3005


If you run the docker in the GCP you can access the web-app and keyrock in:

http://<EXTERNAL-IP>:80
http://<EXTERNAL-IP>:3005
  
Where external ip is given by GCP

Also to access the 3005 and 80 ports in the cloud you need to configure the ports from the firewall in the GCP
