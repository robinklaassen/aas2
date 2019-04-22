# Install prerequisite

- Install WAMP

  - Download the WAMP server. [direct download](https://downloads.sourceforge.net/project/wampserver/WampServer%203/WampServer%203.0.0/wampserver3.1.7_x64.exe?r=https%3A%2F%2Fsourceforge.net%2Fprojects%2Fwampserver%2Ffiles%2FWampServer%25203%2FWampServer%25203.0.0%2Fwampserver3.1.7_x64.exe%2Fdownload&ts=1555748835).
  - Install WAMP, you can leave everything as default.
  - You might need some extra things, if you start wamp and have an error about a missing Msvcr110.dll [this windows update package thingy](https://www.microsoft.com/en-us/download/details.aspx?id=30679) (if you are not sure which to download, you propably need the x64 one)

- Startup WAMP and ensure it is on php `7.1.x`

  - Open WAMP
  - Change the PHP CLI version to `7.1.x`
    - In the system-tray, right click on the WAMP logo
    - goto `Tools`
    - goto `Change PHP CLI version`
    - Select `7.1.x`

* Install Composer

  - Download composer. [direct download](https://getcomposer.org/Composer-Setup.exe)
  - Install composer. You can leave most as default see below.
    - There is a page to select the proper PHP version. select 7.1.x in the dropdown

* Install a Git environment. Either one of the below should do fine (NOT BOTH)
  - Github for windows (should be dummy proof, but never used it)
    - Download GitHub for windows. [direct download](https://desktop.github.com/)
    - Install GitHub
    - Log into the GitHub for windows
  - Git bash
    - Download git bash. [Direct download](https://github.com/git-for-windows/git/releases/download/v2.21.0.windows.1/Git-2.21.0-64-bit.exe)
    - Install it, you can leave everything as default
    - Congrats, you can now open `git bash` and feel like an absolute hackerman

# Getting aas

- Getting the sources

  - Either use the github for windows to clone this repo
  - or use some CLI magic `git clone https://github.com/robinklaassen/aas2.git <dest-folder>`

# Setting everything up

- Creating an empty database
  - Ensure WAMP runs (the system tray should be green)
  - Open up your favorite browser
  - Navigate to http://localhost/
  - Open phpmyadmin
  - Login with root and an empty password
  - Click on `New` or you local equivelent.
  - Fill in an name for the dabase, dont use spaces or special characters, just keep it simple (exmaple: `aas2`)
  - Click `Create`
  - Close phpmyadmin, that's it.
  
- Create an virtual host

  - Ensure WAMP runs (the system tray should be green)
  - Open up your favorite browser
  - Navigate to http://localhost/
  - Click `Add a Virtual Host`
  - Fill in a short name for aas, dont use spaces or special characters, just keep it simple (example `aas2`)
  - Fill in the path to the public folder of the project you cloned from github.
  - Press the big button
  - Wait until WAMP is done
  - Right mouse click on the WAMP icon in the system tray
  - Goto tools -> Restart DNS
  

- Open the GitHub repo in your favorite editor (for example VSCode)
  - Open vscode
  - goto File
  - select `open folder`
  - navigate to the GitHub repo
  - Press open
  
- copy the .env.example and rename it to .env

  - After `DB_DATABASE=` fill in the database name you used 
  - Remove the password
  - Fill in `root` after `DB_USERNAME=`

- For some reason composer doesn't include the creation of some folders it actually needs, so lets create them for it.
  - Goto the folder `storage` in the root of the repo
  - Create a folder named `framework`
  - Navigate into the newly created folder
  - Create a folder named `sessions`
  - Create a folder name `cache`
  - Create a folder named `views`

- Open a terminal
  - In VC Code the shortcut is: ``` ctrl+` ```  
  - 
  - Type `composer install` <enter>
  - Wait a bit.
  - Wait a bit longer
  - Get yourselve some coffee
  - Type `composer update` <enter>
  - Type `php artisan key:generate` <enter>
  - Type `php artisan migrate --seed` <enter>
 
- Open a browser and navigate to `http://{your-vhost-name}` where `{your-vhost-name}` is replaced by the name you entered in the step above.
