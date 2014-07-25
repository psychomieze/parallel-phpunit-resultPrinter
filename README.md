parallel-phpunit-resultPrinter
==============================


This is a very ugly but working hack to get nicer results when running tests with gnu parallels.

Works like this:

<pre>
time find -L . -name \*Test.php -path \*typo3_src/typo3/sysext/*/Tests/Functional* | parallel --gnu 'echo; echo "Running functional {} test case";  ./bin/phpunit --colors -c typo3/sysext/core/Build/FunctionalTests.xml --printer "\Parallel\PhpUnit\ResultPrinter" {}' > /dev/null
cat test.txt
rm test.txt
</pre>

Add the following to your composer.json:

"psychomieze/parallel-phpunit-resultPrinter": "*@dev"

and run composer update.
