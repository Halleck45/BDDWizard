<?php

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();
$app['debug'] = true;

//
// configuration
$app['behat.paths.features'] = getenv('GHERKIN_FEATURES');
$app['behat.paths.reports'] = getenv('GHERKIN_REPORTS');

//
// Factory of features
$app['hbt.feature.factory'] = function() use ($app) {
    $parser = $app['gherkin.parser'];
    $reportRepository = $app['hbt.report.repository'];
    $container = $app;
    return new Hal\BehatWizard\Domain\Factory\Feature($parser, $reportRepository, $container);
};

//
// Repositories
$app['hbt.feature.repository'] = function() use($app) {
    return new Hal\BehatWizard\Domain\Repository\Feature(
        $app['behat.paths.features']
        , $app['hbt.feature.factory']
    );
};

$app['hbt.report.repository'] = function() use($app) {
    return new Hal\BehatWizard\Domain\Repository\Report(
        $app['behat.paths.reports']
    );
};


//
// Gherkin parser
$app['gherkin.keywords'] = function() use ($app) {
    $keywords = require __DIR__.'/../vendor/behat/gherkin/i18n.php';
    return new \Behat\Gherkin\Keywords\ArrayKeywords($keywords);
};
$app['gherkin.parser'] = function() use ($app) {
    $lexer = new \Behat\Gherkin\Lexer($app['gherkin.keywords']);
    return new \Behat\Gherkin\Parser($lexer);
};

//
// Twig
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../app/views',
));


//
// Session
$app->register(new Silex\Provider\SessionServiceProvider());


//
// translations
$app->before(function () use ($app) {
    $app->register(new Silex\Provider\TranslationServiceProvider(), array(
        'locale_fallback'           => 'en',
        'translation.class_path'    => __DIR__. '/../lib/vendor/symfony/src',
    ));

    $app['translator']->addLoader('yml', new Symfony\Component\Translation\Loader\YamlFileLoader());
    $app['translator']->addResource('yml', __DIR__.'/../app/translations/en.yml', 'en');
    $app['translator']->addResource('yml', __DIR__.'/../app/translations/fr.yml', 'fr');

    $app['translator.loader'] = new Symfony\Component\Translation\Loader\YamlFileLoader();
    $app['twig']->addExtension(new Symfony\Bridge\Twig\Extension\TranslationExtension($app['translator']));

    $app->before(function () use ($app) {
        if ($locale = $app['request']->get('locale')) {
            $app['locale'] = $locale;
        }
    });
});


//
// Application
// --------------------------------------------------------
//
// listing
$app->get('/', function () use ($app) {
    $repository = $app['hbt.feature.repository'];
    return $app['twig']->render(
        'feature/list.html.twig'
        , array(
            'features' => $repository->getFeatures()
            , 'pendingFeatures' => $repository->getPendingFeatures()
            , 'failingFeatures' => $repository->getFailingFeatures()
            , 'validFeatures' => $repository->getValidFeatures()
        )
    );
});

//
// Edit / Add
$app->get('/edit/{feature}', function($feature, \Symfony\Component\HttpFoundation\Request $request) use($app) {
    $repository = $app['hbt.feature.repository'];
    $feature = $repository->loadFeatureByHash($feature);

    if (is_null($feature)) {
        $feature = $repository->createFeature();
    }
    return $app['twig']->render(
        'feature/edit.html.twig'
        , array(
            'feature' => $feature
            , 'gherkin' => $feature->getGherkin()
            , 'report' => $feature->getReport()
            , 'dumper' => new Hal\BehatWizard\Domain\Model\Feature\Dumper\Json($feature)
        )
    );
})->value('feature', null);

//
// Edit / Add (post)
$app->post('/edit/{feature}', function($feature, \Symfony\Component\HttpFoundation\Request $request) use($app) {
    $repository = $app['hbt.feature.repository'];
    $feature = $repository->loadFeatureByHash($feature);
    if (!is_null($feature)) {
        $repository->removeFeature($feature);
    } else {
        $feature = $repository->createFeature();
    }
    $feature->setContent($request->request->get('content'));
    $repository->saveFeature($feature);
    return $app->redirect('/');
})->value('feature', null);

//
// Remove
$app->get('/remove/{feature}', function($feature, \Symfony\Component\HttpFoundation\Request $request) use($app) {
    $repository = $app['hbt.feature.repository'];
    $feature = $repository->loadFeatureByHash($feature);
    if (!$feature) {
        return new \Symfony\Component\HttpFoundation\Response('Feature was not found', 404);
    }
    $repository->removeFeature($feature);
    return $app->redirect('/');
});

$app->run();