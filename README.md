# ModBook
A modular BookStack theme

## 📚 Project Definition
ModBook is a collection of smaller mods that I have created and put into a format, which can be dynamically loaded. This repository can be cloned into the themes folder, and enabled like any BookStack theme, using the .env file.

By default this theme won't change anything visually, however after first reload of BookStack, a config.php should be generated within the ModBook folder. This file can be used to enable various mods. The target of this project is to generate a JavaScript and CSS file dynamically, to achieve a very customized experience.

Due to the fact that mods can be incompatible with each other; I have decided that this will still be called a modular theme, rather than a mod-loader of some sort.

## 🎁 Contributing, Issues
Feel free to create issues to point out incompatible code or issues with mods. I will still decide which mods make it into this repository, however: Feel free to fork this, and make your own modpack. If you decide to fork and redistribute it, please use another name than ModBook, so cloning into the themes folder won't cause complications.

## 🖥️ Get Started
> You may have to use `sudo -u www-data` and replace www-data with your webserver user here.\
> Prepend this in front of the **git**, **nano** and **php** commands.
### Step 1:
First, log-in to your server, then navigate to your BookStack installations theme folder:
```bash
$ cd /var/www/bookstack/themes/
```

### Step 2:
Then run following to clone ModBook into your themes folder:
```bash
$ git clone https://github.com/DiscordDigital/ModBook.git --branch release --single-branch
```

### Step 3:
Edit your .env file:
```bash
$ cd ..
$ nano .env
```
And append following variable to the end of the file, which corresponds to the existence of the ModBook folder:
```env
APP_THEME=ModBook
```
Reload your page to create the config.php file within the `themes/ModBook` folder.

## 📦 Updating ModBook
Navigate to your ModBook folder:
```bash
$ cd /var/www/bookstack/themes/ModBook/
```
Then run following command as your webserver user, to update ModBook:
```bash
$ git pull origin release
```

Compare your `config.php` with `defaults.php` and migrate over mods of your interest.

Alternatively delete your `config.php` file and reload BookStack to generate a new one.

## 🛠️ Using mb.php
With mb.php you can show the status of the mods available. You can also clear the cache.

Showing available commands:
```bash
$ php mb.php
```

View a status of the available mods:
```bash
$ php mb.php lsmod
```

Clear cache:
```bash
$ php mb.php clear
```

## ❌ Uninstalling
### Step 1:
Remove following from your `.env` file:
```env
APP_THEME=ModBook
```

### Step 2:
Run following as your webserver user in the `themes/ModBook` directory:
```php
$ php mb.php clear
```
This will remove changes made to the `public` directory.

### Step 3:
Remove the `ModBook` folder from themes.

✅ ModBook should now be uninstalled.

## 💾 Backup warning
Although I have tested everything posted on here, I can't support repairing any instances breaking in the process.

If you haven't already, please create regular backups of your instance and make sure you have a most recent one before installing ModBook.

Everything you do, is at your own risk.
