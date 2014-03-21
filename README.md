# BDDWizard

GUI Tool for Behavior Driven Development fans.

This tool helps Product Owners to manage their features. They can:

+ list their features and know the state of each feature
+ filter features by state, tag, etc...
+ edit features
+ add new features

![Listing](docs/screen-home-small.jpg)

![Edit feature](docs/screen-edit-small.jpg)

# Usage

    wget https://github.com/Halleck45/BDDWizard/raw/master/build/bdd-wizard.phar
    php bdd-wizard.phar --features=/path/of/features --reports=/path/of/junit-format/results

PHP BuiltIn server will listen on http://localhost:8001 (change it with the `--server` option).

# Requirements

+ PHP 5.4

# Author

+ Jean-François Lépine <blog.lepine.pro>

# License

See the LICENCE file
