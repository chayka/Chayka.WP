Chayka\WP\Helpers\EmailHelper
===============

Class EmailHelper is a handy wrapper around PHPMailer




* Class name: EmailHelper
* Namespace: Chayka\WP\Helpers





Properties
----------


### $scriptPaths

    protected array $scriptPaths = array()

Paths to look for views



* Visibility: **protected**
* This property is **static**.


Methods
-------


### addScriptPath

    mixed Chayka\WP\Helpers\EmailHelper::addScriptPath($path)

Add script paths for the View to look for templates;



* Visibility: **public**
* This method is **static**.


#### Arguments
* $path **mixed**



### getNotificationEmailAddress

    string Chayka\WP\Helpers\EmailHelper::getNotificationEmailAddress()

Get email address where admin notifications should be sent to



* Visibility: **public**
* This method is **static**.




### setupPhpMailer

    mixed Chayka\WP\Helpers\EmailHelper::setupPhpMailer(\Chayka\WP\Helpers\PhpMailer $phpMailer)

Set up PhpMailer instance



* Visibility: **public**
* This method is **static**.


#### Arguments
* $phpMailer **Chayka\WP\Helpers\PhpMailer**



### send

    boolean Chayka\WP\Helpers\EmailHelper::send(string $subject, string $html, string $to, string $from, string $cc, string $bcc)

Send email message



* Visibility: **public**
* This method is **static**.


#### Arguments
* $subject **string**
* $html **string**
* $to **string**
* $from **string**
* $cc **string**
* $bcc **string**



### getTemplatePath

    string Chayka\WP\Helpers\EmailHelper::getTemplatePath()

Get template filename



* Visibility: **public**
* This method is **static**.




### getView

    \Chayka\WP\Helpers\View; Chayka\WP\Helpers\EmailHelper::getView()

Nice function to redefine with:

return Plugin::getView();

* Visibility: **public**
* This method is **static**.




### sendTemplate

    boolean Chayka\WP\Helpers\EmailHelper::sendTemplate(string $subject, string $template, array $params, string $to, string $from, string $cc, string $bcc)

Send templated message



* Visibility: **public**
* This method is **static**.


#### Arguments
* $subject **string**
* $template **string**
* $params **array**
* $to **string**
* $from **string**
* $cc **string**
* $bcc **string**


