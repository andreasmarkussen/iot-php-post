

http://bildelspriser.dk/iot/maker.php?type=newGmailAttachment&subject={{Subject}}


# Install on Windows
Get the right php version that my hosting provider uses

    choco install php --version 5.6.30

And then install composer

    choco install composer

## Installing Pear and Phing - as an Administrator

go to your php home:

    wget http://pear.php.net/go-pear.phar
    php go-pear.phar  
    pear channel-update pear.php.net
    pear install Net_FTP


# Testing

## Behat instructions
+d features - place your *.feature files here
+d features\bootstrap - place your context classes here
+f features\bootstrap\FeatureContext.php - place your definitions, transformations and hooks here


# Code and the organisation

http://symfony.com/doc/current/quick_tour/the_architecture.html


#Upload to FTP


    phing upload_dev
