<?php

final class ArcanistMypyLinter extends ArcanistExternalLinter {

  public function getInfoName() {
    return 'Python Mypy type checker';
  }

  public function getInfoURI() {
    return 'http://mypy-lang.org/';
  }

  public function getInfoDescription() {
    return pht(
      'Mypy is an optional static type checker for Python that aims to combine '.
      'the benefits of dynamic (or "duck") typing and static typing.');
  }

  public function getLinterName() {
    return 'mypy';
  }

  public function getLinterConfigurationName() {
    return 'mypy';
  }

  public function getDefaultBinary() {
    return 'mypy';
  }

  public function getInstallInstructions() {
    return pht('Install Mypy using `%s`.', 'pip3 install mypy');
  }

  public function shouldExpectCommandErrors() {
    return true;
  }

  protected function getMandatoryFlags() {
    return array('--show-column-numbers', '--show-error-codes');
  }

  protected function parseLinterOutput($path, $err, $stdout, $stderr) {
    $lines = phutil_split_lines($stdout, false);

    $regexp = '/^(?<path>.+?):(?<line>\d+):(?<column>\d+):\s(?<severity>\w+):'.
      '\s(?<name>.+?)(\s\((?<description>.+)\))?(\s+\[(?<code>.+)\])?$/';
    $i = 0;
    $messages = array();
    foreach ($lines as $line) {
      $matches = null;
      if (!preg_match($regexp, $line, $matches)) {
        continue;
      }

      // Skip lines that are not related to the current path
      if ($path != $matches['path']) {
        continue;
      }

      $message = new ArcanistLintMessage();
      $message->setPath($path);
      $message->setLine($matches['line']);
      $message->setChar($matches['column']);
      $message->setCode($matches['code']);
      $message->setName($this->getLinterName().' '.$matches['name']);
      if (array_key_exists('description', $matches)) {
        $message->setDescription($matches['description']);
      }
      $message->setSeverity($this->getLintMessageSeverity($matches['severity']));

      $messages[] = $message;
    }

    return $messages;
  }

  protected function getDefaultMessageSeverity($severity) {
    switch ($severity) {
      case 'note':
        return ArcanistLintSeverity::SEVERITY_ADVICE;
      case 'warning':
        return ArcanistLintSeverity::SEVERITY_WARNING;
      default:
        return ArcanistLintSeverity::SEVERITY_ERROR;
    }
  }
}
