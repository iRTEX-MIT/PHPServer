<img src="https://i.ibb.co/7Wff6bq/Cardboard-Banner.png" width="100%">

### Initial Setup
#### How to install a server
First, check the PHP version - it should be higher than version 7.0. We recommend using PHP version 7.4. If the version meets the required version, start the installation with the following command

```console
(sudo mkdir /bin/cardboard && sudo mkdir /bin/cardboard/repo && cd /bin/cardboard && sudo git clone https://github.com/iRTEX-MIT/PHPServer repo && cd "repo" && echo "CardBoard Server successfully installed")
```

<sub><sup><b>Explanation of the code:</b> The command installs the server on your computer</sup></sub>

#### How to run a server
To start the server, use a simple command

```console
php /bin/cardboard/repo/SERVER.php
```

<sub><sup><b>Explanation of the code:</b> The command will start the server</sup></sub>

#### How to use the server
The server structure is simple - it works the same way as nginx. Upload the files to the HTTP folder and they will be available at the address written in the console after starting the server. but cardboard has a number of advantages - all your html files are templated, the server is equipped with a router, without additional configuration, you can execute python, ruby and php files.


#### Table of templating variables
| Variable      | Description                   |
|---------------|-------------------------------|
| Helpers.relative_path | Directory of the current file |
|               |                               |
|               |                               |