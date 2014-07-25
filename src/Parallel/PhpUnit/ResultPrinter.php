<?php

namespace Parallel\PhpUnit;

class ResultPrinter extends \PHPUnit_TextUI_ResultPrinter {



	/**
	 * @param string $buffer
	 */
	public function writeResults($buffer)
	{
		$handle = fopen('test.txt', 'a');
		if ($handle) {
			fwrite($handle, $buffer);

			if ($this->autoFlush) {
				$this->incrementalFlush();
			}
		} else {
			if (PHP_SAPI != 'cli') {
				$buffer = htmlspecialchars($buffer);
			}

			print $buffer;

			if ($this->autoFlush) {
				$this->incrementalFlush();
			}
		}
	}

	/**
	 * @param \PHPUnit_Framework_TestFailure $defect
	 * @param integer                       $count
	 */
	protected function printDefectHeader(\PHPUnit_Framework_TestFailure $defect, $count)
	{
		$failedTest = $defect->failedTest();

		if ($failedTest instanceof \PHPUnit_Framework_SelfDescribing) {
			$testName = $failedTest->toString();
		} else {
			$testName = get_class($failedTest);
		}

		$buffer = sprintf(
			"\n%d) %s\n",

			$count,
			$testName
		);
		$this->write(
			$buffer
		);
		$this->writeResults($buffer);
	}

	/**
	 * @param \PHPUnit_Framework_TestFailure $defect
	 */
	protected function printDefectTrace(\PHPUnit_Framework_TestFailure $defect)
	{
		$this->write($defect->getExceptionAsString());
		$this->writeResults($defect->getExceptionAsString());

		$trace = \PHPUnit_Util_Filter::getFilteredStacktrace(
			$defect->thrownException()
		);

		if (!empty($trace)) {
			$this->write("\n" . $trace);
			$this->writeResults("\n" . $trace);
		}

		$e = $defect->thrownException()->getPrevious();

		while ($e) {
			$buffer = "\nCaused by\n" .
				\PHPUnit_Framework_TestFailure::exceptionToString($e) . "\n" .
				\PHPUnit_Util_Filter::getFilteredStacktrace($e);
			$this->write(
				$buffer
			);
			$this->writeResults($buffer);

			$e = $e->getPrevious();
		}
	}


	protected function writeWithColor($color, $buffer)
	{
		$buffer = $this->formatWithColor($color, $buffer);
		$this->writeResults($buffer . "\n");
		$this->write($buffer . "\n");
	}

}