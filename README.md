PHP GitHub webhook script
==========================

## Overview ##

This script contains actions that allow keeping up to date git repository by performing `git pull` on every `git push` to your GitHub repository. To achieve this you have to add this simple script to your git repository and configure webhook on your GitHub repository.

## Webhook script ##

For a webhook script to work you have to do 3 things:
1. Add this simple PHP script that will actually handle calls to webhook to your web application
2. Check and modify variables in header of this script to match your enviroment
3. Configure your GitHub repository webhook to be called every time commits are
   pushed to GitHub

### Webhook script ###

To actually handle webhook calls, you have to add PHP script that will be accessible publicly and it will run `git pull` every time GitHub calls it.
Folowing settings should be adjusted before begin:

```php
$secret_key = 's0me_s3cret_4ey';
$app_folder = '/var/www/html/app';
$log_file   = $app_folder.'/githook.log';
$path_export='export PATH=/usr/local/sbin:/usr/local/bin:/sbin:/bin:/usr/sbin:/usr/bin &&';
$bash_script=$path_export." cd $app_folder && git pull ";


```

In the script above `<your secret>` should be some random string you choose and it should be later supplied to GitHub when defining webhook. For more
information about secrets and how they are used in GitHub webhook read [Webhooks | GitHub API](https://developer.github.com/webhooks/).

NOTE: Since this script has sensitive data in it (`secret` that is used to validate requests), it is advised to not put that script under git control by excluding it in `.gitignore` file. Another option is to take `secret` from some environment variable that would be defined by other means (like `SetEnv` in apache configuration).

### GitHub repository configuration ###

To set up a repository webhook on GitHub, head over to the **Settings** page of your repository, and click on **Webhooks & services**. After that, click on **Add webhook**.

Fill in following values in form:
* **Payload URL** - Enter full URL to your webhook script
* **Content type** - should be "x-www-form-urlencoded"
* **Secret** - same value you set in to script variable 'secret_key'
* Webhook should receive only push events and of course be active

Click **Add webhook** button and that's it.

