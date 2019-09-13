## About The App

This app is simple transactional email microservice built using Laravel 5.8 and Vue.js 2, a coding challenge for [Takeaway.com](http://takeaway.com). The requirements were to build a microservice that is horizontaly scaleable with the ability to use more than one email service (The main service and other fallbacks).

## Install Instructions

-   Download the repo using git clone or just click the download button on the top right side
-   Navigate to the repo folder and run the following commands using the command line

```
composer install
npm install
```

## So, what is it all about?

First thing, the challenge was to create a transactional email microservice that has at least two providers, one as the main service and the following as a fallback, if the first failed to send and it should use a queueing system for sending emails. Second thing is the creation and sending of emails should happen through two ways, a command line and a Vue.js app.

```
My design is not the best and can be improved in many ways but I had a limited time.
```

### Now, I designed the app according to the following points:

#### The Providers:

```

There was no need to create a new HTTP implementation for hitting the providers endpoints as it was already provided by the service provider.

```

-   The service providers chosen are Sendgrid and Mailjet, they both provid great API and PHP packages for handling stuff easily
-   The providers are added to the .env file as a key called MAIL_SERVICE_PROVIDERS which contains a comma separated list of the providers' names we are going to use, starting with the service we want to use first
-   All the providers api keys and secrets are in the .env and are referenced in the config/services.php file.

#### The Database

The design is very simple,

-   the emails table hold email data
-   the recipients table holds the
-   the email_recipient table holds the relations between emails and recipients with the other information like status and provider name

To better handle the multiple recipients and to easily keep track of each email status sent to each email address, I chose to make it a many-to-many relationship between the emails and recipients tables

Also to offer flexibility and options, I added an option to each email to identify weather we want to send the message in the BCC to all recipients or send it separately to each one

### The Commands

To separate the email creation from sending, have two commands:

-   email:create Can be used to create the email it self using the syntax below but without sending it, and it returns the ID of the created Email

```
php artisan email:create

Description:
  Create a transactional email

Usage:
  email:create <type> <subject> <body> <recipients>

Arguments:
  type                  The type of the email (text/plain|text/html|text/markdown)
  subject               The email subject
  body                  The email message content
  recipients            Comma separated list of recipiens email addresses
```

-   email: send Can be used to send an Email with the ID using the following sysntax

```
php artisan email:send

Description:
  Send a previously created email with ID

Usage:
  email:send <email_id>

Arguments:
  email_id              The ID for the created email to send
```

## What happens when we create?

The application validates and sanitizes all valid email addresses passed in and only uses the valid ones, if it fails to find at least 1 valid email address, it returns an error.

Also we specify the subject, body and type of the email, subject and body can be empty and the type defaults to text/plain if empty or not valid.

## What happens when we send?

The application creates simply creates a new email job and adds it to the emails queue and the then it will be handled as follows:

-   Make sure email exists
-   Get list of available providers
-   Prepares the email settings accordingly
-   Checks weather we are adding all recipients to BCC or separately
-   Loops through the providers in order and tries to send the email using the provider specific package
-   When it fails, we log the error and update the status for the recipient to failed and tries with the next provider, if succeed it also logs the state and updates the recipient's email status and moves to the next recipient (if there is).

```
We make sure everything is logged in this operation
```

## The Vue!

The frontend was built using Vue.js, a small SPA application to show all the emails we previously sent and a beautiful easy to use form for creating and sending emails.

You can also click on the (view) link next to each email to see all the email information about it.

The form has a recipients input box to enter/paste comma separated recipients easily with great frontend validation and it has 3 editors, each editor is displayed according to the email type to provide better user experience and ease of use.

-   A normal textarea for plain text emails
-   A Quill editor for HTML emails and lastly
-   A very good Markdown editor on the left with a preview on the right.

```
It also has an option to specify the sending weather separately or in BCC, when using BCC it is better for sure to minimize the api usage limits.
```
