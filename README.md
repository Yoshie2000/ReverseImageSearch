# Reverse Image Search

## Setup

1. Clone the repository with `git clone https://github.com/Yoshie2000/ReverseImageSearch`
2. Install Vagrant
3. Install a VirtualBox version that is compatible with your version of Vagrant
4. Reboot your machine
5. Go into the Git repository folder
6. Run `vagrant up` to boot the VM
7. Run `vagrant provision` just to be sure everything is fine. It will probably throw the error *Could not find a suitable provider for rvm_gem*, but that's fine. Everything we need is installed by now
8. Run `vagrant ssh` to access the VM
9. Run `ifconfig` to look up the IP address of your VM and append the following to the file `C:\Windows\System32\drivers\etc\hosts`: `${ip_of_your_vm} reverseimagesearch.test`
10. In your VM, go to `/var/www/html` and run `composer install` to install all the necessary dependencies. If it throws a RuntimeException and says `Could not delete /var/www/html/vendor/zendframework/zend-skeleton-installer/something`, that's fine
11. Now we need to install ImageMagick for PHP. You can do this by running the following commands:
  ```
  sudo apt -y install gcc make autoconf libc-dev pkg-config
  sudo apt -y install libmagickwand-dev
  sudo pecl install imagick
  sudo bash -c "echo extension=imagick.so > /etc/php/7.2/fpm/conf.d/imagick.ini"
  ```
  Just hit enter when PECL asks you to configure it
12. Restart nginx and PHP:
  ```
  sudo systemctl restart nginx.service
  sudo systemctl restart php7.2-fpm.service
  ```
13. Install RabbitMQ, create a new user and enable the web interface: 
  ```
  sudo apt install rabbitmq-server
  sudo rabbitmqctl add_user patrick.leonhardt Check24.de
  sudo rabbitmqctl set_user_tags patrick.leonhardt administrator
  sudo rabbitmq-plugins enable rabbitmq_management
  ```
  This specific user is required for the program to connect to RabbitMQ, but you can change the user here: `/var/www/html/module/Application/config/module.config.php`
14. Disable iptables so you can access the RabbitMQ interface from your host. You can find the instructions for that [here](https://www.cyberciti.biz/faq/debian-iptables-stop/)
15. Go to [http://reverseimagesearch.test:15672](http://reverseimagesearch.test:15672), go to `Admin`, click on your user (or patrick.leonhardt), and click on `Set permission`

Now you can access [http://reverseimagesearch.test](http://reverseimagesearch.test) in your browser, it will redirect you to the ip of your VM.
Right now, it will tell you that it can't find the tables, so you need to go to `/var/www/html/module/Application/src/Repository/` and in the `URLRepository.php` and `ImageRepository.php`, uncomment the second of the three lines in the `getAll(Images|URLs)` method that creates the tables. Now reload the page. Comment that line out and reload again. Now everything is ready to go.

## How to setup the crawler services
// TODO in progress

## Usage
You can manually put in URLs into the program by going to [http://reverseimagesearch.test](http://reverseimagesearch.test) and typing in your URL into the form. Every URL you put in will be crawled with a high priority, but depending on how much you put in, you might need to wait a little bit as the crawlers will crawl your URL for new URLs and crawl all of those URLs for more URLs, all of that happens with high priority. After that, everything will go back to normal priority.
To start the crawlers, simple type `sudo sh /var/www/setup/bash/ris-crawler-start.sh`. To stop, type `sudo sh /var/www/setup/bash/ris-crawler-stop.sh`. You can edit those files to run as many crawlers as you want and your computer can handle :)
