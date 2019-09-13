<template>
  <div>
    <div class="markdown-body md-body">
      <h2 id="about-the-app" data-source-line="1">
        <a class="anchor" href="#about-the-app">
          <span class="octicon octicon-link"></span>
        </a>About The App
      </h2>
      <p data-source-line="3">
        This app is simple transactional email microservice built using Laravel 5.8 and Vue.js 2, a coding challenge for
        <a
          href="http://takeaway.com"
          target="_blank"
          rel="noopener"
        >Takeaway.com</a>. The requirements were to build a microservice that is horizontaly scaleable with the ability to use more than one email service (The main service and other fallbacks).
      </p>
      <h2 id="install-instructions" data-source-line="5">
        <a class="anchor" href="#install-instructions">
          <span class="octicon octicon-link"></span>
        </a>Install Instructions
      </h2>
      <ul data-source-line="7">
        <li>Download the repo using git clone or just click the download button on the top right side</li>
        <li>Navigate to the repo folder and run the following commands using the command line</li>
      </ul>
      <pre><code>composer install
npm install
</code></pre>
      <h2 id="so-what-is-it-all-about" data-source-line="15">
        <a class="anchor" href="#so-what-is-it-all-about">
          <span class="octicon octicon-link"></span>
        </a>So, what is it all about?
      </h2>
      <p
        data-source-line="17"
      >First thing, the challenge was to create a transactional email microservice that has at least two providers, one as the main service and the following as a fallback, if the first failed to send and it should use a queueing system for sending emails. Second thing is the creation and sending of emails should happen through two ways, a command line and a Vue.js app.</p>
      <pre><code>My design is not the best and can be improved in many ways but I had a limited time.
</code></pre>
      <h3 id="now-i-designed-the-app-according-to-the-following-points" data-source-line="23">
        <a class="anchor" href="#now-i-designed-the-app-according-to-the-following-points">
          <span class="octicon octicon-link"></span>
        </a>Now, I designed the app according to the following points:
      </h3>
      <h4 id="the-providers" data-source-line="25">
        <a class="anchor" href="#the-providers">
          <span class="octicon octicon-link"></span>
        </a>The Providers:
      </h4>
      <pre><code>
There was no need to create a new HTTP implementation for hitting the providers endpoints as it was already provided by the service provider.

</code></pre>
      <ul data-source-line="33">
        <li>The service providers chosen are Sendgrid and Mailjet, they both provid great API and PHP packages for handling stuff easily</li>
        <li>The providers are added to the .env file as a key called MAIL_SERVICE_PROVIDERS which contains a comma separated list of the providers' names we are going to use, starting with the service we want to use first</li>
        <li>All the providers api keys and secrets are in the .env and are referenced in the config/services.php file.</li>
      </ul>
      <h4 id="the-database" data-source-line="37">
        <a class="anchor" href="#the-database">
          <span class="octicon octicon-link"></span>
        </a>The Database
      </h4>
      <p data-source-line="39">The design is very simple,</p>
      <ul data-source-line="41">
        <li>the emails table hold email data</li>
        <li>the recipients table holds the</li>
        <li>the email_recipient table holds the relations between emails and recipients with the other information like status and provider name</li>
      </ul>
      <p
        data-source-line="45"
      >To better handle the multiple recipients and to easily keep track of each email status sent to each email address, I chose to make it a many-to-many relationship between the emails and recipients tables</p>
      <p
        data-source-line="47"
      >Also to offer flexibility and options, I added an option to each email to identify weather we want to send the message in the BCC to all recipients or send it separately to each one</p>
      <h3 id="the-commands" data-source-line="49">
        <a class="anchor" href="#the-commands">
          <span class="octicon octicon-link"></span>
        </a>The Commands
      </h3>
      <p data-source-line="51">To separate the email creation from sending, have two commands:</p>
      <ul data-source-line="53">
        <li>email:create Can be used to create the email it self using the syntax below but without sending it, and it returns the ID of the created Email</li>
      </ul>
      <pre><code>php artisan email:create

Description:
  Create a transactional email

Usage:
  email:create &lt;type&gt; &lt;subject&gt; &lt;body&gt; &lt;recipients&gt;

Arguments:
  type                  The type of the email (text/plain|text/html|text/markdown)
  subject               The email subject
  body                  The email message content
  recipients            Comma separated list of recipiens email addresses
</code></pre>
      <ul data-source-line="71">
        <li>email: send Can be used to send an Email with the ID using the following sysntax</li>
      </ul>
      <pre><code>php artisan email:send

Description:
  Send a previously created email with ID

Usage:
  email:send &lt;email_id&gt;

Arguments:
  email_id              The ID for the created email to send
</code></pre>
      <h2 id="what-happens-when-we-create" data-source-line="86">
        <a class="anchor" href="#what-happens-when-we-create">
          <span class="octicon octicon-link"></span>
        </a>What happens when we create?
      </h2>
      <p
        data-source-line="88"
      >The application validates and sanitizes all valid email addresses passed in and only uses the valid ones, if it fails to find at least 1 valid email address, it returns an error.</p>
      <p
        data-source-line="90"
      >Also we specify the subject, body and type of the email, subject and body can be empty and the type defaults to text/plain if empty or not valid.</p>
      <h2 id="what-happens-when-we-send" data-source-line="92">
        <a class="anchor" href="#what-happens-when-we-send">
          <span class="octicon octicon-link"></span>
        </a>What happens when we send?
      </h2>
      <p
        data-source-line="94"
      >The application creates simply creates a new email job and adds it to the emails queue and the then it will be handled as follows:</p>
      <ul data-source-line="96">
        <li>Make sure email exists</li>
        <li>Get list of available providers</li>
        <li>Prepares the email settings accordingly</li>
        <li>Checks weather we are adding all recipients to BCC or separately</li>
        <li>Loops through the providers in order and tries to send the email using the provider specific package</li>
        <li>When it fails, we log the error and update the status for the recipient to failed and tries with the next provider, if succeed it also logs the state and updates the recipient's email status and moves to the next recipient (if there is).</li>
      </ul>
      <pre><code>We make sure everything is logged in this operation
</code></pre>
      <h2 id="the-vue" data-source-line="107">
        <a class="anchor" href="#the-vue">
          <span class="octicon octicon-link"></span>
        </a>The Vue!
      </h2>
      <p
        data-source-line="109"
      >The frontend was built using Vue.js, a small SPA application to show all the emails we previously sent and a beautiful easy to use form for creating and sending emails.</p>
      <p
        data-source-line="111"
      >You can also click on the (view) link next to each email to see all the email information about it.</p>
      <p
        data-source-line="113"
      >The form has a recipients input box to enter/paste comma separated recipients easily with great frontend validation and it has 3 editors, each editor is displayed according to the email type to provide better user experience and ease of use.</p>
      <ul data-source-line="115">
        <li>A normal textarea for plain text emails</li>
        <li>A Quill editor for HTML emails and lastly</li>
        <li>A very good Markdown editor on the left with a preview on the right.</li>
      </ul>
      <pre><code>It also has an option to specify the sending weather separately or in BCC, when using BCC it is better for sure to minimize the api usage limits.
</code></pre>
    </div>
  </div>
</template>