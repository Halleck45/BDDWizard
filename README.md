# BehatWizard

GUI Tool for Behavior Driven Development fans.


![Listing](docs/screen-home-small.jpg)

![Edit feature](docs/screen-edit-small.jpg)



# Standalone usage

    php -S localhost:8001 -t web

## Reports and features location

+ Features should be declared with gherkin syntax
+ Reports should respect JUnit format

Please use `GHERKIN_FEATURES` and `GHERKIN_REPORTS` environment variables to declare folders to use:

    GHERKIN_FEATURES=/path/of/your/choice GHERKIN_REPORTS=/path/of/your/reports php -S localhost:8001 -t web

# As Behat Extension

Edit the `behat.yml` file, and activate extension :

    default:
      ...
      extensions:
        Behat\WizardExtension\Extension: ~

You should also active junit report for your test suite:

    default:
      ...
      formatter:
          name: pretty,junit
          parameters:
            output_path: null,build/behat-junit

Then run `./vendor/bin/behat --wizard`


# Author

+ Jean-François Lépine <blog.lepine.pro>
