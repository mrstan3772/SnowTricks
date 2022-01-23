[![Codacy Badge](https://app.codacy.com/project/badge/Grade/3d7cac4c79c54f8d8871227b098eb0ae)](https://www.codacy.com/gh/mrstan3772/SnowTricks/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=mrstan3772/SnowTricks&amp;utm_campaign=Badge_Grade)

# SnowTricks

SnowTrick website is about connecting thousands novices and professionals around the amazing snowboard sport. 

It was entirely designed in PHP with Symfony framework.
It's provided with a set of resources including this one:
- the images of the snowboard trick
- the text documents
- the fonts
- Article attachments
- third packages libraries and extensions
- etc.


## Contexte
Jimmy Sweat is an innovative and enthusiastic businessman with a strong interest in the sport of snowboarding. His goal is to create a collaborative site to make this sport accessible to the general public and to help people learn tricks.

He wants to capitalize on the content provided by users in order to develop rich content that attracts the interest of users of the site. Later on, Jimmy wants to develop a business of establishing partnerships with snowboard brands based on the traffic that the content will have generated.

Description of the need

You are in charge of developing the site to meet Jimmy's needs. You have to implement the following features : 

1. a list of snowboard tricks. You can be inspired by the [list of tricks](https://fr.wikipedia.org/wiki/Snowboard_freestyle#Les_types_de_tricks) on Wikipedia. Just add 10 tricks, the rest will be entered by the users;
2. figure management (creation, modification, consultation);
3. a shared discussion space for all the figures.
4. To implement these features, you must create the following pages:

1. the home page where the list of figures will appear ; 
2. the page for creating a new figure;
3. the page for modifying a figure;
4. the presentation page of a figure (containing the shared discussion space about a figure).
5. The detailed specifications for the pages to be developed are available here : [Detailed specifications](https://fr.wikipedia.org/wiki/Snowboard_freestyle#Les_types_de_tricks).


## Prerequisites:
In order to make this project work, you must:
- Use **PHP 8.0.X | 8.1.X**
- [Download composer](https://getcomposer.org/) to install PHP dependencies
- Extensions (which are installed and enabled by default in most PHP 8 installations): [Ctype](https://www.php.net/book.ctype), [iconv](https://www.php.net/book.iconv), [Session](https://www.php.net/book.session), [SimlpleXML](https://www.php.net/book.simplexml), [Tokenizer](https://www.php.net/book.tokenizer), [PCRE](https://www.php.net/book.pcre)

Optional : [Install Symfony CLI](https://symfony.com/download)

The symfony binary also provides a tool to check if your computer meets all requirements. Open your console terminal and run this command:

`symfony check:requirements`

Without this tool you have to replace in the terminal `symfony` with `php bin/console` and always at the root of the project.

## Installation

### Dependencies

Use the command `compose install` from the project root directory(SnowTricks). Do not answer questions if you see any during the installation (press enter to skip). Once this step is done you will have all the necessary dependencies for the main project.

## Deployment


### Application Configuration

Edit the `.env` file to the root of the directory. On the example below adapt the configuration according to your credentials for the values `DATABASE_URL` which concerns the SQL database and `MAILER_DSN` if you want to send mail (for user system work).

```env
#TO CHANGE USER ENVIRONNEMENT (prod, dev, test)
APP_ENV=dev
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7"
MAILER_DSN=smtp://smtp_user:smtp_password@smtp_address:smtp_port
#BASE URL IF YOUR WEB SITE PATH IS NOT POINTING ON THE DOMAIN ROOT
SITE_BASE_URL='/'
```
Found this example in the root folder under the file name [".env.example"](https://github.com/mrstan3772/SnowTricks/blob/master/.env.example)


### Run Server

Type this command inside the root folder(Snow Tricks) to start running web server :

`symfony serve`

An address in the format 127.0.0.1:<port> is shown on the terminal.

Copy and paste this address in the navigation bar of your browser.

That's all !


### Creating tables in the database (MySQL)

From now on, we will focus on creating the tables required to record trick and user information. All we have to do is type this command and follow :  

```bash
#Same name in your .env file to replace "db_name" 
symfony console doctrine:database:create
symfony console make:migration
symfony console doctrine:migrations:migrate

#OPTIONAL
symfony console doctrine:migrations:diff
symfony console doctrine:schema:update --force
```

And load fixtures data with this command : 

`symfony console doctrine:fixtures:load`

### Ressources

In order to keep the directory as light as possible, the resources for demonstrations have simply been ignored.

However, you can download them from [link](https://mega.nz/file/l9VQgBrL#kDtQNtmQQD6MDDU8xpZxzTEUVRzYBVDz1gVk--w1O28) and add them to the folder following the path `/public/assets/content/uploads/images` and `/public/assets/content/uploads/videos`.

## Version

Version : 1.0.0

We use SemVer for versioning. For more details, see [link](https://semver.org/).


## Authors

**Stanley LOUIS JEAN** - *Web Dev* - [MrStan](https://github.com/mrstan3772)


## License

![GPL-v3](https://zupimages.net/up/21/46/iarl.png)


## Thanks
Blog made from the template provided by : 
https://getbootstrap.com/